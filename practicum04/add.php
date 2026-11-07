<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$errors = [];
$number = '';
$capacity = '';
$pricePerNight = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $number = trim($_POST['number'] ?? '');
    $capacity = trim($_POST['capacity'] ?? '');
    $pricePerNight = trim($_POST['price_per_night'] ?? '');

    if ($number === '') {
        $errors[] = 'Вкажіть номер кімнати або назву зали.';
    }

    if ($capacity === '' || !is_numeric($capacity) || (int)$capacity <= 0) {
        $errors[] = 'Місткість має бути цілим додатним числом.';
    }

    if ($pricePerNight === '' || !is_numeric($pricePerNight) || (float)$pricePerNight < 0) {
        $errors[] = 'Ціна за добу має бути додатним числом.';
    }

    if (empty($errors)) {
        try {
            addRoom($pdo, $number, (int)$capacity, (float)$pricePerNight);
            header('Location: index.php?msg=added');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Номер або зала з назвою \"{$number}\" вже існує в базі даних!";
            } else {
                $errors[] = 'Помилка бази даних: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати номер | Практична робота №4</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .header-bg { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: #fff; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark header-bg py-3 mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand mb-0 h1">
            <i class="bi bi-building"></i> Готель & Конференц-зали (Варіант 8)
        </span>
        <a href="index.php" class="btn btn-sm btn-outline-light">
            <i class="bi bi-arrow-left"></i> До списку номерів
        </a>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger shadow-sm">
                    <h6 class="alert-heading fw-bold mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Виправте наступні помилки:
                    </h6>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-plus-circle me-1"></i> Додавання нового номеру / зали
                    </h5>
                    <small class="text-muted">Заповніть усі обов'язкові поля форми</small>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="add.php" novalidate>
                        <div class="mb-3">
                            <label for="number" class="form-label fw-semibold">
                                Номер кімнати або назва зали <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="number" 
                                   name="number" 
                                   placeholder="Наприклад: 104 або Конференц-зал 'Бета'" 
                                   value="<?= htmlspecialchars($number) ?>" 
                                   required>
                            <div class="form-text">Унікальний ідентифікатор кімнати або зали.</div>
                        </div>

                        <div class="mb-3">
                            <label for="capacity" class="form-label fw-semibold">
                                Місткість (кількість осіб) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-people"></i></span>
                                <input type="number" 
                                       class="form-control" 
                                       id="capacity" 
                                       name="capacity" 
                                       placeholder="Наприклад: 2" 
                                       min="1" 
                                       value="<?= htmlspecialchars($capacity) ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="price_per_night" class="form-label fw-semibold">
                                Вартість за добу (грн) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₴</span>
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       class="form-control" 
                                       id="price_per_night" 
                                       name="price_per_night" 
                                       placeholder="Наприклад: 1500.00" 
                                       value="<?= htmlspecialchars($pricePerNight) ?>" 
                                       required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Скасувати
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Зберегти номер
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
