<?php
require_once 'db_connect.php'; //

if (!isset($_SESSION['user_id'])) { //
    header('Location: login.php'); //
    exit(); //
}
$user_id = $_SESSION['user_id']; //
$user_first_name = $_SESSION['first_name']; //
$policy_type_name = "ОСАГО"; // Определяем тип полиса для этой страницы //
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Полис <?php echo $policy_type_name; ?> - MyInsure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Piazzolla:ital,opsz,wght@0,8..30,100..900;1,8..30,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/Style.css">
    <link rel="stylesheet" href="Style/auth_style.css">
    <style>
        .form-container {
            padding: 30px 15px;
            max-width: 800px; 
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .form-container .form-control, .form-container .form-select {
            margin-bottom: 1rem;
        }
        .btn-submit-policy {
            background-color: #f94a77;
            color: white;
            border: 2px solid black;
            padding: 10px 20px;
        }
        .btn-submit-policy:hover {
            background-color: #c82a70;
            color: white;
        }
        .form-section {
            padding-top: 20px;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        .form-section:first-child {
            border-top: none;
            margin-top: 0;
            padding-top: 0;
        }
        .method-selection .btn-check:checked + .btn-outline-primary,
        .driver-limit-selection .btn-check:checked + .btn-outline-secondary { 
            background-color: #e83e8c;
            border-color: #e83e8c;
            color: white;
        }
        .method-selection .btn-outline-primary,
        .driver-limit-selection .btn-outline-secondary { 
            border-color: #e83e8c;
            color: #e83e8c;
        }
         .method-selection .btn-outline-primary:hover,
         .driver-limit-selection .btn-outline-secondary:hover {
            background-color: #f9d7e3;
            color: #e83e8c;
        }
        .stage-heading {
            font-family: 'Piazzolla', serif;
            color: #333;
            margin-top: 25px;
            margin-bottom: 20px; 
            padding-bottom: 10px; 
            border-bottom: 1px solid #ddd;
            font-size: 1.4rem; 
        }
        .sub-stage-heading { 
            font-family: 'Nunito Sans', sans-serif;
            font-weight: bold;
            color: #555;
            margin-top: 15px;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        .form-text.custom-format-hint {
            font-size: 0.8em;
            color: #6c757d;
        }
        .driver-block { 
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
        .hidden-fields {
            display: none;
        }
        .remove-driver-btn {
            margin-left: 10px;
        }
    </style>
</head>
<body class="auth-page">

    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand piazzolla" href="index.php">MyInsure</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="my_policies.php">Мои Полисы</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="#">Подать Претензию</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="#">Проверка Статуса</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="#">Типы Страхования</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="#">FAQ</a></li>
                    </ul>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="navbar-text nunito-sans-B me-3">Привет, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</span>
                        <a href="logout.php" class="btn login-btn nunito-sans-B" style="background-color: #dc3545; border-color: #dc3545;">
                            <span class="button-text">ВЫХОД</span>
                        </a>
                    <?php else: ?>
                         <a href="login.php" class="btn login-btn nunito-sans-B">
                            <span class="button-text">ВХОД</span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>
    <div class="полоса" style="margin-bottom: 0;"></div>

    <main class="auth-main-content">
        <div class="form-container">
            <h2 class="piazzolla text-center mb-4">Добавить новый полис <?php echo $policy_type_name; ?></h2>
            
            <?php if (isset($_SESSION['form_errors'])): ?>
                <div class="alert alert-danger">
                    <?php foreach ($_SESSION['form_errors'] as $error): ?>
                        <p class="mb-0"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['form_errors']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['form_success'])): ?>
                <div class="alert alert-success">
                    <p class="mb-0"><?php echo htmlspecialchars($_SESSION['form_success']); ?></p>
                </div>
                <?php unset($_SESSION['form_success']); ?>
            <?php endif; ?>

            <div class="mb-4 text-center method-selection">
                <p class="nunito-sans-B fs-5">Выберите способ добавления полиса:</p>
                <div class="btn-group" role="group" aria-label="Способ добавления полиса">
                    <input type="radio" class="btn-check" name="add_method_option" id="method_upload_option" value="upload" autocomplete="off" checked>
                    <label class="btn btn-outline-primary nunito-sans-B p-2 px-3" for="method_upload_option">Загрузить существующий PDF</label>

                    <input type="radio" class="btn-check" name="add_method_option" id="method_manual_option" value="manual" autocomplete="off">
                    <label class="btn btn-outline-primary nunito-sans-B p-2 px-3" for="method_manual_option">Ввести данные вручную</label>
                </div>
            </div>

            <form action="add_osago_policy_handler.php" method="POST" enctype="multipart/form-data" id="form_upload_pdf" class="form-section" style="display: block;">
                <input type="hidden" name="submission_type" value="upload_pdf">
                <input type="hidden" name="insurance_type" value="<?php echo $policy_type_name; ?>"> 
                <h4 class="piazzolla mt-0 mb-3 text-center">Загрузка существующего PDF полиса <?php echo $policy_type_name; ?></h4>
                
                <div class="mb-3">
                    <label for="upload_policy_file" class="form-label nunito-sans-B">Файл полиса (только PDF):<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="upload_policy_file" name="policy_file" accept=".pdf" > </div>
                
                <h5 class="stage-heading">Основные данные полиса <?php echo $policy_type_name; ?></h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_policy_number" class="form-label nunito-sans-B">Номер полиса (серия и номер):<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_policy_number" name="policy_number" placeholder="Например, ХХХ 0123456789" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="upload_insurance_company" class="form-label nunito-sans-B">Страховая компания:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_insurance_company" name="insurance_company" placeholder="Например, Росгосстрах" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_start_date" class="form-label nunito-sans-B">Дата начала действия:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="upload_start_date" name="start_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_end_date" class="form-label nunito-sans-B">Дата окончания действия:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="upload_end_date" name="end_date" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_premium" class="form-label nunito-sans-B">Страховая премия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="upload_premium" name="premium" step="0.01" placeholder="Например, 8500.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                         <label for="upload_status" class="form-label nunito-sans-B">Статус полиса:<span class="text-danger">*</span></label>
                         <select class="form-select" id="upload_status" name="status" required>
                            <option value="Активен" selected>Активен</option>
                            <option value="Истек">Истек</option>
                            <option value="Аннулирован">Аннулирован</option>
                         </select>
                    </div>
                </div>


                <h5 class="stage-heading">Этап 1: Данные о транспортном средстве</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_registration_plate" class="form-label nunito-sans-B">Государственный номер (ТС):<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_vehicle_registration_plate" name="vehicle_registration_plate" placeholder="А 000 АА 000" maxlength="15" required>
                        <small class="form-text custom-format-hint">Формат: А 123 БВ 456. Разрешенные буквы: А, В, Е, К, М, Н, О, Р, С, Т, У, Х.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_make" class="form-label nunito-sans-B">Марка ТС:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_vehicle_make" name="vehicle_make" placeholder="LADA (ВАЗ)" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_model" class="form-label nunito-sans-B">Модель ТС:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_vehicle_model" name="vehicle_model" placeholder="Vesta" required>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_year" class="form-label nunito-sans-B">Год выпуска ТС:<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="upload_vehicle_year" name="vehicle_year" min="1900" max="<?php echo date('Y') + 1; ?>" placeholder="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_engine_power_hp" class="form-label nunito-sans-B">Мощность двигателя (л.с.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="upload_engine_power_hp" name="engine_power_hp" step="0.1" placeholder="106.0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="upload_registration_document_type" class="form-label nunito-sans-B">Документ о регистрации ТС:<span class="text-danger">*</span></label>
                        <select class="form-select" id="upload_registration_document_type" name="registration_document_type" required>
                            <option value="" disabled selected>Выберите документ</option>
                            <option value="СТС">СТС (Свидетельство о регистрации ТС)</option>
                            <option value="ПТС">ПТС (Паспорт ТС)</option>
                            <option value="ЭПТС">ЭПТС (Электронный паспорт ТС)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="upload_registration_document_series_number" class="form-label nunito-sans-B" id="upload_reg_doc_series_number_label">Серия и Номер документа:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_registration_document_series_number" name="registration_document_series_number" required>
                        <small class="form-text custom-format-hint" id="upload_reg_doc_format_hint">Подсказка по формату появится после выбора документа.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="upload_registration_document_issue_date" class="form-label nunito-sans-B">Дата выдачи документа:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="upload_registration_document_issue_date" name="registration_document_issue_date" required>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_identification_type" class="form-label nunito-sans-B">Идентификация ТС:<span class="text-danger">*</span></label>
                        <select class="form-select" id="upload_vehicle_identification_type" name="vehicle_identification_type" required>
                            <option value="" disabled selected>Выберите тип идентификатора</option>
                            <option value="VIN">VIN Номер</option>
                            <option value="Кузов">Номер кузова</option>
                            <option value="Шасси">Номер шасси</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_vehicle_identification_number" class="form-label nunito-sans-B" id="upload_vehicle_id_number_label">Идентификационный номер:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="upload_vehicle_identification_number" name="vehicle_identification_number" required>
                         <small class="form-text custom-format-hint" id="upload_vehicle_id_format_hint">Подсказка по формату появится после выбора типа.</small>
                    </div>
                </div>

                <h5 class="stage-heading">Этап 2: Сведения о водителях</h5>
                <div class="mb-3 text-center driver-limit-selection">
                    <p class="nunito-sans-B">Ограничение по количеству водителей:<span class="text-danger">*</span></p>
                    <div class="btn-group" role="group" aria-label="Ограничение по водителям">
                        <input type="radio" class="btn-check" name="upload_driver_limit_type" id="upload_driver_limit_specified" value="specified" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary nunito-sans-B p-2 px-3" for="upload_driver_limit_specified">Указать водителей</label>

                        <input type="radio" class="btn-check" name="upload_driver_limit_type" id="upload_driver_limit_unlimited" value="unlimited" autocomplete="off">
                        <label class="btn btn-outline-secondary nunito-sans-B p-2 px-3" for="upload_driver_limit_unlimited">Без ограничений</label>
                    </div>
                </div>

                <div id="upload_drivers_container">
                    </div>
                <div class="text-center mt-2 mb-3" id="upload_add_driver_button_container">
                    <button type="button" class="btn btn-sm btn-outline-success nunito-sans-B" id="upload_add_driver_button">
                        <i class="bi bi-plus-circle"></i> Добавить водителя
                    </button>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit-policy nunito-sans-B">
                        <i class="bi bi-upload"></i> Загрузить и сохранить полис <?php echo $policy_type_name; ?>
                    </button>
                </div>
            </form>

            <form action="add_osago_policy_handler.php" method="POST" id="form_manual_entry" class="form-section" style="display: none;">
                <input type="hidden" name="submission_type" value="manual_entry">
                <input type="hidden" name="insurance_type" value="<?php echo $policy_type_name; ?>">
                <h4 class="piazzolla mt-0 mb-3 text-center">Ввод данных полиса <?php echo $policy_type_name; ?> вручную</h4>
                
                <h5 class="stage-heading">Основные данные полиса <?php echo $policy_type_name; ?></h5>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_policy_number" class="form-label nunito-sans-B">Номер полиса (серия и номер):<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_policy_number" name="policy_number" placeholder="Например, ХХХ 0123456789" required>
                    </div>
                     <div class="col-md-6 mb-3">
                        <label for="manual_insurance_company" class="form-label nunito-sans-B">Страховая компания:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_insurance_company" name="insurance_company" placeholder="Например, Росгосстрах" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_start_date" class="form-label nunito-sans-B">Дата начала действия:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="manual_start_date" name="start_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_end_date" class="form-label nunito-sans-B">Дата окончания действия:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="manual_end_date" name="end_date" required>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="manual_premium" class="form-label nunito-sans-B">Страховая премия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="manual_premium" name="premium" step="0.01" placeholder="Например, 8500.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                         <label for="manual_status" class="form-label nunito-sans-B">Статус полиса:<span class="text-danger">*</span></label>
                         <select class="form-select" id="manual_status" name="status" required>
                            <option value="Активен" selected>Активен</option>
                            <option value="Истек">Истек</option>
                            <option value="Аннулирован">Аннулирован</option>
                         </select>
                    </div>
                </div>

                <h5 class="stage-heading">Этап 1: Данные о транспортном средстве</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_registration_plate" class="form-label nunito-sans-B">Государственный номер (ТС):<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_vehicle_registration_plate" name="vehicle_registration_plate" placeholder="А 000 АА 000" maxlength="15" required>
                        <small class="form-text custom-format-hint">Формат: А 123 БВ 456. Разрешенные буквы: А, В, Е, К, М, Н, О, Р, С, Т, У, Х.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_make" class="form-label nunito-sans-B">Марка ТС:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_vehicle_make" name="vehicle_make" placeholder="LADA (ВАЗ)" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_model" class="form-label nunito-sans-B">Модель ТС:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_vehicle_model" name="vehicle_model" placeholder="Vesta" required>
                    </div>
                </div>
                 <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_year" class="form-label nunito-sans-B">Год выпуска ТС:<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="manual_vehicle_year" name="vehicle_year" min="1900" max="<?php echo date('Y') + 1; ?>" placeholder="<?php echo date('Y'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_engine_power_hp" class="form-label nunito-sans-B">Мощность двигателя (л.с.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="manual_engine_power_hp" name="engine_power_hp" step="0.1" placeholder="106.0" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="manual_registration_document_type" class="form-label nunito-sans-B">Документ о регистрации ТС:<span class="text-danger">*</span></label>
                        <select class="form-select" id="manual_registration_document_type" name="registration_document_type" required>
                            <option value="" disabled selected>Выберите документ</option>
                            <option value="СТС">СТС (Свидетельство о регистрации ТС)</option>
                            <option value="ПТС">ПТС (Паспорт ТС)</option>
                            <option value="ЭПТС">ЭПТС (Электронный паспорт ТС)</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="manual_registration_document_series_number" class="form-label nunito-sans-B" id="manual_reg_doc_series_number_label">Серия и Номер документа:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_registration_document_series_number" name="registration_document_series_number" required>
                        <small class="form-text custom-format-hint" id="manual_reg_doc_format_hint">Подсказка по формату появится после выбора документа.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="manual_registration_document_issue_date" class="form-label nunito-sans-B">Дата выдачи документа:<span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="manual_registration_document_issue_date" name="registration_document_issue_date" required>
                    </div>
                </div>
                <div class="row">
                     <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_identification_type" class="form-label nunito-sans-B">Идентификация ТС:<span class="text-danger">*</span></label>
                        <select class="form-select" id="manual_vehicle_identification_type" name="vehicle_identification_type" required>
                            <option value="" disabled selected>Выберите тип идентификатора</option>
                            <option value="VIN">VIN Номер</option>
                            <option value="Кузов">Номер кузова</option>
                            <option value="Шасси">Номер шасси</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_vehicle_identification_number" class="form-label nunito-sans-B" id="manual_vehicle_id_number_label">Идентификационный номер:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" id="manual_vehicle_identification_number" name="vehicle_identification_number" required>
                        <small class="form-text custom-format-hint" id="manual_vehicle_id_format_hint">Подсказка по формату появится после выбора типа.</small>
                    </div>
                </div>

                <h5 class="stage-heading">Этап 2: Сведения о водителях</h5>
                 <div class="mb-3 text-center driver-limit-selection">
                    <p class="nunito-sans-B">Ограничение по количеству водителей:<span class="text-danger">*</span></p>
                    <div class="btn-group" role="group" aria-label="Ограничение по водителям">
                        <input type="radio" class="btn-check" name="manual_driver_limit_type" id="manual_driver_limit_specified" value="specified" autocomplete="off" checked>
                        <label class="btn btn-outline-secondary nunito-sans-B p-2 px-3" for="manual_driver_limit_specified">Указать водителей</label>

                        <input type="radio" class="btn-check" name="manual_driver_limit_type" id="manual_driver_limit_unlimited" value="unlimited" autocomplete="off">
                        <label class="btn btn-outline-secondary nunito-sans-B p-2 px-3" for="manual_driver_limit_unlimited">Без ограничений</label>
                    </div>
                </div>

                <div id="manual_drivers_container">
                    </div>
                <div class="text-center mt-2 mb-3" id="manual_add_driver_button_container">
                    <button type="button" class="btn btn-sm btn-outline-success nunito-sans-B" id="manual_add_driver_button">
                        <i class="bi bi-plus-circle"></i> Добавить водителя
                    </button>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit-policy nunito-sans-B">
                        <i class="bi bi-save2-fill"></i> Сохранить данные полиса <?php echo $policy_type_name; ?>
                    </button>
                </div>
            </form>
        </div>
    </main>

    <footer class="site-footer" style="margin-top: auto;">
         <div class="container">
            <hr class="footer-divider" style="margin-top: 0;">
            <div class="row align-items-center footer-bottom">
                <div class="col-md-6 footer-copyright nunito-sans-B">
                    <p>&copy; MyInsure <?php echo date("Y"); ?>. Все права защищены.</p>
                </div>
                <div class="col-md-6">
                    <ul class="footer-nav list-unstyled d-flex justify-content-center justify-content-md-center nunito-sans-B">
                        <li class="nav-item footer-nav-item-nowrap"><a href="index.php#" class="nav-link p-0">О Нас</a></li>
                        <li class="nav-item"><a href="index.php#" class="nav-link p-0">Поддержка</a></li>
                        <li class="nav-item"><a href="index.php#" class="nav-link p-0">Контакты</a></li>
                        <li class="nav-item"><a href="index.php#" class="nav-link p-0">Политика Конфиденциальности</a></li>
                        <li class="nav-item"><a href="index.php#" class="nav-link p-0">Условия Использования</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <div id="driver_template" class="driver-block" style="display: none;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="sub-stage-heading mb-0">Водитель <span class="driver-number"></span></h6>
            <button type="button" class="btn btn-sm btn-outline-danger remove-driver-btn"><i class="bi bi-trash"></i> Удалить</button>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label for="" class="form-label nunito-sans-B">Фамилия Имя Отчество:<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="" placeholder="Иванов Иван Иванович" >
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Дата рождения:<span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="" >
            </div>
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Серия и номер ВУ:<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="" placeholder="1234 567890" >
                <small class="form-text custom-format-hint">10 цифр. Формат: XXXX XXXXXX</small>
            </div>
        </div>
            <div class="row">
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Дата начала стажа (кат. B):<span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="" >
            </div>
                <div class="col-md-6 mb-3">
                <label class="form-label nunito-sans-B">Менялись права за год?<span class="text-danger">*</span></label>
                <div>
                    <input type="radio" class="btn-check" name="" value="no" checked autocomplete="off">
                    <label class="btn btn-sm btn-outline-secondary" for="">Нет</label>
                    <input type="radio" class="btn-check" name="" value="yes" autocomplete="off">
                    <label class="btn btn-sm btn-outline-secondary" for="">Да</label>
                </div>
            </div>
        </div>
        <div class="row hidden-fields previous-license-fields-template">
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Фамилия в прошлых правах:</label>
                <input type="text" class="form-control" name="">
            </div>
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Серия и номер прошлых прав:</label>
                <input type="text" class="form-control" name="" placeholder="1234 567890">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Серия и номер паспорта:<span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="" placeholder="1234 567890" >
                    <small class="form-text custom-format-hint">10 цифр. Формат: XXXX XXXXXX</small>
            </div>
            <div class="col-md-6 mb-3">
                <label for="" class="form-label nunito-sans-B">Дата выдачи паспорта:<span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="" >
            </div>
        </div>
        <div class="mb-3">
            <label for="" class="form-label nunito-sans-B">Адрес регистрации:<span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="" placeholder="Город, улица, дом" >
        </div>
        <div class="mb-3">
            <label for="" class="form-label nunito-sans-B">Номер квартиры (если есть):</label>
            <input type="text" class="form-control" name="" placeholder="123">
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="">
                <label class="form-check-label nunito-sans-B" for="">
                    Водитель собственник автомобиля
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="">
                <label class="form-check-label nunito-sans-B" for="">
                    Водитель является Страхователем
                </label>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const methodRadios = document.querySelectorAll('input[name="add_method_option"]');
        const formUploadPdf = document.getElementById('form_upload_pdf');
        const formManualEntry = document.getElementById('form_manual_entry');
        
        let uploadDriverCounter = 0;
        let manualDriverCounter = 0;

        const driverTemplateHtml = document.getElementById('driver_template').innerHTML;

        function toggleFormsVisibility() {
            const isUpload = document.getElementById('method_upload_option').checked;
            formUploadPdf.style.display = isUpload ? 'block' : 'none';
            formManualEntry.style.display = isUpload ? 'none' : 'block';

            // Обновляем состояние required и disabled для всех полей в зависимости от активной формы
            updateFormElementsState(formUploadPdf, isUpload);
            updateFormElementsState(formManualEntry, !isUpload);
        }
        
        // Обновляет состояние элементов формы (включая блоки водителей)
        function updateFormElementsState(form, isActive) {
            form.querySelectorAll('input, select, textarea').forEach(el => {
                // Файл полиса в upload_pdf всегда required, если форма активна
                if (form.id === 'form_upload_pdf' && el.id === 'upload_policy_file') {
                    el.required = isActive;
                }
                // Для остальных полей в неактивной форме - выключаем
                if (!isActive) {
                    el.disabled = true;
                } else {
                     // В активной форме - включаем, если они не в скрытых блоках водителей или скрытых полях предыдущего ВУ
                    const driverLimitSpecified = form.querySelector(`input[name="${form.id.startsWith('form_upload') ? 'upload' : 'manual'}_driver_limit_type"][value="specified"]`)?.checked;
                    const isDriverField = el.closest('.driver-block');
                    const isPrevLicenseField = el.closest('.previous-license-fields-template'); // Проверяем по классу шаблона

                    if (isDriverField && !driverLimitSpecified) {
                        el.disabled = true; // Поля водителей выключены, если не "указать водителей"
                    } else if (isPrevLicenseField && isPrevLicenseField.style.display === 'none'){
                        el.disabled = true; // Поля предыдущего ВУ выключены, если блок скрыт
                    }
                    else {
                        el.disabled = false;
                    }
                }
            });
        }


        methodRadios.forEach(radio => radio.addEventListener('change', toggleFormsVisibility));
        
        function initializeDriverBlockLogic(driverBlock, driverIndex, formPrefix) {
            const licenseChangedRadios = driverBlock.querySelectorAll(`input[name="drivers[${driverIndex}][license_changed]"]`);
            const previousLicenseFields = driverBlock.querySelector(`.previous-license-fields-template`); // Используем класс для поиска

            licenseChangedRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const isYes = this.value === 'yes' && this.checked;
                    previousLicenseFields.style.display = isYes ? 'flex' : 'none';
                    previousLicenseFields.querySelectorAll('input').forEach(input => {
                        input.required = isYes;
                        if (!isYes) input.value = '';
                    });
                     updateFormElementsState(driverBlock.closest('form'), true); // Перепроверка disabled состояния
                });
            });
            // Trigger change for initial state
            const checkedLicenseChanged = driverBlock.querySelector(`input[name="drivers[${driverIndex}][license_changed]"]:checked`);
            if (checkedLicenseChanged) checkedLicenseChanged.dispatchEvent(new Event('change'));


            const licenseInput = driverBlock.querySelector(`input[name="drivers[${driverIndex}][license_series_number]"]`);
            if(licenseInput) {
                licenseInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '').substring(0, 10);
                    e.target.value = value.length > 4 ? value.substring(0, 4) + ' ' + value.substring(4) : value;
                });
            }
            const prevLicenseInput = driverBlock.querySelector(`input[name="drivers[${driverIndex}][previous_license_series_number]"]`);
             if(prevLicenseInput) {
                prevLicenseInput.addEventListener('input', function(e) {
                     let value = e.target.value.replace(/\D/g, '').substring(0, 10);
                    e.target.value = value.length > 4 ? value.substring(0, 4) + ' ' + value.substring(4) : value;
                });
            }

            const passportInput = driverBlock.querySelector(`input[name="drivers[${driverIndex}][passport_series_number]"]`);
            if(passportInput) {
                passportInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '').substring(0, 10);
                     e.target.value = value.length > 4 ? value.substring(0, 4) + ' ' + value.substring(4) : value;
                });
            }
            
            const removeButton = driverBlock.querySelector('.remove-driver-btn');
            if(removeButton){
                removeButton.addEventListener('click', function(){
                    driverBlock.remove();
                    // Optionally re-number drivers or adjust counter if needed, though for submission distinct indices are fine
                });
            }
        }
        
        function addDriver(formPrefix) {
            let driverCounter = (formPrefix === 'upload') ? ++uploadDriverCounter : ++manualDriverCounter;
            
            const driversContainer = document.getElementById(`${formPrefix}_drivers_container`);
            const newDriverBlock = document.createElement('div');
            newDriverBlock.classList.add('driver-block');
            newDriverBlock.innerHTML = driverTemplateHtml; // Используем innerHTML шаблона

            // Обновляем заголовок и ID блока
            newDriverBlock.id = `${formPrefix}_driver_block_${driverCounter}`;
            newDriverBlock.querySelector('.driver-number').textContent = driverCounter;
            
            // Обновляем атрибуты для всех интерактивных элементов
            newDriverBlock.querySelectorAll('label').forEach(label => {
                const oldFor = label.getAttribute('for');
                if (oldFor) { // Не все label могут иметь for (например, для группы радио)
                    label.setAttribute('for', `${formPrefix}_driver_${driverCounter}_${oldFor.split('_').pop()}`);
                }
            });

            newDriverBlock.querySelectorAll('input[type="text"], input[type="date"], input[type="checkbox"], select').forEach(input => {
                const oldId = input.id;
                const baseName = input.name; // Имя из шаблона (пустое)
                const nameParts = baseName ? baseName.match(/drivers\[\d*\]\[(.*)\]/) : null; // Извлекаем имя поля
                let fieldName = input.dataset.fieldName || oldId?.split('_').pop() || baseName; // Пытаемся получить имя поля

                 // Для имен полей из шаблона (там они пустые, берем из data-атрибута или формируем)
                if (input.closest('#driver_template')) { // Если это элемент из шаблона, у него может не быть правильного name
                     const placeholderName = input.placeholder || input.type; // Примерное имя
                     // Это очень упрощенно, в шаблоне лучше прописать data-fieldName атрибуты
                     if (input.type === 'text' && input.placeholder.includes('Иванов')) fieldName = 'full_name';
                     else if (input.type === 'date' && (oldId?.includes('date_of_birth') || input.name.includes('date_of_birth'))) fieldName = 'date_of_birth';
                     else if (input.placeholder === '1234 567890' && (oldId?.includes('license_series_number') || input.name.includes('license_series_number'))) fieldName = 'license_series_number';
                     else if (oldId?.includes('experience_start_date') || input.name.includes('experience_start_date')) fieldName = 'experience_start_date';
                     else if (oldId?.includes('previous_full_name')|| input.name.includes('previous_full_name')) fieldName = 'previous_full_name';
                     else if (oldId?.includes('previous_license_series_number') || input.name.includes('previous_license_series_number')) fieldName = 'previous_license_series_number';
                     else if (input.placeholder === '1234 567890' && (oldId?.includes('passport_series_number') || input.name.includes('passport_series_number'))) fieldName = 'passport_series_number';
                     else if (oldId?.includes('passport_issue_date')|| input.name.includes('passport_issue_date')) fieldName = 'passport_issue_date';
                     else if (input.placeholder === 'Город, улица, дом' || input.name.includes('registration_address')) fieldName = 'registration_address';
                     else if (input.placeholder === '123' || input.name.includes('apartment_number')) fieldName = 'apartment_number';
                     else if (input.type === 'checkbox' && (oldId?.includes('is_owner')|| input.name.includes('is_owner'))) fieldName = 'is_owner';
                     else if (input.type === 'checkbox' && (oldId?.includes('is_policyholder') || input.name.includes('is_policyholder'))) fieldName = 'is_policyholder';
                }


                input.id = `${formPrefix}_driver_${driverCounter}_${fieldName || input.name || input.type}`;
                input.name = `drivers[${driverCounter}][${fieldName || input.name}]`;
                
                if (input.type !== 'checkbox' && input.type !== 'radio') input.value = '';
                if (input.type === 'checkbox') input.checked = false;

                // Устанавливаем required для основных полей, если не в предыдущих правах
                if (!input.closest('.previous-license-fields-template')) {
                     if(['full_name', 'date_of_birth', 'license_series_number', 'experience_start_date', 'passport_series_number', 'passport_issue_date', 'registration_address'].includes(fieldName)){
                        input.required = true;
                    }
                } else {
                    input.required = false; // Поля предыдущих прав не обязательны по умолчанию
                }
            });
            
            newDriverBlock.querySelectorAll('input[type="radio"]').forEach(radio => {
                const oldId = radio.id;
                const baseName = radio.name; // e.g. drivers[1][license_changed]
                let fieldName = radio.dataset.fieldName || baseName?.match(/\[(.*?)\]$/)?.[1] || oldId?.split('_').pop();
                
                radio.id = `${formPrefix}_driver_${driverCounter}_${fieldName}_${radio.value}`;
                radio.name = `drivers[${driverCounter}][${fieldName}]`;
                // Устанавливаем 'no' для license_changed по умолчанию
                radio.checked = (fieldName === 'license_changed' && radio.value === 'no');

                const label = newDriverBlock.querySelector(`label[for="${oldId}"]`);
                if (label) label.setAttribute('for', radio.id);
            });


            driversContainer.appendChild(newDriverBlock);
            initializeDriverBlockLogic(newDriverBlock, driverCounter, formPrefix);
            updateFormElementsState(driversContainer.closest('form'), true); // Обновить состояние элементов после добавления
        }

        document.getElementById('upload_add_driver_button').addEventListener('click', () => addDriver('upload'));
        document.getElementById('manual_add_driver_button').addEventListener('click', () => addDriver('manual'));

        function handleDriverLimitChange(formPrefix) {
            const specifiedRadio = document.getElementById(`${formPrefix}_driver_limit_specified`);
            const unlimitedRadio = document.getElementById(`${formPrefix}_driver_limit_unlimited`);
            const driversContainer = document.getElementById(`${formPrefix}_drivers_container`);
            const addDriverButtonContainer = document.getElementById(`${formPrefix}_add_driver_button_container`);

            if (!specifiedRadio || !driversContainer || !addDriverButtonContainer) return;

            const toggleDriverSection = () => {
                const isActiveForm = (formPrefix === 'upload' && formUploadPdf.style.display === 'block') || (formPrefix === 'manual' && formManualEntry.style.display === 'block');
                
                if (specifiedRadio.checked) {
                    driversContainer.style.display = 'block';
                    addDriverButtonContainer.style.display = 'block'; 
                    if (driversContainer.children.length === 0) { // Добавляем первого водителя, если контейнер пуст
                        addDriver(formPrefix);
                    }
                    // Управление required/disabled для полей водителей
                    driversContainer.querySelectorAll('.driver-block input, .driver-block select').forEach(el => {
                         // Только если вся форма активна
                        if(isActiveForm) {
                            const isPrevLicenseField = el.closest('.previous-license-fields-template');
                            if (isPrevLicenseField && isPrevLicenseField.style.display === 'none') {
                                el.disabled = true;
                                el.required = false;
                            } else {
                                el.disabled = false;
                                // Установка required для основных полей
                                const nameAttr = el.name;
                                if(nameAttr && (nameAttr.includes('[full_name]') || nameAttr.includes('[date_of_birth]') || nameAttr.includes('[license_series_number]') || nameAttr.includes('[experience_start_date]') || nameAttr.includes('[passport_series_number]') || nameAttr.includes('[passport_issue_date]') || nameAttr.includes('[registration_address]'))) {
                                   if (!isPrevLicenseField) el.required = true; // Не делаем required для полей предыдущего ВУ по умолчанию
                                }
                            }
                        } else {
                             el.disabled = true;
                        }
                    });
                } else { // "Без ограничений"
                    driversContainer.style.display = 'none';
                    addDriverButtonContainer.style.display = 'none';
                    driversContainer.querySelectorAll('.driver-block input, .driver-block select').forEach(el => {
                        el.disabled = true;
                        el.required = false;
                    });
                }
            };

            specifiedRadio.addEventListener('change', toggleDriverSection);
            if (unlimitedRadio) unlimitedRadio.addEventListener('change', toggleDriverSection);
            
             // Изначально добавляем одного водителя, если выбрано "Указать водителей"
            if (specifiedRadio.checked && driversContainer.children.length === 0) {
               // addDriver(formPrefix); // Не добавляем автоматически, пусть пользователь нажмет кнопку
            }
            toggleDriverSection(); // Initial state call
        }

        handleDriverLimitChange('upload');
        handleDriverLimitChange('manual');
        
        // --- Логика для Этапа 1 ---
        function setupRegDocLogic(prefix) {
            const docTypeSelect = document.getElementById(prefix + '_registration_document_type');
            const seriesNumberInput = document.getElementById(prefix + '_registration_document_series_number');
            const seriesNumberLabel = document.getElementById(prefix + '_reg_doc_series_number_label');
            const formatHint = document.getElementById(prefix + '_reg_doc_format_hint');

            if (!docTypeSelect || !seriesNumberInput || !seriesNumberLabel || !formatHint) return;

            docTypeSelect.addEventListener('change', function() {
                seriesNumberInput.value = '';
                const selectedType = this.value;
                let placeholder = ''; let hintText = ''; let maxLength = 20;

                if (selectedType === 'СТС') {
                    seriesNumberLabel.innerHTML = 'Серия и Номер СТС:<span class="text-danger">*</span>'; placeholder = '11 22 333444';
                    hintText = 'Формат СТС: 10 цифр, например: 12 34 567890'; maxLength = 12; 
                } else if (selectedType === 'ПТС') {
                    seriesNumberLabel.innerHTML = 'Серия и Номер ПТС:<span class="text-danger">*</span>'; placeholder = '11 АА 222333';
                    hintText = 'Формат ПТС: 2ц, 2б (АВЕКМНОРСТУХ), 6ц. Прим: 25ТА123456'; maxLength = 12;
                } else if (selectedType === 'ЭПТС') {
                    seriesNumberLabel.innerHTML = 'Номер ЭПТС:<span class="text-danger">*</span>'; placeholder = '15 цифр без пробелов';
                    hintText = 'Формат ЭПТС: 15 цифр без пробелов.'; maxLength = 15;
                }
                seriesNumberInput.placeholder = placeholder; seriesNumberInput.maxLength = maxLength + (selectedType !== 'ЭПТС' ? 2 : 0); // +2 for spaces
                formatHint.textContent = hintText;
            });

            seriesNumberInput.addEventListener('input', function(e){
                const selectedType = docTypeSelect.value; let value = e.target.value; let formattedValue = value;
                if (selectedType === 'СТС') { 
                    value = value.replace(/[^0-9]/g, '').substring(0, 10);
                    formattedValue = value.length > 4 ? value.substring(0, 2) + ' ' + value.substring(2, 4) + ' ' + value.substring(4) : (value.length > 2 ? value.substring(0, 2) + ' ' + value.substring(2) : value);
                    e.target.value = formattedValue.trim();
                } else if (selectedType === 'ПТС') { 
                    value = value.toUpperCase().replace(/[^0-9АВЕКМНОРСТУХ]/g, '').substring(0,10);
                    let d1 = value.match(/^\d{0,2}/)?.[0] || ''; value = value.substring(d1.length);
                    let l1 = value.match(/^[АВЕКМНОРСТУХ]{0,2}/)?.[0] || ''; value = value.substring(l1.length);
                    let d2 = value.match(/^\d{0,6}/)?.[0] || '';
                    formattedValue = d1 + (d1.length === 2 && (l1.length > 0 || d2.length > 0) ? ' ' : '') + l1 + (l1.length === 2 && d2.length > 0 ? ' ' : '') + d2;
                    e.target.value = formattedValue.trim();
                } else if (selectedType === 'ЭПТС') {
                    e.target.value = value.replace(/[^0-9]/g, '').substring(0, 15);
                }
            });
             if(docTypeSelect.value) docTypeSelect.dispatchEvent(new Event('change')); // Initial call if value pre-selected
        }
        setupRegDocLogic('upload');
        setupRegDocLogic('manual');

        function setupVehicleIdLogic(prefix) {
            const idTypeSelect = document.getElementById(prefix + '_vehicle_identification_type');
            const idNumberInput = document.getElementById(prefix + '_vehicle_identification_number');
            const formatHint = document.getElementById(prefix + '_vehicle_id_format_hint');

            if(!idTypeSelect || !idNumberInput || !formatHint) return;

            idTypeSelect.addEventListener('change', function() {
                idNumberInput.value = ''; const selectedType = this.value;
                let placeholder = ''; let maxLength = 30; let hintText = '';
                if (selectedType === 'VIN') {
                    placeholder = '17 латинских букв и цифр'; maxLength = 17; hintText = 'VIN: 17 симв. (лат. A-Z кроме I,O,Q; 0-9).'; idNumberInput.pattern = '[A-HJ-NPR-Z0-9]{17}';
                } else if (selectedType === 'Кузов') {
                    placeholder = '9-12 цифр'; maxLength = 12; hintText = 'Номер кузова: 9-12 цифр.'; idNumberInput.pattern = '[0-9]{9,12}';
                } else if (selectedType === 'Шасси') {
                    placeholder = 'До 17 лат. букв и цифр'; maxLength = 17; hintText = 'Номер шасси: до 17 симв. (лат. A-Z кроме I,O,Q; 0-9).'; idNumberInput.pattern = '[A-HJ-NPR-Z0-9]{1,17}';
                }
                idNumberInput.placeholder = placeholder; idNumberInput.maxLength = maxLength; formatHint.textContent = hintText;
            });
            idNumberInput.addEventListener('input', function(e){
                const selectedType = idTypeSelect.value; let value = e.target.value.toUpperCase();
                if (selectedType === 'VIN') e.target.value = value.replace(/[^A-HJ-NPR-Z0-9]/g, '').substring(0, 17);
                else if (selectedType === 'Кузов') e.target.value = value.replace(/[^0-9]/g, '').substring(0, 12);
                else if (selectedType === 'Шасси') e.target.value = value.replace(/[^A-HJ-NPR-Z0-9]/g, '').substring(0, 17);
            });
            if(idTypeSelect.value) idTypeSelect.dispatchEvent(new Event('change')); // Initial call
        }
        setupVehicleIdLogic('upload');
        setupVehicleIdLogic('manual');

        ['upload_vehicle_registration_plate', 'manual_vehicle_registration_plate'].forEach(id => {
            const input = document.getElementById(id);
            if (!input) return;
            input.addEventListener('input', function(e) {
                let value = e.target.value.toUpperCase().replace(/[^АВЕКМНОРСТУХ0-9]/g, '');
                let p1 = value.substring(0,1); let p2 = value.substring(1,4); let p3 = value.substring(4,6); let p4 = value.substring(6,9);
                e.target.value = `${p1}${p2 ? ' ' + p2 : ''}${p3 ? ' ' + p3 : ''}${p4 ? ' ' + p4 : ''}`.trim().substring(0, 15);
            });
        });
        
        toggleFormsVisibility(); // Инициализация состояния форм при загрузке
    });
    </script>
</body>
</html>