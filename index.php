<?php session_start(); include 'db.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ароматный Мир - Премиум кофе и чай</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
    <script src="script.js" defer></script>
    <style>
    .promo-section {
        padding: 80px 5%;
        background: linear-gradient(135deg, #F8F5F0 0%, #FFFDF9 100%);
        position: relative;
        overflow: hidden;
    }

    .promo-slider-container {
        max-width: 1400px;
        margin: 0 auto;
        position: relative;
    }

    .promo-slider {
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-hover);
        height: 400px;
    }

    .promo-slide {
        position: relative;
        height: 100%;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        padding: 0 60px;
    }

    .promo-slide::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.4) 50%, transparent 100%);
    }

    .promo-content {
        position: relative;
        z-index: 2;
        max-width: 500px;
        color: white;
    }

    .promo-badge {
        display: inline-block;
        padding: 8px 20px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
    }

    .promo-title {
        font-family: 'Cormorant Garamond', serif;
        font-size: 32px;
        line-height: 1.2;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .promo-description {
        font-size: 16px;
        line-height: 1.5;
        margin-bottom: 25px;
        opacity: 0.9;
        text-shadow: 0 1px 5px rgba(0,0,0,0.3);
    }

    .promo-price {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .old-price {
        font-size: 20px;
        color: rgba(255,255,255,0.6);
        text-decoration: line-through;
    }

    .new-price {
        font-size: 28px;
        font-weight: 700;
        color: #FFD166;
        text-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }

    .promo-button {
        padding: 15px 35px;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .promo-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: var(--transition);
        border: none;
        color: var(--primary);
        font-size: 20px;
    }

    .slider-nav:hover {
        background: white;
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 5px 20px rgba(0,0,0,0.2);
    }

    .slider-nav.prev {
        left: 15px;
    }

    .slider-nav.next {
        right: 15px;
    }

    .swiper-pagination {
        bottom: 15px !important;
    }

    .swiper-pagination-bullet {
        width: 10px !important;
        height: 10px !important;
        background: rgba(255,255,255,0.5) !important;
        opacity: 1 !important;
    }

    .swiper-pagination-bullet-active {
        background: white !important;
        transform: scale(1.2);
    }

    .benefits-section {
        padding: 100px 5%;
        background: var(--background);
    }
    
    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .benefit-card {
        background: var(--card);
        padding: 40px 25px;
        border-radius: var(--radius-lg);
        text-align: center;
        transition: var(--transition);
        border: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }
    
    .benefit-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        transition: transform 0.3s ease;
    }
    
    .benefit-card:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-hover);
    }
    
    .benefit-icon {
        width: 70px;
        height: 70px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, var(--primary-light), var(--primary));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        transition: var(--transition);
    }
    
    .benefit-card:hover .benefit-icon {
        transform: rotateY(180deg) scale(1.1);
    }
    
    .benefit-card h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 24px;
        margin-bottom: 15px;
        color: var(--text);
    }
    
    .benefit-card p {
        color: var(--text-light);
        font-size: 15px;
        line-height: 1.6;
    }
    
    /* Стили для товаров */
    .products-section {
        padding: 100px 5%;
        background: var(--surface);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 40px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .product-card {
        background: var(--card);
        border-radius: var(--radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border: 1px solid var(--border);
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
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
    
    .product-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .product-image-overlay {
        opacity: 1;
    }
    
    .overlay-content .btn {
        transform: translateY(20px);
        transition: transform 0.3s ease;
    }
    
    .product-card:hover .overlay-content .btn {
        transform: translateY(0);
    }
    
    .product-info {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
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
    }
    
    .product-origin {
        font-size: 12px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .product-origin i {
        font-size: 10px;
    }
    
    .product-info h3 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 22px;
        margin-bottom: 12px;
        color: var(--text);
        line-height: 1.3;
    }
    
    .product-description {
        color: var(--text-light);
        margin-bottom: 20px;
        font-size: 14px;
        line-height: 1.5;
        flex: 1;
    }
    
    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--border);
    }
    
    .product-price {
        font-size: 24px;
        font-weight: 700;
        color: var(--primary);
        font-family: 'Inter', sans-serif;
    }
    
    .product-stock {
        font-size: 13px;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .product-stock i {
        color: #27ae60;
    }
    
    .product-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
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
    
    /* Адаптивность */
    @media (max-width: 992px) {
        .promo-slider {
            height: 350px;
        }
        
        .promo-slide {
            padding: 0 40px;
        }
        
        .promo-title {
            font-size: 28px;
        }
        
        .promo-description {
            font-size: 15px;
        }
        
        .new-price {
            font-size: 24px;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .promo-slider {
            height: 300px;
        }
        
        .promo-slide {
            padding: 0 25px;
        }
        
        .promo-title {
            font-size: 24px;
        }
        
        .promo-content {
            max-width: 100%;
        }
        
        .promo-badge {
            font-size: 12px;
            padding: 6px 16px;
        }
        
        .slider-nav {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .promo-button {
            padding: 12px 25px;
            font-size: 14px;
        }
        
        .products-grid {
            grid-template-columns: 1fr;
        }
        
        .product-image {
            height: 180px;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-info h3 {
            font-size: 20px;
        }
        
        .product-price {
            font-size: 20px;
        }
        
        .product-actions {
            flex-direction: column;
            gap: 8px;
        }
    }

    @media (max-width: 480px) {
        .promo-slider {
            height: 250px;
        }
        
        .promo-slide {
            padding: 0 20px;
        }
        
        .promo-title {
            font-size: 20px;
            margin-bottom: 10px;
        }
        
        .promo-description {
            font-size: 14px;
            margin-bottom: 20px;
        }
        
        .promo-price {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .old-price, .new-price {
            font-size: 18px;
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
                <li><a href="index.php" class="active">Главная</a></li>
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

    <main>
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="hero-content animate-fadeInUp">
                <h1>Откройте мир премиального кофе и чая</h1>
                <p>Эксклюзивные сорта из лучших регионов мира. Каждая чашка — это уникальное путешествие вкуса и аромата.</p>
                <div class="hero-buttons">
                    <a href="shop.php" class="btn btn-primary">В каталог</a>
                    <a href="#promo" class="btn btn-secondary">Спецпредложения</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Ароматный кофе">
            </div>
        </section>

        <!-- Слайдер с акциями -->
        <section class="promo-section" id="promo">
            <div class="section-header animate-fadeInUp">
                <h2>Специальные предложения</h2>
                <p>Выгодные акции для наших клиентов</p>
            </div>
            
            <div class="promo-slider-container animate-fadeInUp">
                <div class="swiper promo-slider">
                    <div class="swiper-wrapper">
                        <!-- Слайд 1: Широкий выбор -->
                        <div class="swiper-slide promo-slide" style="background-image: url('https://images.unsplash.com/photo-1544787219-7f47ccb76574?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');">
                            <div class="promo-content">
                                <div class="promo-badge" style="background: linear-gradient(135deg, #9b59b6, #8e44ad);">Популярное</div>
                                <h2 class="promo-title">Широкий выбор кофе и чая</h2>
                                <p class="promo-description">Более 30 сортов премиум кофе и элитного чая. Гарантия качества и свежести продукции.</p>
                                
                                <div style="display: flex; gap: 15px; margin-bottom: 25px;">
                                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: var(--radius-sm);">
                                        <i class="fas fa-coffee" style="color: var(--primary); font-size: 18px; display: block; margin-bottom: 8px;"></i>
                                        <span style="font-size: 13px;">Премиум кофе</span>
                                    </div>
                                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: var(--radius-sm);">
                                        <i class="fas fa-mug-hot" style="color: var(--secondary); font-size: 18px; display: block; margin-bottom: 8px;"></i>
                                        <span style="font-size: 13px;">Элитный чай</span>
                                    </div>
                                </div>
                                
                                <div class="promo-price">
                                    <span class="new-price">Лучшие цены</span>
                                    <span style="color: white; font-weight: 600; font-size: 18px;">Высшее качество</span>
                                </div>
                                
                                <a href="shop.php" class="promo-button">
                                    <i class="fas fa-shopping-bag"></i> Смотреть каталог
                                </a>
                            </div>
                        </div>
                        
                        <!-- Слайд 2: Бесплатная доставка (ИЗМЕНЕНА КАРТИНКА!) -->
                        <div class="swiper-slide promo-slide" style="background-image: url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80'); background-position: center;">
                            <div class="promo-content">
                                <div class="promo-badge" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">Бесплатно</div>
                                <h2 class="promo-title">Бесплатная доставка от 3000 ₽</h2>
                                <p class="promo-description">Все заказы от 3000 ₽ доставляются бесплатно по всей России. Быстрая доставка 1-3 дня.</p>
                                
                                <div class="promo-price">
                                    <span class="old-price">до 500 ₽</span>
                                    <span class="new-price">0 ₽</span>
                                    <span style="color: white; font-weight: 600; font-size: 18px;">Экономия до 500 ₽</span>
                                </div>
                                
                                <a href="delivery.php" class="promo-button">
                                    <i class="fas fa-truck"></i> Условия доставки
                                </a>
                            </div>
                        </div>
                        
                        <!-- Слайд 3: Отзывы -->
                        <div class="swiper-slide promo-slide" style="background-image: url('https://images.unsplash.com/photo-1515823064-d6e0c04616a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');">
                            <div class="promo-content">
                                <div class="promo-badge" style="background: linear-gradient(135deg, var(--primary), var(--accent));">Бонус</div>
                                <h2 class="promo-title">Скидка за отзыв</h2>
                                <p class="promo-description">Оставьте отзыв о нашем магазине и получите персональную скидку на следующий заказ!</p>
                                
                                <div style="display: flex; gap: 15px; margin-bottom: 25px;">
                                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: var(--radius-sm);">
                                        <i class="fas fa-star" style="color: #FFD166; font-size: 18px; display: block; margin-bottom: 8px;"></i>
                                        <span style="font-size: 13px;">Оставить отзыв</span>
                                    </div>
                                    <div style="background: rgba(255,255,255,0.2); padding: 12px; border-radius: var(--radius-sm);">
                                        <i class="fas fa-percent" style="color: #e74c3c; font-size: 18px; display: block; margin-bottom: 8px;"></i>
                                        <span style="font-size: 13px;">Получить скидку</span>
                                    </div>
                                </div>
                                
                                <div class="promo-price">
                                    <span class="new-price">−5%</span>
                                    <span style="color: #FFD166; font-weight: 600; font-size: 18px;">на следующий заказ</span>
                                </div>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="feedback.php" class="promo-button">
                                    <i class="fas fa-comment-alt"></i> Оставить отзыв
                                </a>
                                <?php else: ?>
                                <a href="login.php" class="promo-button">
                                    <i class="fas fa-sign-in-alt"></i> Войти для отзыва
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Навигация -->
                    <button class="slider-nav prev">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="slider-nav next">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    
                    <!-- Пагинация -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </section>

        <section class="products-section" id="products">
            <div class="section-header animate-fadeInUp">
                <h2>Популярные товары</h2>
                <p>Лучшие предложения этой недели</p>
            </div>
            
            <div class="products-grid">
                <?php
                // Получаем товары из базы данных
                $stmt = $pdo->query("SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT 6");
                
                $product_images = [
                    1 => 'https://www.printingnewyork.com/wp-content/uploads/coffee-packaging-1.jpg', // Арабика Бразилия
                    2 => 'https://i0.wp.com/packagingoftheworld.com/wp-content/uploads/2025/04/01.png?fit=1366%2C768&ssl=1', // Робуста Вьетнам
                    3 => 'https://marktwendell.com/cdn/shop/products/japanese-sencha-green_8-ounce-tin_949x600_90.jpg?v=1697291156&width=1500', // Зеленый чай
                    4 => 'https://m.media-amazon.com/images/I/61y33No5AzL._AC_UF894,1000_QL80_.jpg', // Черный чай
                ];
                
                
                while ($product = $stmt->fetch()):
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
                <div class="product-card animate-fadeInUp">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($image_url); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        <div class="product-image-overlay">
                            <div class="overlay-content">
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-small">
                                    <i class="fas fa-eye"></i> Быстрый просмотр
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-header">
                            <span class="product-category"><?php echo $product['type'] == 'coffee' ? 'Кофе' : 'Чай'; ?></span>
                            <span class="product-origin">
                                <i class="fas fa-globe"></i> <?php echo htmlspecialchars($product['origin']); ?>
                            </span>
                        </div>
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?php echo htmlspecialchars($product['short_desc']); ?></p>
                        <div class="product-footer">
                            <div class="product-price"><?php echo number_format($product['price'], 0, ',', ' '); ?> ₽</div>
                            <div class="product-stock">
                                <i class="fas fa-box"></i> <?php echo $product['stock']; ?> шт.
                            </div>
                        </div>
                        <div class="product-actions">
                            <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-secondary btn-small">
                                <i class="fas fa-info-circle"></i> Подробнее
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
            
            <div style="text-align: center; margin-top: 60px;">
                <a href="shop.php" class="btn btn-primary">
                    <i class="fas fa-store"></i> Смотреть весь каталог
                </a>
            </div>
        </section>

        <section class="benefits-section">
            <div class="section-header animate-fadeInUp">
                <h2>Почему выбирают нас</h2>
                <p>Гарантируем лучшее качество и сервис</p>
            </div>
            
            <div class="benefits-grid">
                <div class="benefit-card animate-fadeInUp">
                    <div class="benefit-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <h3>Натуральное сырьё</h3>
                    <p>Только органические ингредиенты без добавок и искусственных ароматизаторов.</p>
                </div>
                
                <div class="benefit-card animate-fadeInUp">
                    <div class="benefit-icon">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <h3>Быстрая доставка</h3>
                    <p>Доставим заказ за 1-2 дня по всей России. Бесплатная доставка от 3000 ₽.</p>
                </div>
                
                <div class="benefit-card animate-fadeInUp">
                    <div class="benefit-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3>Высший сорт</h3>
                    <p>Гарантия качества от производителей. Сертифицированная продукция.</p>
                </div>
            </div>
        </section>
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
    document.addEventListener('DOMContentLoaded', function() {
        const promoSlider = new Swiper('.promo-slider', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            speed: 800,
            effect: 'fade',
            fadeEffect: {
                crossFade: true
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.slider-nav.next',
                prevEl: '.slider-nav.prev',
            },
        });
        
        const sliderContainer = document.querySelector('.promo-slider-container');
        if (sliderContainer) {
            sliderContainer.addEventListener('mouseenter', () => {
                promoSlider.autoplay.stop();
            });
            
            sliderContainer.addEventListener('mouseleave', () => {
                promoSlider.autoplay.start();
            });
        }
    });
    </script>
</body>
</html>
