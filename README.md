# Автосалон Харбор

Современный лендинг автосалона с админ-панелью и интеграцией Telegram.

## Стек технологий

- **PHP 7.4+** — серверная логика
- **SCSS** — модульные стили с переменными и миксинами
- **Vanilla JS** — интерактивность без зависимостей
- **SQLite** — хранение данных
- **Swiper** — слайдеры
- **GSAP + Lenis** — анимации и плавный скролл

## Структура проекта

```
harbor/
├── public/              # Document root
│   ├── index.php        # Главная страница
│   ├── car.php          # Страница автомобиля
│   ├── credit.php       # Кредит
│   ├── tradein.php      # Trade-in
│   ├── about.php        # О компании
│   ├── contacts.php     # Контакты
│   ├── privacy.php      # Политика конфиденциальности
│   ├── api/             # API endpoints
│   ├── admin/           # Админ-панель
│   ├── css/             # Скомпилированные стили
│   ├── js/              # JavaScript
│   ├── img/             # Изображения
│   ├── fonts/           # Шрифты
│   └── svg/             # Иконки
├── src/config/
│   ├── config.php       # Конфигурация
│   └── functions.php    # Функции
├── scss/                # Исходники стилей
│   ├── base/            # Переменные, миксины, reset, кнопки
│   ├── components/      # Hero, секции, карточки, модалки, слайдер
│   ├── layout/          # Header, footer
│   └── pages/           # Стили страниц
├── templates/components/
│   ├── header.php       # Шапка
│   └── footer.php       # Подвал
└── data/                # SQLite база данных
```

## Установка

1. **Настройте веб-сервер** с document root на `public/`

2. **Конфигурация** в `src/config/config.php`:
   ```php
   define('TG_BOT_TOKEN', 'ваш_токен_бота');
   define('TG_CHAT_ID', 'ваш_chat_id');
   ```

3. **Права на запись**:
   ```bash
   chmod 755 data/
   ```

## Компиляция SCSS

```bash
# Однократная компиляция
sass scss/main.scss public/css/style.css --style=compressed

# Режим наблюдения
sass --watch scss/main.scss:public/css/style.css
```

## Функционал

### Для посетителей:
- Hero-слайдер с автомобилями
- Каталог с карточками авто
- Выбор цветов кузова
- Комплектации и характеристики
- Калькулятор кредита
- Формы заявок с маской телефона
- Плавные анимации (GSAP)
- Smooth scroll (Lenis)
- Адаптивный дизайн

### Для администратора:
- Управление автомобилями
- Просмотр заявок
- Уведомления в Telegram

## SEO

- Семантическая HTML5 разметка
- Schema.org (AutoDealer, Car, Offer)
- Open Graph теги
- Canonical URLs
- Оптимизированные meta-теги
- Lazy loading изображений

## Библиотеки

- [Swiper](https://swiperjs.com/) — слайдеры
- [GSAP](https://greensock.com/gsap/) — анимации
- [Lenis](https://lenis.studiofreight.com/) — smooth scroll
- [Material Icons](https://fonts.google.com/icons) — иконки

## Админ-панель

- URL: `/admin/`
- Логин: `admin`
- Пароль: `password`

## Лицензия

MIT
