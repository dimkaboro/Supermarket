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

// Отримання даних товару з бази даних
try {
    $stmt = $pdo->prepare("SELECT * FROM Zbozi WHERE ID = :id");
    $stmt->execute(['id' => $itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        die("Помилка: Товару з таким ID не знайдено.");
    }
} catch (PDOException $e) {
    die("Помилка під час отримання даних товару: " . $e->getMessage());
}

// Оновлення даних товару, якщо форма була відправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = isset($_POST['nazev']) ? htmlspecialchars(trim($_POST['nazev'])) : '';
    $cena = isset($_POST['cena']) ? floatval($_POST['cena']) : 0;
    $hmotnost = isset($_POST['hmotnost']) ? floatval($_POST['hmotnost']) : 0;

    // Перевірка, чи всі поля заповнені
    if (empty($nazev) || $cena <= 0 || $hmotnost <= 0) {
        $errorMessage = "Помилка: Будь ласка, заповніть всі поля.";
    } else {
        // Оновлення даних товару в базі даних
        try {
            $stmt = $pdo->prepare("UPDATE Zbozi SET 
                Nazev = :nazev, 
                Cena = :cena, 
                Hmotnost = :hmotnost 
                WHERE ID = :id");

            $stmt->execute([
                'nazev' => $nazev,
                'cena' => $cena,
                'hmotnost' => $hmotnost,
                'id' => $itemId
            ]);

            $successMessage = "Дані товару успішно оновлено.";
        } catch (PDOException $e) {
            $errorMessage = "Помилка під час оновлення даних товару: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування товару</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <!-- Основной контейнер -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Редагування товару</h1>

        <!-- Ссылки -->
        <a href="admin_panel.php" class="text-green-500 hover:text-green-600 mb-4 block text-center">Повернутися до адмінпанелі</a>

        <!-- Сообщения об ошибке/успехе -->
        <?php if (isset($errorMessage)): ?>
            <p class="text-red-500 mb-4"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p class="text-green-500 mb-4"><?php echo $successMessage; ?></p>
        <?php endif; ?>

        <!-- Форма редактирования -->
        <form method="POST" class="space-y-4">
            <!-- Назва товару -->
            <div>
                <label for="nazev" class="block text-gray-700 font-medium">Назва товару:</label>
                <input type="text" id="nazev" name="nazev" 
                       value="<?php echo htmlspecialchars($item['Nazev']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Ціна -->
            <div>
                <label for="cena" class="block text-gray-700 font-medium">Ціна:</label>
                <input type="number" step="0.01" id="cena" name="cena" 
                       value="<?php echo htmlspecialchars($item['Cena']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Вага -->
            <div>
                <label for="hmotnost" class="block text-gray-700 font-medium">Вага (кг):</label>
                <input type="number" step="0.001" id="hmotnost" name="hmotnost" 
                       value="<?php echo htmlspecialchars($item['Hmotnost']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Кнопки -->
            <div class="flex justify-between items-center mt-6">
                <a href="admin_panel.php" 
                   class="text-gray-600 hover:text-gray-900">Повернутися до адмінпанелі</a>
                <button type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition">
                    Зберегти зміни
                </button>
            </div>
        </form>
    </div>

</body>
</html>