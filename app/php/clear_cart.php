<?php
session_start();
header('Content-Type: application/json');

// Очистка корзины
$_SESSION['cart'] = [];

// Возвращаем пустую корзину
echo json_encode($_SESSION['cart']);
?>
