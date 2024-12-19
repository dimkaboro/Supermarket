<?php
// Начало сессии для хранения данных пользователя
session_start();

// Проверка, был ли отправлен POST-запрос
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Подключение к базе данных
    $dsn = 'mysql:host=sql310.infinityfree.com;dbname=if0_37950136_supermarket;charset=utf8'; // Новый хост и имя базы данных
    $db_username = 'if0_37950136'; // Новый MySQL Username
    $db_password = 'tGgX9jy15tX1VF';
 // Новый MySQL Password

    try {
        $pdo = new PDO($dsn, $db_username, $db_password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }

    // Получение данных из формы
    $usernameOrEmail = isset($_POST['identifier']) ? htmlspecialchars(trim($_POST['identifier'])) : '';
    $pass = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Проверка, заполнены ли все поля
    if (empty($usernameOrEmail) || empty($pass)) {
        die("Ошибка: Пожалуйста, заполните все поля.");
    }

    try {
        // Поиск пользователя в базе данных по логину или email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute(['username' => $usernameOrEmail, 'email' => $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Проверка, существует ли пользователь и верен ли пароль
        if ($user && password_verify($pass, $user['password'])) {
            // Сохранение данных пользователя в сессии
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Сохраняем роль пользователя

            // Перенаправление на соответствующую страницу в зависимости от роли
            if ($user['role'] === 'admin') {
                header("Location: /php/admin_panel.php"); // Если админ
                exit();
            } else {
                header("Location: /php/indexUser.php"); // Если обычный пользователь
                exit();
            }
        } else {
            // Ошибка авторизации
            die("Ошибка: Неправильный логин или пароль.");
        }

    } catch (PDOException $e) {
        // Ошибка при выполнении запроса к базе данных
        die("Ошибка при авторизации: " . $e->getMessage());
    }
} else {
    // Если запрос не является POST, перенаправляем на страницу авторизации
    header("Location: /html/authorization.html");
    exit();
}
