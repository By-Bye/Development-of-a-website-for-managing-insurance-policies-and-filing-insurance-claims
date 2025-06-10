<?php
require_once 'db_connect.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    http_response_code(403); // Forbidden
    die("Доступ запрещен. Пожалуйста, авторизуйтесь.");
}

if (!isset($_GET['policy_id']) || !is_numeric($_GET['policy_id'])) {
    http_response_code(400); // Bad Request
    die("Некорректный запрос: отсутствует или неверный ID полиса.");
}

$policy_id = (int)$_GET['policy_id'];
$current_user_id = $_SESSION['user_id'];
$base_policy_dir = __DIR__ . '/uploads/policies/'; // Путь к папке с полисами

// Получаем информацию о файле полиса из БД, проверяя принадлежность пользователю
$stmt = mysqli_prepare($db, "SELECT document_filename, policy_number FROM Insurance_Policies WHERE id = ? AND user_id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "ii", $policy_id, $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) == 1) {
    $policy = mysqli_fetch_assoc($result);
    $filename = $policy['document_filename'];
    $policy_number_for_filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $policy['policy_number']); // Очистка номера полиса для имени файла

    if (!empty($filename)) {
        $filepath = $base_policy_dir . $filename;

        if (file_exists($filepath)) {
            // Определяем MIME-тип (базовый вариант для PDF)
            $mime_type = mime_content_type($filepath);
            if (!$mime_type || $mime_type == 'application/octet-stream') { // Если не удалось определить или общий тип
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                if ($extension === 'pdf') {
                    $mime_type = 'application/pdf';
                } elseif ($extension === 'doc') {
                    $mime_type = 'application/msword';
                } elseif ($extension === 'docx') {
                    $mime_type = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                }
                // Добавьте другие типы по необходимости
            }
            
            // Формируем более читаемое имя файла для скачивания
            $download_filename = "Policy_" . $policy_number_for_filename . "." . pathinfo($filename, PATHINFO_EXTENSION);


            header('Content-Description: File Transfer');
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . basename($download_filename) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Сбрасываем буфер вывода системы
            readfile($filepath);
            exit;
        } else {
            http_response_code(404);
            die("Файл полиса не найден на сервере.");
        }
    } else {
        http_response_code(404);
        die("Для данного полиса не указан файл документа.");
    }
} else {
    http_response_code(404); // Not Found или 403 Forbidden, если полис есть, но не принадлежит пользователю
    die("Полис не найден или у вас нет прав для его скачивания.");
}
mysqli_stmt_close($stmt);
?>