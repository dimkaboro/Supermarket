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

// Обробка форми, якщо вона була відправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = isset($_POST['nazev']) ? htmlspecialchars(trim($_POST['nazev'])) : '';
    $cena = isset($_POST['cena']) ? floatval($_POST['cena']) : 0;
    $hmotnost = isset($_POST['hmotnost']) ? floatval($_POST['hmotnost']) : 0;

    // Перевірка, чи всі поля заповнені
    if (empty($nazev) || $cena <= 0 || $hmotnost <= 0) {
        $errorMessage = "Помилка: Будь ласка, заповніть всі поля.";
    } else {
        // Додавання товару до бази даних
        try {
            $stmt = $pdo->prepare("INSERT INTO Zbozi (Nazev, Cena, Hmotnost) VALUES (:nazev, :cena, :hmotnost)");
            $stmt->execute([
                'nazev' => $nazev,
                'cena' => $cena,
                'hmotnost' => $hmotnost
            ]);

            $successMessage = "Товар успішно додано.";
        } catch (PDOException $e) {
            $errorMessage = "Помилка під час додавання товару: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Додати товар</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Заголовок -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="admin_panel.php" class="text-green-600 hover:text-green-500 font-bold text-lg">
                ← Повернутися до адмінпанелі
            </a>
            <h1 class="text-2xl font-bold text-gray-700">Додати товар</h1>
        </div>
    </header>

    <!-- Форма добавления товара -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Форма додавання товару</h2>
            
            <!-- Сообщения об ошибке или успехе -->
            <?php if (isset($errorMessage)): ?>
                <p class="text-red-500 text-sm text-center mb-4"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <?php if (isset($successMessage)): ?>
                <p class="text-green-500 text-sm text-center mb-4"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <!-- Форма -->
            <form method="POST" class="space-y-4">
                <!-- Назва товару -->
                <div>
                    <label for="nazev" class="block text-sm font-medium text-gray-700">Назва товару:</label>
                    <input type="text" id="nazev" name="nazev" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Ціна -->
                <div>
                    <label for="cena" class="block text-sm font-medium text-gray-700">Ціна:</label>
                    <input type="number" step="0.01" id="cena" name="cena" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Вага -->
                <div>
                    <label for="hmotnost" class="block text-sm font-medium text-gray-700">Вага (кг):</label>
                    <input type="number" step="0.001" id="hmotnost" name="hmotnost" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Кнопка отправки -->
                <div>
                    <button type="submit" 
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Додати товар
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