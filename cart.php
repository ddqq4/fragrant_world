<?php 
session_start(); 
include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$product_images = [
    1 => 'https://www.printingnewyork.com/wp-content/uploads/coffee-packaging-1.jpg',
    2 => 'https://i0.wp.com/packagingoftheworld.com/wp-content/uploads/2025/04/01.png?fit=1366%2C768&ssl=1',
    3 => 'https://marktwendell.com/cdn/shop/products/japanese-sencha-green_8-ounce-tin_949x600_90.jpg?v=1697291156&width=1500',
    4 => 'https://m.media-amazon.com/images/I/61y33No5AzL._AC_UF894,1000_QL80_.jpg',
];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['cart_id'])) {
    $cart_id = (int)$_POST['cart_id'];
    $action = $_POST['action'];
    
    if ($action == 'increase') {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
    } elseif ($action == 'decrease') {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = GREATEST(quantity - 1, 1) WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
    } elseif ($action == 'remove') {
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
        $stmt->execute([$cart_id, $user_id]);
    }
    
    header("Location: cart.php");
    exit();
}
$stmt = $pdo->prepare("SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.type FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();
$subtotal = 0;
$items_count = 0;

foreach ($cart_items as $item) {
    $subtotal += $item['price'] * $item['quantity'];
    $items_count += $item['quantity'];
}

$delivery = ($subtotal >= 3000) ? 0 : 300;
$total = $subtotal + $delivery;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина - Ароматный Мир</title>
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
                <a href="cart.php" class="cart-link active">
                    <i class="fas fa-shopping-bag"></i> Корзина
                    <?php if (!empty($cart_items)): ?>
                        <span id="cart-count" class="cart-count"><?php echo count($cart_items); ?></span>
                    <?php endif; ?>
                </a>
                <a href="feedback.php">Обратная связь</a>
                <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i></a>
            <?php else: ?>
                <a href="login.php">Вход</a>
                <a href="register.php" class="cta-button">Регистрация</a>
            <?php endif; ?>
        </div>
    </header>

    <main class="cart-section">
        <div class="cart-container">
            <div class="cart-header">
                <h1>Ваша корзина</h1>
                <p>Товаров: <?php echo $items_count; ?> шт.</p>
            </div>
            
            <?php if (empty($cart_items)): ?>
                <div class="empty-cart" style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-shopping-cart" style="font-size: 60px; color: #ccc; margin-bottom: 20px;"></i>
                    <h2 style="color: var(--text-light); margin-bottom: 15px;">Корзина пуста</h2>
                    <p style="color: var(--text-light); margin-bottom: 30px;">Добавьте товары из каталога</p>
                    <a href="shop.php" class="btn btn-primary">В каталог</a>
                </div>
            <?php else: ?>
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): 
                        $image_url = isset($product_images[$item['product_id']]) ? $product_images[$item['product_id']] : '';
                        $item_total = $item['price'] * $item['quantity'];
                    ?>
                    <div class="cart-item" style="display: flex; align-items: center; padding: 20px; background: var(--surface); border-radius: var(--radius); margin-bottom: 15px; border: 1px solid var(--border);">
                        <div class="cart-item-image" style="width: 100px; height: 100px; border-radius: var(--radius-sm); overflow: hidden; margin-right: 20px; flex-shrink: 0;">
                            <?php if ($image_url): ?>
                                <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; background: #f8f5f0; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-image" style="font-size: 24px; color: #ccc;"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="cart-item-info" style="flex: 1;">
                            <h3 style="margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h3>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div class="cart-item-price" style="font-weight: 600; color: var(--primary); font-size: 18px;">
                                        <?php echo number_format($item['price'], 0, ',', ' '); ?> ₽
                                    </div>
                                    <div style="font-size: 14px; color: var(--text-light); margin-top: 5px;">
                                        ID: <?php echo $item['product_id']; ?>
                                    </div>
                                </div>
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <div class="cart-item-quantity" style="display: flex; align-items: center; gap: 10px;">
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <input type="hidden" name="action" value="decrease">
                                            <button type="submit" class="quantity-btn decrease" style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid var(--border); background: white; cursor: pointer;">-</button>
                                        </form>
                                        <span class="quantity-value" style="font-weight: 600; min-width: 30px; text-align: center;"><?php echo $item['quantity']; ?></span>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                            <input type="hidden" name="action" value="increase">
                                            <button type="submit" class="quantity-btn increase" style="width: 30px; height: 30px; border-radius: 50%; border: 1px solid var(--border); background: white; cursor: pointer;">+</button>
                                        </form>
                                    </div>
                                    <div class="cart-item-total" style="font-weight: 700; color: var(--text); min-width: 100px; text-align: right;">
                                        <?php echo number_format($item_total, 0, ',', ' '); ?> ₽
                                    </div>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                                        <input type="hidden" name="action" value="remove">
                                        <button type="submit" class="cart-item-remove" style="color: #ff6b6b; background: none; border: none; cursor: pointer; font-size: 18px; padding: 8px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary" style="background: var(--surface); padding: 30px; border-radius: var(--radius); margin-top: 30px; border: 1px solid var(--border);">
                    <div class="summary-row" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>Сумма товаров:</span>
                        <span style="font-weight: 600;"><?php echo number_format($subtotal, 0, ',', ' '); ?> ₽</span>
                    </div>
                    <div class="summary-row" style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border);">
                        <span>Доставка:</span>
                        <span style="font-weight: 600;">
                            <?php echo number_format($delivery, 0, ',', ' '); ?> ₽
                            <?php if ($delivery == 0): ?>
                                <span style="color: #27ae60; font-size: 14px;">(бесплатно от 3000 ₽)</span>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="summary-row total" style="display: flex; justify-content: space-between; padding: 15px 0; font-size: 20px; font-weight: 700;">
                        <span>Итого к оплате:</span>
                        <span class="cart-total" style="color: var(--primary);"><?php echo number_format($total, 0, ',', ' '); ?> ₽</span>
                    </div>
                    
                    <?php if ($subtotal < 3000): ?>
                        <div class="free-delivery-info" style="background: rgba(168, 200, 176, 0.1); padding: 15px; border-radius: var(--radius-sm); margin-top: 20px; display: flex; align-items: center; gap: 10px; color: var(--text);">
                            <i class="fas fa-truck" style="color: var(--primary);"></i>
                            <span>Добавьте товаров на <strong><?php echo number_format(3000 - $subtotal, 0, ',', ' '); ?> ₽</strong> для бесплатной доставки!</span>
                        </div>
                    <?php endif; ?>
                    
                    <a href="checkout.php" class="btn btn-primary" style="width: 100%; margin-top: 30px; padding: 18px; font-size: 16px;">
                        <i class="fas fa-credit-card"></i> Перейти к оформлению
                    </a>
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
            <p>© 2025 Ароматный Мир. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>