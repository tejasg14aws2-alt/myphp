<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'db.php';
/*
<?php
// Get completed and total goals for a type
function getProgress($db, $type) {
    $total = $db->querySingle("SELECT COUNT(*) FROM goals WHERE goal_type='$type'");
    $done  = $db->querySingle("SELECT COUNT(*) FROM goals WHERE goal_type='$type' AND status='Completed'");

    // Ensure numeric values
    if ($done === null) $done = 0;
    if ($total === null) $total = 0;

    return array($done, $total); // Use array() instead of [ ]
}
?>
*/

$typeFilter = $_GET['type'] ?? 'All';

/*
<div class="progress-section">

<?php
$types = array('Daily', 'Weekly', 'Monthly', 'Yearly'); // array() for compatibility

foreach ($types as $type) {
    $progress = getProgress($db, $type);
    $done  = $progress[0];   // Access array elements safely
    $total = $progress[1];

    $percent = ($total > 0) ? round(($done / $total) * 100) : 0;

    echo "
    <div class='progress-card'>
        <strong>$type Goals</strong>
        <div class='progress-bar'>
            <div class='progress-fill' style='width: {$percent}%'></div>
        </div>
        <small>$done / $total completed ($percent%)</small>
    </div>
    ";
}
?>

</div>

*/


$query = "SELECT * FROM goals";
if ($typeFilter !== 'All') {
    $query .= " WHERE goal_type = '$typeFilter'";
}
$query .= " ORDER BY created_at DESC";

$result = $db->query($query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>🎯 Goal Tracker</h1>

<!-- ADD GOAL FORM -->
<form action="add_goal.php" method="POST">
    <input type="text" name="title" placeholder="Enter your goal" required>

    <select name="goal_type">
        <option>Daily</option>
        <option>Weekly</option>
        <option>Monthly</option>
        <option>Yearly</option>
    </select>

    <button type="submit">Add Goal</button>
</form>

<!-- FILTER -->
<div class="filters">
    <a href="?type=All">All</a>
    <a href="?type=Daily">Daily</a>
    <a href="?type=Weekly">Weekly</a>
    <a href="?type=Monthly">Monthly</a>
    <a href="?type=Yearly">Yearly</a>
</div>

/*
<h2>🕒 Pending Goals</h2>

<?php
$result->reset();
while ($row = $result->fetchArray()) {
    if ($row['status'] === 'Pending') {
        echo "
        <div class='goal pending'>
            <strong>{$row['title']}</strong>
            <span>({$row['goal_type']})</span>

            <a href='complete_goal.php?id={$row['id']}'>✔</a>
            <a href='delete_goal.php?id={$row['id']}' onclick=\"return confirm('Delete this goal?')\">🗑</a>
        </div>";
    }
}
?>


<h2>✅ Completed Goals</h2>

<?php
$result->reset();
while ($row = $result->fetchArray()) {
    if ($row['status'] === 'Completed') {
        echo "
        <div class='goal completed'>
            <strong>{$row['title']}</strong>
            <span>({$row['goal_type']})</span>

            <a href='delete_goal.php?id={$row['id']}' onclick=\"return confirm('Delete this goal?')\">🗑</a>
        </div>";
    }
}
?>

</body>
</html>

