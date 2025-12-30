<?php
include 'db.php';

$id = $_GET['id'];
$db->exec("UPDATE goals SET status='Completed' WHERE id=$id");

header("Location: index.php");
?>

