<?php
require_once 'db_connect.php';
require_once 'vendor/autoload.php'; // Подключаем автозагрузчик Composer

use Dompdf\Dompdf;
use Dompdf\Options;

if (!isset($_SESSION['user_id'])) {
    $_SESSION['form_errors'] = ["Доступ запрещен. Пожалуйста, авторизуйтесь."];
    header('Location: generate_policy_form.php');
    exit();
}

$errors = [];
$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение и базовая очистка данных из формы
    $policy_number = trim($_POST['policy_number']);
    $insurance_type = trim($_POST['insurance_type']);
    $start_date = trim($_POST['start_date']);
    $end_date = trim($_POST['end_date']);
    $coverage_amount = filter_var(trim($_POST['coverage_amount']), FILTER_VALIDATE_FLOAT);
    $premium = filter_var(trim($_POST['premium']), FILTER_VALIDATE_FLOAT);
    $status = trim($_POST['status']);

    // Данные для PDF (опциональные)
    $pdf_insured_object = !empty(trim($_POST['pdf_insured_object'])) ? trim($_POST['pdf_insured_object']) : 'Не указан';
    $pdf_additional_conditions = !empty(trim($_POST['pdf_additional_conditions'])) ? trim($_POST['pdf_additional_conditions']) : 'Отсутствуют';


    // --- Валидация данных ---
    if (empty($policy_number)) { $errors[] = "Номер полиса обязателен."; }
    // Проверка уникальности номера полиса
    $stmt_check = mysqli_prepare($db, "SELECT id FROM Insurance_Policies WHERE policy_number = ?");
    mysqli_stmt_bind_param($stmt_check, "s", $policy_number);
    mysqli_stmt_execute($stmt_check);
    $result_check = mysqli_stmt_get_result($stmt_check);
    if (mysqli_num_rows($result_check) > 0) {
        $errors[] = "Полис с таким номером уже существует: " . htmlspecialchars($policy_number);
    }
    mysqli_stmt_close($stmt_check);

    if (empty($insurance_type)) { $errors[] = "Тип страхования обязателен."; }
    if (empty($start_date)) { $errors[] = "Дата начала обязательна."; }
    if (empty($end_date)) { $errors[] = "Дата окончания обязательна."; }
    if ($coverage_amount === false || $coverage_amount <= 0) { $errors[] = "Сумма покрытия должна быть положительным числом."; }
    if ($premium === false || $premium <= 0) { $errors[] = "Страховая премия должна быть положительным числом."; }
    if (empty($status)) { $errors[] = "Статус полиса обязателен."; }

    if (strtotime($end_date) <= strtotime($start_date)) {
        $errors[] = "Дата окончания должна быть позже даты начала.";
    }

    if (empty($errors)) {
        // --- Генерация PDF ---
        $pdf_filename = "policy_" . preg_replace('/[^a-z0-9_]/i', '_', $policy_number) . "_" . time() . ".pdf";
        $pdf_filepath = __DIR__ . '/uploads/policies/' . $pdf_filename;

        // Получение данных пользователя для PDF
        $stmt_user = mysqli_prepare($db, "SELECT first_name, last_name, middle_name, email, phone, address, region, passport_number, date_of_birth FROM Users WHERE id = ?");
        mysqli_stmt_bind_param($stmt_user, "i", $user_id);
        mysqli_stmt_execute($stmt_user);
        $user_result = mysqli_stmt_get_result($stmt_user);
        $user_data = mysqli_fetch_assoc($user_result);
        mysqli_stmt_close($stmt_user);

        if (!$user_data) {
            $errors[] = "Не удалось получить данные пользователя для генерации PDF.";
        } else {
            // Формируем HTML для PDF
            $user_full_name = htmlspecialchars($user_data['last_name'] . " " . $user_data['first_name'] . " " . $user_data['middle_name']);
            $user_dob = htmlspecialchars(date("d.m.Y", strtotime($user_data['date_of_birth'])));
            $user_passport = htmlspecialchars($user_data['passport_number']);
            $user_address_full = htmlspecialchars(trim($user_data['region'] . ", " . $user_data['address'], ", "));


            $html_content = "
            <!DOCTYPE html>
            <html lang='ru'>
            <head>
                <meta charset='UTF-8'>
                <title>Страховой Полис " . htmlspecialchars($policy_number) . "</title>
                <style>
                    body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; }
                    .container { width: 90%; margin: 20px auto; border: 1px solid #ccc; padding: 20px; }
                    h1 { text-align: center; color: #e83e8c; border-bottom: 2px solid #e83e8c; padding-bottom: 10px; }
                    h2 { color: #555; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 25px; font-size: 1.2em;}
                    p { margin: 5px 0; }
                    strong { color: #000; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f7f7f7; }
                    .footer { text-align: center; margin-top: 30px; font-size: 0.9em; color: #777; }
                    .signature-area { margin-top: 50px; padding-top: 20px; border-top: 1px dashed #ccc; }
                    .signature-line { display: inline-block; width: 200px; border-bottom: 1px solid #000; margin: 0 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <h1>Страховой Полис № " . htmlspecialchars($policy_number) . "</h1>
            
                    <h2>Страхователь</h2>
                    <p><strong>ФИО:</strong> " . $user_full_name . "</p>
                    <p><strong>Дата рождения:</strong> " . $user_dob . "</p>
                    <p><strong>Паспорт:</strong> " . $user_passport . "</p>
                    <p><strong>Адрес:</strong> " . $user_address_full . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($user_data['email']) . "</p>
                    <p><strong>Телефон:</strong> " . htmlspecialchars($user_data['phone']) . "</p>

                    <h2>Условия Страхования</h2>
                    <table>
                        <tr><th>Тип страхования</th><td>" . htmlspecialchars($insurance_type) . "</td></tr>
                        <tr><th>Объект страхования</th><td>" . htmlspecialchars($pdf_insured_object) . "</td></tr>
                        <tr><th>Период действия</th><td>с " . htmlspecialchars(date("d.m.Y", strtotime($start_date))) . " по " . htmlspecialchars(date("d.m.Y", strtotime($end_date))) . "</td></tr>
                        <tr><th>Страховая сумма (покрытие)</th><td>" . htmlspecialchars(number_format($coverage_amount, 2, ',', ' ')) . " руб.</td></tr>
                        <tr><th>Страховая премия</th><td>" . htmlspecialchars(number_format($premium, 2, ',', ' ')) . " руб.</td></tr>
                        <tr><th>Статус полиса</th><td>" . htmlspecialchars($status) . "</td></tr>
                    </table>

                    <h2>Дополнительные Условия</h2>
                    <p>" . nl2br(htmlspecialchars($pdf_additional_conditions)) . "</p>

                    <div class='signature-area'>
                        <p>Дата оформления: " . date("d.m.Y") . "</p>
                        <p>Подпись Страхователя: <span class='signature-line'></span></p>
                        <p>Представитель Страховщика: <span class='signature-line'></span> (М.П.)</p>
                    </div>
                    <div class='footer'>
                        <p>Благодарим за выбор MyInsure!</p>
                    </div>
                </div>
            </body>
            </html>";

            try {
                $options = new Options();
                $options->set('isHtml5ParserEnabled', true);
                $options->set('isRemoteEnabled', true); // Для загрузки внешних CSS/изображений, если есть
                $options->set('defaultFont', 'DejaVu Sans'); // Важно для кириллицы

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($html_content);
                $dompdf->setPaper('A4', 'portrait'); // Можно 'landscape' для альбомной
                $dompdf->render();
                
                // Сохраняем PDF файл
                $output = $dompdf->output();
                if (file_put_contents($pdf_filepath, $output) === false) {
                     $errors[] = "Не удалось сохранить PDF файл на сервере.";
                }

            } catch (Exception $e) {
                $errors[] = "Ошибка при генерации PDF: " . $e->getMessage();
            }
        }
        // --- Конец генерации PDF ---

        // Если нет ошибок с PDF (или если PDF не критичен для создания записи), сохраняем в БД
        if (empty($errors)) {
            $stmt_insert = mysqli_prepare($db, "INSERT INTO Insurance_Policies (user_id, policy_number, insurance_type, start_date, end_date, coverage_amount, premium, status, document_filename) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_insert, "issssddss",
                $user_id,
                $policy_number,
                $insurance_type,
                $start_date,
                $end_date,
                $coverage_amount,
                $premium,
                $status,
                $pdf_filename // Сохраняем имя сгенерированного файла
            );

            if (mysqli_stmt_execute($stmt_insert)) {
                $_SESSION['form_success'] = "Полис '" . htmlspecialchars($policy_number) . "' успешно создан и PDF сгенерирован!";
                header('Location: my_policies.php'); // Перенаправляем на страницу с полисами
                exit();
            } else {
                $errors[] = "Ошибка при сохранении полиса в базу данных: " . mysqli_stmt_error($stmt_insert);
                // Если PDF был создан, но запись в БД не удалась, возможно, стоит удалить PDF
                if (file_exists($pdf_filepath)) {
                    unlink($pdf_filepath);
                }
            }
            mysqli_stmt_close($stmt_insert);
        }
    }
} else {
    // Если не POST запрос, перенаправляем на форму
    header('Location: generate_policy_form.php');
    exit();
}

// Если были ошибки, сохраняем их в сессию и перенаправляем обратно на форму
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    // Сохранить введенные данные для повторного заполнения формы (опционально)
    // foreach ($_POST as $key => $value) { $_SESSION['form_data'][$key] = $value; }
    header('Location: generate_policy_form.php');
    exit();
}
?>