<?php
session_start();

$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'] ?? '';
    $password_input = $_POST['password'] ?? '';

    if ($login && $password_input) {
        $stmt = $pdo->prepare("SELECT * FROM managers WHERE login = :login");
        $stmt->execute(['login' => $login]);
        $manager = $stmt->fetch(PDO::FETCH_ASSOC);

       if ($manager && password_verify($password_input, $manager['password'])) {
    $_SESSION['manager_id'] = $manager['id'];
    $_SESSION['manager_login'] = $manager['login'];
    header("Location: profile_managers.php");
    exit();
} else {
    $_SESSION['login_error'] = "Неверный логин или пароль.";
    header("Location: db_connect.php"); // замените на вашу страницу входа
    exit();
}
    } else {
        echo "Заполните все поля.";
    }
}
?>