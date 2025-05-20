<?php
session_start();

$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

// Проверка авторизации менеджера
if (!isset($_SESSION['manager_id'])) {
    header("db_connect.php"); // Страница входа
    exit();
}



// Подключение к базе данных
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Контроль параметра GET 'page' (если используется для навигации)
$allowed_pages = ['users', 'computers', 'sessions', 'generate_word_payment','generate_word_booking', 'generate_excel_report'];
$page = $_GET['page'] ?? ''; // например, http://site.php?page=users

if ($page !== '' && !in_array($page, $allowed_pages)) {
    // Недопустимый параметр - 404 или редирект
    header("HTTP/1.0 404 Not Found");
    exit("Страница не найдена");
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    padding: 20px;
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.content {
    flex: 1;
}

.reports-menu {
    width: 300px;
    background-color: none;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
}

.reports-menu h2 {
    margin-top: 0;
    font-size: 18px;
    margin-bottom: 10px;
}

.reports-menu ul {
    list-style-type: none;
    padding-left: 0;
}

.reports-menu li {
    margin-bottom: 8px;
}

.reports-menu a {
    text-decoration: none;
    color: white;
    font-size: 14px;
}

.reports-menu a:hover {
    text-decoration: underline;
}

.menu {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 20px;
}

.menu a {
    text-decoration: none;
    color: #007BFF;
    font-size: 16px;
}

.menu a:hover {
    text-decoration: underline;
}

.back-button-container {
    margin-top: 20px;
}

.back-button {
    padding: 8px 16px;
    font-size: 14px;
    cursor: pointer;
}

</style>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Профиль менеджера</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <div class="container">
        <main class="content">
            <h1>Профиль менеджера: <?= htmlspecialchars($_SESSION['manager_login']) ?></h1>
            
            <div class="menu">
                <a href="users.php">Управление пользователями</a>
                <a href="computers.php">Управление компьютерами</a>
                <a href="sessions.php">Управление сессиями</a>
            </div>
        </main>

        <aside class="reports-menu">
            <h2>Отчеты</h2>
            <ul>
                <li><a href="generate_word_payment.php">Создать отчет Word с оплатами</a></li>
                <li><a href="generate_word_booking.php">Создать отчет Word с бронированиями</a></li>
                <li><a href="generate_word_computers.php">Создать отчет Word с компьютерами</a></li>
                <li><a href="generate_word_sessions.php">Создать отчет Word с сессиями</a></li>
                <li><a href="generate_excel_payment.php">Создать отчет Excel с оплатами</a></li>
                <li><a href="generate_excel_booking.php">Создать отчет Excel с бронированиями</a></li>
                <li><a href="generate_excel_computers.php">Создать отчет Excel с компьютерами</a></li>
                <li><a href="generate_excel_sessions.php">Создать отчет Excel с сессиями</a></li>
            </ul>
        </aside>

        <div class="container">
        <main class="content">
            <div class="back-button-container">
                <a href="db_connect.php"><button class="back-button">Выход</button></a>
            </div>
        </div>
    </main>

    <?php
    // Если хотите динамически подключать страницы по параметру page
    /*
    if ($page !== '') {
        include "pages/{$page}.php";
    }
    */
    ?>
</body>
</html>
