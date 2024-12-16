<?php
session_start();

// Перевірка, чи користувач є адміністратором
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: authorization.html"); // Якщо не адмін, перенаправляємо на сторінку авторизації
    exit();
}

// Підключення до бази даних
$dsn = 'mysql:host=db;dbname=supermarket;charset=utf8';
$username = 'user';
$password = 'user_password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази даних: " . $e->getMessage());
}

// Отримання даних з таблиці users
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Отримання даних з таблиці Zbozi
$stmt = $pdo->query("SELECT * FROM Zbozi");
$zbozi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Адмінпанель</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Заголовок -->
    <header class="bg-white shadow-md relative z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="index.html">
                <img src="../images/logoo.png" alt="MixMart Logo" class="h-12 w-auto">
            </a>
            <h1 class="text-2xl font-bold text-green-600">Адмінпанель</h1>
        </div>
    </header>

    <!-- Основное содержимое -->
    <main class="container mx-auto px-4 py-8">
        <!-- Таблица пользователей -->
        <section class="mb-12 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-700">Користувачі</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Ім'я</th>
                            <th class="p-2">Прізвище</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Телефон</th>
                            <th class="p-2">Роль</th>
                            <th class="p-2">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition">
                            <td class="p-2 text-center"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="p-2 text-center">
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="text-blue-500 hover:text-blue-700">Редагувати</a> |
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Ви впевнені?')">Видалити</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Таблица товаров -->
        <section class="mb-12 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-700">Товари</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Назва</th>
                            <th class="p-2">Ціна</th>
                            <th class="p-2">Вага</th>
                            <th class="p-2">Дії</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($zbozi as $item): ?>
                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition">
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['ID']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($item['Nazev']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['Cena']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['Hmotnost']); ?></td>
                            <td class="p-2 text-center">
                                <a href="edit_item.php?id=<?php echo $item['ID']; ?>" class="text-blue-500 hover:text-blue-700">Редагувати</a> |
                                <a href="delete_item.php?id=<?php echo $item['ID']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Ви впевнені?')">Видалити</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Ссылки на добавление -->
        <div class="flex justify-center gap-6 mt-6">
            <a href="add_user.php" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition">Додати користувача</a>
            <a href="add_item.php" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition">Додати товар</a>
        </div>
    </main>

    <!-- Подвал -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>