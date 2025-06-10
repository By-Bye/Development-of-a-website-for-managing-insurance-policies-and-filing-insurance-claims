<?php
require_once 'db_connect.php'; // Подключаем файл для работы с БД и сессиями

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Если нет, перенаправляем на страницу входа
    exit();
}

$current_user_id = $_SESSION['user_id'];
$policies = [];
$errors = [];

// Получение полисов для текущего пользователя
$stmt = mysqli_prepare($db, "SELECT id, policy_number, insurance_type, start_date, end_date, coverage_amount, premium, status, document_filename FROM Insurance_Policies WHERE user_id = ? ORDER BY start_date DESC");
mysqli_stmt_bind_param($stmt, "i", $current_user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $policies[] = $row;
    }
} else {
    $errors[] = "Ошибка при загрузке полисов: " . mysqli_error($db);
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои Полисы - MyInsure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Piazzolla:ital,opsz,wght@0,8..30,100..900;1,8..30,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/Style.css">
    <link rel="stylesheet" href="Style/auth_style.css"> 
    <style>
        .policies-container {
            padding: 30px 15px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .policy-card {
            background-color: #fff;
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 25px; /* Немного увеличим внутренний отступ для "большего" вида */
            margin-bottom: 25px; /* Увеличим отступ между карточками */
            box-shadow: 0 4px 8px rgba(0,0,0,0.07); /* Чуть более заметная тень */
            display: flex; /* Для полного заполнения высоты, если карточки в ряду разной высоты */
            flex-direction: column;
            height: 100%; /* Карточки в одном ряду будут одинаковой высоты */
        }
        .policy-card h5 {
            font-family: 'Piazzolla', serif;
            color: #e83e8c; 
            margin-bottom: 15px; /* Добавим отступ под номером полиса */
        }
        .policy-details {
            font-family: 'Nunito Sans', sans-serif;
            flex-grow: 1; /* Позволяет этому блоку занимать доступное пространство */
        }
        .policy-details p {
            margin-bottom: 0.75rem; /* Немного увеличим отступы между параграфами */
            font-size: 0.95rem; /* Слегка увеличим шрифт деталей */
        }
        .policy-details strong {
            color: #333;
        }
        .status-active { color: green; font-weight: bold; }
        .status-expired { color: orange; font-weight: bold; }
        .status-cancelled { color: red; font-weight: bold; }
        
        .action-link { 
            display: inline-block;
            padding: 8px 15px;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            font-family: 'Nunito Sans', sans-serif;
            font-weight: bold;
            border: 2px solid black;
            transition: background-color 0.2s ease-in-out;
        }
        .download-link {
            background-color: #f94a77; 
            margin-top: 15px; /* Увеличим отступ для кнопки скачивания */
        }
        .download-link:hover {
            background-color: #c82a70;
        }

        .add-policy-dropdown-container { /* Контейнер для выпадающей кнопки */
            margin-bottom: 30px; /* Отступ под кнопкой */
            text-align: center; /* Центрируем кнопку */
        }
        .btn-add-policy { /* Стиль для основной кнопки dropdown */
            background-color: #5cb85c; /* Зеленый */
            color: white !important;
            border: 2px solid black;
            padding: 10px 20px;
            font-size: 1.1rem;
        }
        .btn-add-policy:hover, .btn-add-policy:focus {
            background-color: #4cae4c;
            color: white !important;
        }
        .dropdown-menu .dropdown-item {
            font-family: 'Nunito Sans', sans-serif;
            font-weight: 600;
        }
        .dropdown-menu .dropdown-item:hover {
            background-color: #f0f0f0;
        }

        .no-policies {
            text-align: center;
            font-size: 1.2rem;
            color: #777;
            margin-top: 40px;
        }
        .page-title { /* Стиль для заголовка страницы */
            font-family: 'Piazzolla', serif;
            color: #333;
            text-align: center;
            margin-bottom: 20px; /* Отступ под заголовком */
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
        <div class="policies-container">
            <h2 class="page-title">Мои страховые полисы</h2>

            <div class="add-policy-dropdown-container">
                <div class="dropdown">
                    <button class="btn btn-add-policy dropdown-toggle nunito-sans-B" type="button" id="addPolicyDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-circle-fill"></i> Добавить новый полис
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="addPolicyDropdownButton">
                        <li><a class="dropdown-item" href="add_policy_form.php?type=kasko">КАСКО</a></li>
                        <li><a class="dropdown-item" href="add_osago_policy.php">ОСАГО</a></li>
                        <li><a class="dropdown-item" href="add_policy_form.php?type=oms">ОМС</a></li>
                    </ul>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (empty($policies) && empty($errors)): ?>
                <p class="no-policies">У вас пока нет оформленных страховых полисов.</p>
            <?php else: ?>
                <div class="row gy-4">
                    <?php foreach ($policies as $policy): ?>
                        <div class="col-md-6 col-lg-6 d-flex align-items-stretch"> 
                            <div class="policy-card">
                                <h5>Полис № <?php echo htmlspecialchars($policy['policy_number']); ?></h5>
                                <div class="policy-details">
                                    <p><strong>Тип страхования:</strong> <?php echo htmlspecialchars($policy['insurance_type']); ?></p>
                                    <p><strong>Дата начала:</strong> <?php echo htmlspecialchars(date("d.m.Y", strtotime($policy['start_date']))); ?></p>
                                    <p><strong>Дата окончания:</strong> <?php echo htmlspecialchars(date("d.m.Y", strtotime($policy['end_date']))); ?></p>
                                    <p><strong>Сумма покрытия:</strong> <?php echo htmlspecialchars(number_format($policy['coverage_amount'], 2, ',', ' ')); ?> руб.</p>
                                    <p><strong>Страховая премия:</strong> <?php echo htmlspecialchars(number_format($policy['premium'], 2, ',', ' ')); ?> руб.</p>
                                    <p><strong>Статус:</strong>
                                        <?php
                                        $status_class = '';
                                        $status_text = htmlspecialchars($policy['status']);
                                        switch (strtolower($policy['status'])) {
                                            case 'активен': case 'active': $status_class = 'status-active'; break;
                                            case 'истек': case 'expired': $status_class = 'status-expired'; break;
                                            case 'отменен': case 'cancelled': $status_class = 'status-cancelled'; break;
                                        }
                                        echo "<span class='{$status_class}'>{$status_text}</span>";
                                        ?>
                                    </p>
                                    <?php if (!empty($policy['document_filename'])): ?>
                                        <a href="download_policy.php?policy_id=<?php echo $policy['id']; ?>" class="action-link download-link">
                                            <i class="bi bi-download"></i> Скачать полис
                                        </a>
                                    <?php else: ?>
                                        <p><small class="text-muted">Документ полиса не загружен.</small></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
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
</body>
</html>