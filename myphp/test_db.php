<?php
$db = new SQLite3('/var/www/html/myphp/goals.db');

$results = $db->query("PRAGMA table_info(goals);");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
?>

