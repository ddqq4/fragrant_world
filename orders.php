<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT o.*, 
                       (SELECT COUNT(*) FROM order_items oi WHERE oi.order_id = o.id) as items_count
                       FROM orders o 
                       WHERE o.user_id = ? 
                       ORDER BY o.created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мои заказы - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
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
            <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="orders.php" class="active"><i class="fas fa-shopping-bag"></i> Мои заказы</a>
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>

    <main class="orders-section">
        <div class="section-header animate-fadeInUp">
            <h1>Мои заказы</h1>
            <p>История ваших покупок</p>
        </div>
        
        <?php if (empty($orders)): ?>
            <div class="empty-orders">
                <i class="fas fa-shopping-bag"></i>
                <h2>У вас еще нет заказов</h2>
                <p>Совершите свою первую покупку!</p>
                <a href="shop.php" class="btn btn-primary">В каталог</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-number">
                            <h3>Заказ <?php echo $order['order_number']; ?></h3>
                            <span class="order-date">
                                <?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?>
                            </span>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php 
                                $statuses = [
                                    'pending' => 'В обработке',
                                    'processing' => 'Готовится',
                                    'shipped' => 'Отправлен',
                                    'delivered' => 'Доставлен',
                                    'cancelled' => 'Отменен'
                                ];
                                echo $statuses[$order['status']] ?? $order['status'];
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="order-info">
                        <div class="info-row">
                            <span><i class="fas fa-map-marker-alt"></i> Адрес:</span>
                            <span><?php echo htmlspecialchars($order['address']); ?></span>
                        </div>
                        <div class="info-row">
                            <span><i class="fas fa-phone"></i> Телефон:</span>
                            <span><?php echo htmlspecialchars($order['phone']); ?></span>
                        </div>
                        <div class="info-row">
                            <span><i class="fas fa-credit-card"></i> Оплата:</span>
                            <span><?php 
                                $payment_methods = [
                                    'cash' => 'Наличные',
                                    'card' => 'Карта онлайн',
                                    'sbp' => 'СБП'
                                ];
                                echo $payment_methods[$order['payment_method']] ?? $order['payment_method'];
                            ?></span>
                        </div>
                        <div class="info-row">
                            <span><i class="fas fa-box"></i> Товаров:</span>
                            <span><?php echo $order['items_count']; ?> шт.</span>
                        </div>
                    </div>
                    
                    <div class="order-footer">
                        <div class="order-total">
                            <span>Итого:</span>
                            <span class="total-amount"><?php echo number_format($order['total_amount'], 0, ',', ' '); ?> ₽</span>
                        </div>
                        <a href="order_details.php?id=<?php echo $order['id']; ?>" class="btn btn-secondary btn-small">
                            <i class="fas fa-eye"></i> Подробнее
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
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
</body>
</html>