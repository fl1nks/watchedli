<?php
session_start();

$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

$surname = $_POST['surname'] ?? '';
$email = $_POST['email'] ?? '';

if (!empty($surname) && !empty($email)) {
    // Приводим email к нижнему регистру и обрезаем пробелы для корректного сравнения
    $email = strtolower(trim($email));

    // Проверяем, существует ли пользователь с таким email
    $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
    if (!$stmt) {
        die("Ошибка подготовки запроса: " . $mysqli->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Пользователь с таким email уже существует.";
        $stmt->close();
    } else {
        $stmt->close();

        // Вставляем нового пользователя
        $stmt = $mysqli->prepare("INSERT INTO users (surname, email, data_reg, balance) VALUES (?, ?, NOW(), 0)");
        if (!$stmt) {
            die("Ошибка подготовки запроса: " . $mysqli->error);
        }
        $stmt->bind_param("ss", $surname, $email);

        if ($stmt->execute()) {
            $_SESSION['user_id'] = $stmt->insert_id;
            $_SESSION['user_surname'] = $surname;
            $_SESSION['user_email'] = $email;
            header("Location: profile_users.php");
            exit;
        } else {
            echo "Ошибка при регистрации: " . $stmt->error;
        }
        $stmt->close();
    }
} else {
    echo "Заполните все поля.";
}

$mysqli->close();
?>
