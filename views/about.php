<?php 
// Nastavenie pre tmavú hlavičku rovnako ako na podstránkach
$is_subpage = true; 
require __DIR__ . '/layout/header.php'; 
?>

<!-- Hlavička podstránky (rovnaký štýl ako portfólio) -->
<div class="page-header" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.8)), url('https://images.unsplash.com/photo-1452587925148-ce544e77e70d?auto=format&fit=crop&w=1920&q=80'); background-size: cover; background-position: center; padding: 120px 0 80px; text-align: center; color: white;">
    <h1 style="font-family: var(--font-heading); font-weight: 300; font-size: 3rem; letter-spacing: 4px; text-transform: uppercase;">O mne</h1>
    <p style="font-family: var(--font-body); font-size: 1.1rem; color: #ccc; max-width: 600px; margin: 20px auto 0;">Svetlo, tieň a momenty, ktoré tvoria príbeh.</p>
</div>

<div class="container">
    <!-- Hlavná sekcia (Príbeh a Fotka) -->
    <section class="about-hero">
        <div class="about-hero-image">
            <img src="https://images.unsplash.com/photo-1516961642265-531546e84af2?auto=format&fit=crop&w=800&q=80" alt="Fotografické vybavenie a prístup">
        </div>
        <div class="about-hero-text">
            <span class="about-subtitle">Môj prístup</span>
            <h2>Viac ako 10 rokov za objektívom.</h2>
            
            <p>Vítajte na mojom portfóliu. Už dlhé roky sa venujem foteniu eventov, biznis portrétov a reklamných kampaní. Mojou úlohou je zachytiť to tak, aby z fotiek bolo cítiť energiu a emócie.</p>
            
            <p>Dôraz kladiem na presnosť, prirodzenosť a vizuálne spracovanie, ktoré vystihne klienta a jeho hodnoty. Verím, že osobný prístup je rovnako dôležitý ako samotné technické spracovanie.</p>
            
            <div class="about-signature">
                Milan Simon
            </div>
        </div>
    </section>

    <!-- Sekcia "Prečo so mnou" s ikonami -->
    <section class="about-features">
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-camera-retro"></i></div>
            <h3>Profesionálna technika</h3>
            <p>Pracujem so špičkovými full-frame fotoaparátmi a svetelnou technikou, ktorá si poradí aj v náročných podmienkach.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-handshake"></i></div>
            <h3>Osobný prístup</h3>
            <p>Pred každým fotením preberieme vaše očakávania, aby výsledok presne sedel do vizuálnej identity vašej značky.</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-bolt"></i></div>
            <h3>Rýchle dodanie</h3>
            <p>Viem, že na fotky z eventov sa často čaká kvôli PR. Preto sa snažím prvé výstupy doručiť už do 24 hodín.</p>
        </div>
    </section>
</div>

<!-- Odkaz na akciu -->
<section class="about-cta">
    <div class="container text-center">
        <h2>Hľadáte fotografa pre váš projekt?</h2>
        <p>Rád si vypočujem vašu predstavu a pripravím nezáväznú ponuku.</p>
        <a href="/kontakt" class="btn-primary">Kontaktujte ma</a>
    </div>
</section>

<?php require __DIR__ . '/layout/footer.php'; ?>
