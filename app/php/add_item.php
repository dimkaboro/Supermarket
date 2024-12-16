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
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <h1>Додати товар</h1>
    <a href="admin_panel.php">Повернутися до адмінпанелі</a>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nazev">Назва товару:</label>
        <input type="text" id="nazev" name="nazev" required><br>

        <label for="cena">Ціна:</label>
        <input type="number" step="0.01" id="cena" name="cena" required><br>

        <label for="hmotnost">Вага (кг):</label>
        <input type="number" step="0.001" id="hmotnost" name="hmotnost" required><br>

        <button type="submit">Додати товар</button>
    </form>
</body>
</html>