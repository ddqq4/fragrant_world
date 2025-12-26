<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Доставка и оплата - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <a href="index.php" class="logo">
            <div class="logo-img">AM</div>
            <div class="logo-text">Ароматный Мир</div>
        </a>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="shop.php">Каталог</a></li>
                <li><a href="delivery.php" class="active">Доставка</a></li>
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </nav>
        <div class="auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-bag"></i> Корзина
                </a>
                <a href="feedback.php">Обратная связь</a>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
                <a href="login.php">Вход</a>
                <a href="register.php" class="cta-button">Регистрация</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="delivery-section">
        <div class="section-header animate-fadeInUp">
            <h1>Доставка и оплата</h1>
            <p>Удобные способы получения заказа</p>
        </div>

        <div class="delivery-content">
            <div class="delivery-grid">
                <div class="delivery-card">
                    <div class="delivery-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Курьерская доставка</h3>
                    <p>Доставка по Москве в течение 2-3 часов</p>
                    <ul>
                        <li>Стоимость: <strong>300 ₽</strong></li>
                        <li>Время: 10:00 - 22:00</li>
                        <li>Бесплатно при заказе от 3000 ₽</li>
                    </ul>
                </div>

                <div class="delivery-card">
                    <div class="delivery-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Доставка по России</h3>
                    <p>Доставка в любые регионы России</p>
                    <ul>
                        <li>Срок: 3-7 рабочих дней</li>
                        <li>Стоимость: от 500 ₽</li>
                        <li>Транспортные компании: СДЭК, Boxberry</li>
                    </ul>
                </div>

                <div class="delivery-card">
                    <div class="delivery-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3>Самовывоз</h3>
                    <p>Заберите заказ самостоятельно</p>
                    <ul>
                        <li>Адрес: Москва, ул. Кофейная, 1</li>
                        <li>Время: 9:00 - 21:00</li>
                        <li>Бесплатно</li>
                    </ul>
                </div>

                <div class="delivery-card">
                    <div class="delivery-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Способы оплаты</h3>
                    <p>Выберите удобный способ оплаты</p>
                    <ul>
                        <li>Наличные при получении</li>
                        <li>Банковской картой онлайн</li>
                        <li>СБП (Система быстрых платежей)</li>
                    </ul>
                </div>
            </div>

            <div class="delivery-faq">
                <h2>Часто задаваемые вопросы</h2>
                
                <div class="faq-item">
                    <div class="faq-question">
                        <span>Как отследить заказ?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>После отправки заказа мы вышлем вам трек-номер для отслеживания на email и SMS.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Можно ли изменить адрес доставки?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Да, вы можете изменить адрес доставки до момента передачи заказа курьеру.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <span>Есть ли скидки на доставку?</span>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>Да, бесплатная доставка при заказе от 3000 ₽ в пределах Москвы.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <div class="footer-col">
                <h3>Ароматный Мир</h3>
                <p>Мы делаем каждый день особенным с нашим кофе и чаем.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-vk"></i></a>
                    <a href="#"><i class="fab fa-telegram"></i></a>
                </div>
            </div>
            <div class="footer-col">
                <h3>Каталог</h3>
                <ul>
                    <li><a href="shop.php?type=coffee">Кофе</a></li>
                    <li><a href="shop.php?type=tea">Чай</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Информация</h3>
                <ul>
                    <li><a href="delivery.php">Доставка и оплата</a></li>
                    <li><a href="contacts.php">Контакты</a></li>
                    <li><a href="feedback.php">Обратная связь</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h3>Контакты</h3>
                <ul>
                    <li><i class="fas fa-phone"></i> +7 (999) 123-45-67</li>
                    <li><i class="fas fa-envelope"></i> hello@aromamir.ru</li>
                    <li><i class="fas fa-map-marker-alt"></i> Москва, ул. Кофейная, 1</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Ароматный Мир. Все права защищены.</p>
        </div>
    </footer>

    <style>
    .delivery-section {
        padding: 160px 5% 100px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .delivery-content {
        margin-top: 60px;
    }
    
    .delivery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 80px;
    }
    
    .delivery-card {
        background: white;
        padding: 40px 30px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
    }
    
    .delivery-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 30px 60px rgba(139, 115, 85, 0.15);
    }
    
    .delivery-icon {
        width: 80px;
        height: 80px;
        background: var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
    }
    
    .delivery-icon i {
        font-size: 36px;
        color: var(--primary);
    }
    
    .delivery-card h3 {
        font-size: 22px;
        margin-bottom: 15px;
        color: var(--text);
    }
    
    .delivery-card p {
        color: var(--text-light);
        margin-bottom: 25px;
        line-height: 1.6;
    }
    
    .delivery-card ul {
        list-style: none;
        text-align: left;
    }
    
    .delivery-card ul li {
        margin-bottom: 12px;
        padding-left: 25px;
        position: relative;
        color: var(--text);
    }
    
    .delivery-card ul li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--primary);
        font-weight: bold;
    }
    
    .delivery-faq {
        background: white;
        padding: 50px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    
    .delivery-faq h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        margin-bottom: 40px;
        text-align: center;
        color: var(--text);
    }
    
    .faq-item {
        border-bottom: 1px solid rgba(139, 115, 85, 0.1);
        margin-bottom: 20px;
    }
    
    .faq-question {
        padding: 25px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-size: 18px;
        font-weight: 600;
        color: var(--text);
        transition: var(--transition);
    }
    
    .faq-question:hover {
        color: var(--primary);
    }
    
    .faq-question i {
        transition: transform 0.3s ease;
        color: var(--primary-light);
    }
    
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    
    .faq-answer.active {
        max-height: 200px;
        padding-bottom: 25px;
    }
    
    .faq-answer p {
        color: var(--text-light);
        line-height: 1.7;
    }
    
    @media (max-width: 768px) {
        .delivery-section {
            padding: 140px 5% 60px;
        }
        
        .delivery-card,
        .delivery-faq {
            padding: 30px 20px;
        }
    }
    </style>

    <script>
    // FAQ аккордеон
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            const icon = question.querySelector('i');
            
            // Закрываем другие открытые вопросы
            document.querySelectorAll('.faq-answer').forEach(otherAnswer => {
                if (otherAnswer !== answer && otherAnswer.classList.contains('active')) {
                    otherAnswer.classList.remove('active');
                    otherAnswer.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
                }
            });
            
            answer.classList.toggle('active');
            icon.style.transform = answer.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
        });
    });
    </script>
</body>
</html>
