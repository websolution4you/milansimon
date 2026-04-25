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

<!-- TESTOVACIE MENU -->
<section class="test-menu-container">
    <div class="test-menu-item m-1">
        <span>1. Montserrat Tenký (Aktuálny - 200)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-2">
        <span>2. Montserrat Bold (600, UPPERCASE)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-3">
        <span>3. Playfair Display (Elegantné Serif)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-4">
        <span>4. Playfair Display Italic (Kurzíva)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-5">
        <span>5. Cormorant Garamond (Prémiový Serif, UPPERCASE)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-6">
        <span>6. Raleway Light (Moderný široký, 200)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-7">
        <span>7. Poppins (Čistý geometrický sans)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-8">
        <span>8. Lora (Klasický literárny serif)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-9">
        <span>9. Inter Extra Light (Ultramoderný)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-10">
        <span>10. Bodoni Moda (Vysoký kontrast, módny časopis)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-11">
        <span>11. Oswald (Kondenzovaný/vysoký, 300)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-12">
        <span>12. Quicksand (Zaoblený, jemný)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
    <div class="test-menu-item m-13">
        <span>13. Montserrat Semi-Bold (500, Wide tracking)</span>
        <nav class="test-nav">
            <a href="#">Domov</a>
            <a href="#">Portfólio</a>
            <a href="#">O mne</a>
            <a href="#">Kontakt</a>
        </nav>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
