<?php
require_once __DIR__ . '/../../src/config/functions.php';
session_start();

if (!isAdmin()) { header('Location: /admin/'); exit; }

$cars = getCars();
$message = '';
$editCar = null;

if (isset($_GET['delete'])) {
    deleteCar($_GET['delete']);
    header('Location: /admin/cars.php?msg=deleted');
    exit;
}

if (isset($_GET['edit']) && isset($cars[$_GET['edit']])) {
    $editCar = $cars[$_GET['edit']];
    $editCar['id'] = $_GET['edit'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?: uniqid();
    
    $colors = [];
    if (!empty($_POST['color_name'])) {
        foreach ($_POST['color_name'] as $i => $name) {
            if ($name) $colors[] = ['name' => $name, 'hex' => $_POST['color_hex'][$i] ?? '#000', 'image' => $_POST['color_image'][$i] ?? ''];
        }
    }
    
    $trims = [];
    if (!empty($_POST['trim_name'])) {
        foreach ($_POST['trim_name'] as $i => $name) {
            if ($name) $trims[] = [
                'name' => $name,
                'price' => (int)($_POST['trim_price'][$i] ?? 0),
                'features' => array_filter(array_map('trim', explode("\n", $_POST['trim_features'][$i] ?? '')))
            ];
        }
    }
    
    $specs = [];
    if (!empty($_POST['spec_label'])) {
        foreach ($_POST['spec_label'] as $i => $label) {
            if ($label && !empty($_POST['spec_value'][$i])) $specs[$label] = $_POST['spec_value'][$i];
        }
    }
    
    saveCar($id, [
        'brand' => trim($_POST['brand'] ?? ''),
        'model' => trim($_POST['model'] ?? ''),
        'price_from' => (int)($_POST['price_from'] ?? 0),
        'engine' => trim($_POST['engine'] ?? ''),
        'transmission' => trim($_POST['transmission'] ?? ''),
        'banner' => trim($_POST['banner'] ?? ''),
        'is_new' => isset($_POST['is_new']),
        'colors' => $colors,
        'trims' => $trims,
        'specs' => $specs,
        'gallery' => array_filter(array_map('trim', explode("\n", $_POST['gallery'] ?? '')))
    ]);
    
    header('Location: /admin/cars.php?msg=saved');
    exit;
}

$message = match($_GET['msg'] ?? '') { 'saved' => 'Автомобиль сохранён', 'deleted' => 'Автомобиль удалён', default => '' };
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Автомобили | Админ-панель</title>
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
        .card { background: #fff; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.1); padding: 24px; margin-bottom: 24px; }
        .card h2 { font-size: 18px; color: #1f2937; margin-bottom: 20px; }
        .message { background: #d1fae5; color: #065f46; padding: 14px 18px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .full { grid-column: 1 / -1; }
        .field { margin-bottom: 0; }
        .field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; color: #374151; }
        .field input, .field textarea, .field select { width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 14px; transition: border-color .2s; }
        .field input:focus, .field textarea:focus { outline: none; border-color: #c41e3a; }
        .checkbox { display: flex; align-items: center; gap: 10px; }
        .checkbox input { width: auto; }
        .repeater { border: 1px solid #e5e7eb; padding: 16px; margin-bottom: 12px; border-radius: 8px; position: relative; background: #f9fafb; }
        .repeater-remove { position: absolute; top: 12px; right: 12px; background: #ef4444; color: #fff; border: none; width: 28px; height: 28px; border-radius: 6px; cursor: pointer; font-size: 18px; line-height: 1; }
        .add-btn { background: #10b981; color: #fff; border: none; padding: 10px 18px; cursor: pointer; border-radius: 8px; font-size: 14px; font-weight: 500; }
        .btn { padding: 14px 28px; background: #c41e3a; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; }
        .btn:hover { background: #a01830; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #f3f4f6; }
        th { background: #f9fafb; font-size: 13px; font-weight: 600; color: #6b7280; text-transform: uppercase; }
        tr:hover { background: #f9fafb; }
        .btn-sm { padding: 6px 14px; font-size: 13px; border-radius: 6px; cursor: pointer; border: none; font-weight: 500; }
        .btn-edit { background: #3b82f6; color: #fff; }
        .btn-delete { background: #ef4444; color: #fff; margin-left: 6px; }
        h3 { font-size: 16px; color: #374151; margin: 28px 0 14px; }
        @media (max-width: 768px) { .grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="admin">
        <div class="header">
            <h1>Автомобили</h1>
            <nav class="nav">
                <a href="/admin/">Главная</a>
                <a href="/admin/cars.php" class="active">Автомобили</a>
                <a href="/admin/leads.php">Заявки</a>
                <a href="/" target="_blank">Сайт ↗</a>
                <a href="/admin/?logout">Выход</a>
            </nav>
        </div>
        
        <?php if ($message): ?><div class="message"><?= e($message) ?></div><?php endif; ?>
        
        <div class="card">
            <h2><?= $editCar ? 'Редактировать' : 'Добавить' ?> автомобиль</h2>
            <form method="POST">
                <input type="hidden" name="id" value="<?= e($editCar['id'] ?? '') ?>">
                
                <div class="grid">
                    <div class="field"><label>Бренд *</label><input type="text" name="brand" value="<?= e($editCar['brand'] ?? '') ?>" required></div>
                    <div class="field"><label>Модель *</label><input type="text" name="model" value="<?= e($editCar['model'] ?? '') ?>" required></div>
                    <div class="field"><label>Цена от *</label><input type="number" name="price_from" value="<?= e($editCar['price_from'] ?? '') ?>" required></div>
                    <div class="field"><label>Двигатель</label><input type="text" name="engine" value="<?= e($editCar['engine'] ?? '') ?>" placeholder="2.0 л"></div>
                    <div class="field"><label>Трансмиссия</label><input type="text" name="transmission" value="<?= e($editCar['transmission'] ?? '') ?>" placeholder="АКПП"></div>
                    <div class="field"><label>Баннер (URL)</label><input type="text" name="banner" value="<?= e($editCar['banner'] ?? '') ?>" placeholder="/img/banner/..."></div>
                    <div class="field full"><label class="checkbox"><input type="checkbox" name="is_new" <?= !empty($editCar['is_new']) ? 'checked' : '' ?>> Новинка</label></div>
                </div>
                
                <h3>Цвета</h3>
                <div id="colors">
                    <?php foreach ($editCar['colors'] ?? [] as $c): ?>
                    <div class="repeater">
                        <button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button>
                        <div class="grid">
                            <div class="field"><input type="text" name="color_name[]" value="<?= e($c['name']) ?>" placeholder="Название"></div>
                            <div class="field"><input type="text" name="color_hex[]" value="<?= e($c['hex']) ?>" placeholder="#ffffff"></div>
                            <div class="field full"><input type="text" name="color_image[]" value="<?= e($c['image']) ?>" placeholder="URL изображения"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-btn" onclick="addColor()">+ Добавить цвет</button>
                
                <h3>Комплектации</h3>
                <div id="trims">
                    <?php foreach ($editCar['trims'] ?? [] as $t): ?>
                    <div class="repeater">
                        <button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button>
                        <div class="grid">
                            <div class="field"><input type="text" name="trim_name[]" value="<?= e($t['name']) ?>" placeholder="Название"></div>
                            <div class="field"><input type="number" name="trim_price[]" value="<?= e($t['price']) ?>" placeholder="Цена"></div>
                            <div class="field full"><textarea name="trim_features[]" rows="3" placeholder="Опции (каждая с новой строки)"><?= e(implode("\n", $t['features'] ?? [])) ?></textarea></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-btn" onclick="addTrim()">+ Добавить комплектацию</button>
                
                <h3>Характеристики</h3>
                <div id="specs">
                    <?php foreach ($editCar['specs'] ?? [] as $l => $v): ?>
                    <div class="repeater">
                        <button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button>
                        <div class="grid">
                            <div class="field"><input type="text" name="spec_label[]" value="<?= e($l) ?>" placeholder="Параметр"></div>
                            <div class="field"><input type="text" name="spec_value[]" value="<?= e($v) ?>" placeholder="Значение"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="add-btn" onclick="addSpec()">+ Добавить характеристику</button>
                
                <h3>Галерея</h3>
                <div class="field">
                    <textarea name="gallery" rows="4" placeholder="URL изображений (каждый с новой строки)"><?= e(implode("\n", $editCar['gallery'] ?? [])) ?></textarea>
                </div>
                
                <div style="margin-top: 28px; display: flex; gap: 16px; align-items: center;">
                    <button type="submit" class="btn">Сохранить</button>
                    <?php if ($editCar): ?><a href="/admin/cars.php">Отмена</a><?php endif; ?>
                </div>
            </form>
        </div>
        
        <div class="card">
            <h2>Все автомобили (<?= count($cars) ?>)</h2>
            <?php if (empty($cars)): ?><p style="color:#9ca3af;text-align:center;padding:30px;">Автомобилей пока нет</p>
            <?php else: ?>
            <table>
                <thead><tr><th>Бренд</th><th>Модель</th><th>Цена от</th><th>Цветов</th><th>Действия</th></tr></thead>
                <tbody>
                    <?php foreach ($cars as $id => $car): ?>
                    <tr>
                        <td><?= e($car['brand']) ?></td>
                        <td><?= e($car['model']) ?></td>
                        <td><?= formatPrice($car['price_from']) ?></td>
                        <td><?= count($car['colors']) ?></td>
                        <td>
                            <a href="?edit=<?= e($id) ?>" class="btn-sm btn-edit">Ред.</a>
                            <a href="?delete=<?= e($id) ?>" class="btn-sm btn-delete" onclick="return confirm('Удалить автомобиль?')">Удал.</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>
    <script>
    const add = (id, html) => document.getElementById(id).insertAdjacentHTML('beforeend', html);
    const addColor = () => add('colors', `<div class="repeater"><button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button><div class="grid"><div class="field"><input type="text" name="color_name[]" placeholder="Название"></div><div class="field"><input type="text" name="color_hex[]" placeholder="#ffffff"></div><div class="field full"><input type="text" name="color_image[]" placeholder="URL изображения"></div></div></div>`);
    const addTrim = () => add('trims', `<div class="repeater"><button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button><div class="grid"><div class="field"><input type="text" name="trim_name[]" placeholder="Название"></div><div class="field"><input type="number" name="trim_price[]" placeholder="Цена"></div><div class="field full"><textarea name="trim_features[]" rows="3" placeholder="Опции"></textarea></div></div></div>`);
    const addSpec = () => add('specs', `<div class="repeater"><button type="button" class="repeater-remove" onclick="this.parentElement.remove()">×</button><div class="grid"><div class="field"><input type="text" name="spec_label[]" placeholder="Параметр"></div><div class="field"><input type="text" name="spec_value[]" placeholder="Значение"></div></div></div>`);
    </script>
</body>
</html>
