#!/usr/bin/env php
<?php
/**
 * Парсер автомобилей для автосалона Харбор
 * - Парсит данные с avtogermes.ru (цены, характеристики, комплектации)
 * - Использует локальные фото из папки public/img
 * - Заполняет SQLite базу данных
 */

error_reporting(E_ERROR | E_PARSE);

define('BASE_DIR', __DIR__);
define('DB_PATH', BASE_DIR . '/data/database.sqlite');
define('IMG_DIR', BASE_DIR . '/public/img');

// Маппинг цветов: ключевые слова в имени файла -> название и hex
// ВАЖНО: более специфичные ключи должны быть первыми!
$COLOR_MAP = [
    'black-pearl' => ['Черный жемчуг', '#1a1a1a'],
    'white-pearl' => ['Белый перламутр', '#f5f5f5'],
    'clear-white' => ['Белый', '#ffffff'],
    'deep-black' => ['Глубокий черный', '#0a0a0a'],
    'deep-blue' => ['Глубокий синий', '#1e3a5f'],
    'sky-blue' => ['Небесно-синий', '#0284c7'],
    'gravity-blue' => ['Синий гравити', '#1e40af'],
    'gravity-gray' => ['Серый гравити', '#4b5563'],
    'horizon-blue' => ['Горизонт синий', '#3b82f6'],
    'neptune-blue' => ['Нептун синий', '#0369a1'],
    'yacht-blue' => ['Яхтенный синий', '#1d4ed8'],
    'interstellar-gray' => ['Серый космос', '#52525b'],
    'steel-gray' => ['Стальной серый', '#71717a'],
    'ocean-green' => ['Океан зеленый', '#0e7490'],
    'jungle-green' => ['Джунгли', '#14532d'],
    'runway-red' => ['Красный', '#b91c1c'],
    'dawning-red' => ['Рассветный красный', '#991b1b'],
    'modern-black' => ['Черный', '#1f2937'],
    'white' => ['Белый', '#ffffff'],
    'pearl' => ['Перламутр', '#f5f5f5'],
    'clear' => ['Белый', '#ffffff'],
    'black' => ['Черный', '#1a1a1a'],
    'gray' => ['Серый', '#6b7280'],
    'steel' => ['Стальной', '#71717a'],
    'interstellar' => ['Космос', '#52525b'],
    'gravity' => ['Гравити', '#4b5563'],
    'silver' => ['Серебристый', '#a1a1aa'],
    'blue' => ['Синий', '#1e40af'],
    'deep' => ['Глубокий', '#1e3a5f'],
    'sky' => ['Небесный', '#0284c7'],
    'yacht' => ['Яхтенный', '#1d4ed8'],
    'horizon' => ['Горизонт', '#3b82f6'],
    'neptune' => ['Нептун', '#0369a1'],
    'ocean' => ['Океан', '#0e7490'],
    'green' => ['Зеленый', '#166534'],
    'jungle' => ['Джунгли', '#14532d'],
    'red' => ['Красный', '#dc2626'],
    'runway' => ['Красный', '#b91c1c'],
    'dawning' => ['Рассветный', '#991b1b'],
    'modern' => ['Черный', '#1f2937'],
];

