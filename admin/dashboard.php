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

$is_db_connected = ($pdo !== null);

// Získanie vybratej kategórie (predvolená je portrety)
$selected_category = $_GET['category'] ?? 'portrety';
$allowed_categories = ['portrety', 'sport', 'eventy'];
if (!in_array($selected_category, $allowed_categories)) {
    $selected_category = 'portrety';
}

if ($is_db_connected) {
    // Spracovanie nahrania fotiek (podpora viacerých súborov)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photos']) && !isset($_POST['update_order'])) {
        $category = $_POST['category'] ?? 'portrety';
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
            header('Location: dashboard.php?category=' . urlencode($category) . '&uploaded=' . $success_count);
            exit;
        }
        if (!empty($errors)) {
            $_SESSION['upload_errors'] = $errors;
            header('Location: dashboard.php?category=' . urlencode($category));
            exit;
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
            
            header('Location: dashboard.php?category=' . urlencode($category) . '&deleted=1');
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

    // Spracovanie zmeny poradia
    if (isset($_POST['update_order'])) {
        foreach ($_POST['order'] as $id => $val) {
            $stmt = $pdo->prepare('UPDATE photos SET sort_order = ? WHERE id = ?');
            $stmt->execute([(int)$val, (int)$id]);
        }
        header('Location: dashboard.php?category=' . urlencode($selected_category) . '&ordered=1');
        exit;
    }

    // Spracovanie notifikácií/správ
    if (isset($_GET['uploaded'])) {
        $count = (int)$_GET['uploaded'];
        $message = '<div class="success">Úspešne nahraných ' . $count . ' fotiek do kategórie ' . htmlspecialchars($selected_category) . '!</div>';
    }
    if (isset($_SESSION['upload_errors'])) {
        $message .= '<div class="error">' . implode('<br>', $_SESSION['upload_errors']) . '</div>';
        unset($_SESSION['upload_errors']);
    }
    if (isset($_GET['deleted'])) {
        $message = '<div class="success">Fotka bola vymazaná a poradie ostatných bolo automaticky upravené.</div>';
    }
    if (isset($_GET['ordered'])) {
        $message = '<div class="success">Nové poradie fotiek pre kategóriu ' . htmlspecialchars($selected_category) . ' bolo úspešne uložené!</div>';
    }

    // Získanie fotiek z DB pre vybranú kategóriu zoradených podľa poradia
    $stmt = $pdo->prepare("SELECT * FROM photos WHERE category = ? ORDER BY sort_order ASC, created_at DESC");
    $stmt->execute([$selected_category]);
    $photos = $stmt->fetchAll();

} else {
    $message = '<div class="error" style="background:#fff3cd; color:#856404; border-color:#ffeeba; border: 1px solid;"><strong>Upozornenie:</strong> Nie ste pripojený k databáze (localhost). Zmeny sa neuložia a zoznam fotiek je prázdny. Pre plnú funkčnosť nastavte lokálnu databázu v db.php.</div>';
    $photos = [];
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Dashboard | Milan Šimon</title>
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
        
        .category-tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .tab-btn {
            text-decoration: none;
            color: #555;
            background: #fff;
            border: 1px solid #ddd;
            padding: 12px 24px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .tab-btn:hover {
            background: #f0f0f0;
            color: #111;
            border-color: #ccc;
        }
        .tab-btn.active {
            background: #333;
            color: #fff;
            border-color: #333;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="logout.php" class="logout">Odhlásiť sa</a>
        <h1>Milan Šimon - Správa fotiek</h1>
        
        <?php echo $message; ?>

        <div class="upload-section">
            <h3>Nahrať novú fotku</h3>
            <form method="POST" enctype="multipart/form-data" action="dashboard.php?category=<?php echo $selected_category; ?>">
                <select name="category" required>
                    <option value="portrety" <?php echo $selected_category === 'portrety' ? 'selected' : ''; ?>>Biznis Portréty</option>
                    <option value="sport" <?php echo $selected_category === 'sport' ? 'selected' : ''; ?>>Reklama a Šport</option>
                    <option value="eventy" <?php echo $selected_category === 'eventy' ? 'selected' : ''; ?>>Eventy</option>
                </select>
                <input type="file" name="photos[]" accept="image/*" multiple required>
                <button type="submit"><i class="fas fa-cloud-upload-alt"></i> NAHRAŤ NA SERVER</button>
            </form>
        </div>

        <!-- Filtrovanie podľa kategórie -->
        <div class="category-tabs">
            <a href="dashboard.php?category=portrety" class="tab-btn <?php echo $selected_category === 'portrety' ? 'active' : ''; ?>">
                <i class="fas fa-user-tie"></i> Biznis Portréty
            </a>
            <a href="dashboard.php?category=sport" class="tab-btn <?php echo $selected_category === 'sport' ? 'active' : ''; ?>">
                <i class="fas fa-running"></i> Reklama a Šport
            </a>
            <a href="dashboard.php?category=eventy" class="tab-btn <?php echo $selected_category === 'eventy' ? 'active' : ''; ?>">
                <i class="fas fa-calendar-alt"></i> Eventy
            </a>
        </div>

        <form method="POST" action="dashboard.php?category=<?php echo $selected_category; ?>">
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
            <form method="POST" id="deleteForm" action="dashboard.php?category=<?php echo $selected_category; ?>">
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
