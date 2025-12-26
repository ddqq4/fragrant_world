<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

$stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$user_email = $user['email'] ?? '';
$stmt = $pdo->prepare("SELECT c.id, c.product_id, p.name, p.price, p.image_url, c.quantity 
                       FROM cart c 
                       JOIN products p ON c.product_id = p.id 
                       WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items) && $step < 3) {
    header("Location: cart.php");
    exit();
}
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery = ($subtotal >= 3000) ? 0 : 300;
$total = $subtotal + $delivery;

if ($step == 2 && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $comment = trim($_POST['comment'] ?? '');
    $payment = $_POST['payment'];

    $errors = [];
    
    if (empty($name)) $errors[] = "Укажите ФИО";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Укажите корректный email";
    if (empty($phone) || strlen($phone) < 5) $errors[] = "Укажите корректный телефон";
    if (empty($address)) $errors[] = "Укажите адрес доставки";
    
    if (empty($errors)) {
        $_SESSION['order_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'address' => $address,
            'comment' => $comment,
            'payment' => $payment
        ];
        header("Location: checkout.php?step=3");
        exit();
    } else {
        $error_message = implode("<br>", $errors);
    }
}

if ($step == 3 && isset($_SESSION['order_data'])) {
    $order_data = $_SESSION['order_data'];
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, email, phone, address, comment, payment_method, subtotal, delivery_cost, total_amount, status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->execute([
            $user_id, 
            $order_data['name'],
            $order_data['email'],
            $order_data['phone'],
            $order_data['address'],
            $order_data['comment'],
            $order_data['payment'],
            $subtotal,
            $delivery,
            $total
        ]);
        $order_id = $pdo->lastInsertId();
        
        // Добавляем товары
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) 
                               VALUES (?, ?, ?, ?, ?)");
        foreach ($cart_items as $item) {
            $stmt->execute([$order_id, $item['product_id'], $item['name'], $item['price'], $item['quantity']]);
        }
        
        // Очищаем корзину
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        $pdo->commit();
        
        $order_success = true;
        $order_number = 'O' . str_pad($order_id, 6, '0', STR_PAD_LEFT);
        
        // Очищаем данные заказа из сессии
        unset($_SESSION['order_data']);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Ошибка при оформлении заказа: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оформление заказа - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 50px;
        gap: 40px;
    }
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .step-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 20px;
        transition: all 0.3s;
    }
    .step.active .step-circle {
        background: var(--primary);
        color: white;
    }
    .step.inactive .step-circle {
        background: var(--surface);
        color: var(--text-light);
    }
    .step.completed .step-circle {
        background: #27ae60;
        color: white;
    }
    .step-text {
        font-size: 14px;
        color: var(--text-light);
    }
    .step.active .step-text {
        color: var(--primary);
        font-weight: 500;
    }
    .step-line {
        width: 80px;
        height: 2px;
        background: var(--surface);
        margin-top: 25px;
    }
    .checkout-content {
        max-width: 800px;
        margin: 0 auto;
    }
    .order-summary-box {
        background: var(--surface);
        padding: 30px;
        border-radius: var(--radius);
        margin-bottom: 30px;
    }
    .validation-error {
        color: #e74c3c;
        font-size: 14px;
        margin-top: 5px;
        display: block;
    }
    </style>
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
            <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </header>

    <main class="checkout-section">
        <div class="checkout-container">
            <h1>Оформление заказа</h1>
            
            <!-- Шаги оформления -->
            <div class="checkout-steps">
                <div class="step <?php echo $step >= 1 ? 'active' : 'inactive'; ?>">
                    <div class="step-circle">1</div>
                    <div class="step-text">Корзина</div>
                </div>
                <div class="step-line"></div>
                <div class="step <?php echo $step == 2 ? 'active' : ($step > 2 ? 'completed' : 'inactive'); ?>">
                    <div class="step-circle"><?php echo $step > 2 ? '✓' : '2'; ?></div>
                    <div class="step-text">Данные</div>
                </div>
                <div class="step-line"></div>
                <div class="step <?php echo $step == 3 ? 'active' : 'inactive'; ?>">
                    <div class="step-circle">3</div>
                    <div class="step-text">Подтверждение</div>
                </div>
            </div>
            
            <div class="checkout-content">
                <?php if (isset($error)): ?>
                    <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="error-message"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <?php if ($step == 1): ?>
                    <!-- Шаг 1: Просмотр корзины -->
                    <div class="order-summary-box">
                        <h2>Ваш заказ</h2>
                        <?php foreach ($cart_items as $item): ?>
                        <div class="order-item" style="display: flex; justify-content: space-between; padding: 15px 0; border-bottom: 1px solid rgba(139, 115, 85, 0.1);">
                            <div>
                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                <div style="color: var(--text-light); font-size: 14px;">
                                    <?php echo $item['quantity']; ?> × <?php echo number_format($item['price'], 0, ',', ' '); ?> ₽
                                </div>
                            </div>
                            <div>
                                <?php echo number_format($item['price'] * $item['quantity'], 0, ',', ' '); ?> ₽
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid var(--primary);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Товары:</span>
                                <span><?php echo number_format($subtotal, 0, ',', ' '); ?> ₽</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span>Доставка:</span>
                                <span>
                                    <?php echo number_format($delivery, 0, ',', ' '); ?> ₽
                                    <?php if ($delivery == 0): ?>
                                        <span style="color: #27ae60; font-size: 14px;">(бесплатно)</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 20px; font-weight: bold; color: var(--primary);">
                                <span>Итого:</span>
                                <span><?php echo number_format($total, 0, ',', ' '); ?> ₽</span>
                            </div>
                        </div>
                    </div>
                    
                    <div style="text-align: center;">
                        <a href="checkout.php?step=2" class="btn btn-primary btn-lg">
                            <i class="fas fa-arrow-right"></i> Перейти к оформлению
                        </a>
                        <a href="cart.php" class="btn btn-secondary" style="margin-left: 20px;">
                            <i class="fas fa-arrow-left"></i> Вернуться в корзину
                        </a>
                    </div>
                    
                <?php elseif ($step == 2): ?>
                    <!-- Шаг 2: Данные доставки -->
                    <form method="POST" class="checkout-form">
                        <h2>Контактные данные</h2>
                        
                        <div class="form-group">
                            <label>ФИО *</label>
                            <input type="text" name="name" required 
                                   value="<?php echo htmlspecialchars($_SESSION['username']); ?>"
                                   placeholder="Иванов Иван Иванович">
                        </div>
                        
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required 
                                   value="<?php echo htmlspecialchars($user_email); ?>"
                                   placeholder="example@mail.ru">
                            <small class="validation-error">На этот email придет подтверждение заказа</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Телефон *</label>
                            <input type="tel" name="phone" required 
                                   placeholder="+7 (999) 123-45-67">
                        </div>
                        
                        <div class="form-group">
                            <label>Адрес доставки *</label>
                            <textarea name="address" required rows="3"
                                      placeholder="Город, улица, дом, квартира, индекс"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Комментарий к заказу</label>
                            <textarea name="comment" rows="3"
                                      placeholder="Особые пожелания, время доставки"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Способ оплаты *</label>
                            <select name="payment" required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid rgba(139, 115, 85, 0.2);">
                                <option value="cash">Наличные при получении</option>
                                <option value="card">Карта онлайн</option>
                                <option value="sbp">СБП (Система быстрых платежей)</option>
                            </select>
                        </div>
                        
                        <div style="display: flex; gap: 20px; margin-top: 40px;">
                            <a href="checkout.php?step=1" class="btn btn-secondary" style="flex: 1;">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                            <button type="submit" class="btn btn-primary" style="flex: 2;">
                                Продолжить <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                    
                <?php elseif ($step == 3): ?>
                    <!-- Шаг 3: Подтверждение -->
                    <?php if (isset($order_success) && $order_success): ?>
                        <div class="success-message" style="text-align: center; padding: 50px;">
                            <i class="fas fa-check-circle" style="font-size: 80px; color: #27ae60; margin-bottom: 30px;"></i>
                            <h2 style="color: #27ae60; margin-bottom: 20px;">Заказ успешно оформлен!</h2>
                            <p style="font-size: 20px; margin-bottom: 30px;">
                                Номер вашего заказа: <strong><?php echo $order_number; ?></strong>
                            </p>
                            <p style="margin-bottom: 20px;">
                                На email <strong><?php echo htmlspecialchars($order_data['email'] ?? ''); ?></strong> 
                                отправлено подтверждение заказа.
                            </p>
                            <p style="margin-bottom: 40px;">
                                Мы свяжемся с вами для подтверждения заказа в течение 30 минут.
                            </p>
                            <div style="display: flex; gap: 20px; justify-content: center;">
                                <a href="index.php" class="btn btn-primary">
                                    <i class="fas fa-home"></i> На главную
                                </a>
                                <a href="shop.php" class="btn btn-secondary">
                                    <i class="fas fa-shopping-bag"></i> Продолжить покупки
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="text-align: center;">
                            <p>Обработка заказа...</p>
                        </div>
                    <?php endif; ?>
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
</body>
</html>
