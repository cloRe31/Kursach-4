<?php
require_once __DIR__ . '/src/config/bootstrap.php';
require_once __DIR__ . '/src/config/functions.php';
session_start();

$pageTitle = SITE_TITLE;
$pageDescription = SITE_DESCRIPTION;
$darkHeader = true;
$cars = getCars();

include __DIR__ . '/templates/components/header.php';
?>

<!-- Hero -->
<section class="hero">
    <div class="hero__bg">
        <div class="hero-slider">
            <div class="swiper" id="heroSlider">
                <div class="swiper-wrapper">
                    <?php foreach (array_slice($cars, 0, 3, true) as $id => $car): ?>
                    <div class="swiper-slide">
                        <img src="<?= e($car['banner']) ?>" alt="<?= e($car['brand'] . ' ' . $car['model']) ?>">
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
    <div class="hero__content">
        <span class="hero__badge">Официальный дилер</span>
        <h1 class="hero__title">Автомобили <span>в наличии</span><br>с выгодой до <span>300 000 ₽</span></h1>
        <p class="hero__subtitle">Кредит от 0.1% · Trade-in с выгодой · Гарантия до 10 лет</p>
        <div class="hero__actions">
            <a href="#catalog" class="btn btn--primary btn--lg">
                <span>Смотреть каталог</span>
                <span class="material-icons">arrow_forward</span>
            </a>
            <button class="btn btn--outline-light btn--lg" data-modal="callback">
                <span class="material-icons">phone</span>
                <span>Заказать звонок</span>
            </button>
        </div>
        <div class="hero__stats">
            <div class="hero__stat">
                <span class="hero__stat-num">8+</span>
                <span class="hero__stat-text">лет на рынке</span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-num">5000+</span>
                <span class="hero__stat-text">довольных клиентов</span>
            </div>
            <div class="hero__stat">
                <span class="hero__stat-num">50+</span>
                <span class="hero__stat-text">авто в наличии</span>
            </div>
        </div>
    </div>
    <div class="hero__scroll">
        <span class="material-icons">expand_more</span>
    </div>
</section>

<!-- Benefits -->
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Преимущества</span>
            <h2 class="section__title">Почему выбирают нас</h2>
            <p class="section__subtitle">Покупка автомобиля в <?= SITE_NAME ?> — это просто, выгодно и надёжно</p>
        </div>
        <div class="benefits">
            <article class="benefit">
                <div class="benefit__icon"><img src="/public_html/svg/ИКОНКА ЩИТ КРАСНАЯ.svg" alt="" width="28" height="28"></div>
                <h3 class="benefit__title">Официальная гарантия</h3>
                <p class="benefit__text">Гарантия производителя до 10 лет или 1 000 000 км пробега</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><img src="/public_html/svg/ИКОНКА МОЛНИИ КРАСНАЯ.svg" alt="" width="28" height="28"></div>
                <h3 class="benefit__title">Быстрое оформление</h3>
                <p class="benefit__text">Оформление документов за 1 день. Выезд в тот же день</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><img src="/public_html/svg/ИКОНКА ЛАЙК КРАСНАЯ.svg" alt="" width="28" height="28"></div>
                <h3 class="benefit__title">Выгодные условия</h3>
                <p class="benefit__text">Кредит от 0.1%, рассрочка без переплат, Trade-in</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><img src="/public_html/svg/ИКОНКА ОГНЯ КРАСНАЯ.svg" alt="" width="28" height="28"></div>
                <h3 class="benefit__title">Подарки покупателям</h3>
                <p class="benefit__text">Комплект резины, защита картера или скидка до 300 000 ₽</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><img src="/public_html/svg/ИКОНКА ПОВТОРА (СТРЕЛКИ) КРАСНАЯ.svg" alt="" width="28" height="28"></div>
                <h3 class="benefit__title">Trade-in</h3>
                <p class="benefit__text">Обменяйте старый автомобиль с выгодой до 150 000 ₽</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">credit_card</span></div>
                <h3 class="benefit__title">Любая форма оплаты</h3>
                <p class="benefit__text">Наличные, безналичный расчёт, кредит, лизинг</p>
            </article>
        </div>
    </div>
</section>

