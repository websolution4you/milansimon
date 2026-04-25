<?php 
require __DIR__ . '/layout/header.php'; 

function get_category_thumbnail($category) {
    $dir = __DIR__ . '/../assets/img/' . $category;
    if (is_dir($dir)) {
        $files = glob($dir . '/*.{jpg,jpeg,png,JPG,JPEG,PNG}', GLOB_BRACE);
        if (!empty($files)) {
            return '/assets/img/' . $category . '/' . basename($files[0]);
        }
    }
    return 'https://via.placeholder.com/800x1000?text=' . $category;
}
?>

<div class="container">
    <section style="padding: var(--spacing-xl) 0; text-align: center;">
        <h2 style="font-family: var(--font-heading); font-weight: 300; font-size: 2.5rem; color: var(--color-accent); margin-bottom: var(--spacing-md);">Portfólio</h2>
        <p style="color: var(--color-text-light);">Vyberte si kategóriu na zobrazenie galérie.</p>
    </section>

    <section class="categories-grid">
        <a href="/portfolio/eventy" class="category-card">
            <img src="<?php echo get_category_thumbnail('eventy'); ?>" alt="Fotenie eventov">
            <div class="category-overlay">
                <h2>Fotenie eventov</h2>
            </div>
        </a>
        <a href="/portfolio/portrety" class="category-card">
            <img src="<?php echo get_category_thumbnail('portrety'); ?>" alt="Fotenie portrétov">
            <div class="category-overlay">
                <h2>Fotenie portrétov</h2>
            </div>
        </a>
        <a href="/portfolio/sport" class="category-card">
            <img src="<?php echo get_category_thumbnail('sport'); ?>" alt="Športové fotenie">
            <div class="category-overlay">
                <h2>Športové fotenie</h2>
            </div>
        </a>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
