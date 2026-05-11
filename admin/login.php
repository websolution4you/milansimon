<?php
require_once __DIR__ . '/../db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Nesprávne meno alebo heslo.';
        }
    } catch (Exception $e) {
        $error = 'Chyba databázy: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Milan Simon</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0f0f0f;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        h1 { font-weight: 300; letter-spacing: 2px; margin-bottom: 30px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            background: #2a2a2a;
            border: 1px solid #333;
            color: white;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #fff;
            color: #000;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }
        button:hover { background: #ccc; }
        .error { color: #ff4d4d; margin-bottom: 20px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="login-card">
        <h1>LOGIN</h1>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Meno" required>
            <input type="password" name="password" placeholder="Heslo" required>
            <button type="submit">VSTÚPIŤ</button>
        </form>
    </div>
</body>
</html>
