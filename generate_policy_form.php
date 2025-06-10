<?php
require_once 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$user_first_name = $_SESSION['first_name'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить Полис - MyInsure</title>
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
            margin: 20px auto; /* Добавлен отступ сверху */
            background-color: #fff; /* Фон для контейнера формы */
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .form-container .form-control, .form-container .form-select {
            margin-bottom: 1rem; /* Увеличен стандартный отступ */
        }
        .btn-submit-policy { /* Общий класс для кнопок отправки */
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
        /* Стили для радиокнопок-переключателей */
        .method-selection .btn-check:checked + .btn-outline-primary {
            background-color: #e83e8c; /* Активный цвет бренда */
            border-color: #e83e8c;
            color: white;
        }
        .method-selection .btn-outline-primary {
            border-color: #e83e8c;
            color: #e83e8c;
        }
         .method-selection .btn-outline-primary:hover {
            background-color: #f9d7e3; /* Светлый оттенок при наведении */
            color: #e83e8c;
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
            <h2 class="piazzolla text-center mb-4">Добавить новый страховой полис</h2>
            
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

            <form action="add_policy_handler.php" method="POST" enctype="multipart/form-data" id="form_upload_pdf" class="form-section" style="display: block;">
                <input type="hidden" name="submission_type" value="upload_pdf">
                <h4 class="piazzolla mt-0 mb-3">1. Загрузка существующего PDF полиса</h4>
                
                <div class="mb-3">
                    <label for="policy_file" class="form-label nunito-sans-B">Файл полиса (только PDF):<span class="text-danger">*</span></label>
                    <input type="file" class="form-control" id="policy_file" name="policy_file" accept=".pdf" required>
                </div>
                
                <h5 class="piazzolla mt-4 mb-3">Данные полиса (из загружаемого документа)</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="upload_policy_number" class="form-label nunito-sans-B">Номер полиса:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="upload_policy_number" name="policy_number" placeholder="Например, AAA 123456789" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_insurance_type" class="form-label nunito-sans-B">Тип страхования:<span class="text-danger">*</span></label>
                        <select class="form-select" id="upload_insurance_type" name="insurance_type" required>
                            <option value="" disabled selected>Выберите тип</option>
                            <option value="Автомобильное">Автомобильное (КАСКО/ОСАГО)</option>
                            <option value="Медицинское">Медицинское (ДМС)</option>
                            <option value="Имущественное">Имущественное (квартира, дом)</option>
                            <option value="Путешествия">Путешествия (ВЗР)</option>
                            <option value="Жизни и здоровья">Жизни и здоровья</option>
                            <option value="Другое">Другое</option>
                        </select>
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
                        <label for="upload_coverage_amount" class="form-label nunito-sans-B">Сумма покрытия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="upload_coverage_amount" name="coverage_amount" step="0.01" placeholder="500000.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="upload_premium" class="form-label nunito-sans-B">Страховая премия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="upload_premium" name="premium" step="0.01" placeholder="15000.00" required>
                    </div>
                </div>
                <div class="mb-3">
                     <label for="upload_status" class="form-label nunito-sans-B">Статус полиса:<span class="text-danger">*</span></label>
                     <select class="form-select" id="upload_status" name="status" required>
                        <option value="Активен" selected>Активен</option>
                        <option value="Истек">Истек</option>
                        <option value="Отменен">Отменен</option>
                     </select>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit-policy nunito-sans-B">
                        <i class="bi bi-upload"></i> Загрузить и сохранить полис
                    </button>
                </div>
            </form>

            <form action="add_policy_handler.php" method="POST" id="form_manual_entry" class="form-section" style="display: none;">
                <input type="hidden" name="submission_type" value="manual_entry">
                <h4 class="piazzolla mt-0 mb-3">2. Ввод данных полиса вручную</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="manual_policy_number" class="form-label nunito-sans-B">Номер полиса:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="manual_policy_number" name="policy_number" placeholder="Например, BBB 987654321" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_insurance_type" class="form-label nunito-sans-B">Тип страхования:<span class="text-danger">*</span></label>
                        <select class="form-select" id="manual_insurance_type" name="insurance_type" required>
                            <option value="" disabled selected>Выберите тип</option>
                            <option value="Автомобильное">Автомобильное (КАСКО/ОСАГО)</option>
                            <option value="Медицинское">Медицинское (ДМС)</option>
                            <option value="Имущественное">Имущественное (квартира, дом)</option>
                            <option value="Путешествия">Путешествия (ВЗР)</option>
                            <option value="Жизни и здоровья">Жизни и здоровья</option>
                            <option value="Другое">Другое</option>
                        </select>
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
                        <label for="manual_coverage_amount" class="form-label nunito-sans-B">Сумма покрытия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="manual_coverage_amount" name="coverage_amount" step="0.01" placeholder="500000.00" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="manual_premium" class="form-label nunito-sans-B">Страховая премия (руб.):<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="manual_premium" name="premium" step="0.01" placeholder="15000.00" required>
                    </div>
                </div>
                <div class="mb-3">
                     <label for="manual_status" class="form-label nunito-sans-B">Статус полиса:<span class="text-danger">*</span></label>
                     <select class="form-select" id="manual_status" name="status" required>
                        <option value="Активен" selected>Активен</option>
                        <option value="Истек">Истек</option>
                        <option value="Отменен">Отменен</option>
                     </select>
                </div>
                <div class="mb-3">
                    <label for="manual_policy_file_comment" class="form-label nunito-sans-B">Комментарий к файлу (если есть, например, где хранится оригинал):</label>
                    <input type="text" class="form-control" id="manual_policy_file_comment" name="policy_file_comment" placeholder="Например, скан в email от 01.01.2024">
                    <small class="form-text text-muted">При ручном вводе сам PDF не загружается здесь. Это поле для заметки.</small>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit-policy nunito-sans-B">
                        <i class="bi bi-save2-fill"></i> Сохранить данные полиса
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodRadios = document.querySelectorAll('input[name="add_method_option"]');
            const formUploadPdf = document.getElementById('form_upload_pdf');
            const formManualEntry = document.getElementById('form_manual_entry');

            function toggleFormsVisibility() {
                if (document.getElementById('method_upload_option').checked) {
                    formUploadPdf.style.display = 'block';
                    // Отключаем required для полей скрытой формы, чтобы не мешать отправке видимой
                    formManualEntry.querySelectorAll('[required]').forEach(el => el.disabled = true);
                    formManualEntry.style.display = 'none';
                    formUploadPdf.querySelectorAll('[required]').forEach(el => el.disabled = false);


                } else if (document.getElementById('method_manual_option').checked) {
                    formUploadPdf.style.display = 'none';
                    formUploadPdf.querySelectorAll('[required]').forEach(el => el.disabled = true);
                    formManualEntry.style.display = 'block';
                    formManualEntry.querySelectorAll('[required]').forEach(el => el.disabled = false);
                }
            }

            methodRadios.forEach(radio => {
                radio.addEventListener('change', toggleFormsVisibility);
            });

            // Инициализация отображения форм при загрузке страницы
            toggleFormsVisibility();
        });
    </script>
</body>
</html>