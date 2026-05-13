<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../config.php';

require_login();

$message = '';
$upload_dir = __DIR__ . '/../img/';

// Vytvorenie adresára, ak neexistuje
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Spracovanie nahrania fotiek (podpora viacerých súborov)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos'])) {
    $category = $_POST['category'] ?? '';
    $files = $_FILES['photos'];
    $success_count = 0;
    $errors = [];

    if (is_array($files['name'])) {
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] === 0) {
                $extension = strtolower(pathinfo($files['name'][$i], PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
                
                if (in_array($extension, $allowed_extensions)) {
                    $filename = uniqid('img_') . '.' . $extension;
                    $destination = $upload_dir . $filename;
                    
                    if (move_uploaded_file($files['tmp_name'][$i], $destination)) {
                        $db_path = '/img/' . $filename;
                        
                        $stmtOrder = $pdo->prepare('SELECT MAX(sort_order) as max_order FROM photos WHERE category = ?');
                        $stmtOrder->execute([$category]);
                        $rowOrder = $stmtOrder->fetch();
                        $next_order = ($rowOrder && $rowOrder['max_order'] > 0) ? $rowOrder['max_order'] + 1 : 1;
                        
                        $stmt = $pdo->prepare('INSERT INTO photos (category, s3_url, sort_order) VALUES (?, ?, ?)');
                        $stmt->execute([$category, $db_path, $next_order]);
                        $success_count++;
                    } else {
                        $errors[] = "Chyba pri ukladaní: " . htmlspecialchars($files['name'][$i]);
                    }
                } else {
                    $errors[] = "Nepodporovaný formát: " . htmlspecialchars($files['name'][$i]);
                }
            } elseif ($files['error'][$i] !== 4) { // 4 = UPLOAD_ERR_NO_FILE
                $errors[] = "Chyba pri nahrávaní " . htmlspecialchars($files['name'][$i]) . " (kód: " . $files['error'][$i] . ")";
            }
        }
    }

    if ($success_count > 0) {
        $message = '<div class="success">Úspešne nahraných ' . $success_count . ' fotiek!</div>';
    }
    if (!empty($errors)) {
        $message .= '<div class="error">' . implode('<br>', $errors) . '</div>';
    }
}

