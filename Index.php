<?php require_once 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление Страховым Полисом и Претензиями</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Piazzolla:ital,opsz,wght@0,8..30,100..900;1,8..30,100..900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="Style/Style.css">
</head>

<body>


<?php
// Показываем сообщение об успешной авторизации/регистрации
if (isset($_SESSION['show_auth_success_message'])) {
    echo '<div class="auth-success-alert" id="authSuccessAlert">';
    echo htmlspecialchars($_SESSION['show_auth_success_message']);
    // Добавляем кнопку закрытия (опционально)
    // echo '<button type="button" class="btn-close-alert" onclick="document.getElementById(\'authSuccessAlert\').style.display=\'none\';">&times;</button>';
    echo '</div>';
    unset($_SESSION['show_auth_success_message']); // Удаляем сообщение, чтобы не показывать снова

    // Скрипт для автоматического скрытия сообщения через несколько секунд
    echo '<script>
            setTimeout(function() {
                var alert = document.getElementById("authSuccessAlert");
                if (alert) {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(function() { alert.style.display = "none"; }, 500);
                }
            }, 5000); // Сообщение исчезнет через 5 секунд
          </script>';
}
?>


    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand piazzolla" href="#">MyInsure</a> <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link nunito-sans-B" href="#">Мои Полисы</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nunito-sans-B" href="#">Подать Претензию</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nunito-sans-B" href="#">Проверка Статуса</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nunito-sans-B" href="#">Типы Страхования</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nunito-sans-B" href="#">FAQ</a>
                        </li>
                    </ul>
                    <button class="btn t-btn" type="button">
                        <img src="img/phone.svg" alt="" class="button-icon">
                        <span class="button-text">ПОДДЕРЖКА</span>
                    </button>
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
    <div class="полоса"></div>

    <section class="content-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 left-content">
                    <h1 class="content-title piazzolla">Ваши Полисы <br>Под Контролем</h1>
                    <p class="content-text nunito-sans-B">
                        Легко управляйте своими страховыми полисами и подавайте претензии онлайн. <br>Мы упрощаем
                        процесс, когда вам это больше всего нужно. </p>
                    <button class="btn learn-more-btn nunito-sans-B">НАЧАТЬ УПРАВЛЕНИЕ</button>

                </div>
                <div class="col-md-6 right-content">
                    <img src="img/74c029ad-1469-4253-b906-e8d9fc2fa502 1.jpg" alt="Управление полисом онлайн"
                        class="content-image">
                </div>
            </div>
        </div>
    </section>




    <section class="reviews-section">
        <div class="wave-bg"></div>
        <div class="container reviews-container">
            <div class="top-rated">
                <h2 class="top-rated-title piazzolla">Нам Доверяют</h2>
                <div class="stars stars-bottom">
                    <i class="bi bi-star-fill star-icon"></i>
                    <i class="bi bi-star-fill star-icon"></i>
                    <i class="bi bi-star-fill star-icon"></i>
                    <i class="bi bi-star-fill star-icon"></i>
                    <i class="bi bi-star-fill star-icon"></i>
                </div>
            </div>

            <div class="reviews-carousel">
                <button class="carousel-button prev-button">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <button class="carousel-button next-button">
                    <i class="bi bi-chevron-right"></i>
                </button>

                <div class="carousel-inner">
                    <div class="review-card">
                        <p class="review-text nunito-sans-B">"Подача претензии через сайт была невероятно быстрой! Все
                            интуитивно понятно, и ответ получил в тот же день."</p>
                        <p class="reviewer-name piazzolla">Анна К.</p>
                        <div class="review-stars stars">
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                        </div>
                    </div>
                    <div class="review-card">
                        <p class="review-text nunito-sans-B">"Управлять всеми моими полисами в одном месте очень удобно.
                            Интерфейс простой и понятный, легко найти нужную информацию."</p>
                        <p class="reviewer-name piazzolla">Иван Д.</p>
                        <div class="review-stars stars">
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                        </div>
                    </div>
                    <div class="review-card">
                        <p class="review-text nunito-sans-B">"Возможность отслеживать статус моей претензии онлайн
                            сэкономила мне много времени и нервов. Отличная функция!"</p>
                        <p class="reviewer-name piazzolla">Елена С.</p>
                        <div class="review-stars stars">
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                        </div>
                    </div>
                    <div class="review-card">
                        <p class="review-text nunito-sans-B">"Приложение для управления полисом оказалось очень удобным.
                            Все мои документы всегда под рукой."</p>
                        <p class="reviewer-name piazzolla">Петр В.</p>
                        <div class="review-stars stars">
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                            <i class="bi bi-star-fill star-icon-small"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-bg wave-bg-bottom"></div>
    </section>




    <section class="how-it-works-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <h2 class="how-it-works-title piazzolla">Как Это Работает</h2>
                    <p class="how-it-works-subtitle nunito-sans-B">Управление полисом и подача претензий в несколько
                        шагов</p>
                </div>
            </div>
            <div class="row justify-content-center align-items-center how-it-works-content">
                <div class="col-md-8 text-center position-relative">
                    <img src="img/brain chemistry-pana 1.png" alt="Процесс управления полисом"
                        class="how-it-works-image img-fluid">
                    <div class="text-around text-around-top-right nunito-sans-B">
                        <center> Регистрация <br> и Добавление Полиса </center>
                    </div>
                    <div class="text-around text-around-left nunito-sans-B">
                        <center>Управление<br> и Просмотр Деталей</center>
                    </div>
                    <div class="text-around text-around-bottom-right nunito-sans-B">
                        <center>Подача Претензии <br> и Отслеживание</center>
                    </div>
                </div>
            </div>
        </div>
    </section>









    <section class="faq-section reviews-section">
        <div class="wave-bg"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <h2 class="faq-title piazzolla text-white">Часто Задаваемые Вопросы</h2>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button nunito-sans-B" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Как мне добавить свой страховой полис на платформу? </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body nunito-sans-B">
                                    Чтобы добавить полис, войдите в свой личный кабинет, перейдите в раздел "Мои Полисы"
                                    и нажмите кнопку "Добавить Полис". Вам потребуется ввести основные данные полиса,
                                    такие как номер полиса и тип страхования. При необходимости вы сможете загрузить
                                    скан или фото вашего полиса.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed nunito-sans-B" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    Как подать страховую претензию через сайт? </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body nunito-sans-B">
                                    В личном кабинете выберите полис, по которому хотите подать претензию, и нажмите
                                    "Подать Претензию". Следуйте инструкциям на экране, заполните необходимые поля о
                                    происшествии и прикрепите подтверждающие документы (фото, справки и т.д.). Ваша
                                    заявка будет автоматически отправлена на рассмотрение.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed nunito-sans-B" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false"
                                    aria-controls="collapseThree">
                                    Могу ли я отслеживать статус своей претензии онлайн? </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body nunito-sans-B">
                                    Да, конечно. В разделе "Мои Претензии" в вашем личном кабинете вы сможете увидеть
                                    список всех поданных претензий и их текущий статус (например, "На рассмотрении",
                                    "Требуется дополнительная информация", "Одобрено", "Выплачено"). Обновления статуса
                                    происходят в реальном времени.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button collapsed nunito-sans-B" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false"
                                    aria-controls="collapseFour">
                                    Какие типы страхования я могу управлять через вашу платформу? </button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body nunito-sans-B">
                                    Наша платформа поддерживает управление различными типами страховых полисов, включая
                                    страхование автомобилей, недвижимости (квартиры, дома), жизни, здоровья и другие, в
                                    зависимости от страховых продуктов, которые мы поддерживаем. Полный список доступен
                                    в разделе "Типы Страхования".
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button collapsed nunito-sans-B" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false"
                                    aria-controls="collapseFive">
                                    Можно ли внести изменения в мой полис через личный кабинет? </button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive"
                                data-bs-parent="#faqAccordion">
                                <div class="accordion-body nunito-sans-B">
                                    В зависимости от типа полиса и политики страховой компании, вы можете иметь
                                    возможность внести некоторые изменения (например, обновить контактные данные)
                                    непосредственно через личный кабинет. Для более сложных изменений может
                                    потребоваться связаться с нашей службой поддержки или вашим страховым агентом.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="wave-bg wave-bg-bottom"></div>
    </section>




    <section class="still-curious-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <h2 class="still-curious-title piazzolla">Остались Вопросы?</h2>
                    <p class="still-curious-subtitle nunito-sans-B">
                        Надеемся, вы нашли необходимую информацию об управлении полисами и подаче претензий. Если у вас
                        есть дополнительные вопросы, не стесняйтесь обращаться к нам. Мы здесь, чтобы помочь вам
                        получить максимум от вашей страховки. </p>
                    <div class="mt-4">
                        <button class="btn learn-more-btn nunito-sans-B">СВЯЗАТЬСЯ С НАМИ</button>
                    </div>
                </div>
            </div>
        </div>
    </section>




    <footer class="site-footer">
        <div class="container">
            <div class="row align-items-center footer-top">
                <div class="col-md-6">
                    <a class="footer-brand piazzolla" href="#">MyInsure</a>
                </div>
                <div class="col-md-6 text-md-end footer-social-icons">
                    <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row align-items-center footer-bottom">
                <div class="col-md-6 footer-copyright nunito-sans-B">
                    <p>&copy; MyInsure 2025. Все права защищены.</p>
                </div>
                <div class="col-md-6">
                    <ul
                        class="footer-nav list-unstyled d-flex justify-content-center justify-content-md-center nunito-sans-B">
                        <li class="nav-item footer-nav-item-nowrap"><a href="#" class="nav-link p-0">О Нас</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0">Поддержка</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0">Контакты</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0">Политика Конфиденциальности</a></li>
                        <li class="nav-item"><a href="#" class="nav-link p-0">Условия Использования</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JavaScript/javaScript.js"></script>
</body>

</html>