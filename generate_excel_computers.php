<?php
// Включить буферизацию в самом начале
ob_start();

// Подключение к БД
$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

// Очистка буфера и установка заголовков
ob_end_clean();
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=report_".date('Y-m-d_His').".xls");
header("Pragma: no-cache");
header("Expires: 0");

// BOM для UTF-8
echo "\xEF\xBB\xBF";

// Основной запрос данных
$computers = $pdo->query("SELECT * FROM computers")->fetchAll(PDO::FETCH_ASSOC);

// Генерация отчета
echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        .user-header {background: #4CAF50; color: black; padding: 10px;}
        .section-header {background: #2196F3; color: white; padding: 8px;}
        .error {color: red;}
        table {border-collapse: collapse; margin-bottom: 20px;}
        th, td {border: 1px solid #ddd; padding: 5px 10px;}
    </style>
</head>
<body>';

foreach ($computers as $computer) {
    // Блок пользователя
    echo '<div class="user-header">Компьютер: '.htmlspecialchars($computer['id']).'</div>';
    
    // Основная информация
    echo '<table>
        <tr>
            <th>Имя пк</th>
            <td>'.$computer['computer_name'].'</td>
            <th>Характеристики</th>
            <td>'.$computer['specs'].'.</td>
            <th>Цена</th>
            <td>'.$computer['price'].'</td>
        </tr>
    </table>';
}

echo '</body></html>';
exit;
