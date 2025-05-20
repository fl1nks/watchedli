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
$users = $pdo->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);

// Генерация отчета
echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <style>
        .user-header {background: #4CAF50; color: white; padding: 10px;}
        .section-header {background: #2196F3; color: white; padding: 8px;}
        .error {color: red;}
        table {border-collapse: collapse; margin-bottom: 20px;}
        th, td {border: 1px solid #ddd; padding: 5px 10px;}
    </style>
</head>
<body>';

foreach ($users as $user) {
    // Блок пользователя
    echo '<div class="user-header">Пользователь: '.htmlspecialchars($user['surname']).'</div>';
    
    // Основная информация
    echo '<table>
        <tr>
            <th>ID</th>
            <td>'.$user['id'].'</td>
            <th>Баланс</th>
            <td>'.$user['balance'].' руб.</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>'.$user['email'].'</td>
            <th>Регистрация</th>
            <td>'.$user['data_reg'].'</td>
        </tr>
    </table>';

    // История пополнений
    echo '<div class="section-header">История пополнений</div>';
    $payments = $pdo->prepare("SELECT * FROM payment WHERE user_id = ?");
    $payments->execute([$user['id']]);
    
    if($payments->rowCount() > 0) {
        echo '<table>
            <tr>
                <th>Дата</th>
                <th>Сумма</th>
                <th>Метод</th>
                <th>Номер карты</th>
            </tr>';
        
        while($payment = $payments->fetch()) {
            echo '<tr>
                <td>'.$payment['date'].'</td>
                <td>'.$payment['amount'].' руб.</td>
                <td>'.$payment['method'].'</td>
                <td>'.$payment['number_card'].'</td>
            </tr>';
        }
        echo '</table>';
    } else {
        echo '<div class="error">Нет данных о пополнениях</div>';
    }

}

echo '</body></html>';
exit;
