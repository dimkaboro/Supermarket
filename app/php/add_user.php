<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: authorization.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dsn = 'mysql:host=db;dbname=supermarket;charset=utf8';
    $username = 'user';
    $password = 'user_password';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Помилка підключення до бази даних: " . $e->getMessage());
    }

    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstName, $lastName, $email, $phone, $gender, $username, $password]);

    header("Location: admin_panel.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add user</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Заголовок -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.html">
                <img src="../images/logoo.png" alt="MixMart Logo" class="h-12 w-auto">
            </a>
            <h1 class="text-2xl font-bold text-green-600">Add user</h1>
        </div>
    </header>

    <!-- Форма добавления пользователя -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-gray-700 text-center">Form for adding user</h2>
            <form method="POST" class="space-y-4">
                <!-- Ім'я -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">name:</label>
                    <input type="text" name="first_name" id="first_name" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Прізвище -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">last name:</label>
                    <input type="text" name="last_name" id="last_name" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Телефон -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">phone number:</label>
                    <input type="tel" name="phone" id="phone" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Пол -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Sex:</label>
                    <select name="gender" id="gender" required 
                            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="male">Man</option>
                        <option value="female">Woman</option>
                        <option value="other">Another</option>
                    </select>
                </div>

                <!-- Ім'я користувача -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Users name:</label>
                    <input type="text" name="username" id="username" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Пароль -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Кнопка отправки -->
                <div>
                    <button type="submit" 
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Подвал -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-4 text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>