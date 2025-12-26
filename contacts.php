<?php 
session_start(); 
include 'db.php'; // Добавляем подключение к БД

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_text = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_text)) {
        $error = "Пожалуйста, заполните все обязательные поля";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Введите корректный email адрес";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contacts_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $message_text]);
            $success = true;
            
            $_POST = array();
            
        } catch (PDOException $e) {
            $error = "Ошибка при сохранении сообщения: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Контакты - Ароматный Мир</title>
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
                <li><a href="delivery.php">Доставка</a></li>
                <li><a href="contacts.php" class="active">Контакты</a></li>
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

    <main class="contacts-section">
        <div class="section-header animate-fadeInUp">
            <h1>Контакты</h1>
            <p>Свяжитесь с нами удобным для вас способом</p>
        </div>

        <div class="contacts-content">
            <div class="contacts-grid">
                <div class="contacts-info">
                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>Адрес</h3>
                        <p>Москва, ул. Кофейная, 1</p>
                        <p>Бизнес-центр "Аромат", 3 этаж</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h3>Телефон</h3>
                        <p>+7 (999) 123-45-67</p>
                        <p>Ежедневно с 9:00 до 21:00</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h3>Email</h3>
                        <p>hello@aromamir.ru</p>
                        <p>Заказы: orders@aromamir.ru</p>
                    </div>

                    <div class="contact-card">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3>Время работы</h3>
                        <p>Пн-Пт: 9:00 - 21:00</p>
                        <p>Сб-Вс: 10:00 - 20:00</p>
                    </div>
                </div>

                <div class="contacts-map">
                    <div class="map-placeholder">
                        <div class="map-content">
                            <h3>Как добраться</h3>
                            <p>Станция метро "Кофейная", выход №2</p>
                            <p>От метро 5 минут пешком</p>
                            <div class="transport">
                                <span><i class="fas fa-subway"></i> Метро: Кофейная</span>
                                <span><i class="fas fa-bus"></i> Автобусы: 123, 456</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contacts-form-section">
                <h2>Напишите нам</h2>
                
                <?php if ($success): ?>
                    <div class="success-message" style="background: #e8f5e9; border: 1px solid #27ae60; border-radius: 12px; padding: 30px; text-align: center; margin-bottom: 30px;">
                        <i class="fas fa-check-circle" style="font-size: 40px; color: #27ae60; margin-bottom: 15px; display: block;"></i>
                        <h3 style="color: #27ae60; margin-bottom: 10px;">Сообщение успешно отправлено!</h3>
                        <p style="margin-bottom: 20px;">Спасибо за ваше сообщение! Мы ответим вам в течение 24 часов.</p>
                        <div class="success-actions" style="display: flex; gap: 15px; justify-content: center;">
                            <a href="contacts.php" class="btn btn-secondary">
                                <i class="fas fa-envelope"></i> Отправить еще
                            </a>
                            <a href="index.php" class="btn btn-primary">
                                <i class="fas fa-home"></i> На главную
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="error-message" style="background: #ffebee; border: 1px solid #e74c3c; border-radius: 12px; padding: 20px; color: #e74c3c; margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form class="contacts-form" method="POST">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Ваше имя *</label>
                                <input type="text" name="name" required 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                                       placeholder="Иван Иванов">
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                       placeholder="example@mail.ru">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Тема сообщения *</label>
                            <input type="text" name="subject" required 
                                   value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"
                                   placeholder="Вопрос о заказе">
                        </div>
                        
                        <div class="form-group">
                            <label>Сообщение *</label>
                            <textarea name="message" required 
                                      placeholder="Ваше сообщение..." 
                                      rows="5"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Отправить сообщение
                        </button>
                    </form>
                <?php endif; ?>
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
    .contacts-section {
        padding: 160px 5% 100px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .contacts-content {
        margin-top: 60px;
    }
    
    .contacts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 80px;
    }
    
    .contacts-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }
    
    .contact-card {
        background: white;
        padding: 30px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        text-align: center;
        transition: var(--transition);
    }
    
    .contact-card:hover {
        transform: translateY(-5px);
    }
    
    .contact-icon {
        width: 70px;
        height: 70px;
        background: var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    
    .contact-icon i {
        font-size: 30px;
        color: var(--primary);
    }
    
    .contact-card h3 {
        font-size: 20px;
        margin-bottom: 15px;
        color: var(--text);
    }
    
    .contact-card p {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 8px;
    }
    
    .contacts-map {
        background: var(--surface);
        border-radius: var(--radius);
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }
    
    .map-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
    }
    
    .map-content {
        text-align: center;
    }
    
    .map-content h3 {
        font-size: 24px;
        margin-bottom: 20px;
        color: var(--text);
    }
    
    .map-content p {
        color: var(--text-light);
        margin-bottom: 15px;
    }
    
    .transport {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-top: 30px;
    }
    
    .transport span {
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
        color: var(--text);
    }
    
    .transport i {
        color: var(--primary);
        font-size: 20px;
    }
    
    .contacts-form-section {
        background: white;
        padding: 50px;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    
    .contacts-form-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        margin-bottom: 40px;
        text-align: center;
        color: var(--text);
    }
    
    .contacts-form .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .contacts-form .form-group {
        margin-bottom: 25px;
    }
    
    .contacts-form label {
        display: block;
        margin-bottom: 10px;
        font-weight: 500;
        color: var(--text);
    }
    
    .contacts-form input,
    .contacts-form textarea {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid rgba(139, 115, 85, 0.1);
        border-radius: 12px;
        font-size: 16px;
        font-family: 'Inter', sans-serif;
        transition: var(--transition);
        background: var(--surface);
    }
    
    .contacts-form input:focus,
    .contacts-form textarea:focus {
        outline: none;
        border-color: var(--primary);
        background: white;
    }
    
    .contacts-form textarea {
        min-height: 120px;
        resize: vertical;
    }
    
    @media (max-width: 1024px) {
        .contacts-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .contacts-info {
            grid-template-columns: 1fr 1fr;
        }
    }
    
    @media (max-width: 768px) {
        .contacts-section {
            padding: 140px 5% 60px;
        }
        
        .contacts-info {
            grid-template-columns: 1fr;
        }
        
        .contacts-form .form-row {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .contact-card,
        .contacts-form-section {
            padding: 30px 20px;
        }
    }
    </style>
</body>
</html>
