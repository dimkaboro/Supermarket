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
                <div class="flex items-center">
                    <a href="index.html">
                        <img src="../images/logoo.png" alt="MixMart Logo" class="h-12 w-auto">
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <!-- Так как indexUser.php находится в php/, ссылка на index.html в html/ будет ../html/index.html -->
                    <a href="../html/index.html" class="text-gray-600 hover:text-green-500">Home</a>
                    <a href="../html/index.html#about-us" class="text-gray-600 hover:text-green-500">About Us</a>
                    <a href="../html/catalog.html" class="text-gray-600 hover:text-green-500">Product Catalog</a>
                    <a href="../html/checkout.html" class="text-gray-600 hover:text-green-500">Order Page</a>
                </div>
                <div class="flex-1 px-8 max-w-sm">
                    <div class="relative">
                        <input type="text" placeholder="Search products..." 
                               class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:border-green-500">
                        <button class="absolute right-3 top-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="../html/authorization.html" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600">
                        Logout
                    </a>

                    <div class="relative cart-dropdown flex items-center" id="cart-container">
                        <button class="text-gray-600 hover:text-green-500 relative flex items-center" id="cart-button">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <!-- Счётчик товаров -->
                            <span class="absolute -top-2 -right-2 bg-green-500 text-white rounded-full w-5 h-5 
                                         flex items-center justify-center text-xs">2</span>
                        </button>
                        <!-- Выпадающее меню -->
                        <div class="cart-content absolute right-0 top-full mt-2 w-72 bg-white rounded-lg shadow-lg p-4 z-50">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium">Shopping Cart (2)</span>
                                    <button class="text-sm text-green-500 hover:text-green-600">View All</button>
                                </div>
                                <div class="divide-y divide-gray-100">
                                    <div class="flex items-center py-2">
                                        <img src="https://images.unsplash.com/photo-1587132137056-bfbf0166836e" 
                                             alt="Organic Apples" class="w-12 h-12 rounded object-cover">
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium">Organic Apples</p>
                                            <p class="text-sm text-gray-500">$4.99</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center py-2">
                                        <img src="https://images.unsplash.com/photo-1598965675045-45c5e72c7d05" 
                                             alt="Fresh Bread" class="w-12 h-12 rounded object-cover">
                                        <div class="ml-3 flex-1">
                                            <p class="text-sm font-medium">Fresh Bread</p>
                                            <p class="text-sm text-gray-500">$3.99</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t pt-4">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm font-medium">Total:</span>
                                        <span class="text-sm font-bold">$8.98</span>
                                    </div>
                                    <a href="../html/checkout.html" 
                                       class="w-full bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 text-center block">
                                        Checkout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a href="profile.php" class="text-gray-600 hover:text-green-500" title="Profile">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </a>
                </div>

                        <!-- Можно отобразить имя пользователя, если нужно -->
                        <!-- <span class="text-gray-600">Привет, <?php echo htmlspecialchars($username); ?></span> -->
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
                    <a href="../html/catalog.html" class="bg-green-500 text-white px-6 py-3 rounded-full hover:bg-green-600">Shop Now</a>
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
            <h2 class="text-2xl font-bold mb-6">Shop by Category</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <img src="../images/freshfruits.jpg" alt="Fruits" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Fruits</h3>
                        <a href="../html/catalog.html" class="text-green-500 hover:text-green-600">Shop Now →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <img src="../images/freshvegetables.jpg" alt="Vegetables" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Vegetables</h3>
                        <a href="../html/catalog.html" class="text-green-500 hover:text-green-600">Shop Now →</a>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <img src="../images/freshbakery.jpg" alt="Bakery" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Bakery</h3>
                        <a href="../html/catalog.html" class="text-green-500 hover:text-green-600">Shop Now →</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6">Featured Products</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <div>
                        <img src="../images/bananas.jpg" alt="Banana" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="font-semibold mb-6">Banana</h3>
                    </div>
                    <a href="../html/catalog.html" 
                       class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                        Go to Catalog
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <div>
                        <img src="../images/peppers.jpg" alt="Pepper" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="font-semibold mb-6">Pepper</h3>
                    </div>
                    <a href="../html/catalog.html" 
                       class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                        Go to Catalog
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <div>
                        <img src="../images/croissants.jpg" alt="Croissant" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="font-semibold mb-6">Croissant</h3>
                    </div>
                    <a href="../html/catalog.html" 
                       class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                        Go to Catalog
                    </a>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col transform transition-transform hover:scale-105 hover:shadow-2xl">
                    <div>
                        <img src="../images/milk.jpg" alt="Milk" class="w-full h-48 object-cover rounded-md mb-4">
                        <h3 class="font-semibold mb-6">Milk</h3>
                    </div>
                    <a href="../html/catalog.html" 
                       class="mt-auto w-full bg-green-500 text-white px-4 py-2 rounded-full text-center hover:bg-green-600">
                        Go to Catalog
                    </a>
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
                    <p class="text-gray-600">For orders more than 50$.</p>
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
                        <a href="#" class="text-gray-600 hover:text-green-500"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><!-- path --></svg></a>
                        <a href="#" class="text-gray-600 hover:text-green-500"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><!-- path --></svg></a>
                        <a href="#" class="text-gray-600 hover:text-green-500"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><!-- path --></svg></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t text-center text-gray-600">
                <p>© 2024 MixMart. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
