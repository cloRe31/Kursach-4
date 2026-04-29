    </main>
    
    <!-- Pre-footer -->
    <section class="prefooter">
        <div class="container">
            <div class="prefooter__inner">
                <div class="prefooter__content">
                    <h2>Остались вопросы?</h2>
                    <p>Оставьте заявку и мы перезвоним за 15 минут</p>
                </div>
                <button class="btn btn--white btn--lg" data-modal="callback">
                    <span class="material-icons">phone_callback</span>
                    Заказать звонок
                </button>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div>
                    <a href="/" class="footer__logo"><?= SITE_NAME ?></a>
                    <p class="footer__desc">Официальный дилер Chery и Kia в Москве. Продажа, кредит, trade-in, сервис.</p>
                    <div class="footer__social">
                        <a href="#" aria-label="Telegram"><img src="/public_html/svg/TELEGRAM LOGO.svg" alt="" width="20" height="20"></a>
                        <a href="#" aria-label="VK"><img src="/public_html/svg/VK LOGO.svg" alt="" width="20" height="20"></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="footer__title">Каталог</h4>
                    <nav class="footer__nav">
                        <?php $footerCars = getCars(); foreach (array_slice($footerCars, 0, 5, true) as $id => $car): ?>
                        <a href="/car.php?id=<?= $id ?>"><?= e($car['brand'] . ' ' . $car['model']) ?></a>
                        <?php endforeach; ?>
                    </nav>
                </div>
                
                <div>
                    <h4 class="footer__title">Услуги</h4>
                    <nav class="footer__nav">
                        <a href="/public_html/credit.php">Автокредит</a>
                        <a href="/public_html/tradein.php">Trade-in</a>
                        <a href="/public_html/about.php">О компании</a>
                        <a href="/public_html/contacts.php">Контакты</a>
                        <a href="/public_html/privacy.php">Политика конфиденциальности</a>
                    </nav>
                </div>
                
                <div>
                    <h4 class="footer__title">Контакты</h4>
                    <div class="footer__contact">
                        <span class="material-icons">phone</span>
                        <a href="tel:<?= PHONE_RAW ?>"><?= PHONE ?></a>
                    </div>
                    <div class="footer__contact">
                        <span class="material-icons">location_on</span>
                        <span><?= ADDRESS ?></span>
                    </div>
                    <div class="footer__contact">
                        <span class="material-icons">schedule</span>
                        <span>Ежедневно 9:00 — 21:00</span>
                    </div>
                </div>
            </div>
            
            <div class="footer__bottom">
                <p>&copy; <?= date('Y') ?> <?= SITE_NAME ?>. Все права защищены.</p>
                <p>Информация не является публичной офертой</p>
            </div>
        </div>
    </footer>
    
    <!-- Modal -->
    <div class="modal" id="callbackModal">
        <div class="modal__overlay"></div>
        <div class="modal__container">
            <div class="modal__content">
                <button class="modal__close" aria-label="Закрыть">
                    <span class="material-icons">close</span>
                </button>
                <div class="modal__icon">
                    <span class="material-icons">phone_in_talk</span>
                </div>
                <h3 class="modal__title">Заказать звонок</h3>
                <p class="modal__subtitle">Оставьте номер и мы перезвоним за 15 минут</p>
                <form class="modal__form" data-type="callback">
                    <div class="form-group">
                        <input type="text" name="name" placeholder="Ваше имя" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="+7 (___) ___-__-__" required>
                    </div>
                    <input type="hidden" name="car" id="modalCar" value="">
                    <input type="hidden" name="type" value="callback">
                    <button type="submit" class="btn btn--primary btn--full btn--lg">Жду звонка</button>
                    <p class="modal__privacy">Нажимая кнопку, вы соглашаетесь с <a href="/public_html/privacy.php">политикой конфиденциальности</a></p>
                </form>
                <div class="modal__success" style="display:none">
                    <span class="material-icons">check_circle</span>
                    <h4>Заявка отправлена!</h4>
                    <p>Мы перезвоним вам в ближайшее время</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://unpkg.com/lenis@1/dist/lenis.min.js"></script>
    <script src="https://unpkg.com/gsap@3/dist/gsap.min.js"></script>
    <script src="https://unpkg.com/gsap@3/dist/ScrollTrigger.min.js"></script>
    <script src="/public_html/js/animations.js"></script>
    <script src="/public_html/js/app.js"></script>
</body>
</html>
