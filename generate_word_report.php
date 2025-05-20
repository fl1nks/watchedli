<?php
// Файл: generate_word_report.php

// Подключение к базе данных
$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Функция для безопасного получения данных
function get_data($pdo, $query, $params = []) {
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Ошибка SQL: " . $e->getMessage());
        return []; // Возвращаем пустой массив при ошибке
    }
}

// Получаем данные пользователей
$users = get_data($pdo, "SELECT * FROM users");

// Установка заголовков для Word (.doc)
header("Content-Type: application/msword; charset=utf-8");
header("Content-Disposition: attachment; filename=отчет_" . date('Y-m-d') . ".doc");
header("Pragma: no-cache");
header("Expires: 0");

// Добавим BOM для корректного отображения кириллицы в Word
echo "\xEF\xBB\xBF";

echo '
<html xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta charset="UTF-8">
    <title>Отчет пользователей и их транзакций</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #003366; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background-color: #f2f2f2; }
        .user-section { background: #f8f9fa; padding: 15px; margin-bottom: 20px; }
        .photo { width: 150px; height: auto; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Полный отчет пользователей</h1>
    <p>Дата генерации: ' . date('d.m.Y H:i') . '</p>';

// Генерация отчета для каждого пользователя
foreach ($users as $user) {
    echo '
    <div class="user-section">
        <h2>Пользователь: ' . htmlspecialchars($user['surname']) . '</h2>
        <p>ID: ' . htmlspecialchars($user['id']) . '</p>
        <p>Email: ' . htmlspecialchars($user['email']) . '</p>
        <p>Баланс: ' . htmlspecialchars($user['balance']) . ' руб.</p>
        <p>Дата регистрации: ' . htmlspecialchars($user['data_reg']) . '</p>

        <h3>История пополнений:</h3>
        <table>
            <tr>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Метод оплаты</th>
                <th>Номер карты</th>
            </tr>';

    // Получаем платежи пользователя
    $payments = get_data($pdo, 
        "SELECT * FROM popolnenie WHERE user_id = :user_id", 
        ['user_id' => $user['id']]
    );

    foreach ($payments as $payment) {
        echo '<tr>
                <td>' . htmlspecialchars($payment['date']) . '</td>
                <td>' . htmlspecialchars($payment['amount']) . ' руб.</td>
                <td>' . htmlspecialchars($payment['method']) . '</td>
                <td>' . htmlspecialchars($payment['number_card']) . '</td>
              </tr>';
    }

    echo '</table>';

    // Секция бронирований (если таблица существует)
    echo '<h3>История бронирований:</h3>';
    
    $bookings = get_data($pdo, 
        "SELECT * FROM booking WHERE user_id = :user_id", 
        ['user_id' => $user['id']]
    );

    if (!empty($bookings)) {
        echo '<table>
                <tr>
                    <th>Дата бронирования</th>
                    <th>Время</th>
                    <th>Компьютер</th>
                </tr>';
        
        foreach ($bookings as $booking) {
            echo '<tr>
                    <td>' . htmlspecialchars($booking['booking_date'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($booking['booking_time'] ?? 'N/A') . '</td>
                    <td>' . htmlspecialchars($booking['computer_name'] ?? 'N/A') . '</td>
                  </tr>';
        }
        echo '</table>';
    } else {
        echo '<p>Бронирования отсутствуют</p>';
    }

    echo '</div>'; // Закрываем секцию пользователя
}


