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

// Перевірка, чи передано ID користувача
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Помилка: ID користувача не вказано.");
}

$userId = intval($_GET['id']);

// Отримання даних користувача з бази даних
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Помилка: Користувача з таким ID не знайдено.");
    }
} catch (PDOException $e) {
    die("Помилка під час отримання даних користувача: " . $e->getMessage());
}

// Оновлення даних користувача, якщо форма була відправлена
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
    $lastName = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';

    // Перевірка, чи всі поля заповнені
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($username)) {
        $errorMessage = "Помилка: Будь ласка, заповніть всі поля.";
    } else {
        // Оновлення даних користувача в базі даних
        try {
            $stmt = $pdo->prepare("UPDATE users SET 
                first_name = :first_name, 
                last_name = :last_name, 
                email = :email, 
                phone = :phone, 
                gender = :gender, 
                username = :username 
                WHERE id = :id");

            $stmt->execute([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'username' => $username,
                'id' => $userId
            ]);

            $successMessage = "Дані користувача успішно оновлено.";
        } catch (PDOException $e) {
            $errorMessage = "Помилка під час оновлення даних користувача: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування користувача</title>
    <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
    <h1>Редагування користувача</h1>
    <a href="admin_panel.php">Повернутися до адмінпанелі</a>

    <?php if (isset($errorMessage)): ?>
        <p style="color: red;"><?php echo $errorMessage; ?></p>
    <?php endif; ?>

    <?php if (isset($successMessage)): ?>
        <p style="color: green;"><?php echo $successMessage; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="first_name">Ім'я:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required><br>

        <label for="last_name">Прізвище:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

        <label for="phone">Телефон:</label>
        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br>

        <label for="gender">Пohlaví:</label>
        <select id="gender" name="gender" required>
            <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Мужской</option>
            <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Женский</option>
            <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Другое</option>
        </select><br>

        <label for="username">Ім'я користувача:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>

        <button type="submit">Зберегти зміни</button>
    </form>
</body>
</html>