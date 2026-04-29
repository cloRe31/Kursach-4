<?php
/**
 * Функции для работы с SQLite
 */

require_once __DIR__ . '/config.php';

function getDB(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dir = dirname(DB_PATH);
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        
        initDB($pdo);
    }
    return $pdo;
}

function initDB(PDO $pdo): void {
    $pdo->exec("
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
            sort_order INTEGER DEFAULT 0,
            FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS car_trims (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            car_id TEXT NOT NULL,
            name TEXT NOT NULL,
            price INTEGER NOT NULL,
            features TEXT,
            sort_order INTEGER DEFAULT 0,
            FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS car_specs (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            car_id TEXT NOT NULL,
            label TEXT NOT NULL,
            value TEXT NOT NULL,
            FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
        );
        
        CREATE TABLE IF NOT EXISTS car_gallery (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            car_id TEXT NOT NULL,
            image TEXT NOT NULL,
            sort_order INTEGER DEFAULT 0,
            FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE
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

// === CARS ===

function getCars(): array {
    $pdo = getDB();
    $cars = [];
    
    $stmt = $pdo->query("SELECT * FROM cars ORDER BY is_new DESC, price_from ASC");
    foreach ($stmt->fetchAll() as $row) {
        $id = $row['id'];
        $cars[$id] = [
            'brand' => $row['brand'],
            'model' => $row['model'],
            'price_from' => (int)$row['price_from'],
            'engine' => $row['engine'],
            'transmission' => $row['transmission'],
            'banner' => $row['banner'],
            'is_new' => (bool)$row['is_new'],
            'colors' => getCarColors($id),
            'trims' => getCarTrims($id),
            'specs' => getCarSpecs($id),
            'gallery' => getCarGallery($id)
        ];
    }
    return $cars;
}

function getCarById(string $id): ?array {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    
    if (!$row) return null;
    
    return [
        'brand' => $row['brand'],
        'model' => $row['model'],
        'price_from' => (int)$row['price_from'],
        'engine' => $row['engine'],
        'transmission' => $row['transmission'],
        'banner' => $row['banner'],
        'is_new' => (bool)$row['is_new'],
        'colors' => getCarColors($id),
        'trims' => getCarTrims($id),
        'specs' => getCarSpecs($id),
        'gallery' => getCarGallery($id)
    ];
}

function getCarColors(string $carId): array {
    $stmt = getDB()->prepare("SELECT name, hex, image FROM car_colors WHERE car_id = ? ORDER BY 
        CASE WHEN hex LIKE '#f%' OR hex LIKE '#e%' OR hex LIKE '#d%' THEN 0 ELSE 1 END, 
        sort_order");
    $stmt->execute([$carId]);
    return $stmt->fetchAll();
}

function getCarTrims(string $carId): array {
    $stmt = getDB()->prepare("SELECT name, price, features FROM car_trims WHERE car_id = ? ORDER BY sort_order");
    $stmt->execute([$carId]);
    $trims = [];
    foreach ($stmt->fetchAll() as $row) {
        $trims[] = [
            'name' => $row['name'],
            'price' => (int)$row['price'],
            'features' => $row['features'] ? json_decode($row['features'], true) : []
        ];
    }
    return $trims;
}

function getCarSpecs(string $carId): array {
    $stmt = getDB()->prepare("SELECT label, value FROM car_specs WHERE car_id = ?");
    $stmt->execute([$carId]);
    $specs = [];
    foreach ($stmt->fetchAll() as $row) {
        $specs[$row['label']] = $row['value'];
    }
    return $specs;
}

function getCarGallery(string $carId): array {
    $stmt = getDB()->prepare("SELECT image FROM car_gallery WHERE car_id = ? ORDER BY sort_order");
    $stmt->execute([$carId]);
    return array_column($stmt->fetchAll(), 'image');
}

function saveCar(string $id, array $data): bool {
    $pdo = getDB();
    
    $stmt = $pdo->prepare("
        INSERT INTO cars (id, brand, model, price_from, engine, transmission, banner, is_new) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ON CONFLICT(id) DO UPDATE SET 
            brand = excluded.brand,
            model = excluded.model,
            price_from = excluded.price_from,
            engine = excluded.engine,
            transmission = excluded.transmission,
            banner = excluded.banner,
            is_new = excluded.is_new
    ");
    $stmt->execute([
        $id,
        $data['brand'],
        $data['model'],
        $data['price_from'],
        $data['engine'] ?? '',
        $data['transmission'] ?? '',
        $data['banner'] ?? '',
        $data['is_new'] ? 1 : 0
    ]);
    
    $pdo->prepare("DELETE FROM car_colors WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_trims WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_specs WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_gallery WHERE car_id = ?")->execute([$id]);
    
    if (!empty($data['colors'])) {
        $stmt = $pdo->prepare("INSERT INTO car_colors (car_id, name, hex, image, sort_order) VALUES (?, ?, ?, ?, ?)");
        foreach ($data['colors'] as $i => $color) {
            $stmt->execute([$id, $color['name'], $color['hex'], $color['image'] ?? '', $i]);
        }
    }
    
    if (!empty($data['trims'])) {
        $stmt = $pdo->prepare("INSERT INTO car_trims (car_id, name, price, features, sort_order) VALUES (?, ?, ?, ?, ?)");
        foreach ($data['trims'] as $i => $trim) {
            $stmt->execute([$id, $trim['name'], $trim['price'], json_encode($trim['features'] ?? []), $i]);
        }
    }
    
    if (!empty($data['specs'])) {
        $stmt = $pdo->prepare("INSERT INTO car_specs (car_id, label, value) VALUES (?, ?, ?)");
        foreach ($data['specs'] as $label => $value) {
            $stmt->execute([$id, $label, $value]);
        }
    }
    
    if (!empty($data['gallery'])) {
        $stmt = $pdo->prepare("INSERT INTO car_gallery (car_id, image, sort_order) VALUES (?, ?, ?)");
        foreach ($data['gallery'] as $i => $img) {
            $stmt->execute([$id, $img, $i]);
        }
    }
    
    return true;
}

function deleteCar(string $id): bool {
    $pdo = getDB();
    $pdo->prepare("DELETE FROM car_colors WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_trims WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_specs WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM car_gallery WHERE car_id = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM cars WHERE id = ?")->execute([$id]);
    return true;
}

// === LEADS ===

function saveLead(array $data): bool {
    $stmt = getDB()->prepare("INSERT INTO leads (name, phone, car, message, type) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([
        $data['name'],
        $data['phone'],
        $data['car'] ?? '',
        $data['message'] ?? '',
        $data['type'] ?? 'callback'
    ]);
}

function getLeads(?string $type = null): array {
    $pdo = getDB();
    if ($type) {
        $stmt = $pdo->prepare("SELECT * FROM leads WHERE type = ? ORDER BY created_at DESC");
        $stmt->execute([$type]);
    } else {
        $stmt = $pdo->query("SELECT * FROM leads ORDER BY created_at DESC");
    }
    return $stmt->fetchAll();
}

function getLeadsCount(): int {
    return (int)getDB()->query("SELECT COUNT(*) FROM leads")->fetchColumn();
}

function getTodayLeadsCount(): int {
    return (int)getDB()->query("SELECT COUNT(*) FROM leads WHERE date(created_at) = date('now')")->fetchColumn();
}

// === HELPERS ===

function sendToTelegram(array $data): bool {
    if (TG_BOT_TOKEN === 'YOUR_BOT_TOKEN') return false;
    
    $text = "🚗 *Новая заявка!*\n\n";
    $text .= "👤 Имя: {$data['name']}\n";
    $text .= "📱 Телефон: {$data['phone']}\n";
    if (!empty($data['car'])) $text .= "🚙 Авто: {$data['car']}\n";
    if (!empty($data['message'])) $text .= "💬 Сообщение: {$data['message']}\n";
    $text .= "\n📅 " . date('d.m.Y H:i');

    $ch = curl_init("https://api.telegram.org/bot" . TG_BOT_TOKEN . "/sendMessage");
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => ['chat_id' => TG_CHAT_ID, 'text' => $text, 'parse_mode' => 'Markdown'],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result !== false;
}

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function isAdmin(): bool {
    return isset($_SESSION['admin']) && $_SESSION['admin'] === true;
}

function formatPrice(int $price): string {
    return number_format($price, 0, '', ' ') . ' ₽';
}

function getBanks(): array {
    return [
        ['svg' => 'sber','name' => 'Сбербанк'],
        ['svg' => 'vtb','name' => 'ВТБ'],
        ['svg' => 'alfa','name' => 'Альфа-Банк'],
        ['svg' => 'gazprom','name' => 'Газпромбанк'],
        ['svg' => 'tinkoff','name' => 'Тинькофф'],
        ['svg' => 'rosbank','name' => 'Росбанк'],
    ];
}
