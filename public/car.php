<?php
require_once __DIR__ . '/../src/config/functions.php';
session_start();

$id = $_GET['id'] ?? '';
$car = getCarById($id);

if (!$car) {
    header('Location: /');
    exit;
}

$carName = $car['brand'] . ' ' . $car['model'];
$pageTitle = $carName . ' — купить в ' . SITE_NAME;
$pageDescription = 'Купить ' . $carName . ' от ' . formatPrice($car['price_from']) . '. Кредит, Trade-in, гарантия до 10 лет.';

include __DIR__ . '/../templates/components/header.php';
?>

<section class="car-page">
    <div class="container">
        <nav class="breadcrumbs" aria-label="Хлебные крошки">
            <a href="/">Главная</a>
            <span class="material-icons">chevron_right</span>
            <a href="/#catalog">Каталог</a>
            <span class="material-icons">chevron_right</span>
            <span><?= e($carName) ?></span>
        </nav>
        
        <div class="car-hero" itemscope itemtype="https://schema.org/Car">
            <meta itemprop="brand" content="<?= e($car['brand']) ?>">
            <meta itemprop="model" content="<?= e($car['model']) ?>">
            
            <div class="car-hero__gallery">
                <div class="car-hero__main">
                    <img src="<?= e($car['colors'][0]['image'] ?? '/img/placeholder.jpg') ?>" alt="<?= e($carName) ?>" id="mainImage" itemprop="image">
                    <?php if (!empty($car['is_new'])): ?>
                    <span class="car-hero__badge">Новинка</span>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($car['colors'])): ?>
                <div class="car-hero__colors">
                    <span class="car-hero__colors-label">Цвет кузова:</span>
                    <div class="car-hero__colors-list">
                        <?php foreach ($car['colors'] as $i => $color): ?>
                        <button class="color-btn <?= $i === 0 ? 'active' : '' ?>" 
                                style="background-color:<?= e($color['hex']) ?>" 
                                data-image="<?= e($color['image']) ?>"
                                data-name="<?= e($color['name']) ?>"
                                title="<?= e($color['name']) ?>"
                                aria-label="Цвет <?= e($color['name']) ?>">
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <span class="car-hero__colors-name" id="colorName"><?= e($car['colors'][0]['name'] ?? '') ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="car-hero__info">
                <div class="car-hero__brand"><?= e($car['brand']) ?></div>
                <h1 class="car-hero__title"><?= e($car['model']) ?></h1>
                
                <div class="car-hero__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <span class="car-hero__price-label">Цена от</span>
                    <span class="car-hero__price-value">
                        <meta itemprop="priceCurrency" content="RUB">
                        <span itemprop="price" content="<?= $car['price_from'] ?>"><?= formatPrice($car['price_from']) ?></span>
                    </span>
                </div>
                
                <div class="car-hero__specs">
                    <?php if (!empty($car['engine'])): ?>
                    <div class="car-hero__spec">
                        <span class="material-icons">local_gas_station</span>
                        <span><?= e($car['engine']) ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($car['transmission'])): ?>
                    <div class="car-hero__spec">
                        <span class="material-icons">settings</span>
                        <span><?= e($car['transmission']) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="car-hero__actions">
                    <button class="btn btn--primary btn--lg btn--full" data-modal="callback" data-car="<?= e($carName) ?>">
                        <span class="material-icons">shopping_cart</span>
                        Купить автомобиль
                    </button>
                    <button class="btn btn--outline btn--lg btn--full" data-modal="callback" data-car="<?= e($carName) ?> - тест-драйв">
                        <span class="material-icons">directions_car</span>
                        Записаться на тест-драйв
                    </button>
                </div>
                
                <div class="car-hero__benefits">
                    <div class="car-hero__benefit">
                        <span class="material-icons">verified</span>
                        <span>Гарантия до 10 лет</span>
                    </div>
                    <div class="car-hero__benefit">
                        <span class="material-icons">payments</span>
                        <span>Кредит от 0.1%</span>
                    </div>
                    <div class="car-hero__benefit">
                        <span class="material-icons">sync_alt</span>
                        <span>Trade-in</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($car['trims'])): ?>
<section class="section section--gray">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title">Комплектации</h2>
        </div>
        <div class="trims-grid">
            <?php foreach ($car['trims'] as $i => $trim): ?>
            <article class="trim-card <?= $i === 0 ? 'trim-card--popular' : '' ?>">
                <h3 class="trim-card__name"><?= e($trim['name']) ?></h3>
                <div class="trim-card__price"><?= formatPrice($trim['price']) ?></div>
                <?php if (!empty($trim['features'])): ?>
                <ul class="trim-card__features">
                    <?php foreach ($trim['features'] as $feature): ?>
                    <li><span class="material-icons">check</span> <?= e($feature) ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                <button class="btn btn--primary btn--full" data-modal="callback" data-car="<?= e($carName . ' ' . $trim['name']) ?>">
                    Заказать
                </button>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($car['specs'])): ?>
<section class="section">
    <div class="container">
        <div class="section__header">
            <h2 class="section__title">Характеристики</h2>
        </div>
        <div class="specs-table">
            <?php foreach ($car['specs'] as $label => $value): ?>
            <div class="specs-row">
                <span class="specs-label"><?= e($label) ?></span>
                <span class="specs-value"><?= e($value) ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="cta">
    <div class="container">
        <div class="cta__content">
            <h2>Интересует <?= e($carName) ?>?</h2>
            <p>Оставьте заявку и получите лучшее предложение</p>
            <button class="btn btn--white btn--lg" data-modal="callback" data-car="<?= e($carName) ?>">
                Получить предложение
            </button>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/components/footer.php'; ?>
