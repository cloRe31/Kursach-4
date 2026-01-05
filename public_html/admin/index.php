<?php
require_once __DIR__ . '/../../src/config/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if ($_POST['username'] === ADMIN_USER && password_verify($_POST['password'], ADMIN_PASS)) {
        $_SESSION['admin'] = true;
        header('Location: /admin/');
        exit;
    }
    $error = 'Неверный логин или пароль';
}

if (isset($_GET['logout'])) { unset($_SESSION['admin']); header('Location: /admin/'); exit; }

if (!isAdmin()):
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .login { background: #fff; padding: 48px; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,.3); max-width: 420px; width: 100%; }
        .login h1 { font-size: 28px; margin-bottom: 8px; color: #1a1a1a; }
        .login p { color: #666; margin-bottom: 32px; }
        .error { background: #fee2e2; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }
        .field { margin-bottom: 16px; }
        .field label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 6px; color: #374151; }
        .field input { width: 100%; padding: 14px 16px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 15px; transition: border-color .2s; }
        .field input:focus { outline: none; border-color: #c41e3a; }
        .btn { width: 100%; padding: 14px; background: #c41e3a; color: #fff; border: none; border-radius: 10px; font-size: 15px; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn:hover { background: #a01830; }
    </style>
</head>
<body>
    <div class="login">
        <h1><?= SITE_NAME ?></h1>
        <p>Вход в панель управления</p>
        <?php if (!empty($error)): ?><div class="error"><?= e($error) ?></div><?php endif; ?>
        <form method="POST">
            <input type="hidden" name="login" value="1">
            <div class="field">
                <label>Логин</label>
                <input type="text" name="username" required autofocus>
            </div>
            <div class="field">
                <label>Пароль</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Войти</button>
        </form>
    </div>
</body>
</html>
<?php exit; endif;

$cars = getCars();
$leads = getLeads();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | <?= SITE_NAME ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f3f4f6; min-height: 100vh; }
        a { color: inherit; text-decoration: none; }
        .admin { max-width: 1400px; margin: 0 auto; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 20px 24px; background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); flex-wrap: wrap; gap: 16px; }
        .header h1 { font-size: 22px; color: #1f2937; }
        .nav { display: flex; gap: 8px; flex-wrap: wrap; }
        .nav a { padding: 10px 18px; background: #f3f4f6; border-radius: 8px; font-size: 14px; font-weight: 500; color: #4b5563; transition: all .2s; }
        .nav a:hover { background: #e5e7eb; }
        .nav a.active { background: #c41e3a; color: #fff; }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px; }
        .stat { background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .stat-value { font-size: 36px; font-weight: 700; color: #c41e3a; }
        .stat-label { font-size: 14px; color: #6b7280; margin-top: 4px; }
        .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); padding: 24px; margin-bottom: 24px; }
        .card h2 { font-size: 18px; color: #1f2937; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #f3f4f6; }
        th { background: #f9fafb; font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; }
        tr:hover { background: #f9fafb; }
        td a { color: #c41e3a; }
        .empty { text-align: center; padding: 40px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="admin">
        <div class="header">
            <h1><?= SITE_NAME ?> — Админ</h1>
            <nav class="nav">
                <a href="/admin/" class="active">Главная</a>
                <a href="/admin/cars.php">Автомобили</a>
                <a href="/admin/leads.php">Заявки</a>
                <a href="/" target="_blank">Сайт ↗</a>
                <a href="?logout">Выход</a>
            </nav>
        </div>
        
        <div class="stats">
            <div class="stat">
                <div class="stat-value"><?= count($cars) ?></div>
                <div class="stat-label">Автомобилей</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?= getLeadsCount() ?></div>
                <div class="stat-label">Всего заявок</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?= getTodayLeadsCount() ?></div>
                <div class="stat-label">Заявок сегодня</div>
            </div>
        </div>
        
        <div class="card">
            <h2>Последние заявки</h2>
            <?php if (empty($leads)): ?>
                <div class="empty">Заявок пока нет</div>
            <?php else: ?>
            <table>
                <thead><tr><th>Дата</th><th>Имя</th><th>Телефон</th><th>Авто</th><th>Тип</th></tr></thead>
                <tbody>
                    <?php foreach (array_slice($leads, 0, 10) as $l): ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($l['created_at'])) ?></td>
                        <td><?= e($l['name']) ?></td>
                        <td><a href="tel:<?= e($l['phone']) ?>"><?= e($l['phone']) ?></a></td>
                        <td><?= e($l['car'] ?: '—') ?></td>
                        <td><?= e($l['type']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
