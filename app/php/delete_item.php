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
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <h1>Видалення товару</h1>
    <p>Ви дійсно хочете видалити товар <strong><?php echo htmlspecialchars($item['Nazev']); ?></strong>?</p>

    <form method="POST">
        <button type="submit">Так, видалити</button>
        <a href="admin_panel.php">Ні, повернутися до адмінпанелі</a>
    </form>
</body>
</html>