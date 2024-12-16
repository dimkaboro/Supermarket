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
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <h1>Редагування товару</h1>
    <a href="admin_panel.php">Повернутися до адмінпанелі</a>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nazev">Назва товару:</label>
        <input type="text" id="nazev" name="nazev" value="<?php echo htmlspecialchars($item['Nazev']); ?>" required><br>

        <label for="cena">Ціна:</label>
        <input type="number" step="0.01" id="cena" name="cena" value="<?php echo htmlspecialchars($item['Cena']); ?>" required><br>

        <label for="hmotnost">Вага (кг):</label>
        <input type="number" step="0.001" id="hmotnost" name="hmotnost" value="<?php echo htmlspecialchars($item['Hmotnost']); ?>" required><br>

        <button type="submit">Зберегти зміни</button>
    </form>
</body>
</html>