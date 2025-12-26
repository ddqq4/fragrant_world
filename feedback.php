<?php 
session_start(); 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$success = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message_text = trim($_POST['message']);
    $user_id = $_SESSION['user_id'];
    
    if (!empty($message_text)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO feedback (user_id, message) VALUES (?, ?)");
            $stmt->execute([$user_id, $message_text]);
            $success = true;
            $message = "Сообщение успешно отправлено!";
        } catch (PDOException $e) {
            $message = "Ошибка: " . $e->getMessage();
        }
    } else {
        $message = "Пожалуйста, введите сообщение";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Обратная связь - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.php" style="display: flex; align-items: center; gap: 12px; text-decoration: none; color: inherit;">
                <div class="logo-img">AM</div>
                <div class="logo-text">Ароматный Мир</div>
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="shop.php">Каталог</a></li>
                <li><a href="delivery.php">Доставка</a></li>
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </nav>
        <div class="auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-bag"></i> Корзина
                </a>
                <a href="feedback.php" class="active">Обратная связь</a>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
                <a href="login.php">Вход</a>
                <a href="register.php" class="cta-button">Регистрация</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="form-section">
        <div class="form-container animate-fadeInUp">
            <h1>Обратная связь</h1>
            <p class="form-subtitle">Напишите нам, и мы обязательно ответим</p>
            
            <?php if ($message): ?>
                <div class="<?php echo $success ? 'success-message' : 'error-message'; ?>" style="margin-bottom: 30px;">
                    <i class="fas <?php echo $success ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (!$success): ?>
            <form method="POST" id="feedbackForm">
                <div class="form-group">
                    <label for="message">Ваше сообщение *</label>
                    <textarea id="message" name="message" required 
                              placeholder="Напишите ваше сообщение, вопрос или предложение..."
                              rows="6"><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                </div>
                
                <div class="form-group">
                    <label>Отправитель</label>
                    <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" 
                           readonly class="readonly-input">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i> Отправить сообщение
                </button>
            </form>
            <?php else: ?>
                <div class="success-actions">
                    <a href="index.php" class="btn btn-primary">На главную</a>
                    <a href="feedback.php" class="btn btn-secondary">Отправить еще</a>
                </div>
            <?php endif; ?>
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
            <p>© 2024 Ароматный Мир. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>