<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$searchNumber = isset($_GET['search_number']) ? trim($_GET['search_number']) : '';
$filterCapacity = isset($_GET['filter_capacity']) && $_GET['filter_capacity'] !== '' ? (int)$_GET['filter_capacity'] : null;

$isFiltered = false;
$filterTitle = 'Усі зареєстровані номери та зали';

if ($searchNumber !== '') {
    $isFiltered = true;
    $filterTitle = 'Результат пошуку за номером: "' . htmlspecialchars($searchNumber) . '"';
    $foundRoom = findByNumber($pdo, $searchNumber);
    $rooms = $foundRoom ? [$foundRoom] : [];
} elseif ($filterCapacity !== null && $filterCapacity > 0) {
    $isFiltered = true;
    $filterTitle = 'Номери з місткістю від ' . $filterCapacity . ' осіб';
    $rooms = findAvailable($pdo, $filterCapacity);
} else {
    $rooms = getAllRooms($pdo);
}

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Практична робота №4 | Варіант 8: Система бронювання</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .table-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        }
        .header-bg {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #fff;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-dark header-bg py-3 mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand mb-0 h1">
            <i class="bi bi-building"></i> Готель & Конференц-зали (Варіант 8)
        </span>
        <span class="navbar-text text-light">
            Практична робота №4 · PDO / MySQL
        </span>
    </div>
</nav>

<div class="container mb-5">
    <?php if ($msg === 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Новий номер/залу успішно <strong>додано</strong> до бази даних!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-pencil-square me-2"></i> Дані номеру успішно <strong>оновлено</strong>!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-trash-fill me-2"></i> Запис успішно <strong>видалено</strong> з бази даних.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($msg === 'not_found'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Номер з таким ID не знайдено!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <div class="row g-3 align-items-center">
                <div class="col-md-3">
                    <a href="add.php" class="btn btn-primary w-100 py-2">
                        <i class="bi bi-plus-circle me-1"></i> Додати номер / залу
                    </a>
                </div>

                <div class="col-md-4">
                    <form method="GET" action="index.php" class="d-flex gap-2">
                        <input type="text" 
                               name="search_number" 
                               class="form-control" 
                               placeholder="Пошук за номером (напр. 101)" 
                               value="<?= htmlspecialchars($searchNumber) ?>">
                        <button type="submit" class="btn btn-outline-secondary" title="Знайти за номером">
                            <i class="bi bi-search"></i>
                        </button>
                    </form>
                </div>

                <div class="col-md-4">
                    <form method="GET" action="index.php" class="d-flex gap-2">
                        <input type="number" 
                               name="filter_capacity" 
                               class="form-control" 
                               placeholder="Мін. місткість (осіб)" 
                               min="1" 
                               value="<?= $filterCapacity !== null ? htmlspecialchars((string)$filterCapacity) : '' ?>">
                        <button type="submit" class="btn btn-outline-secondary" title="Фільтрувати за місткістю">
                            <i class="bi bi-people"></i>
                        </button>
                    </form>
                </div>

                <div class="col-md-1 text-end">
                    <?php if ($isFiltered): ?>
                        <a href="index.php" class="btn btn-outline-danger w-100" title="Скинути фільтри">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0 text-secondary"><?= $filterTitle ?> (знайдено: <?= count($rooms) ?>)</h4>
        <?php if ($isFiltered): ?>
            <a href="index.php" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Показати всі номери
            </a>
        <?php endif; ?>
    </div>

    <div class="card table-card border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width: 70px;">ID</th>
                        <th scope="col">Номер кімнати / Назва зали</th>
                        <th scope="col" style="width: 180px;">Місткість</th>
                        <th scope="col" style="width: 200px;">Ціна за добу</th>
                        <th scope="col" class="text-end" style="width: 220px;">Дії</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rooms)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">
                               <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                               Записів не знайдено за заданими критеріями.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rooms as $room): ?>
                            <tr>
                               <td class="fw-semibold text-secondary">#<?= (int)$room['id'] ?></td>
                               <td>
                                   <span class="fw-bold text-dark fs-6">
                                       <?= htmlspecialchars($room['number']) ?>
                                   </span>
                               </td>
                               <td>
                                   <span class="badge bg-light text-dark border px-2 py-1">
                                       <i class="bi bi-people-fill text-primary me-1"></i>
                                       <?= (int)$room['capacity'] ?> <?= (int)$room['capacity'] === 1 ? 'особа' : 'осіб' ?>
                                   </span>
                               </td>
                               <td>
                                   <span class="fw-semibold text-success">
                                       <?= number_format((float)$room['price_per_night'], 2, '.', ' ') ?> грн
                                   </span>
                               </td>
                               <td class="text-end">
                                   <a href="edit.php?id=<?= (int)$room['id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                       <i class="bi bi-pencil"></i> Редагувати
                                   </a>
                                   <a href="delete.php?id=<?= (int)$room['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Ви дійсно бажаєте видалити номер <?= htmlspecialchars(addslashes($room['number'])) ?> (ID: <?= (int)$room['id'] ?>)?');">
                                       <i class="bi bi-trash"></i> Видалити
                                   </a>
                               </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mt-4 border-info bg-light">
        <div class="card-body">
            <h5 class="card-title text-info-emphasis">
                <i class="bi bi-shield-check"></i> Перевірка захисту від SQL-ін'єкцій
            </h5>
            <p class="card-text text-muted mb-2">
                Усі запити (SELECT, INSERT, UPDATE, DELETE) реалізовано за допомогою <strong>підготовлених запитів (Prepared Statements)</strong> PDO з плейсхолдерами <code>:param</code>.
            </p>
            <p class="card-text small text-secondary mb-0">
                <strong>Тестовий експеримент:</strong> Спробуйте ввести у форму пошуку або додавання символи одинарної лапки <code>'</code> або рядок <code>' OR '1'='1</code>. Завдяки плейсхолдерам PDO значення передається СУБД окремо від тексту SQL-запиту, що повністю запобігає спотворенню структури запиту та знешкоджує SQL-ін'єкції.
            </p>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
