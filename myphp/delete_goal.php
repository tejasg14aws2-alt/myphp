<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db->exec("DELETE FROM goals WHERE id = $id");
}

header("Location: index.php");
exit;

