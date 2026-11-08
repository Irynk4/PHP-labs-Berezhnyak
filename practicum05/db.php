<?php
/**
 * db.php — Файл підключення до бази даних MySQL через розширення PDO.
 * 
 * Практична робота №4: Взаємодія вебзастосунку з базою даних (PDO/MySQL)
 * Варіант 8: Система бронювання (готель/зали)
 */

$host    = 'localhost';
$dbname  = 'practicum4';
$user    = 'root';
$pass    = ''; // За замовчуванням у XAMPP / OpenServer пароль порожній
$charset = 'utf8mb4';

$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

$options = [
    // Увімкнення генерації винятків PDOException при помилках SQL
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Повернення результатів у вигляді асоціативного масиву [назва стовпця => значення]
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Вимкнення емуляції підготовлених запитів для максимальної безпеки від SQL-ін'єкцій
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // У навчальному проекті показуємо повідомлення про помилку
    die("Помилка підключення до бази даних: " . htmlspecialchars($e->getMessage()));
}

// Повертаємо об'єкт $pdo для використання в інших скриптах через require_once
return $pdo;
