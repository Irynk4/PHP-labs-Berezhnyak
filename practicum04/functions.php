<?php
function getAllRooms(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM rooms ORDER BY id ASC');
    return $stmt->fetchAll();
}

function getRoomById(PDO $pdo, int $id): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $room = $stmt->fetch();
    return $room ?: null;
}

function findAvailable(PDO $pdo, int $capacity): array
{
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE capacity >= :capacity ORDER BY capacity ASC, price_per_night ASC');
    $stmt->execute([':capacity' => $capacity]);
    return $stmt->fetchAll();
}

function findByNumber(PDO $pdo, string $number): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM rooms WHERE number = :number');
    $stmt->execute([':number' => $number]);
    $room = $stmt->fetch();
    return $room ?: null;
}

function addRoom(PDO $pdo, string $number, int $capacity, float $pricePerNight): int
{
    $stmt = $pdo->prepare(
        'INSERT INTO rooms (number, capacity, price_per_night) 
         VALUES (:number, :capacity, :price_per_night)'
    );
    $stmt->execute([
        ':number' => $number,
        ':capacity' => $capacity,
        ':price_per_night' => $pricePerNight,
    ]);
    return (int)$pdo->lastInsertId();
}

function updateRoom(PDO $pdo, int $id, string $number, int $capacity, float $pricePerNight): bool
{
    $stmt = $pdo->prepare(
        'UPDATE rooms 
         SET number = :number, capacity = :capacity, price_per_night = :price_per_night 
         WHERE id = :id'
    );
    return $stmt->execute([
        ':number' => $number,
        ':capacity' => $capacity,
        ':price_per_night' => $pricePerNight,
        ':id' => $id,
    ]);
}

function deleteRoom(PDO $pdo, int $id): bool
{
    $stmt = $pdo->prepare('DELETE FROM rooms WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}
