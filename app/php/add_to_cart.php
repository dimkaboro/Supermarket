<?php
session_start();
header('Content-Type: application/json');

// Инициализация корзины
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Получаем данные из запроса
$productID = $_POST['id'] ?? null;
$productName = $_POST['name'] ?? '';
$productPrice = $_POST['price'] ?? 0;

if ($productID) {
    // Проверяем, существует ли товар в корзине
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $productID) {
            $item['quantity'] += 1; // Увеличиваем количество
            $found = true;
            break;
        }
    }

    if (!$found) {
        // Добавляем новый товар
        $_SESSION['cart'][] = ['id' => $productID, 'name' => $productName, 'price' => $productPrice, 'quantity' => 1];
    }
}

// Возвращаем обновленную корзину
echo json_encode($_SESSION['cart']);
?>
