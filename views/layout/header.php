<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milan Šimon Photography</title>
    <!-- Google Fonts: Montserrat (Elegantný/Prémiový) & Lato (Čistý text) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&family=Montserrat:wght@200;300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Cormorant+Garamond:wght@300;400;500;600&family=Raleway:wght@200;300;400;500&family=Poppins:wght@300;400;500&family=Lora:wght@400;500&family=Inter:wght@200;300;400&family=Bodoni+Moda:wght@400;500&family=Oswald:wght@300;400&family=Quicksand:wght@300;400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="main-header <?php echo isset($is_subpage) ? 'header-solid' : ''; ?>">
    <div class="header-container">
        <a href="/" class="logo">
            <img src="/assets/img/ms_logo_white.png" alt="Milan Šimon Logo" class="logo-img">
            <div class="logo-text-wrapper">
                <h1>MILAN ŠIMON</h1>
                <span>PHOTOGRAPHY</span>
            </div>
        </a>
        <nav class="main-nav">
            <ul>
                <li><a href="/o-mne">O mne</a></li>
                <li><a href="/kontakt">Kontakt</a></li>
            </ul>
        </nav>
        <div class="header-socials">
            <a href="https://www.facebook.com/milansimonphotography/" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
            <a href="https://www.instagram.com/milan_simon_photography/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
            <a href="https://www.linkedin.com/in/milansimon/" target="_blank" rel="noopener noreferrer"><i class="fab fa-linkedin-in"></i></a>
        </div>
        <button class="mobile-menu-btn">☰</button>
    </div>
</header>

<main class="<?php 
    $main_classes = [];
    if (isset($is_subpage)) $main_classes[] = 'subpage-main';
    if (isset($no_padding)) $main_classes[] = 'no-padding';
    if (isset($is_dark_subpage)) $main_classes[] = 'subpage-dark';
    echo implode(' ', $main_classes);
?>">
