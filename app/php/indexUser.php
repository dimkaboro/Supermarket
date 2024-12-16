<?php
session_start();

// Проверяем, что пользователь авторизован
if (!isset($_SESSION['user_id'])) {
    // Если не авторизован, перенаправляем на страницу авторизации
    // Так как мы сейчас в app/php, и authorization.html в app/html,
    // используем относительный путь ../html/authorization.html
    header("Location: ../html/authorization.html");
    exit();
}

// Имя пользователя, если хотите вывести
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MixMart - Your Daily Grocery Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function scrollToAbout() {
            document.getElementById('about-us').scrollIntoView({ behavior: 'smooth' });
        }
    </script>
    <style>
        .cart-dropdown:hover .cart-content {
            display: block;
        }

        .cart-content {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-50">
<header class="bg-white shadow-md relative z-10">
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Логотип -->
            <div class="flex items-center">
                <a href="../php/indexUser.php">
                    <img src="../images/logoo.png" alt="MixMart Logo" class="h-12 w-auto">
                </a>
            </div>
            <!-- Навигационное меню -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="../html/index.html#about-us" class="text-gray-600 hover:text-green-500">About Us</a>
                <a href="../html/catalog.html" class="text-gray-600 hover:text-green-500">Product Catalog</a>
                <a href="../html/checkout.html" class="text-gray-600 hover:text-green-500">Order Page</a>
            </div>
            <!-- Поле поиска -->
            <div class="flex-1 px-8 max-w-sm">
                <div class="relative">
                    <input type="text" id="search-input" placeholder="Search products..." 
                           class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:border-green-500">
                    <button class="absolute right-3 top-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Блок с кнопками: Logout, Корзина и Аватар -->
            <div class="flex items-center space-x-4">
                <!-- Кнопка "Logout" -->
                <a href="../html/authorization.html" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600">
                    Logout
                </a>
                <!-- Корзина -->
                <div class="relative">
                    <a href="../html/checkout.html" id="cart-button" class="text-gray-600 hover:text-green-500 flex items-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                        <!-- Значок количества товаров в корзине -->
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
                    </a>
                    <!-- Контент корзины (можно настроить по необходимости) -->
                    <div id="cart-content" class="absolute right-0 mt-2 w-64 bg-white border rounded-lg shadow-lg p-4 hidden">
                        <h3 class="text-lg font-semibold mb-2">Your Cart</h3>
                        <div id="cart-items" class="space-y-2">
                            <p>Your cart is empty.</p>
                        </div>
                        <div class="mt-4">
                            <a href="../html/checkout.html" class="w-full bg-green-500 text-white py-2 rounded-lg text-center hover:bg-green-600">View Cart</a>
                        </div>
                    </div>
                </div>
                <!-- Аватар пользователя -->
                <a href="profile.php" class="text-gray-600 hover:text-green-500" title="Profile">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    // Управление отображением корзины
    const cartButton = document.getElementById('cart-button');
    const cartContent = document.getElementById('cart-content');

    cartButton.addEventListener('click', (e) => {
        e.preventDefault(); // Предотвращаем переход по ссылке
        cartContent.classList.toggle('hidden');
    });

    // Закрытие корзины при клике вне ее
    document.addEventListener('click', (e) => {
        if (!cartButton.contains(e.target) && !cartContent.contains(e.target)) {
            cartContent.classList.add('hidden');
        }
    });
</script>

<main class="container mx-auto px-4 py-8">
    <section class="relative rounded-xl overflow-hidden mb-12">
        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e" alt="Supermarket Banner" class="w-full h-96 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-4">Welcome to MixMart</h1>
                <p class="text-2xl mb-6">Your one-stop shop for fresh groceries</p>
            </div>
        </div>
    </section>

    <section id="about-us" class="mb-12 bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-4xl font-bold mb-10">About Us</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <p class="text-gray-700 leading-relaxed mb-4 text-lg">
                    MixMart has been serving our community with the finest quality groceries since 1995. 
                    We take pride in offering farm-fresh produce, premium meats, and a wide selection of organic products.
                </p>
                <p class="text-gray-700 leading-relaxed text-lg">
                    Our commitment to quality, sustainability, and customer satisfaction makes us your ideal grocery partner.
                </p>
            </div>
            <div class="relative -mt-6"> 
                <img src="../images/supermarketWallpaper.jpg" 
                     alt="Store Interior" 
                     class="rounded-lg shadow-md">
            </div>
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Пример продукта с добавленными классами и атрибутами для поиска -->
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Banana">
                <div>
                    <img src="../images/bananas.jpg" alt="Banana" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Banana</h3>
                </div>
                <button onclick="addToCart('Banana', '../images/bananas.jpg', 1, 0.99)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Pepper">
                <div>
                    <img src="../images/peppers.jpg" alt="Pepper" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Pepper</h3>
                </div>
                <button onclick="addToCart('Pepper', '../images/peppers.jpg', 1, 2.49)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Croissant">
                <div>
                    <img src="../images/croissants.jpg" alt="Croissant" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Croissant</h3>
                </div>
                <button onclick="addToCart('Croissant', '../images/croissants.jpg', 1, 4)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Milk">
                <div>
                    <img src="../images/milk.jpg" alt="Milk" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Milk</h3>
                </div>
                <button onclick="addToCart('Milk', '../images/milk.jpg', 1, 2)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
        </div>
    </section>

    <section class="mb-12 bg-green-50 p-8 rounded-lg">
        <h2 class="text-2xl font-bold mb-6">Weekly Deals</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Buy 1 Get 1 Free</h3>
                <p class="text-gray-600">On all bakery items</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">🎉 20% Discount on Fresh Fruits!</h3>
                <p class="text-gray-600">When Buying 2 kg or More</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Free Delivery</h3>
                <p class="text-gray-600">For orders more than $50.</p>
            </div>
        </div>
    </section>
</main>

<footer class="bg-white border-t">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">About MixMart</h3>
                <p class="text-gray-600">Your trusted source for fresh and quality groceries.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Store Locator</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Careers</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Help Center</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Returns</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Shipping Info</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                <div class="flex space-x-4">
                    <!-- Добавьте реальные SVG иконки социальных сетей -->
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.897 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.897-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
                        </svg>
                        <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">0</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </div>
</header>

<main class="container mx-auto px-4 py-8">
    <section class="relative rounded-xl overflow-hidden mb-12">
        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e" alt="Supermarket Banner" class="w-full h-96 object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-4">Welcome to MixMart</h1>
                <p class="text-2xl mb-6">Your one-stop shop for fresh groceries</p>
            </div>
        </div>
    </section>

    <section id="about-us" class="mb-12 bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-4xl font-bold mb-10">About Us</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <p class="text-gray-700 leading-relaxed mb-4 text-lg">
                    MixMart has been serving our community with the finest quality groceries since 1995. 
                    We take pride in offering farm-fresh produce, premium meats, and a wide selection of organic products.
                </p>
                <p class="text-gray-700 leading-relaxed text-lg">
                    Our commitment to quality, sustainability, and customer satisfaction makes us your ideal grocery partner.
                </p>
            </div>
            <div class="relative -mt-6"> 
                <img src="../images/supermarketWallpaper.jpg" 
                     alt="Store Interior" 
                     class="rounded-lg shadow-md">
            </div>
        </div>
    </section>

    <section class="mb-12">
        <h2 class="text-2xl font-bold mb-6">Products</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Продукт 1 -->
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Banana">
                <div>
                    <img src="../images/bananas.jpg" alt="Banana" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Banana</h3>
                </div>
                <button onclick="addToCart('Banana', '../images/bananas.jpg', 1, 0.99)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <!-- Продукт 2 -->
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Pepper">
                <div>
                    <img src="../images/peppers.jpg" alt="Pepper" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Pepper</h3>
                </div>
                <button onclick="addToCart('Pepper', '../images/peppers.jpg', 1, 2.49)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <!-- Продукт 3 -->
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Croissant">
                <div>
                    <img src="../images/croissants.jpg" alt="Croissant" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Croissant</h3>
                </div>
                <button onclick="addToCart('Croissant', '../images/croissants.jpg', 1, 4)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
            <!-- Продукт 4 -->
            <div class="product-card bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl" data-name="Milk">
                <div>
                    <img src="../images/milk.jpg" alt="Milk" class="w-full h-48 object-cover rounded-md mb-4">
                    <h3 class="font-semibold mb-6">Milk</h3>
                </div>
                <button onclick="addToCart('Milk', '../images/milk.jpg', 1, 2)" 
                    class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                  Add to cart
                </button>
            </div>
        </div>
    </section>

    <section class="mb-12 bg-green-50 p-8 rounded-lg">
        <h2 class="text-2xl font-bold mb-6">Weekly Deals</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Buy 1 Get 1 Free</h3>
                <p class="text-gray-600">On all bakery items</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">🎉 20% Discount on Fresh Fruits!</h3>
                <p class="text-gray-600">When Buying 2 kg or More</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md transform transition-transform hover:scale-105 hover:shadow-2xl">
                <h3 class="text-xl font-semibold mb-4">Free Delivery</h3>
                <p class="text-gray-600">For orders more than $50.</p>
            </div>
        </div>
    </section>
</main>

<footer class="bg-white border-t">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-lg font-semibold mb-4">About MixMart</h3>
                <p class="text-gray-600">Your trusted source for fresh and quality groceries.</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Store Locator</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Careers</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Customer Service</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Help Center</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Returns</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-green-500">Shipping Info</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4">Connect With Us</h3>
                <div class="flex space-x-4">
                    <!-- Добавьте реальные SVG иконки социальных сетей -->
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <!-- Path для Facebook -->
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <!-- Path для Twitter -->
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.916 4.916 0 00-8.384 4.482A13.944 13.944 0 011.671 3.149a4.916 4.916 0 001.523 6.573 4.897 4.897 0 01-2.229-.616c-.054 2.281 1.581 4.415 3.949 4.89a4.935 4.935 0 01-2.224.084 4.918 4.918 0 004.588 3.417A9.867 9.867 0 010 19.54a13.94 13.94 0 007.548 2.209c9.056 0 14.01-7.496 14.01-13.986 0-.213-.005-.425-.014-.636A10.025 10.025 0 0024 4.557z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-green-500">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <!-- Path для Instagram -->
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.333 3.608 1.308a4.92 4.92 0 011.308 3.607c.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85a4.92 4.92 0 01-1.308 3.607 4.92 4.92 0 01-3.607 1.308c-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07a4.92 4.92 0 01-3.607-1.308 4.92 4.92 0 01-1.308-3.607c-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85a4.92 4.92 0 011.308-3.607 4.92 4.92 0 013.607-1.308c1.266-.058 1.646-.07 4.85-.07zm0-2.163C8.756 0 8.333.015 7.052.072 5.765.13 4.647.333 3.678.766a6.92 6.92 0 00-2.48 1.632A6.92 6.92 0 00.766 4.878C.333 5.847.13 6.965.072 8.252.015 9.533 0 9.956 0 12s.015 2.467.072 3.748c.058 1.287.261 2.405.694 3.374a6.92 6.92 0 001.632 2.48 6.92 6.92 0 002.48 1.632c.969.433 2.087.636 3.374.694C8.333 23.985 8.756 24 12 24s3.667-.015 4.948-.072c1.287-.058 2.405-.261 3.374-.694a6.92 6.92 0 002.48-1.632 6.92 6.92 0 001.632-2.48c.433-.969.636-2.087.694-3.374C23.985 14.467 24 14.044 24 12s-.015-2.467-.072-3.748c-.058-1.287-.261-2.405-.694-3.374a6.92 6.92 0 00-1.632-2.48 6.92 6.92 0 00-2.48-1.632c-.969-.433-2.087-.636-3.374-.694C15.667.015 15.244 0 12 0z"/>
                            <path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zm0 10.162a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 11-2.88 0 1.44 1.44 0 012.88 0z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="mt-8 pt-8 border-t text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </div>
</footer>

<!-- Подключение cart.js -->
<script src="../js/cart.js"></script>

<!-- Скрипт для поиска продуктов -->
<script>
    // Функция для фильтрации продуктов
    function filterProducts() {
        const searchInput = document.getElementById('search-input');
        const filter = searchInput.value.toLowerCase();
        const productCards = document.querySelectorAll('.product-card');

        productCards.forEach(card => {
            const productName = card.getAttribute('data-name').toLowerCase();
            if (productName.includes(filter)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Добавляем слушатель события ввода к полю поиска
    document.getElementById('search-input').addEventListener('input', filterProducts);
</script>

<!-- Скрипт для обработки формы оформления заказа -->
<script>
    // Функция обновления количества товаров в корзине
    function updateCartCount() {
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            const totalItems = cart.reduce((acc, item) => acc + item.quantity, 0);
            cartCountElement.textContent = totalItems;
        }
    }

    // Функция для отображения товаров в корзине
    function displayCartItems() {
        const cartItemsContainer = document.getElementById('cart-items');
        if (cartItemsContainer) {
            cartItemsContainer.innerHTML = ''; // Очищаем существующие элементы
            
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
                return;
            }
            
            cart.forEach((item, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'flex items-center justify-between';
                
                itemDiv.innerHTML = `
                    <div class="flex items-center">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-md mr-4">
                        <div>
                            <h4 class="font-semibold">${item.name}</h4>
                            <p>Price: $${item.price.toFixed(2)}</p>
                            <p>Quantity: ${item.quantity}</p>
                        </div>
                    </div>
                    <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700">Remove</button>
                `;
                
                cartItemsContainer.appendChild(itemDiv);
            });
        }
    }

    // Функция для удаления товара из корзины
    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        displayCartItems();
    }

    // Обработчик отправки формы оформления заказа
    function handleCheckoutFormSubmit(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        // Получаем текущую корзину из localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        if (cart.length === 0) {
            alert('Your cart is empty.');
            return;
        }

        // Здесь можно добавить дополнительную обработку данных формы, например, отправку на сервер

        alert('Uspech Nakupu'); // Сообщение о успешном заказе

        // Очистка корзины
        cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));

        // Обновление отображения корзины
        updateCartCount();
        displayCartItems();
    }

    // Добавляем обработчик события для формы оформления заказа
    document.getElementById('checkout-form').addEventListener('submit', handleCheckoutFormSubmit);

    // Инициализация корзины при загрузке страницы
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    updateCartCount();
    displayCartItems();
</script>

</body>
</html>
