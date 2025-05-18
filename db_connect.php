<?php
// db_connect.php
$db_host = 'MySQL-8.0'; // Или 'localhost', если MySQL запущен локально
$db_user = 'root';
$db_pass = ''; // Укажите ваш пароль, если он есть. Если нет, оставьте пустым.
$db_name = 'Ermakov_2025';

$db = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$db) {
    error_log("Ошибка подключения к базе данных: " . mysqli_connect_error());
    die("Не удалось подключиться к базе данных. Пожалуйста, попробуйте позже.");
}

mysqli_set_charset($db, "utf8mb4");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>