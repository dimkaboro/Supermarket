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
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <h1>Адмінпанель</h1>
    <h2>Користувачі</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Ім'я</th>
            <th>Прізвище</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Роль</th>
            <th>Дії</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['id']); ?></td>
            <td><?php echo htmlspecialchars($user['first_name']); ?></td>
            <td><?php echo htmlspecialchars($user['last_name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['phone']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
                <a href="edit_user.php?id=<?php echo $user['id']; ?>">Редагувати</a> |
                <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Ви впевнені?')">Видалити</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Товари</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Назва</th>
            <th>Ціна</th>
            <th>Вага</th>
            <th>Дії</th>
        </tr>
        <?php foreach ($zbozi as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['ID']); ?></td>
            <td><?php echo htmlspecialchars($item['Nazev']); ?></td>
            <td><?php echo htmlspecialchars($item['Cena']); ?></td>
            <td><?php echo htmlspecialchars($item['Hmotnost']); ?></td>
            <td>
                <a href="edit_item.php?id=<?php echo $item['ID']; ?>">Редагувати</a> |
                <a href="delete_item.php?id=<?php echo $item['ID']; ?>" onclick="return confirm('Ви впевнені?')">Видалити</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <a href="add_user.php">Додати користувача</a> |
    <a href="add_item.php">Додати товар</a>
</body>
</html>