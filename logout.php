<?php
// logout.php
require_once 'db_connect.php'; // Для старта сессии, если еще не стартовала

// Уничтожаем все переменные сессии.
$_SESSION = array();

// Если необходимо уничтожить сессию полностью, также удалите cookie сессии.
// Замечание: Это уничтожит сессию, а не только данные сессии!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Наконец, уничтожаем сессию.
session_destroy();

// Перенаправляем на главную страницу
header("Location: index.php");
exit;
?>