document.addEventListener('DOMContentLoaded', () => {
    const footerToggle = document.getElementById('footerToggle');
    const body = document.body;
    const footer = document.getElementById('mainFooter');

    if (footerToggle && footer) {
        // Zisťujeme reálnu výšku footra bez toho "trčiaceho" tlačidla
        const updateFooterPosition = () => {
            if (!body.classList.contains('footer-open')) {
                // Posunieme ho pod obrazovku presne o jeho výšku
                const footerHeight = footer.offsetHeight;
                footer.style.bottom = `-${footerHeight}px`; 
                // A transformujeme naspäť na nulu (skrytý)
                footer.style.transform = `translateY(0)`; 
            } else {
                // Ak je otvorený, vysunieme ho nahor o jeho výšku
                const footerHeight = footer.offsetHeight;
                footer.style.transform = `translateY(-${footerHeight}px)`;
            }
        };

        // Skryť na začiatku po načítaní CSS
        setTimeout(updateFooterPosition, 50); 

        window.addEventListener('resize', updateFooterPosition);

        footerToggle.addEventListener('click', () => {
            body.classList.toggle('footer-open');
            updateFooterPosition();
        });
    }
});