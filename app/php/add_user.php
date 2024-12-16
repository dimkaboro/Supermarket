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
    <title>Додати користувача</title>
</head>
<body>
    <h1>Додати користувача</h1>
    <form method="POST">
        <label for="first_name">Ім'я:</label>
        <input type="text" name="first_name" required><br>

        <label for="last_name">Прізвище:</label>
        <input type="text" name="last_name" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Телефон:</label>
        <input type="tel" name="phone" required><br>

        <label for="gender">Пohlaví:</label>
        <select name="gender" required>
            <option value="male">Мужской</option>
            <option value="female">Женский</option>
            <option value="other">Другое</option>
        </select><br>

        <label for="username">Ім'я користувача:</label>
        <input type="text" name="username" required><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Додати</button>
    </form>
</body>
</html>