<?php
$host = 'localhost';
$db   = 'qTk3FbF9';
$user = 'wMqleY6g';
$pass = 'qIwEInJd6N1C}gd87dv>'; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     $pdo = null; // Nastavíme na null, aby aplikácia nespadla a mohla použiť lokálny fallback obrázkov
}