<!-- Catalog -->
<section class="section section--gray" id="catalog">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Каталог</span>
            <h2 class="section__title">Автомобили в наличии</h2>
            <p class="section__subtitle">Выберите свой идеальный автомобиль</p>
        </div>
        <div class="cars-grid">
            <?php foreach ($cars as $id => $car): ?>
            <article class="car-card" itemscope itemtype="https://schema.org/Car">
                <a href="/public_html/car.php?id=<?= $id ?>" class="car-card__link">
                    <?php if (!empty($car['is_new'])): ?>
                    <span class="car-card__badge">Новинка</span>
                    <?php endif; ?>
                    <div class="car-card__image">
                        <?php if (!empty($car['colors'][0]['image'])): ?>
                        <img src="<?= e($car['colors'][0]['image']) ?>" alt="<?= e($car['brand'] . ' ' . $car['model']) ?>" loading="lazy" itemprop="image">
                        <?php endif; ?>
                    </div>
                    <div class="car-card__content">
                        <div class="car-card__header">
                            <span class="car-card__brand" itemprop="brand"><?= e($car['brand']) ?></span>
                            <div class="car-card__rating">
                                <span class="material-icons">star</span>
                                <span>4.9</span>
                            </div>
                        </div>
                        <h3 class="car-card__model" itemprop="model"><?= e($car['model']) ?></h3>
                        <div class="car-card__specs">
                            <span><span class="material-icons">local_gas_station</span> <?= e($car['engine']) ?></span>
                            <span><span class="material-icons">settings</span> <?= e($car['transmission']) ?></span>
                        </div>
                        <?php if (count($car['colors']) > 1): ?>
                        <div class="car-card__colors">
                            <?php foreach (array_slice($car['colors'], 0, 4) as $color): ?>
                            <span class="car-card__colors-dot" style="background:<?= e($color['hex']) ?>"></span>
                            <?php endforeach; ?>
                            <?php if (count($car['colors']) > 4): ?>
                            <span class="car-card__colors-more">+<?= count($car['colors']) - 4 ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <div class="car-card__footer">
                            <div class="car-card__price" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <meta itemprop="priceCurrency" content="RUB">
                                <span itemprop="price" content="<?= $car['price_from'] ?>">от <?= formatPrice($car['price_from']) ?></span>
                            </div>
                            <span class="car-card__more">Подробнее <span class="material-icons">arrow_forward</span></span>
                        </div>
                    </div>
                </a>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Offers -->
<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Спецпредложения</span>
            <h2 class="section__title">Выгодные условия покупки</h2>
        </div>
        <div class="offers">
            <article class="offer offer--credit">
                <div class="offer__icon"><span class="material-icons">account_balance</span></div>
                <h3 class="offer__title">Кредит от 0.1%</h3>
                <p class="offer__text">Одобрение за 15 минут. Первый взнос от 0%. Срок до 8 лет.</p>
                <a href="/public_html/credit.php" class="btn btn--outline-light">Рассчитать кредит</a>
            </article>
            <article class="offer offer--tradein">
                <div class="offer__icon"><span class="material-icons">sync_alt</span></div>
                <h3 class="offer__title">Trade-in +150 000 ₽</h3>
                <p class="offer__text">Обменяйте старый автомобиль на новый с дополнительной выгодой</p>
                <a href="/public_html/tradein.php" class="btn btn--outline-light">Оценить авто</a>
            </article>
            <article class="offer offer--gift">
                <div class="offer__icon"><span class="material-icons">card_giftcard</span></div>
                <h3 class="offer__title">Подарки покупателям</h3>
                <p class="offer__text">Зимняя резина, защита картера, сигнализация в подарок</p>
                <button class="btn btn--outline-light" data-modal="callback">Узнать подробнее</button>
            </article>
        </div>
    </div>
</section>

<!-- Steps -->
<section class="section section--gray">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Как купить</span>
            <h2 class="section__title">4 простых шага к вашему авто</h2>
        </div>
        <div class="steps">
            <article class="step">
                <h3 class="step__title">Выберите автомобиль</h3>
                <p class="step__text">Изучите каталог или позвоните нам для консультации</p>
            </article>
            <article class="step">
                <h3 class="step__title">Приезжайте на тест-драйв</h3>
                <p class="step__text">Оцените автомобиль лично и задайте все вопросы</p>
            </article>
            <article class="step">
                <h3 class="step__title">Оформите покупку</h3>
                <p class="step__text">Выберите способ оплаты: кредит, рассрочка или наличные</p>
            </article>
            <article class="step">
                <h3 class="step__title">Заберите автомобиль</h3>
                <p class="step__text">Получите ключи и документы в день оформления</p>
            </article>
        </div>
    </div>
</section>

<!-- Contact -->
<section class="section">
    <div class="container">
        <div class="contact">
            <div class="contact__info">
                <h2>Приезжайте к нам</h2>
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
                </div>
            </div>
            <div class="contact__form">
                <h3>Запишитесь на тест-драйв</h3>
                <form data-type="testdrive">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <div class="form-group">
                        <select name="car">
                            <option value="">Выберите автомобиль</option>
                            <?php foreach ($cars as $car): ?>
                            <option value="<?= e($car['brand'] . ' ' . $car['model']) ?>"><?= e($car['brand'] . ' ' . $car['model']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <input type="hidden" name="type" value="testdrive">
                    <button type="submit" class="btn btn--primary btn--full btn--lg">Записаться</button>
                    <p class="form-privacy">Нажимая кнопку, вы соглашаетесь с <a href="/privacy.php">политикой конфиденциальности</a></p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    new Swiper('#heroSlider', {
        loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        effect: 'fade',
        fadeEffect: { crossFade: true },
        pagination: { el: '.swiper-pagination', clickable: true }
    });
});
</script>

<?php include __DIR__ . '/templates/components/footer.php'; ?>
