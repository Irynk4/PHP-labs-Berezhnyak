<?php
require_once 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$number = trim($data['number'] ?? '');
$capacity = (int)($data['capacity'] ?? 0);
$price = (float)($data['price_per_night'] ?? 0);

if (empty($number) || $capacity <= 0 || $price <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Заповніть усі поля коректно.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO rooms (number, capacity, price_per_night) VALUES (:number, :capacity, :price)");
    $stmt->execute([
        'number' => $number,
        'capacity' => $capacity,
        'price' => $price
    ]);
    
    http_response_code(201);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Помилка БД. Можливо, такий номер вже існує.']);
}