// Fallback данные для автомобилей (если парсинг не удался)
$FALLBACK_DATA = [
    'kia-sportage' => [
        'brand' => 'Kia',
        'model' => 'Sportage',
        'price_from' => 3490000,
        'engine' => '2.0 л',
        'transmission' => 'АКПП 6',
        'specs' => [
            'Мощность' => '150 л.с.',
            'Расход топлива' => '7.8 л/100км',
            'Привод' => 'Передний / Полный',
            'Разгон 0-100' => '10.1 сек',
            'Объем багажника' => '591 л'
        ],
        'trims' => [
            ['name' => 'Classic', 'price' => 3490000, 'features' => ['Кондиционер', 'Мультимедиа 8"', 'Камера заднего вида', 'Подогрев сидений']],
            ['name' => 'Comfort', 'price' => 3890000, 'features' => ['Климат-контроль 2-зонный', 'Мультимедиа 12.3"', 'Круиз-контроль', 'LED фары']],
            ['name' => 'Prestige', 'price' => 4390000, 'features' => ['Панорамная крыша', 'Кожаный салон', 'Вентиляция сидений', 'Премиум аудио']]
        ]
    ],
    'kia-k5' => [
        'brand' => 'Kia',
        'model' => 'K5',
        'price_from' => 2890000,
        'engine' => '2.5 л',
        'transmission' => 'АКПП 8',
        'specs' => [
            'Мощность' => '194 л.с.',
            'Расход топлива' => '8.2 л/100км',
            'Привод' => 'Передний',
            'Разгон 0-100' => '8.3 сек',
            'Объем багажника' => '510 л'
        ],
        'trims' => [
            ['name' => 'Luxe', 'price' => 2890000, 'features' => ['Климат-контроль', 'LED фары', 'Мультимедиа 12.3"', 'Камера 360°']],
            ['name' => 'Prestige', 'price' => 3290000, 'features' => ['Кожаный салон', 'Панорамная крыша', 'Премиум аудио Bose', 'Вентиляция сидений']]
        ]
    ],
    'kia-cerato' => [
        'brand' => 'Kia',
        'model' => 'Cerato',
        'price_from' => 2190000,
        'engine' => '2.0 л',
        'transmission' => 'АКПП 6',
        'specs' => [
            'Мощность' => '150 л.с.',
            'Расход топлива' => '7.4 л/100км',
            'Привод' => 'Передний',
            'Разгон 0-100' => '10.2 сек'
        ],
        'trims' => [
            ['name' => 'Classic', 'price' => 2190000, 'features' => ['Кондиционер', 'Мультимедиа 8"', 'Подогрев сидений']],
            ['name' => 'Comfort', 'price' => 2490000, 'features' => ['Климат-контроль', 'Камера заднего вида', 'Круиз-контроль']]
        ]
    ],
    'chery-tiggo-7-pro-max' => [
        'brand' => 'Chery',
        'model' => 'Tiggo 7 Pro Max',
        'price_from' => 2790000,
        'engine' => '1.6 л турбо',
        'transmission' => 'Робот 7DCT',
        'specs' => [
            'Мощность' => '186 л.с.',
            'Расход топлива' => '7.5 л/100км',
            'Привод' => 'Передний / Полный',
            'Разгон 0-100' => '9.0 сек',
            'Объем багажника' => '475 л'
        ],
        'trims' => [
            ['name' => 'Elite', 'price' => 2790000, 'features' => ['Климат-контроль', 'Панорамная крыша', 'LED фары', 'Мультимедиа 12.3"']],
            ['name' => 'Ultimate', 'price' => 3190000, 'features' => ['Полный привод', 'Кожаный салон', 'Премиум аудио Sony', 'Проекция на лобовое']]
        ]
    ],
    'chery-arrizo-8' => [
        'brand' => 'Chery',
        'model' => 'Arrizo 8',
        'price_from' => 2490000,
        'engine' => '1.6 л турбо',
        'transmission' => 'Робот 7DCT',
        'specs' => [
            'Мощность' => '197 л.с.',
            'Расход топлива' => '6.9 л/100км',
            'Привод' => 'Передний',
            'Разгон 0-100' => '7.8 сек',
            'Объем багажника' => '570 л'
        ],
        'trims' => [
            ['name' => 'Active', 'price' => 2490000, 'features' => ['Климат-контроль', 'LED фары', 'Мультимедиа 12"', 'Камера заднего вида']],
            ['name' => 'Prime', 'price' => 2790000, 'features' => ['Панорамная крыша', 'Кожаный салон', 'Камера 360°', 'Подогрев руля']],
            ['name' => 'Ultra Black', 'price' => 3090000, 'features' => ['Премиум интерьер', 'Проекция на лобовое', 'Премиум аудио', 'Адаптивный круиз']]
        ]
    ],
];


