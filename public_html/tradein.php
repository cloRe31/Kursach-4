<?php
require_once __DIR__ . '/../src/config/functions.php';
session_start();

$pageTitle = 'Trade-in — ' . SITE_NAME;
$pageDescription = 'Trade-in с выгодой до 150 000 ₽. Бесплатная оценка вашего автомобиля. Обмен старого авто на новый за 1 день.';
$cars = getCars();

include __DIR__ . '/../templates/components/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <span class="page-hero__label">Trade-in</span>
            <h1 class="page-hero__title">Обменяйте авто с выгодой до 150 000 ₽</h1>
            <p class="page-hero__subtitle">Бесплатная оценка вашего автомобиля. Обмен на новый за 1 день. Доплата или без неё.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="contact">
            <div class="contact__info">
                <h2>Как работает Trade-in</h2>
                <div class="contact__items">
                    <div class="contact__item">
                        <span class="material-icons">search</span>
                        <div>
                            <strong>1. Оценка</strong>
                            <p>Бесплатно оценим ваш автомобиль по рыночной стоимости</p>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">directions_car</span>
                        <div>
                            <strong>2. Выбор нового авто</strong>
                            <p>Выберите новый автомобиль из нашего каталога</p>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">description</span>
                        <div>
                            <strong>3. Оформление</strong>
                            <p>Оформим все документы за 1 день</p>
                        </div>
                    </div>
                    <div class="contact__item">
                        <span class="material-icons">key</span>
                        <div>
                            <strong>4. Получение</strong>
                            <p>Заберите новый автомобиль в тот же день</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contact__form">
                <h3>Оценить автомобиль</h3>
                <form data-type="tradein">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="message" placeholder="Марка и модель вашего авто">
                    </div>
                    <div class="form-group">
                        <select name="car">
                            <option value="">Какой авто хотите получить?</option>
                            <?php foreach ($cars as $car): ?>
                            <option value="<?= e($car['brand'] . ' ' . $car['model']) ?>"><?= e($car['brand'] . ' ' . $car['model']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="type" value="tradein">
                    <button type="submit" class="btn btn--primary btn--full btn--lg">Оценить бесплатно</button>
                    <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy.php">политикой конфиденциальности</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="section section--gray">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Преимущества</span>
            <h2 class="section__title">Почему Trade-in у нас выгоднее</h2>
        </div>
        <div class="benefits">
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">trending_up</span></div>
                <h3 class="benefit__title">Максимальная оценка</h3>
                <p class="benefit__text">Оцениваем ваш автомобиль по максимальной рыночной стоимости</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">card_giftcard</span></div>
                <h3 class="benefit__title">Дополнительная выгода</h3>
                <p class="benefit__text">До 150 000 ₽ дополнительной скидки при обмене</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">schedule</span></div>
                <h3 class="benefit__title">Быстрый обмен</h3>
                <p class="benefit__text">Весь процесс занимает 1 день — приехали и уехали на новом авто</p>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../templates/components/footer.php'; ?>
