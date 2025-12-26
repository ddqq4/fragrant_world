<?php 
session_start(); 
include 'db.php'; 

if (!isset($_GET['id'])) die("ID не указан");
$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) die("Товар не найден");

$product_images = [
    1 => 'https://www.printingnewyork.com/wp-content/uploads/coffee-packaging-1.jpg', // Арабика Бразилия
    2 => 'https://i0.wp.com/packagingoftheworld.com/wp-content/uploads/2025/04/01.png?fit=1366%2C768&ssl=1', // Робуста Вьетнам
    3 => 'https://marktwendell.com/cdn/shop/products/japanese-sencha-green_8-ounce-tin_949x600_90.jpg?v=1697291156&width=1500', // Зеленый чай
    4 => 'https://m.media-amazon.com/images/I/61y33No5AzL._AC_UF894,1000_QL80_.jpg', // Черный чай
];


$product_image = $product['image_url'];
if (empty($product_image) || !filter_var($product_image, FILTER_VALIDATE_URL)) {
    if (isset($product_images[$id])) {
        $product_image = $product_images[$id];
    } else {
        if ($product['type'] == 'coffee') {
            $image_index = ($id - 1) % count($coffee_fallback);
            $product_image = $coffee_fallback[$image_index];
        } else {
            $image_index = ($id - 1) % count($tea_fallback);
            $product_image = $tea_fallback[$image_index];
        }
    }
}

$similar_stmt = $pdo->prepare("SELECT * FROM products WHERE type = ? AND id != ? AND stock > 0 ORDER BY RAND() LIMIT 4");
$similar_stmt->execute([$product['type'], $id]);
$similar_products = $similar_stmt->fetchAll();

foreach ($similar_products as &$similar) {
    if (empty($similar['image_url']) || !filter_var($similar['image_url'], FILTER_VALIDATE_URL)) {
        if (isset($product_images[$similar['id']])) {
            $similar['image_url'] = $product_images[$similar['id']];
        } else {
            if ($similar['type'] == 'coffee') {
                $image_index = ($similar['id'] - 1) % count($coffee_fallback);
                $similar['image_url'] = $coffee_fallback[$image_index];
            } else {
                $image_index = ($similar['id'] - 1) % count($tea_fallback);
                $similar['image_url'] = $tea_fallback[$image_index];
            }
        }
    }
}