class CarParser {
    private $userAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
    
    public function getPage(string $url): ?string {
        $ctx = stream_context_create([
            'http' => [
                'header' => "User-Agent: {$this->userAgent}\r\nAccept: text/html,application/xhtml+xml\r\nAccept-Language: ru-RU,ru;q=0.9\r\n",
                'timeout' => 20,
                'ignore_errors' => true
            ],
            'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]
        ]);
        
        $html = @file_get_contents($url, false, $ctx);
        return $html ?: null;
    }
    
    public function parseCarPage(string $url, string $carId, array $fallback): array {
        $html = $this->getPage($url);
        
        // Начинаем с fallback данных
        $data = $fallback;
        
        if (!$html) {
            echo "  ⚠ Не удалось загрузить страницу, используем fallback\n";
            return $data;
        }
        
        // Пытаемся извлечь актуальную цену
        // Ищем все цены и берем минимальную разумную (от 1.5 до 6 млн)
        $prices = [];
        if (preg_match_all('/от\s*([1-9][\d\s\xa0]{5,8})\s*₽/u', $html, $matches)) {
            foreach ($matches[1] as $m) {
                $price = (int)preg_replace('/\D/', '', $m);
                if ($price >= 1500000 && $price <= 6000000) {
                    $prices[] = $price;
                }
            }
        }
        
        if (!empty($prices)) {
            $minPrice = min($prices);
            $data['price_from'] = $minPrice;
            echo "  📊 Цена с сайта: " . number_format($minPrice, 0, '', ' ') . " ₽\n";
        }
        
        // Пытаемся найти комплектации в таблице
        $trims = $this->parseTrims($html, $data['price_from']);
        if (!empty($trims)) {
            $data['trims'] = $trims;
            echo "  📦 Найдено комплектаций: " . count($trims) . "\n";
        }
        
        return $data;
    }
    
    private function parseTrims(string $html, int $basePrice): array {
        $trims = [];
        $seen = [];
        
        // Паттерн для поиска комплектаций в таблице
        // Ищем строки с названием комплектации и ценой
        $pattern = '/<tr[^>]*>.*?complektacia[^"]*\/([a-z0-9_-]+)\/?["\'][^>]*>\s*\**\s*([^<*]+)/is';
        
        if (preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $slug = trim($match[1]);
                $name = trim(strip_tags($match[2]));
                
                // Пропускаем если уже видели или имя слишком длинное/короткое
                if (isset($seen[$slug]) || strlen($name) < 3 || strlen($name) > 25) continue;
                
                // Ищем цену рядом (в пределах 500 символов после названия)
                $pos = strpos($html, $match[0]);
                $chunk = substr($html, $pos, 800);
                
                $price = $basePrice;
                // Ищем цену в формате X XXX XXX ₽
                if (preg_match('/([1-9][\d\s\xa0]{5,8})\s*₽/u', $chunk, $pm)) {
                    $foundPrice = (int)preg_replace('/\D/', '', $pm[1]);
                    if ($foundPrice >= 1000000 && $foundPrice <= 20000000) {
                        $price = $foundPrice;
                    }
                }
                
                $seen[$slug] = true;
                $trims[] = [
                    'name' => $name,
                    'price' => $price,
                    'features' => $this->getTrimFeatures($name)
                ];
            }
        }
        
        // Сортируем по цене
        usort($trims, fn($a, $b) => $a['price'] <=> $b['price']);
        
