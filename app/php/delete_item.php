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

// Перевірка, чи передано ID товару
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Помилка: ID товару не вказано.");
}

$itemId = intval($_GET['id']);

// Перевірка, чи товар існує в базі даних
try {
    $stmt = $pdo->prepare("SELECT * FROM Zbozi WHERE ID = :id");
    $stmt->execute(['id' => $itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        die("Помилка: Товару з таким ID не знайдено.");
    }
} catch (PDOException $e) {
    die("Помилка під час перевірки товару: " . $e->getMessage());
}

// Якщо користувач підтвердив видалення
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Видалення товару з бази даних
        $stmt = $pdo->prepare("DELETE FROM Zbozi WHERE ID = :id");
        $stmt->execute(['id' => $itemId]);

        // Перенаправлення на адмінпанель з повідомленням про успіх
        header("Location: admin_panel.php?message=Товар успішно видалено.");
        exit();
    } catch (PDOException $e) {
        die("Помилка під час видалення товару: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалення товару</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <!-- Основной контейнер -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-6">Видалення товару</h1>
        
        <!-- Подтверждение -->
        <p class="text-gray-700 mb-6">
            Ви дійсно хочете видалити товар 
            <strong class="text-gray-900">
                <?php echo htmlspecialchars($item['Nazev']); ?>
            </strong>?
        </p>

        <!-- Форма с кнопками -->
        <form method="POST" class="flex flex-col gap-4">
            <!-- Кнопка подтверждения удаления -->
            <button type="submit" 
                    class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition">
                Так, видалити
            </button>
            <!-- Кнопка возврата -->
            <a href="admin_panel.php" 
               class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition">
                Ні, повернутися до адмінпанелі
            </a>
        </form>
    </div>

</body>
</html>