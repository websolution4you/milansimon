<?php require __DIR__ . '/layout/header.php'; ?>

<div class="container">
    <section style="padding: var(--spacing-xl) 0; text-align: center;">
        <h2 style="font-family: var(--font-heading); font-weight: 300; font-size: 2.5rem; color: var(--color-accent); margin-bottom: var(--spacing-md);">Kontakt</h2>
        <p style="color: var(--color-text-light);">Zaujalo vás niektoré z portfólií alebo hľadáte fotografa na konkrétnu spoluprácu? Napíšte mi.</p>
    </section>

    <form class="contact-form" action="#" method="POST">
        <div style="display: flex; gap: var(--spacing-md); width: 100%;">
            <div class="form-group" style="flex: 1;">
                <label for="name">Meno *</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="subject">Predmet</label>
            <input type="text" id="subject" name="subject">
        </div>
        
        <div class="form-group">
            <label for="message">Správa</label>
            <textarea id="message" name="message" rows="6" required></textarea>
        </div>
        
        <div class="form-group" style="flex-direction: row; align-items: center;">
            <input type="checkbox" id="consent" name="consent" required style="width: auto;">
            <label for="consent" style="margin-bottom: 0;">Súhlasím so spracovaním osobných údajov. *</label>
        </div>
        
        <button type="submit" class="btn-submit">Odoslať</button>
    </form>
</div>

<?php require __DIR__ . '/layout/footer.php'; ?>