        return array_slice($trims, 0, 4);
    }
    
    private function getTrimFeatures(string $name): array {
        $name = mb_strtolower($name);
        
        if (strpos($name, 'classic') !== false || strpos($name, 'active') !== false) {
            return ['Кондиционер', 'Мультимедиа 8"', 'Камера заднего вида', 'Подогрев сидений'];
        }
        if (strpos($name, 'comfort') !== false || strpos($name, 'prime') !== false) {
            return ['Климат-контроль', 'LED фары', 'Мультимедиа 10"', 'Камера заднего вида', 'Круиз-контроль'];
        }
        if (strpos($name, 'prestige') !== false || strpos($name, 'premium') !== false) {
            return ['Климат-контроль 2-зонный', 'LED фары', 'Мультимедиа 12"', 'Камера 360°', 'Кожаный салон'];
        }
        if (strpos($name, 'ultimate') !== false || strpos($name, 'ultra') !== false) {
            return ['Климат-контроль 2-зонный', 'Панорамная крыша', 'Кожаный салон', 'Премиум аудио', 'Проекция на лобовое'];
        }
        if (strpos($name, 'luxe') !== false || strpos($name, 'luxury') !== false) {
            return ['Климат-контроль', 'Кожаный салон', 'LED фары', 'Мультимедиа 12"'];
        }
        if (strpos($name, 'elite') !== false) {
            return ['Климат-контроль', 'Панорамная крыша', 'LED фары', 'Мультимедиа 12"'];
        }
        
        return ['Кондиционер', 'Мультимедиа', 'Подогрев сидений'];
    }
}


