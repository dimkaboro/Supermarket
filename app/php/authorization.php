<?php
// Початок сесії для зберігання даних користувача
session_start();

// Перевірка, чи був відправлений POST-запит
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Отримання даних з форми
    $usernameOrEmail = isset($_POST['identifier']) ? htmlspecialchars(trim($_POST['identifier'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Перевірка, чи всі поля заповнені
    if (empty($usernameOrEmail) || empty($password)) {
        die("Помилка: Будь ласка, заповніть всі поля.");
    }

    try {
        // Пошук користувача в базі даних за логіном або email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $usernameOrEmail, 'email' => $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Перевірка, чи користувач існує та чи пароль вірний
        if ($user && password_verify($password, $user['password'])) {
            // Збереження даних користувача в сесії
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Зберігаємо роль користувача

            // Перенаправлення на відповідну сторінку в залежності від ролі
            if ($user['role'] === 'admin') {
                header("Location: admin_panel.php"); // Перенаправляємо на адмінпанель
                exit();
            } else {
                header("Location: index.html"); // Перенаправляємо на головну сторінку для звичайних користувачів
                exit();
            }
        } else {
            // Помилка авторизації
            die("Помилка: Неправильний логін або пароль.");
        }
    } catch (PDOException $e) {
        // Помилка під час виконання запиту до бази даних
        die("Помилка під час авторизації: " . $e->getMessage());
    }
} else {
    // Якщо запит не є POST, перенаправляємо на сторінку авторизації
    header("Location: authorization.html");
    exit();
}
?>