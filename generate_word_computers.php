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
$computers = get_data($pdo, "SELECT * FROM computers");

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
    <h1>Отчет компьютеров</h1>
    <p>Дата генерации: ' . date('d.m.Y H:i') . '</p>';

// Генерация отчета для каждого пользователя
foreach ($computers as $computer) {
    echo '
    <div class="user-section">
        <h2>Имя пк: ' . htmlspecialchars($computer['computer_name']) . '</h2>
        <p>Характеристики: ' . htmlspecialchars($computer['specs']) . '</p>
        <p>Цена: ' . htmlspecialchars($computer['price']) . '</p>
                
            </tr>';


    echo '</table>';

    // Секция бронирований (если таблица существует)

    echo '</div>'; // Закрываем секцию пользователя
}


