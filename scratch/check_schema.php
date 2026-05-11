<?php
require 'db.php';
$stmt = $pdo->query('DESCRIBE photos');
while ($row = $stmt->fetch()) {
    print_r($row);
}
