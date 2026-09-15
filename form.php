<?php
$errors = [];
$successMessage = '';
$guestName = '';
$roomNumber = '';
$checkIn = '';
$checkOut = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guestName = trim($_POST['guestName'] ?? '');
    $roomNumber = trim($_POST['roomNumber'] ?? '');
    $checkIn = trim($_POST['checkIn'] ?? '');
    $checkOut = trim($_POST['checkOut'] ?? '');

    if (empty($guestName)) {
        $errors['guestName'] = 'Ім\'я гостя обов\'язкове.';
    } elseif (!preg_match('/^[a-zA-Zа-яА-ЯіІїЇєЄґҐ\s]+$/u', $guestName)) {
        $errors['guestName'] = 'Ім\'я гостя має містити лише літери та пробіли.';
    }

    if (empty($roomNumber)) {
        $errors['roomNumber'] = 'Номер кімнати обов\'язковий.';
    }

    if (empty($checkIn)) {
        $errors['checkIn'] = 'Дата заїзду обов\'язкова.';
    }
    if (empty($checkOut)) {
        $errors['checkOut'] = 'Дата виїзду обов\'язкова.';
    }

    if (!empty($checkIn) && !empty($checkOut)) {
        if (strtotime($checkOut) <= strtotime($checkIn)) {
            $errors['checkOut'] = 'Дата виїзду має бути пізнішою за дату заїзду.';
        }
    }

    if (empty($errors)) {
        $successMessage = "Бронювання успішно створено для " . htmlspecialchars($guestName) . " у номері " . htmlspecialchars($roomNumber) . " з " . htmlspecialchars($checkIn) . " по " . htmlspecialchars($checkOut) . ".";
        
        $guestName = '';
        $roomNumber = '';
        $checkIn = '';
        $checkOut = '';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма бронювання номера</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-container">
    <h2>Бронювання номера</h2>

    <?php if ($successMessage): ?>
        <div class="success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form id="bookingForm" method="post" action="form.php">
        
        <div class="form-group">
            <label for="guestName">Ім'я гостя:</label>
            <input type="text" id="guestName" name="guestName" value="<?= htmlspecialchars($guestName) ?>" required pattern="^[a-zA-Zа-яА-ЯіІїЇєЄґҐ\s]+$" title="Лише літери та пробіли">
            <?php if (isset($errors['guestName'])): ?>
                <span class="error"><?= $errors['guestName'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="roomNumber">Номер кімнати/залу:</label>
            <input type="text" id="roomNumber" name="roomNumber" value="<?= htmlspecialchars($roomNumber) ?>" required>
            <?php if (isset($errors['roomNumber'])): ?>
                <span class="error"><?= $errors['roomNumber'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="checkIn">Дата заїзду:</label>
            <input type="date" id="checkIn" name="checkIn" value="<?= htmlspecialchars($checkIn) ?>" required>
            <?php if (isset($errors['checkIn'])): ?>
                <span class="error"><?= $errors['checkIn'] ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="checkOut">Дата виїзду:</label>
            <input type="date" id="checkOut" name="checkOut" value="<?= htmlspecialchars($checkOut) ?>" required>
            <?php if (isset($errors['checkOut'])): ?>
                <span class="error"><?= $errors['checkOut'] ?></span>
            <?php endif; ?>
            
            <span id="jsDateError" class="error" style="display: none;">Дата виїзду повинна бути пізнішою за дату заїзду!</span>
        </div>

        <button type="submit">Підтвердити бронювання</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('bookingForm');
        const checkInInput = document.getElementById('checkIn');
        const checkOutInput = document.getElementById('checkOut');
        const jsDateError = document.getElementById('jsDateError');

        if (!checkInInput.value && localStorage.getItem('lastCheckIn')) {
            checkInInput.value = localStorage.getItem('lastCheckIn');
        }
        if (!checkOutInput.value && localStorage.getItem('lastCheckOut')) {
            checkOutInput.value = localStorage.getItem('lastCheckOut');
        }

        checkInInput.addEventListener('change', function() {
            localStorage.setItem('lastCheckIn', this.value);
        });
        checkOutInput.addEventListener('change', function() {
            localStorage.setItem('lastCheckOut', this.value);
        });

        form.addEventListener('submit', function(event) {
            const inDate = new Date(checkInInput.value);
            const outDate = new Date(checkOutInput.value);

            if (checkInInput.value && checkOutInput.value && outDate <= inDate) {
                event.preventDefault();
                jsDateError.style.display = 'block';
            } else {
                jsDateError.style.display = 'none';
            }
        });
    });
</script>

</body>
</html>
