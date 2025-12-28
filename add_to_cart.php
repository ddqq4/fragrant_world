<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Требуется авторизация']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'] ?? 0;
    $quantity = (int)($_POST['quantity'] ?? 1);
    $user_id = $_SESSION['user_id'];
    
    if ($product_id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Неверный ID товара']);
        exit();
    }
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $product_id]);
        $existing = $stmt->fetch();
        
        if ($existing) {
            $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
            $stmt->execute([$quantity, $existing['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$user_id, $product_id, $quantity]);
        }
        
        echo json_encode(['success' => true, 'message' => 'Товар добавлен в корзину']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $e->getMessage()]);
    }
}
?>