function getIconForCharacteristic($char) {
    $char = strtolower(trim($char));
    $icons = [
        'вкус' => 'fas fa-utensils',
        'крепость' => 'fas fa-bolt',
        'обжарка' => 'fas fa-fire',
        'тип' => 'fas fa-tag',
        'вес' => 'fas fa-weight',
        'аромат' => 'fas fa-wind',
        'кислотность' => 'fas fa-lemon',
        'тело' => 'fas fa-wine-glass-alt',
        'послевкусие' => 'fas fa-clock',
        'сорт' => 'fas fa-seedling',
        'регион' => 'fas fa-globe',
        'высота' => 'fas fa-mountain',
        'способ' => 'fas fa-mortar-pestle',
        'упаковка' => 'fas fa-box',
        'срок' => 'fas fa-calendar-alt'
    ];
    
    foreach ($icons as $key => $icon) {
        if (strpos($char, $key) !== false) {
            return $icon;
        }
    }
    
    return 'fas fa-circle';
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?> - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
    <style>
    .product-detail-section {
        padding: calc(var(--header-height) + 40px) 5% 80px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .product-detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 80px;
    }

    .product-detail-image {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-hover);
        height: 500px;
        position: relative;
        background: var(--surface);
    }

    .product-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        background-color: #f8f5f0;
        padding: 20px;
        transition: transform 0.7s ease;
    }

    .product-detail-image:hover img {
        transform: scale(1.05);
    }

    .product-detail-content {
        display: flex;
        flex-direction: column;
    }

    .product-detail-header {
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid var(--border);
    }

    .product-detail-badge {
        display: inline-block;
        padding: 8px 20px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .product-detail-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 48px;
        line-height: 1.2;
        margin-bottom: 20px;
        color: var(--text);
    }

    .product-detail-origin {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-light);
        font-size: 16px;
        margin-bottom: 15px;
    }

    .product-detail-origin i {
        color: var(--primary);
    }

    .product-detail-price-section {
        background: var(--surface);
        padding: 30px;
        border-radius: var(--radius);
        margin-bottom: 30px;
        border: 1px solid var(--border);
    }

    .product-detail-price {
        font-size: 48px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
        font-family: 'Inter', sans-serif;
    }

    .product-detail-stock {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-light);
        font-size: 16px;
    }

    .product-detail-stock i {
        color: #27ae60;
    }

    .product-detail-stock .in-stock {
        color: #27ae60;
        font-weight: 600;
    }

    .product-detail-tabs {
        margin-bottom: 40px;
    }

    .detail-tabs {
        display: flex;
        gap: 5px;
        margin-bottom: 30px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 5px;
    }

    .detail-tab {
        padding: 15px 30px;
        background: transparent;
        border: none;
        border-radius: var(--radius-sm) var(--radius-sm) 0 0;
        font-size: 16px;
        font-weight: 600;
        color: var(--text-light);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .detail-tab.active {
        color: var(--primary);
    }

    .detail-tab.active::after {
        content: '';
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .detail-tab:hover:not(.active) {
        color: var(--text);
        background: var(--surface);
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .tab-content.active {
        display: block;
    }

    .tab-content h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 28px;
        margin-bottom: 20px;
        color: var(--text);
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
    }

    .tab-content p {
        color: var(--text-light);
        line-height: 1.8;
        margin-bottom: 25px;
        font-size: 16px;
    }

    .characteristics-list {
        list-style: none;
        padding: 0;
    }

    .characteristics-list li {
        display: flex;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid var(--border);
    }

    .characteristics-list li:last-child {
        border-bottom: none;
    }

    .char-icon {
        width: 40px;
        height: 40px;
        background: var(--surface);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: var(--primary);
        font-size: 18px;
        flex-shrink: 0;
    }

    .char-content {
        flex: 1;
    }

    .char-title {
        font-weight: 600;
        color: var(--text);
        margin-bottom: 5px;
    }

    .char-value {
        color: var(--text-light);
        font-size: 15px;
    }

    .product-actions-section {
        background: var(--card);
        padding: 30px;
        border-radius: var(--radius);
        border: 1px solid var(--border);
        margin-top: 40px;
    }

    .action-buttons {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .action-buttons .btn {
        flex: 1;
        padding: 20px;
        font-size: 16px;
        font-weight: 600;
    }

    .product-note {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: linear-gradient(135deg, rgba(168, 200, 176, 0.1), rgba(212, 181, 158, 0.1));
        border-radius: var(--radius-sm);
        border-left: 4px solid var(--secondary);
    }

    .product-note i {
        font-size: 24px;
        color: var(--secondary);
    }

    .product-note p {
        margin: 0;
        color: var(--text);
        font-size: 15px;
    }

    /* Секция похожих товаров */
    .similar-products-section {
        padding-top: 60px;
        border-top: 1px solid var(--border);
    }

    .similar-products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .similar-product-card {
        background: var(--card);
        border-radius: var(--radius);
        overflow: hidden;
        border: 1px solid var(--border);
        transition: var(--transition);
    }

    .similar-product-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow);
    }

    .similar-product-image {
        height: 200px;
        overflow: hidden;
        background-color: #f8f5f0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .similar-product-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 15px;
        transition: transform 0.5s ease;
    }

    .similar-product-card:hover .similar-product-image img {
        transform: scale(1.1);
    }

    .similar-product-info {
        padding: 20px;
    }

    .similar-product-info h4 {
        font-size: 18px;
        margin-bottom: 10px;
        color: var(--text);
    }

    .similar-product-price {
        font-weight: 600;
        color: var(--primary);
        margin-bottom: 15px;
        font-size: 20px;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 992px) {
        .product-detail-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        
        .product-detail-image {
            height: 400px;
        }
        
        .product-detail-title {
            font-size: 36px;
        }
        
        .product-detail-price {
            font-size: 36px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
    }

    @media (max-width: 768px) {
        .detail-tabs {
            flex-direction: column;
            gap: 10px;
        }
        
        .detail-tab {
            text-align: center;
        }
        
        .similar-products-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
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
                <li><a href="contacts.php">Контакты</a></li>
            </ul>
        </nav>
        <div class="auth">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="cart.php" class="cart-link">
                    <i class="fas fa-shopping-bag"></i> Корзина
                    <?php 
                    $cart_count = $pdo->prepare("SELECT COUNT(*) FROM cart WHERE user_id = ?");
                    $cart_count->execute([$_SESSION['user_id']]);
                    $count = $cart_count->fetchColumn();
                    if ($count > 0): ?>
                        <span id="cart-count" class="cart-count"><?php echo $count; ?></span>
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

    <main class="product-detail-section">
        <div class="product-detail-grid">
            <div class="product-detail-image">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            
            <div class="product-detail-content">
                <div class="product-detail-header">
                    <div class="product-detail-badge">
                        <?php echo $product['type'] == 'coffee' ? 'Кофе' : 'Чай'; ?>
                    </div>
                    
                    <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>
                    
                    <div class="product-detail-origin">
                        <i class="fas fa-globe-americas"></i>
                        <span>Происхождение: <?php echo htmlspecialchars($product['origin']); ?></span>
                    </div>
                </div>
                
                <div class="product-detail-price-section">
                    <div class="product-detail-price">
                        <?php echo number_format($product['price'], 0, ',', ' '); ?> ₽
                    </div>
                    
                    <div class="product-detail-stock">
                        <i class="fas fa-check-circle"></i>
                        <span class="in-stock">В наличии: <?php echo $product['stock']; ?> шт.</span>
                    </div>
                </div>
                
                <div class="product-detail-tabs">
                    <div class="detail-tabs">
                        <button class="detail-tab active" onclick="switchTab('description')">Описание</button>
                        <button class="detail-tab" onclick="switchTab('characteristics')">Характеристики</button>
                        <button class="detail-tab" onclick="switchTab('taste')">Вкусовой профиль</button>
                    </div>
                    
                    <div class="tab-content active" id="description-tab">
                        <h3>Подробное описание</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['full_desc'])); ?></p>
                    </div>
                    
                    <div class="tab-content" id="characteristics-tab">
                        <h3>Характеристики</h3>
                        <?php 
                        $characteristics = explode(';', $product['characteristics']);
                        if (!empty($characteristics[0])): ?>
                        <ul class="characteristics-list">
                            <?php foreach ($characteristics as $char):
                                $char = trim($char);
                                if (empty($char)) continue;
                                $parts = explode(':', $char, 2);
                                $icon = getIconForCharacteristic($parts[0] ?? '');
                            ?>
                            <li>
                                <div class="char-icon">
                                    <i class="<?php echo $icon; ?>"></i>
                                </div>
                                <div class="char-content">
                                    <div class="char-title"><?php echo htmlspecialchars(trim($parts[0] ?? '')); ?></div>
                                    <div class="char-value"><?php echo htmlspecialchars(trim($parts[1] ?? $char)); ?></div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php else: ?>
                        <p>Характеристики не указаны</p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="tab-content" id="taste-tab">
                        <h3>Вкусовой профиль</h3>
                        <p><?php echo nl2br(htmlspecialchars($product['short_desc'])); ?></p>
                        
                        <?php if ($product['type'] == 'coffee'): ?>
                        <div class="product-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Этот кофе идеально подходит для приготовления эспрессо и альтернативных способов заваривания. Рекомендуем использовать свежемолотый кофе для максимального раскрытия вкуса.</p>
                        </div>
                        <?php else: ?>
                        <div class="product-note">
                            <i class="fas fa-info-circle"></i>
                            <p>Для идеального заваривания используйте свежекипяченую воду температурой 80-90°C. Первую заварку рекомендуется сливать для раскрытия аромата.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="product-actions-section">
                    <div class="action-buttons">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>, 1)">
                                <i class="fas fa-shopping-cart"></i> Добавить в корзину
                            </button>
                            <a href="shop.php?type=<?php echo $product['type']; ?>" class="btn btn-secondary">
                                <i class="fas fa-list"></i> Смотреть все <?php echo $product['type'] == 'coffee' ? 'кофе' : 'чай'; ?>
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Войти для покупки
                            </a>
                            <a href="register.php" class="btn btn-secondary">
                                <i class="fas fa-user-plus"></i> Зарегистрироваться
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-note">
                        <i class="fas fa-truck"></i>
                        <p>Бесплатная доставка при заказе от 3000 ₽. Доставка по России 1-3 дня.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($similar_products)): ?>
        <div class="similar-products-section">
            <div class="section-header">
                <h2>Похожие товары</h2>
                <p>Вам также может понравиться</p>
            </div>
            
            <div class="similar-products-grid">
                <?php foreach ($similar_products as $similar): ?>
                <div class="similar-product-card">
                    <a href="product.php?id=<?php echo $similar['id']; ?>" style="text-decoration: none; color: inherit;">
                        <div class="similar-product-image">
                            <img src="<?php echo htmlspecialchars($similar['image_url']); ?>" alt="<?php echo htmlspecialchars($similar['name']); ?>">
                        </div>
                        <div class="similar-product-info">
                            <h4><?php echo htmlspecialchars($similar['name']); ?></h4>
                            <div class="similar-product-price">
                                <?php echo number_format($similar['price'], 0, ',', ' '); ?> ₽
                            </div>
                            <div style="display: flex; gap: 10px;">
                                <span class="product-category"><?php echo $similar['type'] == 'coffee' ? 'Кофе' : 'Чай'; ?></span>
                                <span style="color: var(--text-light); font-size: 14px;">
                                    <i class="fas fa-globe"></i> <?php echo htmlspecialchars($similar['origin']); ?>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
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
    
    <script>
    function switchTab(tabName) {
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.remove('active');
        });
        
        document.querySelectorAll('.detail-tab').forEach(button => {
            button.classList.remove('active');
        });
        document.getElementById(tabName + '-tab').classList.add('active');
        event.target.classList.add('active');
    }
    </script>
</body>
</html>
