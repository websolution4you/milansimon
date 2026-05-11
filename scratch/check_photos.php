<?php
require 'db.php';
$stmt = $pdo->query('SELECT id, s3_url FROM photos');
while ($row = $stmt->fetch()) {
    echo "ID: " . $row['id'] . " | Path: " . $row['s3_url'] . "\n";
}
