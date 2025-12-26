<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Каталог - Ароматный Мир</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
    <style>
    .filter-section {
        background: var(--card);
        padding: 40px;
        border-radius: var(--radius-lg);
        margin: 0 auto 60px;
        max-width: 1200px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border);
    }
    
    .filter-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .filter-header h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 36px;
        margin-bottom: 15px;
        color: var(--text);
    }
    
    .filter-header p {
        color: var(--text-light);
        font-size: 16px;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .filter-tabs {
        display: flex;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .filter-tab {
        padding: 18px 32px;
        background: var(--surface);
        border: 2px solid var(--border);
        border-radius: 50px;
        font-size: 16px;
        font-weight: 600;
        color: var(--text-light);
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .filter-tab::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        z-index: -1;
        opacity: 0;
        transition: var(--transition);
    }
    
    .filter-tab:hover {
        color: var(--primary);
        border-color: var(--primary);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(154, 123, 95, 0.1);
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border-color: var(--primary);
        box-shadow: 0 10px 30px rgba(154, 123, 95, 0.2);
    }
    
    .filter-tab.active::before {
        opacity: 1;
    }
    
    .filter-tab i {
        font-size: 18px;
    }
    
    .filter-count {
        background: rgba(255, 255, 255, 0.2);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-left: 8px;
    }
    
    .filter-tab.active .filter-count {
        background: rgba(255, 255, 255, 0.3);
    }
    
    /* Анимация товаров */
    .product-card {
        animation: fadeIn 0.6s ease forwards;
        opacity: 0;
    }
    
    @keyframes fadeIn {
        to {
            opacity: 1;
        }
    }
    
    /* Статистика фильтров */
    .filter-stats {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        color: var(--text-light);
        font-size: 14px;
    }
    
    /* Стили для выбранного фильтра */
    .active-filter-indicator {
        display: inline-block;
        margin-left: 15px;
        padding: 6px 15px;
        background: var(--surface);
        border-radius: 20px;
        font-size: 14px;
        color: var(--primary);
        font-weight: 500;
    }
    
    .active-filter-indicator i {
        margin-right: 8px;
    }
    
    /* Стили для товаров в каталоге */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .product-card {
        background: var(--card);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        position: relative;
        border: 1px solid var(--border);
    }
    
    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }
    
    .product-image {
        width: 100%;
        height: 220px;
        overflow: hidden;
        position: relative;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.1);
    }
    
    .product-info {
        padding: 25px;
    }
    
    .product-category {
        display: inline-block;
        padding: 6px 15px;
        background: var(--surface);
        color: var(--primary);
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }
    
    .product-info h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px;
        margin-bottom: 12px;
        color: var(--text);
    }
    
    .product-description {
        color: var(--text-light);
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
        min-height: 60px;
    }
    
    .product-price {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 20px;
        font-family: 'Inter', sans-serif;
    }
    
    .product-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-small {
        padding: 10px 20px;
        font-size: 13px;
        border-radius: var(--radius-sm);
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }
    
    .empty-products {
        text-align: center;
        padding: 60px 20px;
        background: var(--surface);
        border-radius: var(--radius);
        margin: 40px 0;
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
                <li><a href="shop.php" class="active">Каталог</a></li>
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

    <main class="products-section">
        <div class="section-header animate-fadeInUp">
            <h2>Каталог товаров</h2>
            <p>Выберите свой идеальный вкус из нашей коллекции</p>
        </div>
        
        <!-- Эстетичная фильтрация -->
        <div class="filter-section animate-fadeInUp">
            <div class="filter-header">
                <h2>Наши коллекции</h2>
                <p>Исследуйте лучшие сорта кофе и чая со всего мира</p>
            </div>
            
            <div class="filter-tabs">
                <div class="filter-tab active" data-filter="all" onclick="filterProducts('all')">
                    <i class="fas fa-star"></i> Все товары
                    <?php 
                    $all_count = $pdo->query("SELECT COUNT(*) FROM products WHERE stock > 0")->fetchColumn();
                    ?>
                    <span class="filter-count"><?php echo $all_count; ?></span>
                </div>
                
                <div class="filter-tab" data-filter="coffee" onclick="filterProducts('coffee')">
                    <i class="fas fa-coffee"></i> Кофе
                    <?php 
                    $coffee_count = $pdo->query("SELECT COUNT(*) FROM products WHERE type = 'coffee' AND stock > 0")->fetchColumn();
                    ?>
                    <span class="filter-count"><?php echo $coffee_count; ?></span>
                </div>
                
                <div class="filter-tab" data-filter="tea" onclick="filterProducts('tea')">
                    <i class="fas fa-mug-hot"></i> Чай
                    <?php 
                    $tea_count = $pdo->query("SELECT COUNT(*) FROM products WHERE type = 'tea' AND stock > 0")->fetchColumn();
                    ?>
                    <span class="filter-count"><?php echo $tea_count; ?></span>
                </div>
            </div>
            
            <div class="filter-stats">
                <span id="filter-stats-text">Показаны все <?php echo $all_count; ?> товаров</span>
                <span id="active-filter-indicator" class="active-filter-indicator" style="display: none;">
                    <i class="fas fa-filter"></i> <span id="active-filter-name"></span>
                </span>
            </div>
        </div>
        
        <div class="products-grid" id="products-container">
            <?php
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            
            if ($type) {
                $stmt = $pdo->prepare("SELECT * FROM products WHERE type = ? AND stock > 0 ORDER BY price");
                $stmt->execute([$type]);
            } else {
                $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY type, price");
            }
            $product_images = [
                1 => 'https://www.printingnewyork.com/wp-content/uploads/coffee-packaging-1.jpg', // Арабика Бразилия
                2 => 'https://i0.wp.com/packagingoftheworld.com/wp-content/uploads/2025/04/01.png?fit=1366%2C768&ssl=1', // Робуста Вьетнам
                3 => 'https://marktwendell.com/cdn/shop/products/japanese-sencha-green_8-ounce-tin_949x600_90.jpg?v=1697291156&width=1500', // Зеленый чай
                4 => 'https://m.media-amazon.com/images/I/61y33No5AzL._AC_UF894,1000_QL80_.jpg', // Черный чай
            ];
            
            
            $displayed_count = 0;
            while ($product = $stmt->fetch()):
                $displayed_count++;
                $image_url = $product['image_url'];
                if (empty($image_url) || !filter_var($image_url, FILTER_VALIDATE_URL)) {
                    if (isset($product_images[$product['id']])) {
                        $image_url = $product_images[$product['id']];
                    } else {
                        if ($product['type'] == 'coffee') {
                            $image_index = ($product['id'] - 1) % count($coffee_fallback);
                            $image_url = $coffee_fallback[$image_index];
                        } else {
                            $image_index = ($product['id'] - 1) % count($tea_fallback);
                            $image_url = $tea_fallback[$image_index];
                        }
                    }
                }
            ?>
            <div class="product-card animate-fadeInUp" data-type="<?php echo $product['type']; ?>" data-delay="<?php echo min($displayed_count * 0.1, 1); ?>" style="animation-delay: <?php echo min($displayed_count * 0.1, 1); ?>s;">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                </div>
                <div class="product-info">
                    <span class="product-category"><?php echo $product['type'] == 'coffee' ? 'Кофе' : 'Чай'; ?></span>
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="product-description"><?php echo htmlspecialchars($product['short_desc']); ?></p>
                    <div class="product-meta" style="display: flex; gap: 15px; margin-bottom: 15px; font-size: 14px; color: var(--text-light);">
                        <span><i class="fas fa-globe"></i> <?php echo htmlspecialchars($product['origin']); ?></span>
                        <span><i class="fas fa-box"></i> <?php echo $product['stock']; ?> шт.</span>
                    </div>
                    <div class="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?> ₽</div>
                    <div class="product-actions">
                        <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-small">
                            <i class="fas fa-eye"></i> Подробнее
                        </a>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button class="btn btn-primary btn-small" onclick="addToCart(<?php echo $product['id']; ?>)">
                                <i class="fas fa-shopping-cart"></i> В корзину
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        
        <?php if ($displayed_count == 0): ?>
        <div class="empty-products">
            <i class="fas fa-search" style="font-size: 60px; color: var(--primary-light); margin-bottom: 20px; opacity: 0.5;"></i>
            <h3 style="color: var(--text-light); margin-bottom: 15px;">Товары не найдены</h3>
            <p style="color: var(--text-light); margin-bottom: 30px;">Попробуйте выбрать другую категорию</p>
            <button class="btn btn-primary" onclick="filterProducts('all')">
                <i class="fas fa-undo"></i> Показать все товары
            </button>
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
            <p>© 2024 Ароматный Мир. Все права защищены.</p>
        </div>
    </footer>
    
    <script>
    // Функция для фильтрации товаров с анимацией
    function filterProducts(type) {
        // Обновляем активную вкладку
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.closest('.filter-tab').classList.add('active');
        
        // Показываем индикатор активного фильтра
        const indicator = document.getElementById('active-filter-indicator');
        const filterName = document.getElementById('active-filter-name');
        const statsText = document.getElementById('filter-stats-text');
        
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        // Анимация скрытия/показа товаров
        products.forEach(product => {
            const productType = product.dataset.type;
            const delay = product.dataset.delay || 0;
            
            if (type === 'all' || productType === type) {
                product.style.animation = `fadeIn 0.6s ${delay}s ease forwards`;
                setTimeout(() => {
                    product.style.display = 'block';
                }, delay * 1000);
                visibleCount++;
            } else {
                product.style.animation = `fadeOut 0.4s ease forwards`;
                setTimeout(() => {
                    product.style.display = 'none';
                }, 400);
            }
        });
        
        // Обновляем статистику
        setTimeout(() => {
            if (type === 'all') {
                indicator.style.display = 'none';
                statsText.textContent = `Показаны все ${visibleCount} товаров`;
            } else {
                const typeName = type === 'coffee' ? 'Кофе' : 'Чай';
                filterName.textContent = typeName;
                indicator.style.display = 'inline-block';
                statsText.textContent = `Найдено ${visibleCount} товаров`;
            }
        }, 500);
        
        // Обновляем URL без перезагрузки страницы
        history.pushState(null, null, type === 'all' ? 'shop.php' : `shop.php?type=${type}`);
    }
    
    // Инициализация при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const typeParam = urlParams.get('type');
        
        if (typeParam) {
            const filterTab = document.querySelector(`[data-filter="${typeParam}"]`);
            if (filterTab) {
                filterTab.click();
            }
        }
        
        // Добавляем анимацию задержки для каждого товара
        const productCards = document.querySelectorAll('.product-card');
        productCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });
    
    // Добавляем анимацию исчезновения
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
    `;
    document.head.appendChild(style);
    </script>
</body>
</html>