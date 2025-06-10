<?php
// login.php
require_once 'db_connect.php'; 

$errors = [];
$login_attempt = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login_attempt = true;
    $email = mysqli_real_escape_string($db, $_POST['email']); // Изменили identifier на email
    $password = $_POST['password'];

    if (empty($email)) {
        $errors[] = "Email обязателен.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Неверный формат email.";
    }
    if (empty($password)) {
        $errors[] = "Пароль обязателен.";
    }

    if (empty($errors)) {
        // Ищем пользователя по email
        $stmt = mysqli_prepare($db, "SELECT id, first_name, password_hash FROM Users WHERE email=? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name']; // Сохраняем имя для приветствия
                $_SESSION['show_auth_success_message'] = "Вы успешно вошли в систему!";
                header('Location: index.php'); 
                exit();
            } else {
                $errors[] = "Неверный email или пароль.";
            }
        } else {
            $errors[] = "Неверный email или пароль.";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход - MyInsure</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Piazzolla:ital,opsz,wght@0,8..30,100..900;1,8..30,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Style/Style.css">
    <link rel="stylesheet" href="Style/auth_style.css">
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
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="index.php#">Подать Претензию</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="index.php#">Проверка Статуса</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="index.php#">Типы Страхования</a></li>
                        <li class="nav-item"><a class="nav-link nunito-sans-B" href="index.php#faqAccordion">FAQ</a></li>
                    </ul>
                    <button class="btn t-btn" type="button" onclick="location.href='index.php#still-curious-section';">
                        <img src="img/phone.svg" alt="" class="button-icon">
                        <span class="button-text">ПОДДЕРЖКА</span>
                    </button>
                     <a href="register.php" class="btn login-btn nunito-sans-B" style="background-color: #6c757d; border-color: #6c757d;">
                        <span class="button-text">РЕГИСТРАЦИЯ</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="полоса" style="margin-bottom: 0;"></div>

    <main class="auth-main-content">
        <div class="auth-container">
            <h2 class="piazzolla">Вход в MyInsure</h2>

            <?php if ($login_attempt && !empty($errors)): ?>
                <div class="message error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email" class="nunito-sans-B">Email:<span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="password" class="nunito-sans-B">Пароль:<span class="text-danger">*</span></label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <i class="bi bi-eye-slash-fill password-toggle-icon" id="togglePassword"></i>
                    </div>
                </div>
                <button type="submit" class="btn-auth nunito-sans-B">Войти</button>
            </form>
            <a href="register.php" class="auth-link nunito-sans-B">Нет аккаунта? Зарегистрироваться</a>
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
        function setupPasswordToggle(toggleElementId, passwordElementId) {
            const togglePassword = document.getElementById(toggleElementId);
            const password = document.getElementById(passwordElementId);
            if (togglePassword && password) {
                togglePassword.addEventListener('click', function (e) {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);
                    this.classList.toggle('bi-eye-slash-fill');
                    this.classList.toggle('bi-eye-fill');
                });
            }
        }
        setupPasswordToggle('togglePassword', 'password');
    </script>
</body>
</html>