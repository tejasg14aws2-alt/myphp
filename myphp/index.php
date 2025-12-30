<?php
include 'db.php';
$result = $db->query("SELECT * FROM goals ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Goal Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h1>🎯 Goal Tracker</h1>

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

<h2>Your Goals</h2>

<?php while ($row = $result->fetchArray()): ?>
    <div class="goal <?= $row['status'] ?>">
        <strong><?= $row['title'] ?></strong>
        (<?= $row['goal_type'] ?>)
        - <?= $row['status'] ?>

        <?php if ($row['status'] == 'Pending'): ?>
            <a href="complete_goal.php?id=<?= $row['id'] ?>">✔</a>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

</body>
</html>