class LocalImageScanner {
    public function scanColors(string $folder): array {
        global $COLOR_MAP;
        
        $modelsDir = IMG_DIR . '/' . $folder . '/Models';
        if (!is_dir($modelsDir)) {
            echo "  ⚠ Папка Models не найдена\n";
            return [];
        }
        
        $colors = [];
        $files = glob($modelsDir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
        
        foreach ($files as $file) {
            $filename = strtolower(basename($file));
            $webPath = '/img/' . $folder . '/Models/' . basename($file);
            
            // Определяем цвет по имени файла
            $found = false;
            foreach ($COLOR_MAP as $key => [$name, $hex]) {
                if (strpos($filename, $key) !== false) {
                    $colors[] = [
                        'name' => $name,
                        'hex' => $hex,
                        'image' => $webPath
                    ];
                    $found = true;
                    break;
                }
            }
            
            // Если цвет не определен, добавляем как "Стандарт"
            if (!$found) {
                $colors[] = [
                    'name' => 'Стандарт',
                    'hex' => '#6b7280',
                    'image' => $webPath
                ];
            }
        }
        
        return $colors;
    }
    
    public function scanGallery(string $folder): array {
        $photosDir = IMG_DIR . '/' . $folder . '/Photos';
        if (!is_dir($photosDir)) return [];
        
        $gallery = [];
        $files = glob($photosDir . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
        
        foreach ($files as $file) {
            $gallery[] = '/img/' . $folder . '/Photos/' . basename($file);
        }
        
        return $gallery;
    }
}


class DatabaseManager {
    private $pdo;
    
    public function __construct() {
        $this->pdo = new PDO('sqlite:' . DB_PATH);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->initTables();
    }
    
    private function initTables(): void {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS cars (
                id TEXT PRIMARY KEY,
                brand TEXT NOT NULL,
                model TEXT NOT NULL,
                price_from INTEGER NOT NULL,
                engine TEXT,
                transmission TEXT,
                banner TEXT,
                is_new INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS car_colors (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                car_id TEXT NOT NULL,
                name TEXT NOT NULL,
                hex TEXT NOT NULL,
                image TEXT,
                sort_order INTEGER DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS car_trims (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                car_id TEXT NOT NULL,
                name TEXT NOT NULL,
                price INTEGER NOT NULL,
                features TEXT,
                sort_order INTEGER DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS car_specs (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                car_id TEXT NOT NULL,
                label TEXT NOT NULL,
                value TEXT NOT NULL
            );
            CREATE TABLE IF NOT EXISTS car_gallery (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                car_id TEXT NOT NULL,
                image TEXT NOT NULL,
                sort_order INTEGER DEFAULT 0
            );
            CREATE TABLE IF NOT EXISTS leads (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                phone TEXT NOT NULL,
                car TEXT,
                message TEXT,
                type TEXT DEFAULT 'callback',
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
        ");
    }
    
    public function clearCars(): void {
        $this->pdo->exec("DELETE FROM car_gallery");
        $this->pdo->exec("DELETE FROM car_specs");
        $this->pdo->exec("DELETE FROM car_trims");
        $this->pdo->exec("DELETE FROM car_colors");
        $this->pdo->exec("DELETE FROM cars");
        echo "🗑  База данных очищена\n\n";
    }
    
    public function saveCar(string $id, array $car): void {
        // Основные данные
        $stmt = $this->pdo->prepare("
            INSERT OR REPLACE INTO cars (id, brand, model, price_from, engine, transmission, banner, is_new)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $id,
            $car['brand'] ?? '',
            $car['model'] ?? '',
            $car['price_from'] ?? 0,
            $car['engine'] ?? '',
            $car['transmission'] ?? '',
            $car['banner'] ?? '',
            ($car['is_new'] ?? false) ? 1 : 0
        ]);
        
        // Цвета
        $this->pdo->prepare("DELETE FROM car_colors WHERE car_id = ?")->execute([$id]);
        if (!empty($car['colors'])) {
            $stmt = $this->pdo->prepare("INSERT INTO car_colors (car_id, name, hex, image, sort_order) VALUES (?, ?, ?, ?, ?)");
            foreach ($car['colors'] as $i => $color) {
                $stmt->execute([$id, $color['name'], $color['hex'], $color['image'] ?? '', $i]);
            }
        }
        
        // Комплектации
        $this->pdo->prepare("DELETE FROM car_trims WHERE car_id = ?")->execute([$id]);
        if (!empty($car['trims'])) {
            $stmt = $this->pdo->prepare("INSERT INTO car_trims (car_id, name, price, features, sort_order) VALUES (?, ?, ?, ?, ?)");
            foreach ($car['trims'] as $i => $trim) {
                $stmt->execute([$id, $trim['name'], $trim['price'], json_encode($trim['features'] ?? []), $i]);
            }
        }
        
        // Характеристики
        $this->pdo->prepare("DELETE FROM car_specs WHERE car_id = ?")->execute([$id]);
        if (!empty($car['specs'])) {
            $stmt = $this->pdo->prepare("INSERT INTO car_specs (car_id, label, value) VALUES (?, ?, ?)");
            foreach ($car['specs'] as $label => $value) {
                $stmt->execute([$id, $label, $value]);
            }
        }
        
        // Галерея
        $this->pdo->prepare("DELETE FROM car_gallery WHERE car_id = ?")->execute([$id]);
        if (!empty($car['gallery'])) {
            $stmt = $this->pdo->prepare("INSERT INTO car_gallery (car_id, image, sort_order) VALUES (?, ?, ?)");
            foreach ($car['gallery'] as $i => $img) {
                $stmt->execute([$id, $img, $i]);
            }
        }
    }
}


// ============ КОНФИГУРАЦИЯ АВТОМОБИЛЕЙ ============

$CARS = [
    'kia-sportage' => [
        'url' => 'https://www.avtogermes.ru/sale/kia/sportage/',
        'folder' => 'kia sportage',
        'is_new' => true,
        'banner_key' => 'sportage'
    ],
    'kia-k5' => [
        'url' => 'https://www.avtogermes.ru/sale/kia/k5/',
        'folder' => 'kia k5',
        'is_new' => false,
        'banner_key' => 'k5'
    ],
    'kia-cerato' => [
        'url' => 'https://www.avtogermes.ru/sale/kia/cerato/',
        'folder' => 'kia cerato',
        'is_new' => false,
        'banner_key' => 'cerato'
    ],
    'chery-tiggo-7-pro-max' => [
        'url' => 'https://www.avtogermes.ru/sale/chery/tiggo-7-pro-max/',
        'folder' => 'tiggo 7 pro max',
        'is_new' => true,
        'banner_key' => 'tiggo7'
    ],
    'chery-arrizo-8' => [
        'url' => 'https://www.avtogermes.ru/sale/chery/arrizo-8/',
        'folder' => 'chery arizzo 8',
        'is_new' => true,
        'banner_key' => 'arizzo8'
    ],
];


// ============ MAIN ============

echo "\n";
echo "╔═══════════════════════════════════════════════════╗\n";
echo "║     🚗 Парсер автомобилей для Харбор              ║\n";
echo "╚═══════════════════════════════════════════════════╝\n\n";

$parser = new CarParser();
$scanner = new LocalImageScanner();
$db = new DatabaseManager();

// Очищаем базу
$db->clearCars();

$total = count($CARS);
$current = 0;

foreach ($CARS as $carId => $config) {
    $current++;
    echo "[$current/$total] 🚗 $carId\n";
    echo str_repeat('─', 50) . "\n";
    
    // Получаем fallback данные
    $fallback = $FALLBACK_DATA[$carId] ?? [];
    
    // Парсим данные с сайта (с fallback)
    echo "  📥 Загрузка данных...\n";
    $carData = $parser->parseCarPage($config['url'], $carId, $fallback);
    
    // Сканируем локальные изображения
    echo "  🖼  Сканирование изображений...\n";
    $carData['colors'] = $scanner->scanColors($config['folder']);
    $carData['gallery'] = $scanner->scanGallery($config['folder']);
    
    // Баннер - берём первое фото из галереи
    if (!empty($carData['gallery'])) {
        $carData['banner'] = $carData['gallery'][0];
    } else {
        $bannerPath = IMG_DIR . '/banner/' . $config['banner_key'] . '_banner.jpg';
        if (file_exists($bannerPath)) {
            $carData['banner'] = '/img/banner/' . $config['banner_key'] . '_banner.jpg';
        }
    }
    
    $carData['is_new'] = $config['is_new'];
    
    // Корректируем цены комплектаций относительно базовой цены
    if (!empty($carData['trims']) && $carData['price_from'] > 0) {
        $basePrice = $carData['price_from'];
        foreach ($carData['trims'] as $i => &$trim) {
            // Добавляем наценку за комплектацию (0%, +10%, +20%, +30%)
            $markup = $i * 0.10;
            $trim['price'] = (int)round($basePrice * (1 + $markup), -3); // округляем до тысяч
        }
        unset($trim);
    }
    
    // Сохраняем в БД
    echo "  💾 Сохранение...\n";
    $db->saveCar($carId, $carData);
    
    // Вывод результата
    echo "\n";
    echo "  ✅ {$carData['brand']} {$carData['model']}\n";
    echo "     💰 " . number_format($carData['price_from'], 0, '', ' ') . " ₽\n";
    echo "     🔧 {$carData['engine']} / {$carData['transmission']}\n";
    echo "     🎨 Цветов: " . count($carData['colors']) . "\n";
    echo "     📦 Комплектаций: " . count($carData['trims']) . "\n";
    echo "     🖼  Фото: " . count($carData['gallery']) . "\n";
    echo "\n";
}

echo "╔═══════════════════════════════════════════════════╗\n";
echo "║     ✅ Парсинг завершен!                          ║\n";
echo "╚═══════════════════════════════════════════════════╝\n\n";

// Проверка результата
echo "📊 Итого в базе данных:\n";
$pdo = new PDO('sqlite:' . DB_PATH);
$count = $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn();
echo "   • Автомобилей: $count\n";
$colors = $pdo->query("SELECT COUNT(*) FROM car_colors")->fetchColumn();
echo "   • Цветов: $colors\n";
$trims = $pdo->query("SELECT COUNT(*) FROM car_trims")->fetchColumn();
echo "   • Комплектаций: $trims\n";
$specs = $pdo->query("SELECT COUNT(*) FROM car_specs")->fetchColumn();
echo "   • Характеристик: $specs\n";
echo "\n";
