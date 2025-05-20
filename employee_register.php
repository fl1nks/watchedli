<?php
session_start();

$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

$mysqli = new mysqli('mysql', 'root', 'root', 'watchedl');


if ($mysqli->connect_errno) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

$login = $_POST['login'] ?? '';
$password_input = $_POST['password'] ?? '';

if ($login && $password_input) {
    $stmt = $mysqli->prepare("SELECT id FROM managers WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Такой логин уже существует. Пожалуйста, выберите другой.";
    } else {
        $passwordHash = password_hash($password_input, PASSWORD_DEFAULT);

        $stmt = $mysqli->prepare("INSERT INTO managers (login, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $login, $passwordHash);
        if ($stmt->execute()) {
            $_SESSION['manager_id'] = $stmt->insert_id;
            $_SESSION['manager_login'] = $login;
            header("Location: profile_managers.php");
            exit;
        } else {
            echo "Ошибка при регистрации.";
        }
    }
    $stmt->close();
} else {
    echo "Заполните все поля.";
}
$mysqli->close();
?>
