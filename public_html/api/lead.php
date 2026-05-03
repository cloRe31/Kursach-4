<?php
require_once __DIR__ . '/../../src/config/bootstrap.php';

require_once __DIR__ . '/../../src/config/functions.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$car = trim($_POST['car'] ?? '');
$message = trim($_POST['message'] ?? '');
$type = trim($_POST['type'] ?? 'callback');

// Валидация
if (empty($name) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Заполните обязательные поля']);
    exit;
}

// Очистка телефона
$phone = preg_replace('/[^0-9+]/', '', $phone);

$data = [
    'name' => $name,
    'phone' => $phone,
    'car' => $car,
    'message' => $message,
    'type' => $type
];

// Сохраняем заявку
$saved = saveLead($data);

// Отправляем в Telegram
sendNotification($data);

if ($saved) {
    // Если AJAX запрос
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode(['success' => true, 'message' => 'Заявка отправлена! Мы перезвоним вам в ближайшее время.']);
    } else {
        // Редирект с сообщением
        header('Location: /?success=1');
    }
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка сохранения']);
}
