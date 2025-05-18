// js/script.js
document.addEventListener('DOMContentLoaded', function () {
	const carouselInner = document.querySelector('.carousel-inner')
	const prevButton = document.querySelector('.prev-button')
	const nextButton = document.querySelector('.next-button')
	const reviewCards = document.querySelectorAll('.review-card')

	let cardWidth = reviewCards[0].offsetWidth // Ширина карточки
	let scrollPosition = 0
	let cardIndex = 0 // Индекс текущей карточки

	// Функция для прокрутки карусели
	function scrollCarousel(direction) {
		if (direction === 'next') {
			cardIndex = Math.min(cardIndex + 1, reviewCards.length - 1) // Увеличиваем индекс, но не больше последнего
		} else {
			cardIndex = Math.max(cardIndex - 1, 0) // Уменьшаем индекс, но не меньше 0
		}
		scrollPosition = cardIndex * cardWidth
		carouselInner.scrollTo({
			left: scrollPosition,
			behavior: 'smooth', // Плавная прокрутка
		})
	}

	// Обработчики событий для кнопок
	nextButton.addEventListener('click', () => {
		scrollCarousel('next')
	})

	prevButton.addEventListener('click', () => {
		scrollCarousel('prev')
	})

	// Адаптивность (опционально, если карточки должны менять размер при изменении размера экрана)
	window.addEventListener('resize', function () {
		cardWidth = reviewCards[0].offsetWidth // Обновляем ширину карточки при изменении размера окна
		scrollPosition = cardIndex * cardWidth // Пересчитываем позицию прокрутки
		carouselInner.scrollTo({ left: scrollPosition }) // Обновляем прокрутку на новую позицию
	})
})

// JavaScript/javaScript.js
document.addEventListener('DOMContentLoaded', function () {
	// --- Существующий код для карусели ---
	const carouselInner = document.querySelector('.carousel-inner')
	const prevButton = document.querySelector('.prev-button')
	const nextButton = document.querySelector('.next-button')
	const reviewCardsNodeList = document.querySelectorAll('.review-card') // Изменено имя переменной

	if (
		carouselInner &&
		prevButton &&
		nextButton &&
		reviewCardsNodeList.length > 0
	) {
		const reviewCards = Array.from(reviewCardsNodeList) // Преобразуем NodeList в Array для работы
		let cardWidth = reviewCards[0].offsetWidth
		let scrollPosition = 0
		let cardIndex = 0

		function scrollCarousel(direction) {
			if (direction === 'next') {
				cardIndex = Math.min(cardIndex + 1, reviewCards.length - 1)
			} else {
				cardIndex = Math.max(cardIndex - 1, 0)
			}
			scrollPosition = cardIndex * cardWidth
			carouselInner.scrollTo({
				left: scrollPosition,
				behavior: 'smooth',
			})
		}

		nextButton.addEventListener('click', () => {
			scrollCarousel('next')
		})

		prevButton.addEventListener('click', () => {
			scrollCarousel('prev')
		})

		window.addEventListener('resize', function () {
			if (reviewCards.length > 0) {
				// Проверка, что reviewCards не пуст
				cardWidth = reviewCards[0].offsetWidth
				scrollPosition = cardIndex * cardWidth
				carouselInner.scrollTo({ left: scrollPosition })
			}
		})
	}
	// --- Конец кода для карусели ---

	// --- Новый код для переключения видимости пароля ---
	function addPasswordToggleListeners() {
		const togglePasswordIcons = document.querySelectorAll('.toggle-password')
		togglePasswordIcons.forEach(icon => {
			icon.addEventListener('click', function () {
				const targetInput = document.querySelector(this.getAttribute('toggle'))
				if (targetInput) {
					if (targetInput.type === 'password') {
						targetInput.type = 'text'
						this.classList.remove('bi-eye-fill')
						this.classList.add('bi-eye-slash-fill')
					} else {
						targetInput.type = 'password'
						this.classList.remove('bi-eye-slash-fill')
						this.classList.add('bi-eye-fill')
					}
				}
			})
		})
	}
	addPasswordToggleListeners() // Вызываем функцию для активации "глазиков"
	// --- Конец кода для переключения видимости пароля ---

	// --- Новый код для автоматического скрытия уведомления о регистрации на index.php ---
	const registrationAlert = document.getElementById('registrationSuccessAlert')
	if (registrationAlert) {
		setTimeout(() => {
			const bsAlert = bootstrap.Alert.getInstance(registrationAlert)
			if (bsAlert) {
				bsAlert.close()
			} else {
				// Fallback, если Bootstrap JS не инициализировал alert
				registrationAlert.style.display = 'none'
			}
		}, 5000) // Скрыть через 5 секунд
	}
	// --- Конец кода для скрытия уведомления ---
})
