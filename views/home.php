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

<!-- Zobrazenie kategórií na domovskej stránke -->
<section class="categories-grid">
    <div class="category-card">
        <img src="/assets/img/portrety/20211209101600pm.jpg" alt="Fotenie portrétov">
        <div class="category-overlay">
            <a href="/portfolio/portrety" class="category-btn">BIZNIS PORTRÉTY</a>
        </div>
    </div>
    <div class="category-card">
        <img src="<?php echo get_category_thumbnail('sport'); ?>" alt="Športové fotenie">
        <div class="category-overlay">
            <a href="/portfolio/sport" class="category-btn">REKLAMA A ŠPORT</a>
        </div>
    </div>
    <div class="category-card">
        <img src="<?php echo get_category_thumbnail('eventy'); ?>" alt="Fotenie eventov">
        <div class="category-overlay">
            <a href="/portfolio/eventy" class="category-btn">EVENTY</a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
