<?php
include 'db.php';

$title = $_POST['title'];
$type  = $_POST['goal_type'];

$stmt = $db->prepare("INSERT INTO goals (title, goal_type) VALUES (?, ?)");
$stmt->bindValue(1, $title);
$stmt->bindValue(2, $type);
$stmt->execute();

header("Location: index.php");
?>

