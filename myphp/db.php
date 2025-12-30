<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db = new SQLite3('/var/www/html/myphp/goals.db');

if (!$db) {
    die("DB connection failed");
}

$db->exec("
CREATE TABLE IF NOT EXISTS goals (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    goal_type TEXT NOT NULL,
    status TEXT DEFAULT 'Pending',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");
?>

