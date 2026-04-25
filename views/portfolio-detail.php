<?php 
// Simulácia načítania fotiek pre danú kategóriu
$category_titles = [
    'eventy' => 'Fotenie eventov',
    'portrety' => 'Fotenie portrétov',
    'sport' => 'Športové fotenie'
];
$title = isset($category_titles[$category]) ? $category_titles[$category] : 'Galéria';

$photos = [];
$dir = __DIR__ . '/../assets/img/' . $category;
if (is_dir($dir)) {
    $files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
    foreach ($files as $file) {
        $photos[] = '/assets/img/' . $category . '/' . basename($file);
    }
}

require __DIR__ . '/layout/header.php'; 
?>

<div class="container">
    <section style="padding: var(--spacing-xl) 0; text-align: center;">
        <h2 style="font-family: var(--font-heading); font-weight: 300; font-size: 2.5rem; color: var(--color-accent); margin-bottom: var(--spacing-md); text-transform: uppercase;"><?php echo htmlspecialchars($title); ?></h2>
        <a href="/portfolio" style="color: var(--color-text-light); text-decoration: none;">&larr; Späť na kategórie</a>
    </section>

    <!-- Mozaika (Masonry Layout) -->
    <section class="masonry-grid">
        <?php foreach ($photos as $photo): ?>
        <div class="masonry-item">
            <img src="<?php echo $photo; ?>" alt="Fotografia portfólia">
        </div>
        <?php endforeach; ?>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
