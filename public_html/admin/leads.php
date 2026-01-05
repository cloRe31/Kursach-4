<?php
require_once __DIR__ . '/../../src/config/functions.php';
session_start();

if (!isAdmin()) { header('Location: /admin/'); exit; }

$filter = $_GET['filter'] ?? null;
$leads = getLeads($filter);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки | Админ-панель</title>
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
        .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); padding: 24px; }
        .filters { display: flex; gap: 8px; margin-bottom: 24px; flex-wrap: wrap; }
        .filters a { padding: 8px 16px; background: #f3f4f6; border-radius: 6px; font-size: 13px; font-weight: 500; color: #4b5563; }
        .filters a:hover { background: #e5e7eb; }
        .filters a.active { background: #c41e3a; color: #fff; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #f3f4f6; }
        th { background: #f9fafb; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase; letter-spacing: .5px; }
        tr:hover { background: #f9fafb; }
        td a { color: #c41e3a; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-callback { background: #dbeafe; color: #1d4ed8; }
        .badge-testdrive { background: #dcfce7; color: #16a34a; }
        .badge-tradein { background: #fef3c7; color: #d97706; }
        .badge-contact { background: #f3e8ff; color: #9333ea; }
        .badge-credit { background: #ffe4e6; color: #e11d48; }
        .empty { text-align: center; padding: 60px 20px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="admin">
        <div class="header">
            <h1>Заявки (<?= count($leads) ?>)</h1>
            <nav class="nav">
                <a href="/admin/">Главная</a>
                <a href="/admin/cars.php">Автомобили</a>
                <a href="/admin/leads.php" class="active">Заявки</a>
                <a href="/" target="_blank">Сайт ↗</a>
                <a href="/admin/?logout">Выход</a>
            </nav>
        </div>
        
        <div class="card">
            <div class="filters">
                <a href="/admin/leads.php" class="<?= !$filter ? 'active' : '' ?>">Все</a>
                <a href="?filter=callback" class="<?= $filter === 'callback' ? 'active' : '' ?>">Звонок</a>
                <a href="?filter=testdrive" class="<?= $filter === 'testdrive' ? 'active' : '' ?>">Тест-драйв</a>
                <a href="?filter=tradein" class="<?= $filter === 'tradein' ? 'active' : '' ?>">Trade-in</a>
                <a href="?filter=contact" class="<?= $filter === 'contact' ? 'active' : '' ?>">Контакт</a>
            </div>
            
            <?php if (empty($leads)): ?>
                <div class="empty">Заявок пока нет</div>
            <?php else: ?>
            <table>
                <thead><tr><th>Дата</th><th>Тип</th><th>Имя</th><th>Телефон</th><th>Авто</th><th>Сообщение</th></tr></thead>
                <tbody>
                    <?php foreach ($leads as $l): 
                        $badges = [
                            'callback' => 'badge-callback',
                            'testdrive' => 'badge-testdrive',
                            'tradein' => 'badge-tradein',
                            'contact' => 'badge-contact',
                            'credit' => 'badge-credit'
                        ];
                        $badge = $badges[$l['type']] ?? 'badge-callback';
                    ?>
                    <tr>
                        <td><?= date('d.m.Y H:i', strtotime($l['created_at'])) ?></td>
                        <td><span class="badge <?= $badge ?>"><?= e($l['type']) ?></span></td>
                        <td><?= e($l['name']) ?></td>
                        <td><a href="tel:<?= e($l['phone']) ?>"><?= e($l['phone']) ?></a></td>
                        <td><?= e($l['car'] ?: '—') ?></td>
                        <td><?= e($l['message'] ?: '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
