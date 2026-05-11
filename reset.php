<?php
require_once 'db.php';

$username = 'admin';
$password = 'admin123';
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Najskôr vyčistíme tabuľku
    $pdo->exec('TRUNCATE TABLE users');
    
    // Vložíme užívateľa s hashom vygenerovaným priamo týmto serverom
    $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
    
    echo "<h1>Hotovo!</h1>";
    echo "<p>Užívateľ <strong>admin</strong> s heslom <strong>admin123</strong> bol úspešne vytvorený.</p>";
    echo "<p>Teraz sa môžeš prihlásiť tu: <a href='/admin/login.php'>/admin/login.php</a></p>";
    
    // Súbor sa po spustení sám zmaže kvôli bezpečnosti
    // unlink(__FILE__); 
} catch (Exception $e) {
    echo "<h1>Chyba!</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>
