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
    <div class="category-card card-portrety">
        <img src="/assets/img/portrety/20211209101600pm.jpg" alt="Fotenie portrétov">
        <div class="category-overlay">
            <a href="/portfolio/portrety" class="category-btn">
                <span class="btn-title">BIZNIS PORTRÉTY</span>
                <span class="btn-desc">Profesionálne portréty pre firmy a osobnú značku</span>
            </a>
        </div>
    </div>
    <div class="category-card">
        <img src="<?php echo get_category_thumbnail('sport'); ?>" alt="Športové fotenie">
        <div class="category-overlay">
            <a href="/portfolio/sport" class="category-btn">
                <span class="btn-title">REKLAMA A ŠPORT</span>
                <span class="btn-desc">Dynamické vizuály pre kampane a značky</span>
            </a>
        </div>
    </div>
    <div class="category-card">
        <img src="<?php echo get_category_thumbnail('eventy'); ?>" alt="Fotenie eventov">
        <div class="category-overlay">
            <a href="/portfolio/eventy" class="category-btn">
                <span class="btn-title">EVENTY</span>
                <span class="btn-desc">Reportážna fotografia z podujatí</span>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>

