<?php
$servername = "mysql";
$username = "root"; // по умолчанию для XAMPP
$password = "root"; // по умолчанию для XAMPP
$dbname = "watchedl";

// Создание соединения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>
