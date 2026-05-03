<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../src/config/functions.php';
session_start();

$pageTitle = 'О компании — ' . SITE_NAME;
$pageDescription = 'Автосалон ' . SITE_NAME . ' — официальный дилер Chery и Kia в Москве. 8 лет на рынке, более 5000 довольных клиентов.';

include __DIR__ . '/../templates/components/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <span class="page-hero__label">О компании</span>
            <h1 class="page-hero__title">Автосалон <?= SITE_NAME ?></h1>
            <p class="page-hero__subtitle">Официальный дилер Chery и Kia в Москве. Работаем с 2016 года.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="about__grid">
            <div class="about__content">
                <h2>Надёжный партнёр в выборе автомобиля</h2>
                <p>Автосалон <?= SITE_NAME ?> — это команда профессионалов, которая помогает клиентам выбрать идеальный автомобиль уже более 8 лет.</p>
                <p>Мы являемся официальным дилером брендов Chery и Kia, что гарантирует подлинность автомобилей, официальную гарантию и качественное сервисное обслуживание.</p>
                <p>Наша миссия — сделать покупку автомобиля простой, прозрачной и выгодной для каждого клиента.</p>
                
                <div class="about__stats">
                    <div class="about__stat">
                        <strong>8+</strong>
                        <span>лет на рынке</span>
                    </div>
                    <div class="about__stat">
                        <strong>5000+</strong>
                        <span>довольных клиентов</span>
                    </div>
                    <div class="about__stat">
                        <strong>50+</strong>
                        <span>авто в наличии</span>
                    </div>
                </div>
            </div>
            <div class="about__image">
                <img src="/img/banner/sportage_banner.jpg" alt="Автосалон <?= SITE_NAME ?>" loading="lazy">
            </div>
        </div>
    </div>
</section>

<section class="section section--gray">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Наши ценности</span>
            <h2 class="section__title">Почему нам доверяют</h2>
        </div>
        <div class="benefits">
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">handshake</span></div>
                <h3 class="benefit__title">Честность</h3>
                <p class="benefit__text">Прозрачные условия, никаких скрытых платежей и навязанных услуг</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">workspace_premium</span></div>
                <h3 class="benefit__title">Качество</h3>
                <p class="benefit__text">Только новые автомобили с официальной гарантией производителя</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">support_agent</span></div>
                <h3 class="benefit__title">Сервис</h3>
                <p class="benefit__text">Индивидуальный подход и поддержка на всех этапах покупки</p>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/components/footer.php'; ?>
