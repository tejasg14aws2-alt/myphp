<?php
// Include the SQLite database connection
include 'db.php';

// --------------------------
// Handle Adding a New Goal
// -------------------------
if (isset($_POST['add_goal'])) {

    // 1️⃣ Get raw input
    $goal_name = trim($_POST['goal_name'] ?? '');
    $goal_type = $_POST['goal_type'] ?? '';

    // 2️⃣ VALIDATION (STEP 3 – BEFORE DB)
    if ($goal_name === '') {
        die("Goal name cannot be empty");
    }

    if (strlen($goal_name) > 200) {
        die("Goal name too long (max 200 characters)");
    }

    $allowed_types = ['Daily', 'Weekly', 'Monthly', 'Yearly'];
    if (!in_array($goal_type, $allowed_types)) {
        die("Invalid goal type");
    }

    // 3️⃣ Sanitize AFTER validation
    $goal_name = htmlspecialchars($goal_name, ENT_QUOTES, 'UTF-8');

    // 4️⃣ Database insert
    $stmt = $db->prepare("
        INSERT INTO goals (goal_name, goal_type, status)
        VALUES (:name, :type, 'Pending')
    ");
    $stmt->bindValue(':name', $goal_name, SQLITE3_TEXT);
    $stmt->bindValue(':type', $goal_type, SQLITE3_TEXT);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --------------------------
// Handle Marking a Goal Completed / Pending
// --------------------------
if (isset($_POST['goal_id'])) {
    $status = isset($_POST['completed']) ? 'Completed' : 'Pending';
    $stmt = $db->prepare("UPDATE goals SET status=:status WHERE id=:id");
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $_POST['goal_id'], SQLITE3_INTEGER);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
// --------------------------
// Handle Deleting a Goal
// --------------------------
if (isset($_POST['delete_id'])) {
    $stmt = $db->prepare("DELETE FROM goals WHERE id=:id");
    $stmt->bindValue(':id', $_POST['delete_id'], SQLITE3_INTEGER);
    $stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// --------------------------
// Function to get progress counts safely
// --------------------------
function getProgress($db, $type) {
    $done = 0;
    $total = 0;

    try {
        $totalResult = $db->querySingle("SELECT COUNT(*) FROM goals WHERE goal_type='$type'");
        if ($totalResult !== false) $total = $totalResult;

        $doneResult = $db->querySingle("SELECT COUNT(*) FROM goals WHERE goal_type='$type' AND status='Completed'");
        if ($doneResult !== false) $done = $doneResult;
    } catch (Exception $e) {
        $done = 0;
        $total = 0;
    }

    return array($done, $total);
}

$types = array('Daily', 'Weekly', 'Monthly', 'Yearly');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goal Tracker</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { margin-bottom: 20px; }
        .progress-section { display: flex; flex-wrap: wrap; gap: 10px; }
        .progress-card { border: 1px solid #ccc; padding: 10px; width: 200px; border-radius: 5px; }
        .progress-bar { background: #eee; width: 100%; height: 20px; border-radius: 10px; margin: 5px 0; }
        .progress-fill { background: #4caf50; height: 100%; border-radius: 10px; }
        .goal-list { margin-top: 10px; }
    </style>
</head>
<body>

<h1>Goal Tracker</h1>

<!-- Add New Goal Form -->
<h2>Add New Goal</h2>
<form method="post" action="">
    <input type="text" name="goal_name" placeholder="Goal Name" required>
    <select name="goal_type">
        <option value="Daily">Daily</option>
        <option value="Weekly">Weekly</option>
        <option value="Monthly">Monthly</option>
        <option value="Yearly">Yearly</option>
    </select>
    <button type="submit" name="add_goal">Add Goal</button>
</form>

<!-- Progress Section -->
<div class="progress-section">
<?php
foreach ($types as $type) {
    $progress = getProgress($db, $type);
    $done  = $progress[0];
    $total = $progress[1];

    $percent = ($total > 0) ? round(($done / $total) * 100) : 0;

    echo '<div class="progress-card">
        <strong>' . $type . ' Goals</strong>
        <div class="progress-bar">
            <div class="progress-fill" style="width: ' . $percent . '%;"></div>
        </div>
        <small>' . $done . ' / ' . $total . ' completed (' . $percent . '%)</small>
        </div>';
}
?>
</div>

<!-- Goal Lists with Interaction -->
<?php
foreach ($types as $type) {
    echo "<h3>$type Goals</h3>";
    echo '<div class="goal-list">';
    $results = $db->query("SELECT * FROM goals WHERE goal_type='$type'");
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $checked = ($row['status'] == 'Completed') ? 'checked' : '';

        // Checkbox to mark completed/pending
    echo '<div class="goal-item '.strtolower($row['status']).'">
    <input type="checkbox"
           '.$checked.'
           onchange="toggleGoal('.$row['id'].', this)">
    
    '.htmlspecialchars($row['goal_name']).'

    <button onclick="deleteGoal('.$row['id'].')">❌</button>
</div>';

    }
    echo '</div>';
}
?>

<script src="app.js"></script>

</body>
</html>
