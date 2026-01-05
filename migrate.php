<?php
/**
 * Миграция данных из JSON в SQLite
 * Запуск: php migrate.php
 */

require_once __DIR__ . '/src/config/functions.php';

$jsonFile = __DIR__ . '/data/cars.json';

if (!file_exists($jsonFile)) {
    echo "Файл cars.json не найден\n";
    exit(1);
}

$cars = json_decode(file_get_contents($jsonFile), true);

if (empty($cars)) {
    echo "Нет данных для миграции\n";
    exit(1);
}

echo "Миграция " . count($cars) . " автомобилей...\n";

foreach ($cars as $id => $car) {
    saveCar($id, $car);
    echo "✓ {$car['brand']} {$car['model']}\n";
}

echo "\nГотово! База данных: " . DB_PATH . "\n";
