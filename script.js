document.addEventListener('DOMContentLoaded', function() {
    initScrollHeader();
    initCart();
    initAnimations();
    if (document.querySelector('.promo-slider')) {
        initPromoSlider();
    }
});

function initScrollHeader() {
    const header = document.querySelector('header');
    if (!header) return;
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });
}

function addToCart(productId, quantity = 1) {
    if (!isLoggedIn()) {
        showNotification('Для добавления в корзину необходимо войти в систему', 'warning');
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 1500);
        return;
    }
    
    const btn = event?.target || document.querySelector(`[data-product-id="${productId}"]`);
    btn.classList.add('loading');
    
    fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        btn.classList.remove('loading');
        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        btn.classList.remove('loading');
        showNotification('Ошибка при добавлении товара', 'error');
        console.error('Error:', error);
    });
}

// Удаление из корзины
function removeFromCart(cartId) {
    if (!confirm('Удалить товар из корзины?')) return;
    
    const item = event?.target.closest('.cart-item');
    if (item) item.classList.add('loading');
    
    fetch('remove_from_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}`
    })
    .then(response => response.text())
    .then(data => {
        if (item) {
            item.remove();
            showNotification('Товар удален из корзины', 'success');
            updateCartCount();
            updateCartTotal();
        }
    })
    .catch(error => {
        if (item) item.classList.remove('loading');
        showNotification('Ошибка при удалении товара', 'error');
        console.error('Error:', error);
    });
}

// Обновление количества в корзине
function updateCartQuantity(cartId, change) {
    const quantityElement = document.querySelector(`[data-cart-id="${cartId}"] .quantity-value`);
    if (!quantityElement) return;
    
    let quantity = parseInt(quantityElement.textContent);
    quantity = Math.max(1, quantity + change);
    
    quantityElement.textContent = quantity;
    
    // Отправляем AJAX запрос на обновление
    fetch('update_cart.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `cart_id=${cartId}&quantity=${quantity}`
    })
    .then(response => response.text())
    .then(data => {
        // Обновляем строку товара
        const item = document.querySelector(`[data-cart-id="${cartId}"]`);
        if (item) {
            const price = parseFloat(item.querySelector('.cart-item-price').textContent.replace(/[^\d.]/g, ''));
            const total = price * quantity;
            item.querySelector('.cart-item-total').textContent = formatPrice(total);
        }
        updateCartTotal();
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Ошибка обновления количества', 'error');
    });
}

// Обновление общей суммы корзины с учетом доставки
function updateCartTotal() {
    const items = document.querySelectorAll('.cart-item');
    let subtotal = 0;
    
    items.forEach(item => {
        const price = parseFloat(item.querySelector('.cart-item-price').textContent.replace(/[^\d.]/g, ''));
        const quantity = parseInt(item.querySelector('.quantity-value').textContent);
        subtotal += price * quantity;
    });
    
    // Рассчитываем доставку
    const delivery = subtotal >= 3000 ? 0 : 300;
    const total = subtotal + delivery;
    
    // Обновляем элементы на странице
    const subtotalElement = document.querySelector('.subtotal-amount');
    const deliveryElement = document.querySelector('.delivery-amount');
    const totalElement = document.querySelector('.cart-total');
    
    if (subtotalElement) subtotalElement.textContent = formatPrice(subtotal);
    if (deliveryElement) {
        deliveryElement.textContent = formatPrice(delivery);
        // Показываем информацию о бесплатной доставке
        const deliveryInfo = document.querySelector('.delivery-info');
        if (deliveryInfo) {
            if (delivery === 0) {
                deliveryInfo.classList.add('free');
                deliveryInfo.innerHTML = '<i class="fas fa-check-circle"></i> Бесплатная доставка (от 3000₽)';
            } else {
                deliveryInfo.classList.remove('free');
                const needed = 3000 - subtotal;
                deliveryInfo.innerHTML = `<i class="fas fa-truck"></i> Добавьте товаров на ${formatPrice(needed)} для бесплатной доставки`;
            }
        }
    }
    if (totalElement) totalElement.textContent = formatPrice(total);
}

// Обновление счетчика корзины
function updateCartCount() {
    fetch('get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const cartCount = document.getElementById('cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
                cartCount.style.display = data.count > 0 ? 'inline-flex' : 'none';
            }
        }
    })
    .catch(error => {
        console.error('Error updating cart count:', error);
    });
}

// Проверка авторизации
function isLoggedIn() {
    return document.querySelector('.auth span') !== null;
}

// Показать уведомление
function showNotification(message, type = 'info') {
    // Удаляем старые уведомления
    document.querySelectorAll('.notification').forEach(notification => {
        notification.remove();
    });
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Анимация появления
    setTimeout(() => notification.classList.add('show'), 10);
    
    // Закрытие по кнопке
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    });
    
    // Автоматическое закрытие
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }
    }, 5000);
}

// Форматирование цены
function formatPrice(price) {
    return new Intl.NumberFormat('ru-RU', {
        style: 'currency',
        currency: 'RUB',
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    }).format(price).replace('RUB', '₽');
}

// Инициализация корзины
function initCart() {
    // Обновляем общую сумму при загрузке
    updateCartTotal();
    
    // Добавляем обработчики для кнопок количества
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-btn')) {
            const btn = e.target.closest('.quantity-btn');
            const cartItem = btn.closest('.cart-item');
            const cartId = cartItem.dataset.cartId;
            const change = btn.classList.contains('increase') ? 1 : -1;
            updateCartQuantity(cartId, change);
        }
    });
}

// Инициализация анимаций
function initAnimations() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.product-card, .section-header, .benefit-card').forEach(el => {
        observer.observe(el);
    });
}

// Инициализация слайдера акций
function initPromoSlider() {
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
    
    // Пауза автоплея при наведении на слайдер
    const sliderContainer = document.querySelector('.promo-slider-container');
    if (sliderContainer) {
        sliderContainer.addEventListener('mouseenter', () => {
            promoSlider.autoplay.stop();
        });
        
        sliderContainer.addEventListener('mouseleave', () => {
            promoSlider.autoplay.start();
        });
    }
}

// Клик по логотипу для перехода на главную
document.addEventListener('DOMContentLoaded', function() {
    const logo = document.querySelector('.logo');
    if (logo && !logo.getAttribute('href')) {
        logo.addEventListener('click', function() {
            window.location.href = 'index.php';
        });
    }
});

// CSS для уведомлений
const notificationStyles = document.createElement('style');
notificationStyles.textContent = `
    .notification {
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        transform: translateX(400px);
        transition: transform 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    }
    
    .notification.show {
        transform: translateX(0);
    }
    
    .notification-content {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .notification.success .notification-content {
        border-left: 4px solid #A8C8B0;
    }
    
    .notification.error .notification-content {
        border-left: 4px solid #ff6b6b;
    }
    
    .notification.warning .notification-content {
        border-left: 4px solid #ffd166;
    }
    
    .notification-message {
        flex: 1;
        margin-right: 20px;
        font-weight: 500;
    }
    
    .notification-close {
        background: transparent;
        border: none;
        font-size: 24px;
        cursor: pointer;
        color: #999;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.3s;
    }
    
    .notification-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: #333;
    }
    
    /* Стили для состояния загрузки */
    .loading {
        position: relative;
        overflow: hidden;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, 
            transparent, 
            rgba(255,255,255,0.2), 
            transparent);
        animation: shimmer 1.5s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
`;
document.head.appendChild(notificationStyles);