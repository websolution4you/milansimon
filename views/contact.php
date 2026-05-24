<?php 
$is_subpage = true; 
require __DIR__ . '/layout/header.php'; 
?>

<div class="container">
    <!-- Hero sekcia pre Kontakt - v štýle O mne -->
    <section class="about-hero">
        <div class="about-hero-image">
            <img src="https://images.unsplash.com/photo-1534536281715-e28d76689b4d?auto=format&fit=crop&w=800&q=80" alt="Kontaktujte ma">
        </div>
        <div class="about-hero-text">
            <h2>Napíšte mi a dohodneme si fotenie.</h2>
            
            <p>Zaujalo vás niektoré z portfólií alebo hľadáte fotografa na konkrétnu spoluprácu? Či už ide o biznis portrét, event alebo reklamnú kampaň, rád s vami preberiem vaše predstavy.</p>
            
            <p>Snažím sa odpovedať na všetky dopyty do 24 hodín. Ak preferujete telefonický kontakt, neváhajte mi zavolať.</p>
        </div>
    </section>

    <!-- Kontaktné údaje v štýle feature boxov z O mne -->
    <section class="about-features" style="padding-bottom: 60px;">
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-envelope"></i></div>
            <h3>E-mail</h3>
            <p><a href="mailto:msphotography@milansimon.com" style="color: inherit; text-decoration: none;">msphotography@milansimon.com</a></p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-phone"></i></div>
            <h3>Telefón</h3>
            <p><a href="tel:+421905014204" style="color: inherit; text-decoration: none;">+421 905 014 204</a></p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3>Lokalita</h3>
            <p>Celé Slovensko aj zahraničie</p>
        </div>
    </section>

    <!-- Sekcia s formulárom -->
    <section style="padding: 60px 0 120px 0; border-top: 1px solid var(--color-border);">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2 style="font-family: var(--font-heading); font-weight: 300; font-size: 2.2rem; color: #111111;">Napíšte mi správu</h2>
        </div>

        <form class="contact-form" action="#" method="POST">
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label for="name">Meno *</label>
                    <input type="text" id="name" name="name" required placeholder="Vaše meno">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Váš email">
                </div>
            </div>
            
            <div class="form-group">
                <label for="subject">Predmet</label>
                <input type="text" id="subject" name="subject" placeholder="O čo sa jedná?">
            </div>
            
            <div class="form-group">
                <label for="message">Správa</label>
                <textarea id="message" name="message" rows="6" required placeholder="Napíšte vašu predstavu..."></textarea>
            </div>
            
            <div class="form-group" style="flex-direction: row; align-items: center; gap: 10px;">
                <input type="checkbox" id="consent" name="consent" required style="width: auto; margin: 0;">
                <label for="consent" style="margin-bottom: 0; font-size: 0.85rem;">Súhlasím so spracovaním osobných údajov. *</label>
            </div>
            
            <button type="submit" class="btn-submit" style="width: 100%; margin-top: 10px;">ODOSLAŤ</button>
        </form>
    </section>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
