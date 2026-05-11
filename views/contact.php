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
            <span class="about-subtitle">Kontakt</span>
            <h2>Napíšte mi a dohodneme si fotenie.</h2>
            
            <p>Zaujalo vás niektoré z portfólií alebo hľadáte fotografa na konkrétnu spoluprácu? Či už ide o biznis portrét, event alebo reklamnú kampaň, rád s vami preberiem vaše predstavy.</p>
            
            <p>Snažím sa odpovedať na všetky dopyty do 24 hodín. Ak preferujete telefonický kontakt, neváhajte mi zavolať.</p>
            
            <div class="about-signature" style="font-size: 1.5rem; margin-top: 20px;">
                Milan Simon
            </div>
        </div>
    </section>

    <!-- Kontaktné údaje v štýle feature boxov z O mne -->
    <section class="about-features" style="padding-bottom: 60px;">
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-envelope"></i></div>
            <h3>E-mail</h3>
            <p><a href="mailto:info@milansimon.sk" style="color: inherit; text-decoration: none;">info@milansimon.sk</a></p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-phone"></i></div>
            <h3>Telefón</h3>
            <p>+421 900 000 000</p>
        </div>
        <div class="feature-box">
            <div class="feature-icon"><i class="fas fa-map-marker-alt"></i></div>
            <h3>Lokalita</h3>
            <p>Bratislava a celé Slovensko</p>
        </div>
    </section>

    <!-- Sekcia s formulárom -->
    <section style="padding: 60px 0 120px 0; border-top: 1px solid var(--color-border);">
        <div class="text-center" style="margin-bottom: 40px;">
            <h2 style="font-family: var(--font-heading); font-weight: 300; font-size: 2.2rem; color: var(--color-accent);">Napíšte mi správu</h2>
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
