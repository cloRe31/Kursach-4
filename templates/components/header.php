<!DOCTYPE html>
<html lang="ru" class="lenis">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="description" content="<?= $pageDescription ?? SITE_DESCRIPTION ?>">
    <meta name="keywords" content="<?= SITE_KEYWORDS ?>">
    <meta name="robots" content="index, follow">
    <meta name="author" content="<?= SITE_NAME ?>">
    <meta name="theme-color" content="#c41e3a">
    <meta name="format-detection" content="telephone=no">
    <title><?= $pageTitle ?? SITE_TITLE ?></title>
    
    <!-- Canonical -->
    <link rel="canonical" href="https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?><?= parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/svg/favicon.svg">
    <link rel="apple-touch-icon" href="/img/apple-touch-icon.png">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?= $pageTitle ?? SITE_TITLE ?>">
    <meta property="og:description" content="<?= $pageDescription ?? SITE_DESCRIPTION ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?><?= $_SERVER['REQUEST_URI'] ?? '/' ?>">
    <meta property="og:image" content="https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?>/img/og-image.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="<?= SITE_NAME ?>">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $pageTitle ?? SITE_TITLE ?>">
    <meta name="twitter:description" content="<?= $pageDescription ?? SITE_DESCRIPTION ?>">
    <meta name="twitter:image" content="https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?>/img/og-image.jpg">
    
    <!-- Preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://unpkg.com/gsap@3/dist">
    <link rel="preload" href="https://unpkg.com/gsap@3/dist/gsap.min.js" as="script">
    <link rel="preload" href="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js" as="script">

    
    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons&display=swap">
    
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/css/style.css">
    
    <!-- Schema.org -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "AutoDealer",
        "name": "<?= SITE_NAME ?>",
        "description": "<?= SITE_DESCRIPTION ?>",
        "url": "https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?>",
        "logo": "https://<?= $_SERVER['HTTP_HOST'] ?? 'harbor.ru' ?>/img/logo.png",
        "telephone": "<?= PHONE ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= ADDRESS ?>",
            "addressLocality": "Москва",
            "addressCountry": "RU"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "55.574120",
            "longitude": "37.507120"
        },
        "openingHoursSpecification": {
            "@type": "OpeningHoursSpecification",
            "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
            "opens": "09:00",
            "closes": "21:00"
        },
        "priceRange": "₽₽₽",
        "sameAs": []
    }
    </script>
</head>
<body>
    <header class="header<?= !empty($darkHeader) ? ' header--dark' : '' ?>" id="header">
        <div class="container">
            <div class="header__inner">
                <a href="/" class="header__logo" aria-label="<?= SITE_NAME ?> - На главную"><?= SITE_NAME ?></a>
                
                <nav class="header__nav" aria-label="Основная навигация">
                    <a href="/" class="header__link">Главная</a>
                    <a href="/#catalog" class="header__link">Каталог</a>
                    <a href="/credit.php" class="header__link">Кредит</a>
                    <a href="/tradein.php" class="header__link">Trade-in</a>
                    <a href="/about.php" class="header__link">О нас</a>
                    <a href="/contacts.php" class="header__link">Контакты</a>
                </nav>
                
                <div class="header__actions">
                    <a href="tel:<?= PHONE_RAW ?>" class="header__phone" aria-label="Позвонить <?= PHONE ?>">
                        <span class="material-icons" aria-hidden="true">phone</span>
                        <span><?= PHONE ?></span>
                    </a>
                    <button class="btn btn--primary btn--sm" data-modal="callback">Заказать звонок</button>
                </div>
                
                <button class="header__burger" id="burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobileMenu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>
    
    <div class="mobile-menu" id="mobileMenu" aria-hidden="true">
        <div class="mobile-menu__inner">
            <nav class="mobile-menu__nav" aria-label="Мобильная навигация">
                <a href="/">Главная</a>
                <a href="/#catalog">Каталог</a>
                <a href="/credit.php">Кредит</a>
                <a href="/tradein.php">Trade-in</a>
                <a href="/about.php">О нас</a>
                <a href="/contacts.php">Контакты</a>
            </nav>
            <div class="mobile-menu__footer">
                <a href="tel:<?= PHONE_RAW ?>"><?= PHONE ?></a>
                <p><?= ADDRESS ?></p>
            </div>
        </div>
    </div>
    
    <main>
