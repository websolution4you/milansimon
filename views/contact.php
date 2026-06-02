<?php 
$is_subpage = true; 

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    $consent = isset($_POST['consent']) ? $_POST['consent'] : '';
    
    if (empty($name) || empty($email) || empty($message) || empty($consent)) {
        $error_message = 'Prosím, vyplňte všetky povinné polia a odsúhlaste spracovanie osobných údajov.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Prosím, zadajte platnú emailovú adresu.';
    } else {
        // Skúsime odoslať email fotografovi
        $to = 'msphotography@milansimon.com';
        $from_email = 'msphotography@milansimon.com'; // Doménový odosielateľ na WebSupporte
        $email_subject = 'Nový dopyt z webu: ' . (empty($subject) ? 'Kontakt' : $subject);
        
        $email_body = "Dostali ste novú správu z kontaktného formulára na webe Milan Šimon Photography.\n\n";
        $email_body .= "Meno: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Predmet: " . (empty($subject) ? 'Nie je uvedený' : $subject) . "\n\n";
        $email_body .= "Správa:\n$message\n";
        
        // Na WebSupporte a iných hostingoch musí "From" email existovať na danej doméne, aby mail prešiel spamfiltrom
        $headers = "From: $from_email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        $mail_sent = false;
        if (isset($_SERVER['HTTP_HOST']) && (strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false)) {
            // V lokálnom prostredí simulujeme úspech pre testovacie účely
            $mail_sent = true;
        } else {
            // Na live serveri pridávame 5. parameter "-f", ktorý nastavuje Envelope Sender (Return-Path), čo je kľúčové pre prechod cez SPF na WebSupporte
            $mail_sent = @mail($to, $email_subject, $email_body, $headers, "-f" . $from_email);
        }
        
        if ($mail_sent) {
            $success_message = 'Vaša správa bola úspešne odoslaná! Budem vás kontaktovať čo najskôr.';
            // Vyčistíme polia po úspešnom odoslaní
            $_POST = [];
        } else {
            $error_message = 'Odoslanie správy zlyhalo. Prosím, napíšte mi priamo na e-mail: ' . htmlspecialchars($to);
        }
    }
}

require __DIR__ . '/layout/header.php'; 
?>

<div class="container">
    <!-- Hero sekcia pre Kontakt - v štýle O mne -->
    <section class="about-hero">
        <div class="about-hero-image contact-hero-image">
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

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" style="max-width: 600px; margin: 0 auto 30px auto; padding: 20px; background-color: #f7f7f7; border: 1px solid #dddddd; border-radius: 8px; color: #111111; font-family: var(--font-body); display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-check-circle" style="font-size: 1.5rem; color: #111111;"></i>
                <div><?php echo htmlspecialchars($success_message); ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" style="max-width: 600px; margin: 0 auto 30px auto; padding: 20px; background-color: rgba(220, 53, 69, 0.1); border: 1px solid #dc3545; border-radius: 8px; color: #dc3545; font-family: var(--font-body); display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
                <div><?php echo htmlspecialchars($error_message); ?></div>
            </div>
        <?php endif; ?>

        <form class="contact-form" action="/kontakt" method="POST">
            <div class="form-row">
                <div class="form-group" style="flex: 1;">
                    <label for="name">Meno *</label>
                    <input type="text" id="name" name="name" required placeholder="Vaše meno" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="Váš email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="subject">Predmet</label>
                <input type="text" id="subject" name="subject" placeholder="O čo sa jedná?" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="message">Správa</label>
                <textarea id="message" name="message" rows="6" required placeholder="Napíšte vašu predstavu..."><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
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
