<?php
session_start();
if (!isset($_SESSION['allowed'])) {
    header('Location: /watchedl/db_connect.php');
    exit();
}
?>

