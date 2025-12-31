<?php
include 'db.php';
header('Content-Type: application/json');

if ($_POST['action'] === 'toggle') {
    $status = $_POST['status'];
    $id = (int)$_POST['id'];

    $stmt = $db->prepare("UPDATE goals SET status=:status WHERE id=:id");
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

if ($_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];

    $stmt = $db->prepare("DELETE FROM goals WHERE id=:id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

