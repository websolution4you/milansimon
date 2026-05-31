<?php
require_once __DIR__ . '/../db.php';

$category_titles = [
    'eventy' => 'Fotenie eventov',
    'portrety' => 'Fotenie portrétov',
    'sport' => 'Športové fotenie'
];
$title = isset($category_titles[$category]) ? $category_titles[$category] : 'Galéria';

// 1. Skúsime načítať fotky z databázy
$db_photos = [];
try {
    if (isset($pdo)) {
        $stmt = $pdo->prepare('SELECT s3_url FROM photos WHERE category = ? ORDER BY sort_order ASC, created_at DESC');
        $stmt->execute([$category]);
        $db_photos = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
} catch (\Exception $e) {
    // V prípade chyby pripojenia k DB prejdeme na záložné lokálne súbory
}

if (!empty($db_photos)) {
    $all_photos = $db_photos;
} else {
    // 2. Ak v DB nič nie je, použijeme pôvodnú simuláciu z lokálnych súborov
    $photos = [];
    $dir = __DIR__ . '/../assets/img/' . $category;
    if (is_dir($dir)) {
        $files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        foreach ($files as $file) {
            $photos[] = '/assets/img/' . $category . '/' . basename($file);
        }
    }

    $all_photos = [];
    if (!empty($photos)) {
        for ($i = 0; $i < 4; $i++) {
            $all_photos = array_merge($all_photos, $photos);
        }
        shuffle($all_photos);
    }
}

// Zmena hlavičky pre podstránky (aby nebola transparentná nad obrázkami ako na domovskej)
$is_subpage = true; 
$no_padding = true;
require __DIR__ . '/layout/header.php'; 
?>





<!-- Mozaika (Masonry Layout Full-width) -->
<div class="portfolio-masonry-wrapper">
    <a href="/" class="floating-back-btn">&larr; Späť na domov</a>
    
    <section class="masonry-grid-flex">
        <?php
        $num_cols = 4;
        $cols = array_fill(0, $num_cols, []);
        foreach ($all_photos as $index => $photo) {
            $cols[$index % $num_cols][] = ['index' => $index, 'photo' => $photo];
        }
        ?>
        <?php for ($c = 0; $c < $num_cols; $c++): ?>
            <div class="masonry-col">
                <?php foreach ($cols[$c] as $item): ?>
                    <div class="masonry-item-full portfolio-img">
                        <img src="<?php echo $item['photo']; ?>" alt="Fotografia portfólia <?php echo $item['index']; ?>" loading="lazy" data-src="<?php echo $item['photo']; ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endfor; ?>
    </section>
</div>

<!-- Lightbox Modal -->
<div class="lightbox-overlay" id="lightbox">
    <button class="lightbox-close" id="lightboxClose"><i class="fas fa-times"></i></button>
    <button class="lightbox-prev" id="lightboxPrev"><i class="fas fa-chevron-left"></i></button>
    <img src="" alt="Zväčšená fotografia" class="lightbox-img" id="lightboxImg">
    <button class="lightbox-next" id="lightboxNext"><i class="fas fa-chevron-right"></i></button>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>


