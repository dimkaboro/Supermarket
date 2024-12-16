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
    $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : ''; // Додано поле для ролі

    // Перевірка, чи всі поля заповнені
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($username) || empty($role)) {
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
                username = :username,
                role = :role 
                WHERE id = :id");

            $stmt->execute([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'gender' => $gender,
                'username' => $username,
                'role' => $role, // Додано роль
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <!-- Основной контейнер -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Редагування користувача</h1>

        <!-- Ссылка на админпанель -->
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
            <!-- Имя -->
            <div>
                <label for="first_name" class="block text-gray-700 font-medium">Ім'я:</label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($user['first_name']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Фамилия -->
            <div>
                <label for="last_name" class="block text-gray-700 font-medium">Прізвище:</label>
                <input type="text" id="last_name" name="last_name" 
                       value="<?php echo htmlspecialchars($user['last_name']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-gray-700 font-medium">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($user['email']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Телефон -->
            <div>
                <label for="phone" class="block text-gray-700 font-medium">Телефон:</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Пол -->
            <div>
                <label for="gender" class="block text-gray-700 font-medium">Пohlaví:</label>
                <select id="gender" name="gender" 
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                        required>
                    <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Мужской</option>
                    <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Женский</option>
                    <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Другое</option>
                </select>
            </div>

            <!-- Имя пользователя -->
            <div>
                <label for="username" class="block text-gray-700 font-medium">Ім'я користувача:</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($user['username']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Роль -->
            <div>
                <label for="role" class="block text-gray-700 font-medium">Роль:</label>
                <select id="role" name="role" 
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                        required>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>Користувач</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Адміністратор</option>
                </select>
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
