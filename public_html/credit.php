<?php
require_once __DIR__ . '/../src/config/functions.php';
session_start();

$pageTitle = 'Автокредит — ' . SITE_NAME;
$pageDescription = 'Автокредит от 0.1% годовых. Одобрение за 15 минут. Первый взнос от 0%. Работаем с ведущими банками.';
$cars = getCars();
$banks = getBanks();

include __DIR__ . '/../templates/components/header.php';
?>

<section class="page-hero">
    <div class="container">
        <div class="page-hero__content">
            <span class="page-hero__label">Автокредит</span>
            <h1 class="page-hero__title">Кредит от 0.1% годовых</h1>
            <p class="page-hero__subtitle">Одобрение за 15 минут. Первый взнос от 0%. Работаем с ведущими банками России.</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="calculator">
            <div class="calculator__form">
                <h3 class="calculator__title">Рассчитайте кредит</h3>
                
                <div class="calculator__group">
                    <label>Стоимость автомобиля</label>
                    <div class="calculator__range">
                        <input type="range" id="carPrice" min="1000000" max="5000000" step="100000" value="2500000">
                        <div class="calculator__range-values">
                            <span>1 000 000 ₽</span>
                            <span>5 000 000 ₽</span>
                        </div>
                        <div class="calculator__range-current" id="carPriceValue">2 500 000 ₽</div>
                    </div>
                </div>
                
                <div class="calculator__group">
                    <label>Первоначальный взнос</label>
                    <div class="calculator__range">
                        <input type="range" id="downPayment" min="0" max="80" step="5" value="20">
                        <div class="calculator__range-values">
                            <span>0%</span>
                            <span>80%</span>
                        </div>
                        <div class="calculator__range-current" id="downPaymentValue">20% (500 000 ₽)</div>
                    </div>
                </div>
                
                <div class="calculator__group">
                    <label>Срок кредита</label>
                    <div class="calculator__range">
                        <input type="range" id="loanTerm" min="12" max="96" step="12" value="60">
                        <div class="calculator__range-values">
                            <span>1 год</span>
                            <span>8 лет</span>
                        </div>
                        <div class="calculator__range-current" id="loanTermValue">5 лет</div>
                    </div>
                </div>
                
                <button class="btn btn--primary btn--full btn--lg" data-modal="callback" data-car="Заявка на кредит">
                    Оставить заявку
                </button>
            </div>
            
            <div class="calculator__result">
                <h3>Результат расчёта</h3>
                <div class="calculator__payment">
                    <span>Ежемесячный платёж</span>
                    <strong id="monthlyPayment">42 500 ₽</strong>
                </div>
                <div class="calculator__details">
                    <div class="calculator__details-row">
                        <span>Сумма кредита</span>
                        <strong id="loanAmount">2 000 000 ₽</strong>
                    </div>
                    <div class="calculator__details-row">
                        <span>Процентная ставка</span>
                        <strong>от 0.1%</strong>
                    </div>
                    <div class="calculator__details-row">
                        <span>Переплата</span>
                        <strong id="overpayment">550 000 ₽</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section section--gray">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Партнёры</span>
            <h2 class="section__title">Работаем с ведущими банками</h2>
        </div>
        <div class="banks-grid">
            <?php foreach ($banks as $bank): ?>
            <div class="bank-card">
                <img src="/img/banks/<?= strtolower(str_replace([' ', '-'], '', $bank['svg'])) ?>.svg" alt="<?= e($bank['name']) ?>" loading="lazy">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section__header">
            <span class="section__label">Преимущества</span>
            <h2 class="section__title">Почему кредит у нас выгоднее</h2>
        </div>
        <div class="benefits">
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">speed</span></div>
                <h3 class="benefit__title">Быстрое одобрение</h3>
                <p class="benefit__text">Решение по кредиту за 15 минут. Минимум документов.</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">percent</span></div>
                <h3 class="benefit__title">Низкая ставка</h3>
                <p class="benefit__text">Ставка от 0.1% благодаря субсидиям от производителей.</p>
            </article>
            <article class="benefit">
                <div class="benefit__icon"><span class="material-icons">account_balance_wallet</span></div>
                <h3 class="benefit__title">Без первого взноса</h3>
                <p class="benefit__text">Возможность оформить кредит без первоначального взноса.</p>
            </article>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const price = document.getElementById('carPrice');
    const down = document.getElementById('downPayment');
    const term = document.getElementById('loanTerm');
    
    const format = n => n.toLocaleString('ru-RU') + ' ₽';
    
    const calc = () => {
        const p = +price.value;
        const d = +down.value / 100;
        const t = +term.value;
        const loan = p * (1 - d);
        const rate = 0.12 / 12;
        const monthly = loan * rate * Math.pow(1 + rate, t) / (Math.pow(1 + rate, t) - 1);
        const total = monthly * t;
        const over = total - loan;
        
        document.getElementById('carPriceValue').textContent = format(p);
        document.getElementById('downPaymentValue').textContent = down.value + '% (' + format(p * d) + ')';
        document.getElementById('loanTermValue').textContent = (t / 12) + (t === 12 ? ' год' : t <= 48 ? ' года' : ' лет');
        document.getElementById('monthlyPayment').textContent = format(Math.round(monthly));
        document.getElementById('loanAmount').textContent = format(loan);
        document.getElementById('overpayment').textContent = format(Math.round(over));
    };
    
    [price, down, term].forEach(el => el.addEventListener('input', calc));
    calc();
});
</script>

<?php include __DIR__ . '/../templates/components/footer.php'; ?>
