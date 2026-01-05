<?php
/**
 * Конфигурация сайта автосалона Харбор
 */

define('SITE_NAME', 'Харбор');
define('SITE_TITLE', 'Автосалон Харбор в Москве | Официальный дилер Chery и Kia');
define('SITE_DESCRIPTION', 'Автосалон Харбор — официальный дилер Chery и Kia в Москве. Продажа автомобилей, Trade-in, гарантия до 10 лет.');
define('SITE_KEYWORDS', 'автосалон, автомобиль, машина, chery, kia, trade-in, трейд-ин, москва');

define('PHONE', '+7 (906) 607-47-55');
define('PHONE_RAW', '+79066074755');
define('ADDRESS', 'г. Москва, Прокшинский про-кт, 11');
define('WORK_HOURS', 'Ежедневно с 9:00 до 21:00');

// Telegram Bot
define('TG_BOT_TOKEN', '8525422729:AAG2VoBTE1z-qNFJgDYfvml-ti9EGdfPEik');
define('TG_CHAT_ID', '2097270123');

// Пути
define('ROOT_PATH', dirname(__DIR__, 2));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads/cars');

// SQLite
define('DB_PATH', ROOT_PATH . '/data/database.sqlite');

// Админ
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); // password
