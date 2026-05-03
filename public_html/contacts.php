<?php
require_once __DIR__ . '/../src/config/bootstrap.php';

require_once __DIR__ . '/../src/config/functions.php';
session_start();

$pageTitle = 'Контакты — ' . SITE_NAME;
$pageDescription = 'Контакты автосалона ' . SITE_NAME . '. Адрес: ' . ADDRESS . '. Телефон: ' . PHONE . '. Работаем ежедневно с 9:00 до 21:00.';

include __DIR__ . '/../templates/components/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <span class="page-hero__label">Контакты</span>
            <h1 class="page-hero__title">Свяжитесь с нами</h1>
            <p class="page-hero__subtitle">Приезжайте в наш автосалон или позвоните — мы всегда рады помочь</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact">
            <div class="contact__info">
                <h2>Наши контакты</h2>
                <div class="contact__items">
                    <div class="contact__item">
                        <span class="material-icons">location_on</span>
                        <div>
                            <strong>Адрес</strong>
                            <p><?= ADDRESS ?></p>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">phone</span>
                        <div>
                            <strong>Телефон</strong>
                            <a href="tel:<?= PHONE_RAW ?>"><?= PHONE ?></a>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">schedule</span>
                        <div>
                            <strong>Режим работы</strong>
                            <p>Ежедневно с 9:00 до 21:00</p>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">email</span>
                        <div>
                            <strong>Email</strong>
                            <a href="mailto:info@harbor.ru">info@harbor.ru</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact__form">
                <h3>Напишите нам</h3>
                <form data-type="contact">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-group">
                        <textarea name="message" placeholder="Ваше сообщение" rows="4"></textarea>
                    </div>
                    <input type="hidden" name="type" value="contact">
                    <button type="submit" class="btn btn--primary btn--full btn--lg">Отправить</button>
                    <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy.php">политикой конфиденциальности</a></p>
                </form>
            </div>
        </div>
        
        <div class="map">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A0&amp;source=constructor&amp;ll=37.507120%2C55.574120&amp;z=15&amp;pt=37.507120%2C55.574120" allowfullscreen loading="lazy"></iframe>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/components/footer.php'; ?>
