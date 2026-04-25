document.addEventListener('DOMContentLoaded', () => {
    // 1. FOOTER LOGIC
    const footerToggle = document.getElementById('footerToggle');
    const body = document.body;
    const footer = document.getElementById('mainFooter');

    if (footerToggle && footer) {
        const updateFooterPosition = () => {
            if (!body.classList.contains('footer-open')) {
                const footerHeight = footer.offsetHeight;
                footer.style.bottom = `-${footerHeight}px`; 
                footer.style.transform = `translateY(0)`; 
            } else {
                const footerHeight = footer.offsetHeight;
                footer.style.transform = `translateY(-${footerHeight}px)`;
            }
        };

        setTimeout(updateFooterPosition, 50); 
        window.addEventListener('resize', updateFooterPosition);

        footerToggle.addEventListener('click', () => {
            body.classList.toggle('footer-open');
            updateFooterPosition();
        });
    }

    // 2. LIGHTBOX LOGIC
    const portfolioImages = document.querySelectorAll('.portfolio-img img');
    const lightbox = document.getElementById('lightbox');
    
    if (lightbox && portfolioImages.length > 0) {
        const lightboxImg = document.getElementById('lightboxImg');
        const closeBtn = document.getElementById('lightboxClose');
        const prevBtn = document.getElementById('lightboxPrev');
        const nextBtn = document.getElementById('lightboxNext');
        
        let currentIndex = 0;
        const imagesArray = Array.from(portfolioImages);

        // Otvorenie lightboxu
        portfolioImages.forEach((img, index) => {
            img.parentElement.addEventListener('click', () => {
                currentIndex = index;
                showImage(currentIndex);
                lightbox.classList.add('active');
                body.style.overflow = 'hidden'; // Zamedzí scrollovaniu stránky na pozadí
            });
        });

        // Funkcia na zobrazenie konkrétneho obrázka
        const showImage = (index) => {
            // Cirkulácia indexu (aby sme mohli prechádzať dokola)
            if (index >= imagesArray.length) currentIndex = 0;
            if (index < 0) currentIndex = imagesArray.length - 1;
            
            const src = imagesArray[currentIndex].getAttribute('data-src') || imagesArray[currentIndex].src;
            lightboxImg.src = src;
        };

        // Zatvorenie
        const closeLightbox = () => {
            lightbox.classList.remove('active');
            body.style.overflow = '';
            // Počkáme na koniec animácie a vymažeme src (voliteľné)
            setTimeout(() => { lightboxImg.src = ''; }, 400);
        };

        closeBtn.addEventListener('click', closeLightbox);
        
        // Zatvorenie kliknutím mimo obrázka
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) closeLightbox();
        });

        // Posun vpred a vzad
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation(); // Zabráni prebublaniu kliku na pozadie (čo by zavrelo lightbox)
            currentIndex++;
            showImage(currentIndex);
        });

        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIndex--;
            showImage(currentIndex);
        });

                // Ovládanie šípkami na klávesnici
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') { currentIndex++; showImage(currentIndex); }
            if (e.key === 'ArrowLeft') { currentIndex--; showImage(currentIndex); }
        });
    }

    // 3. SCROLL ANIMATION LOGIC (Nástupná animácia pre Masonry grid)
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1 // Stačí, ak vykukne 10% fotky a spustí sa animácia
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                // Pridáme trošku delay, aby sa nenačítali naraz ak ich je v riadku viac
                setTimeout(() => {
                    entry.target.classList.add('is-visible');
                }, index * 50); // 50ms oneskorenie pre kaskádový efekt
                
                // Po zobrazení môžeme prestať sledovať tento konkrétny element
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Nájdeme všetky fotky v grid-e
    const masonryItems = document.querySelectorAll('.masonry-item-full');
    masonryItems.forEach(item => {
        observer.observe(item);
    });
});