// Spracovanie mazania
if (isset($_POST['delete_photo'])) {
    $id = (int)$_POST['delete_photo'];
    $stmt = $pdo->prepare('SELECT category, sort_order, s3_url FROM photos WHERE id = ?');
    $stmt->execute([$id]);
    $photo = $stmt->fetch();

    if ($photo) {
        $category = $photo['category'];
        $deleted_order = $photo['sort_order'];
        $path = $photo['s3_url'];
        
        // 1. Vymazanie fyzického súboru
        if (strpos($path, 'http') === false) {
            $file_path = __DIR__ . '/..' . $path;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // 2. Vymazanie z databázy
        $pdo->prepare('DELETE FROM photos WHERE id = ?')->execute([$id]);

        // 3. Posunutie poradia ostatných fotiek v tej istej kategórii
        $stmtUpdate = $pdo->prepare('UPDATE photos SET sort_order = sort_order - 1 WHERE category = ? AND sort_order > ?');
        $stmtUpdate->execute([$category, $deleted_order]);
        
        header('Location: dashboard.php?deleted=1');
        exit;
    }
}

// Automatická oprava poradia (ak by niekde zostali nuly alebo diery)
$all_photos_to_fix = $pdo->query('SELECT id, category FROM photos WHERE sort_order = 0 ORDER BY created_at ASC')->fetchAll();
if (!empty($all_photos_to_fix)) {
    foreach ($all_photos_to_fix as $fix) {
        $stmtOrder = $pdo->prepare('SELECT MAX(sort_order) as max_order FROM photos WHERE category = ?');
        $stmtOrder->execute([$fix['category']]);
        $m = $stmtOrder->fetch();
        $next = ($m && $m['max_order'] > 0) ? $m['max_order'] + 1 : 1;
        
        $pdo->prepare('UPDATE photos SET sort_order = ? WHERE id = ?')->execute([$next, $fix['id']]);
    }
}

if (isset($_GET['deleted'])) {
    $message = '<div class="success">Fotka bola vymazaná a poradie ostatných bolo automaticky upravené.</div>';
}

// Spracovanie zmeny poradia
if (isset($_POST['update_order'])) {
    foreach ($_POST['order'] as $id => $val) {
        $stmt = $pdo->prepare('UPDATE photos SET sort_order = ? WHERE id = ?');
        $stmt->execute([(int)$val, (int)$id]);
    }
    $message = '<div class="success">Poradie bolo uložené.</div>';
}

// Získanie fotiek z DB - zoradené podľa kategórie (Portréty, Eventy, Šport) a potom podľa poradia
$photos = $pdo->query("SELECT * FROM photos ORDER BY FIELD(category, 'portrety', 'eventy', 'sport'), sort_order ASC, created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Milan Simon</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h1 { font-weight: 300; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .upload-section { background: #fafafa; padding: 20px; border-radius: 5px; margin-bottom: 30px; border: 1px dashed #ccc; }
        select, input[type="file"], button, input[type="number"] { padding: 10px; margin-right: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #333; color: white; border: none; cursor: pointer; transition: 0.3s; }
        button:hover { background: #000; }
        .success { background: #e6ffed; color: #22863a; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .error { background: #ffeef0; color: #cb2431; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        
        .photo-list { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .photo-list th { text-align: left; background: #f9f9f9; padding: 12px; border-bottom: 2px solid #eee; }
        .photo-list td { padding: 12px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .photo-preview { width: 80px; height: 60px; object-fit: cover; border-radius: 3px; }
        .btn-delete { color: #d73a49; text-decoration: none; font-size: 18px; }
        .btn-delete:hover { color: #b31d28; }
        .order-input { width: 60px; text-align: center; }
        .save-order-btn { margin-top: 20px; background: #28a745; padding: 12px 20px; font-weight: 600; }
        .save-order-btn:hover { background: #218838; }
        .logout { float: right; color: #ff4d4d; text-decoration: none; font-size: 14px; border: 1px solid #ff4d4d; padding: 5px 15px; border-radius: 20px; }
        .tag { background: #eee; padding: 3px 10px; border-radius: 10px; font-size: 11px; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout">Odhlásiť sa</a>
        <h1>Milan Simon - Správa fotiek</h1>
        
        <?php echo $message; ?>

        <div class="upload-section">
            <h3>Nahrať novú fotku</h3>
            <form method="POST" enctype="multipart/form-data">
                <select name="category" required>
                    <option value="portrety">Biznis Portréty</option>
                    <option value="sport">Reklama a Šport</option>
                    <option value="eventy">Eventy</option>
                </select>
                <input type="file" name="photos[]" accept="image/*" multiple required>
                <button type="submit"><i class="fas fa-cloud-upload-alt"></i> NAHRAŤ NA SERVER</button>
            </form>
        </div>

        <form method="POST">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h3>Zoznam nahraných fotiek</h3>
                <button type="submit" name="update_order" class="save-order-btn"><i class="fas fa-save"></i> ULOŽIŤ PORADIE</button>
            </div>
            
            <table class="photo-list">
                <thead>
                    <tr>
                        <th>Náhľad</th>
                        <th>Kategória</th>
                        <th>Poradie (menšie = skôr)</th>
                        <th>Akcie</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($photos)): ?>
                        <tr><td colspan="4" style="text-align:center; color:#777;">Zatiaľ žiadne fotky.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($photos as $photo): ?>
                        <tr>
                            <td><img src="<?php echo $photo['s3_url']; ?>" class="photo-preview"></td>
                            <td><span class="tag"><?php echo $photo['category']; ?></span></td>
                            <td>
                                <input type="number" 
                                       name="order[<?php echo $photo['id']; ?>]" 
                                       value="<?php echo $photo['sort_order']; ?>" 
                                       class="order-input"
                                       min="1"
                                       onfocus="this.oldValue = this.value;"
                                       onchange="swapOrder(this)">
                            </td>
                            <td>
                                <button type="button" class="btn-delete" title="Vymazať" style="background:none; border:none; padding:0; cursor:pointer; color: #d73a49;" onclick="openDeleteModal(<?php echo $photo['id']; ?>)">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- Vlastný Modal pre potvrdenie vymazania -->
    <div id="deleteModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
        <div style="background:white; padding:30px; border-radius:10px; max-width:400px; text-align:center; box-shadow:0 5px 15px rgba(0,0,0,0.3);">
            <h3 style="margin-top:0;">Naozaj chcete vymazať túto fotku?</h3>
            <p style="color:#666; margin-bottom:20px;">Táto akcia sa nedá vrátiť späť.</p>
            <form method="POST" id="deleteForm">
                <input type="hidden" name="delete_photo" id="deletePhotoId" value="">
                <button type="button" onclick="closeDeleteModal()" style="background:#ddd; color:#333;">Zrušiť</button>
                <button type="submit" style="background:#d73a49; color:white;">Áno, vymazať</button>
            </form>
        </div>
    </div>

    <script>
    function openDeleteModal(id) {
        document.getElementById('deletePhotoId').value = id;
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function swapOrder(input) {
        const newValue = input.value;
        const oldValue = input.oldValue;
        const inputs = document.querySelectorAll('.order-input');
        
        inputs.forEach(other => {
            if (other !== input && other.value === newValue) {
                other.value = oldValue;
                // Pridáme vizuálny efekt bliknutia, aby bolo jasné, čo sa vymenilo
                other.style.backgroundColor = '#fff3cd';
                setTimeout(() => other.style.backgroundColor = 'white', 500);
            }
        });
        input.oldValue = newValue;
    }
    </script>
</body>
</html>
