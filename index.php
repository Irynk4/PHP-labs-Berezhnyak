<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$rooms = [
    ['number' => '101', 'capacity' => 2, 'pricePerNight' => 1200, 'isBooked' => true],
    ['number' => '102', 'capacity' => 4, 'pricePerNight' => 2500, 'isBooked' => false],
    ['number' => '201', 'capacity' => 2, 'pricePerNight' => 1500, 'isBooked' => true],
    ['number' => '202', 'capacity' => 1, 'pricePerNight' => 800,  'isBooked' => false],
    ['number' => '301', 'capacity' => 5, 'pricePerNight' => 4000, 'isBooked' => true],
];

function formatRoom(array $room): string {
    return "<h2 class='room-title'>Номер {$room['number']}</h2>
            <p>Місткість: <strong>{$room['capacity']} ос.</strong></p>
            <p>Ціна: <strong>{$room['pricePerNight']} грн/ніч</strong></p>";
}

$totalExpectedIncome = 0;
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Система бронювання</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h1>Каталог номерів</h1>
        
        <div class="room-grid">
            <?php foreach ($rooms as $room): ?>
                <?php 
                    $statusClass = $room['isBooked'] ? 'status-booked' : 'status-available';
                    $statusText = $room['isBooked'] ? 'Заброньовано' : 'Вільний'; 
                    
                    if ($room['isBooked']) {
                        $totalExpectedIncome += $room['pricePerNight'];
                    }
                ?>
                
                <div class="room-card">
                    <div class="room-info">
                        <?= formatRoom($room) ?>
                    </div>
                    <div class="room-status <?= $statusClass ?>">
                        <?= $statusText ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="summary-block">
            <h3>Підсумок</h3>
            <p>Сумарний очікуваний дохід від заброньованих номерів:<br> 
               <span class="total-sum"><?= $totalExpectedIncome ?> грн</span>
            </p>
        </div>
    </div>
</body>
</html>