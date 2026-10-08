<?php
require_once 'classes/Room.php';
require_once 'classes/ConferenceRoom.php';
require_once 'classes/BookingSystem.php';
require_once 'lib/functions.php';

$system = new BookingSystem();

$system->addRoom(new Room('101', 2, 1200.0, true));
$system->addRoom(new Room('102', 4, 2500.0, false));

$system->addRoom(new ConferenceRoom('A1', 50, 5000.0, ['Проєктор', 'Мікрофони', 'Фліпчарт'], false));
$system->addRoom(new ConferenceRoom('A2', 20, 3000.0, ['Телевізор', 'Дошка'], true));

$checkIn = '2023-11-01';
$checkOut = '2023-11-05';
$datesStr = formatDateRange($checkIn, $checkOut);
$nights = nightsBetween($checkIn, $checkOut);

?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Система бронювання (ООП)</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f4f7f6;}
        .card { background: white; padding: 15px; margin-bottom: 15px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .summary { margin-top: 20px; padding: 15px; background: #eafaf1; border-left: 5px solid #27ae60; }
    </style>
</head>
<body>
    <h1>Всі номери та зали</h1>
    
    <?php foreach ($system->getAllRooms() as $room): ?>
        <div class="card">
            <?= $room->getInfo() ?>
        </div>
    <?php endforeach; ?>

    <h2>Вільні зараз:</h2>
    <ul>
        <?php foreach ($system->findAvailable() as $room): ?>
            <li><?= $room->getInfo() ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="summary">
        <h3>Підсумок</h3>
        <p>Сумарний очікуваний дохід від заброньованих номерів: <strong><?= $system->calculateTotal() ?> грн</strong></p>
        <p>Приклад роботи бібліотеки: Бронювання на дати <em><?= $datesStr ?></em> триватиме <strong><?= $nights ?> ночей</strong>.</p>
    </div>
</body>
</html>
