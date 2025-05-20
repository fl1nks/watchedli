<?php
session_start();

$host = 'mysql';
$dbname = 'watchedl';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $surname = $_POST['surname'] ?? '';
    $email = $_POST['email'] ?? '';

    if ($surname && $email) {
        $stmt = $pdo->prepare("SELECT id, surname, email FROM users WHERE surname = :surname AND email = :email");
        $stmt->execute([
            ':surname' => $surname,
            ':email' => strtolower(trim($email))
        ]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_surname'] = $user['surname'];
            $_SESSION['user_email'] = $user['email'];
            header("Location: profile_users.php");
            exit;
        } else {
            echo "Неверная фамилия или email.";
        }
    } else {
        echo "Заполните все поля.";
    }
}
?>
