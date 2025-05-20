<?php
session_start();

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Вы не авторизованы.";
    header('Location: db_connect.php');
    exit;
}

// Проверка HTTP_REFERER - запрет прямого доступа по URL
if (!isset($_SERVER['HTTP_REFERER'])) {
    header('Location: http://localhost/watchedl/db_connect.php');
    exit;
}

$referer = $_SERVER['HTTP_REFERER'];
$allowed_domains = [
    'http://localhost',
    // Добавьте другие разрешенные домены при необходимости
];

$is_allowed = false;
foreach ($allowed_domains as $domain) {
    if (strpos($referer, $domain) === 0) {
        $is_allowed = true;
        break;
    }
}

if (!$is_allowed) {
    header('Location: http://localhost/watchedl/db_connect.php');
    exit;
}

// Подключение к БД
$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Получение списка доступных компьютеров
    $stmt = $pdo->query("SELECT * FROM computers");
    $computers = $stmt->fetchAll();

    // Получение бронирований пользователя
    $userId = $_SESSION['user_id'];
    $bookingStmt = $pdo->prepare("SELECT b.*, c.computer_name FROM bookings b JOIN computers c ON b.computer_id = c.id WHERE b.user_id = ?");
    $bookingStmt->execute([$userId]);
    $bookings = $bookingStmt->fetchAll();

    // Получение баланса пользователя
    $balanceStmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $balanceStmt->execute([$userId]);
    $userData = $balanceStmt->fetch(PDO::FETCH_ASSOC);
    $balance = $userData['balance'] ?? 0.00;

    // Получение истории платежей пользователя
    $paymentStmt = $pdo->prepare("SELECT * FROM payment WHERE user_id = ? ORDER BY date DESC");
    $paymentStmt->execute([$userId]);
    $payments = $paymentStmt->fetchAll();

} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Компьютерный клуб</title>
    <style>
        /* ... (Ваши стили из файла, оставьте как есть) ... */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #121212;
            background-image: url('1234.gif');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }
        html, body { height: 100%; }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0px;
            background-color:  rgba(30, 30, 30, 0.7);
            z-index: 1000;
        }
        .header-text {
            font-family: Arial, sans-serif;
            color: rgba(255, 255, 255, 0.7);
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            margin-left: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 100%;
            text-shadow: 1px 2px 3px #000;
        }
        .balance-container {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        #balance { margin-right: 10px; }
        #topup-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        #topup-button:hover { background-color: #2980b9; }
        .logout-button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-right: 20px;
        }
        .logout-button:hover { background-color: #2980b9; }
        main { padding: 20px; }
        .bookings-payments-container {
            display: flex;
            gap: 40px;
            width: 100%;
            justify-content: space-between;
        }
        .glass-panel {
            background-color: rgba(30, 30, 30, 0.7);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            width: 30%;
            box-sizing: border-box;
            min-height: 150px;
            left: 360px;
        }
        .glass-panel h2 {
            color: #00bcd4;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #424242;
            padding-bottom: 10px;
        }
        #bookings-list p { margin-bottom: 8px; }
        #all-computers { margin-top: 150px; }
        .computers-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: flex-start;
            width: 100%;
        }
        .computer-item {
            background-color: rgba(30, 30, 30, 0.7);
            border-radius: 15px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            width: 550px;
            text-align: left;
            margin: 0;
        }
        .date-time-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        .date-time-container input[type="date"],
        .date-time-container input[type="time"] {
            width: auto;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #424242;
            background-color: rgba(255, 255, 255, 0.1);
            color: #e0e0e0;
        }
        .computer-item h3 {
            color: #00bcd4;
            margin-top: 0;
            margin-bottom: 10px;
        }
        .computer-item p { margin-bottom: 8px; }
        .computer-item form label,
        .computer-item form input,
        .computer-item form button {
            display: block;
            margin-bottom: 8px;
        }
        .computer-item form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .computer-item form button:hover { background-color: #2980b9; }
        .empty-message {
            font-style: italic;
            color: #757575;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0; right: 0; bottom: 0; left: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1;
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
        }
        .modal-content form { text-align: left; }
        .modal-content label {
            display: block;
            margin-bottom: 5px;
        }
        .modal-content input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }
        .modal-content button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
        .modal-content button:hover { background-color: #2980b9; }
    </style>
</head>
<body>
<header>
    <div class="header-text">| Компьютерный клуб - Watched |</div>
    <div class="balance-container">
        <span id="balance">Ваш баланс: <?php echo htmlspecialchars($balance); ?> рублей</span>
        <button id="topup-button">Пополнить баланс</button>
    </div>
    <a href="db_connect.php" class="logout-button">Выйти</a>
</header>
<main>
    <?php
    // Вывод сообщений
    if (isset($_SESSION['success'])) {
        echo '<div id="modal" class="modal" style="display:block;">
            <div class="modal-content">
                <p>' . $_SESSION['success'] . '</p>
            </div>
        </div>';
        echo '<script>
            setTimeout(function() {
                document.getElementById("modal").style.display = "none";
            }, 5000);
        </script>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div id="modal" class="modal" style="display:block;">
            <div class="modal-content">
                <p>' . $_SESSION['error'] . '</p>
            </div>
        </div>';
        echo '<script>
            setTimeout(function() {
                document.getElementById("modal").style.display = "none";
            }, 5000);
        </script>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="bookings-payments-container">
        <!-- Мои бронирования -->
        <section id="user-profile" class="glass-panel">
            <h2>Мои бронирования</h2>
            <div id="bookings-list">
                <?php if (empty($bookings)): ?>
                    <p class="empty-message">У вас пока нет бронирований.</p>
                <?php else: ?>
                    <?php foreach ($bookings as $booking): ?>
                        <p>ПК: <?php echo htmlspecialchars($booking['computer_name']); ?> | Дата: <?php echo htmlspecialchars($booking['booking_date']); ?> | Время: <?php echo htmlspecialchars($booking['booking_time']); ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>

        <!-- История платежей -->
        <section id="payment-history" class="glass-panel">
            <h2>История оплат</h2>
            <?php if (empty($payments)): ?>
                <p class="empty-message">У вас пока нет истории оплат.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($payments as $payment): ?>
                        <li>
                            Сумма: <?php echo htmlspecialchars($payment['amount']); ?> рублей |
                            Дата: <?php echo htmlspecialchars($payment['date']); ?> |
                            Способ: <?php echo htmlspecialchars($payment['method']); ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <!-- Список компьютеров в рамочках -->
        <section id="all-computers">
            <h2>Доступные компьютеры</h2>
            <div class="computers-grid">
                <?php foreach ($computers as $computer): ?>
                    <div class="computer-item">
                        <h3><?php echo htmlspecialchars($computer['computer_name']); ?></h3>
                        <p><?php echo isset($computer['specs']) ? htmlspecialchars($computer['specs']) : 'Описание отсутствует'; ?></p>
                        <p>Цена: <?php echo isset($computer['price']) ? htmlspecialchars($computer['price']) : 'Цена не указана'; ?> рублей/час</p>
                        <form method="POST" action="process_booking.php">
                            <input type="hidden" name="computer_id" value="<?php echo htmlspecialchars($computer['id']); ?>">
                            <div class="date-time-container">
                                <label for="booking_date">Дата:</label>
                                <input type="date" name="booking_date" id="booking_date" required>
                                <label for="booking_time">Время:</label>
                                <input type="time" name="booking_time" id="booking_time" required>
                            </div>
                            <button type="submit">Забронировать</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</main>

<!-- Модальное окно для пополнения баланса -->
<div id="topup-modal" class="modal">
    <div class="modal-content">
        <h2>Пополнение баланса</h2>
        <form id="topup-form" method="POST" action="process_topup.php">
            <label for="amount">Сумма:</label>
            <input type="number" name="amount" id="amount" required placeholder="Введите сумму">
            <label for="number_card">Номер карты:</label>
            <input type="text" name="number_card" id="number_card" required placeholder="Введите номер карты">
            <button type="submit">Подтвердить оплату</button>
        </form>
    </div>
</div>

<script>
    // Открытие модального окна при нажатии на кнопку "Пополнить баланс"
    document.getElementById('topup-button').addEventListener('click', function() {
        document.getElementById('topup-modal').style.display = 'block';
    });

    // Закрытие модального окна по клику вне его
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('topup-modal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
</body>
</html>
