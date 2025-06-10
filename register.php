<?php
// register.php
require_once 'db_connect.php'; // Подключаем файл для работы с БД

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение данных из формы и экранирование
    $first_name = mysqli_real_escape_string($db, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($db, $_POST['last_name']);
    $middle_name = isset($_POST['middle_name']) && !empty(trim($_POST['middle_name'])) ? mysqli_real_escape_string($db, trim($_POST['middle_name'])) : NULL;
    $date_of_birth = mysqli_real_escape_string($db, $_POST['date_of_birth']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $address = isset($_POST['address']) && !empty(trim($_POST['address'])) ? mysqli_real_escape_string($db, trim($_POST['address'])) : NULL;
    $region = isset($_POST['region']) && !empty(trim($_POST['region'])) ? mysqli_real_escape_string($db, trim($_POST['region'])) : NULL; // Новое поле "область"
    $passport_number = mysqli_real_escape_string($db, $_POST['passport_number']);
    $password = $_POST['password']; 
    $password_confirm = $_POST['password_confirm'];

    // Валидация обязательных полей
    if (empty($first_name)) { $errors[] = "Имя обязательно для заполнения."; }
    if (empty($last_name)) { $errors[] = "Фамилия обязательна для заполнения."; }
    if (empty($date_of_birth)) { $errors[] = "Дата рождения обязательна для заполнения."; }
    elseif (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date_of_birth)) { $errors[] = "Неверный формат даты рождения (ГГГГ-ММ-ДД)."; }

    if (empty($email)) {
        $errors[] = "Email обязателен для заполнения.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Неверный формат email.";
    }
    if (empty($phone)) { $errors[] = "Номер телефона обязателен для заполнения."; }
    elseif (!preg_match("/^[0-9\-\+\s\(\)]{7,20}$/", $phone)) { $errors[] = "Неверный формат номера телефона."; }

    if (empty($passport_number)) { $errors[] = "Номер и серия паспорта обязательны для заполнения."; }

    if (empty($password)) {
        $errors[] = "Пароль обязателен для заполнения.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Пароль должен содержать не менее 6 символов.";
    }
    if ($password !== $password_confirm) {
        $errors[] = "Пароли не совпадают.";
    }

    // Проверка на уникальность email, телефона и номера паспорта
    if (empty($errors)) {
        $check_query_email = "SELECT id FROM Users WHERE email='$email' LIMIT 1";
        $result_email = mysqli_query($db, $check_query_email);
        if ($result_email && mysqli_num_rows($result_email) > 0) {
            $errors[] = "Пользователь с таким email уже существует.";
        }

        $check_query_phone = "SELECT id FROM Users WHERE phone='$phone' LIMIT 1";
        $result_phone = mysqli_query($db, $check_query_phone);
        if ($result_phone && mysqli_num_rows($result_phone) > 0) {
            $errors[] = "Пользователь с таким номером телефона уже существует.";
        }

        $check_query_passport = "SELECT id FROM Users WHERE passport_number='$passport_number' LIMIT 1";
        $result_passport = mysqli_query($db, $check_query_passport);
        if ($result_passport && mysqli_num_rows($result_passport) > 0) {
            $errors[] = "Пользователь с таким номером паспорта уже существует.";
        }
    }

    // Если ошибок нет, регистрируем пользователя
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = mysqli_prepare($db, "INSERT INTO Users (first_name, last_name, middle_name, date_of_birth, email, phone, address, region, passport_number, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        // Типы: s - string, d - double/decimal, i - integer, b - blob
        mysqli_stmt_bind_param($stmt, "ssssssssss", 
            $first_name, 
            $last_name, 
            $middle_name, 
            $date_of_birth, 
            $email, 
            $phone, 
            $address,
            $region, // Добавили регион
            $passport_number, 
            $hashed_password
        );

        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($db);
            $_SESSION['user_id'] = $user_id;
            $_SESSION['first_name'] = $first_name; 
            $_SESSION['show_auth_success_message'] = "Вы успешно зарегистрировались и вошли в систему!";
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Ошибка при регистрации: " . mysqli_stmt_error($stmt);
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
    <title>Регистрация - MyInsure</title>
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
                    <a href="login.php" class="btn login-btn nunito-sans-B">
                        <span class="button-text">ВХОД</span>
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div class="полоса" style="margin-bottom: 0;"></div>

    <main class="auth-main-content">
        <div class="auth-container" style="max-width: 700px;"> <h2 class="piazzolla">Создать Аккаунт</h2>

            <?php if (!empty($errors)): ?>
                <div class="message error-message">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="register.php">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="last_name" class="nunito-sans-B">Фамилия:<span class="text-danger">*</span></label>
                            <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo isset($_POST['last_name']) ? htmlspecialchars($_POST['last_name']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="first_name" class="nunito-sans-B">Имя:<span class="text-danger">*</span></label>
                            <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo isset($_POST['first_name']) ? htmlspecialchars($_POST['first_name']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="middle_name" class="nunito-sans-B">Отчество:</label>
                            <input type="text" id="middle_name" name="middle_name" class="form-control" value="<?php echo isset($_POST['middle_name']) ? htmlspecialchars($_POST['middle_name']) : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date_of_birth" class="nunito-sans-B">Дата рождения:<span class="text-danger">*</span></label>
                            <input type="date" id="date_of_birth" name="date_of_birth" class="form-control" value="<?php echo isset($_POST['date_of_birth']) ? htmlspecialchars($_POST['date_of_birth']) : ''; ?>" required>
                        </div>
                    </div>
                     <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="nunito-sans-B">Номер телефона:<span class="text-danger">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="+7 (XXX) XXX-XX-XX" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="nunito-sans-B">Email:<span class="text-danger">*</span></label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="example@domain.com" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="passport_number" class="nunito-sans-B">Номер и серия паспорта:<span class="text-danger">*</span></label>
                            <input type="text" id="passport_number" name="passport_number" class="form-control" placeholder="XXXX XXXXXX" value="<?php echo isset($_POST['passport_number']) ? htmlspecialchars($_POST['passport_number']) : ''; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                         <div class="form-group">
                            <label for="region" class="nunito-sans-B">Область:</label>
                            <input type="text" id="region" name="region" class="form-control" value="<?php echo isset($_POST['region']) ? htmlspecialchars($_POST['region']) : ''; ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address" class="nunito-sans-B">Адрес (город, улица, дом, квартира):</label>
                    <textarea id="address" name="address" class="form-control" rows="2"><?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password" class="nunito-sans-B">Пароль:<span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password" class="form-control" required>
                                <i class="bi bi-eye-slash-fill password-toggle-icon" id="togglePassword"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password_confirm" class="nunito-sans-B">Подтвердите пароль:<span class="text-danger">*</span></label>
                            <div class="password-wrapper">
                                <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                                <i class="bi bi-eye-slash-fill password-toggle-icon" id="togglePasswordConfirm"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-auth nunito-sans-B mt-3">Зарегистрироваться</button>
            </form>
            <a href="login.php" class="auth-link nunito-sans-B">Уже есть аккаунт? Войти</a>
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
        setupPasswordToggle('togglePasswordConfirm', 'password_confirm');

        document.addEventListener('DOMContentLoaded', function() {
    const passportInput = document.getElementById('passport_number');

    if (passportInput) {
        passportInput.addEventListener('input', function(e) {
            const input = e.target;
            let value = input.value;
            let originalCursorPos = input.selectionStart; // Сохраняем позицию курсора

            // 1. Удаляем все нецифровые символы, чтобы получить только цифры
            let digits = value.replace(/\D/g, '');

            // 2. Ограничиваем количество цифр до 10 (4 для серии + 6 для номера)
            if (digits.length > 10) {
                digits = digits.substring(0, 10);
            }

            // 3. Форматируем значение
            let formattedValue = '';
            if (digits.length > 4) {
                formattedValue = digits.substring(0, 4) + ' ' + digits.substring(4);
            } else {
                formattedValue = digits;
            }

            // 4. Обновляем значение поля, только если оно изменилось,
            // чтобы избежать бесконечного цикла и проблем с курсором.
            if (input.value !== formattedValue) {
                input.value = formattedValue;

                // 5. Пытаемся восстановить позицию курсора
                // Это упрощенная логика. Идеальное управление курсором при маскировании сложно.
                // Если был добавлен пробел (длина увеличилась), и курсор был после 4-го символа,
                // или если курсор был в конце строки, корректируем его.
                let newCursorPos = originalCursorPos;
                
                // Если пробел был добавлен (formattedValue длиннее value без форматирования, но с теми же цифрами)
                // и курсор был на месте или после места вставки пробела
                if (formattedValue.length > value.length && // Длина увеличилась
                    originalCursorPos > 4 &&                // Курсор был после 4-го символа
                    value.charAt(4) !== ' ' &&              // В старом значении не было пробела на 5-й позиции
                    formattedValue.charAt(4) === ' ') {     // В новом значении есть пробел на 5-й позиции
                     newCursorPos = originalCursorPos + (formattedValue.length - value.length);
                } else if (value.length === 4 && formattedValue.length === 5 && originalCursorPos === 4 && e.inputType !== 'deleteContentBackward') {
                    // Частный случай: ввод 4-й цифры, после чего добавляется пробел
                    // (input.value будет "XXXX", formattedValue будет "XXXX ")
                    // На самом деле, input.value уже будет содержать 4 цифры,
                    // а e.target.value до изменения - 3 цифры, если это был ввод 4-й.
                    // Этот случай покрывается общей логикой, если пользователь вводит 5-ю цифру.
                    // Если пользователь ввел 4-ю цифру, курсор будет после нее.
                    // Если пользователь ввел 5-ю цифру (которая стала 1-й после пробела),
                    // то originalCursorPos был 4 (перед вводом 5-й цифры).
                    // value (до форматирования) было "XXXXD", formattedValue стало "XXXX D"
                     if(e.data){ // если был ввод символа
                        newCursorPos = originalCursorPos + 1 + (formattedValue.length - (digits.length)); // +1 за введенный символ, +1 за пробел (если появился)
                     } else {
                        newCursorPos = formattedValue.length;
                     }
                }


                // Предотвращение выхода курсора за пределы
                if (newCursorPos > formattedValue.length) {
                    newCursorPos = formattedValue.length;
                }
                
                // Если значение стало "XXXX " и курсор был на 4-й позиции
                if (originalCursorPos === 4 && formattedValue === digits.substring(0, 4) + ' ' && e.inputType && e.inputType.startsWith('insert')) {
                    newCursorPos = 5; // Ставим курсор после пробела
                }


                input.setSelectionRange(newCursorPos, newCursorPos);
            }
        });

        // Дополнительно: можно запретить ввод нецифровых символов изначально (кроме Backspace, Delete, стрелок)
        // Но 'input' событие уже обрабатывает это, удаляя \D.
        // Этот обработчик может быть полезен для предотвращения ввода лишних пробелов.
        passportInput.addEventListener('keydown', function(e) {
            const key = e.key;
            const value = e.target.value;
            const selectionStart = e.target.selectionStart;

            // Разрешаем управляющие клавиши
            if (['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'Tab', 'Home', 'End'].includes(key)) {
                return;
            }

            // Если это пробел
            if (key === ' ') {
                // Разрешаем пробел только если он вводится на 5-й позиции (индекс 4)
                // и там еще нет пробела
                if (value.length !== 4 || selectionStart !== 4) {
                    e.preventDefault();
                }
                return;
            }

            // Если не цифра - запрещаем
            if (!/^\d$/.test(key)) {
                e.preventDefault();
            }
        });
    }
});
    </script>
</body>
</html>