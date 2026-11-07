<?php
$pdo = require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0 && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
}

if ($id > 0) {
    try {
        deleteRoom($pdo, $id);
        header('Location: index.php?msg=deleted');
        exit;
    } catch (PDOException $e) {
        die('Помилка при видаленні запису: ' . htmlspecialchars($e->getMessage()));
    }
} else {
    header('Location: index.php?msg=not_found');
    exit;
}
