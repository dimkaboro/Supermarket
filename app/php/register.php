<?php
// Старт сессии для возможного сохранения данных
session_start();

// Подключение к базе данных
$dsn = 'mysql:host=sql310.infinityfree.com;dbname=if0_37950136_supermarket;charset=utf8';
$username = 'if0_37950136';
$db_password = 'tGgX9jy15tX1VF';

try {
    $pdo = new PDO($dsn, $username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// Функция очистки данных
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

// Проверяем отправку формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['first_name']) ? cleanInput($_POST['first_name']) : '';
    $lastName = isset($_POST['last_name']) ? cleanInput($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
    $gender = isset($_POST['gender']) ? cleanInput($_POST['gender']) : '';
    $username = isset($_POST['username']) ? cleanInput($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Проверка обязательных полей
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($username) || empty($password)) {
        die("Пожалуйста, заполните все обязательные поля.");
    }

    // Проверка уникальности email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    if ($stmt->fetchColumn() > 0) {
        die("Этот email уже используется.");
    }

    // Проверка уникальности имени пользователя
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetchColumn() > 0) {
        die("Это имя пользователя уже занято.");
    }

    // Хеширование пароля
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Обработка загрузки изображения профиля
    $profilePicturePath = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileInfo = pathinfo($_FILES['profile_picture']['name']);
        $extension = strtolower($fileInfo['extension']);

        if (!in_array($extension, $allowedExtensions)) {
            die("Допустимые форматы изображений: JPG, JPEG, PNG.");
        }

        // Генерация уникального имени для изображения
        $newFileName = uniqid('profile_', true) . '.' . $extension;
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $targetPath = $uploadDir . $newFileName;
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
            die("Ошибка загрузки изображения.");
        }

        // Установка пути к изображению для сохранения в базе данных
        $profilePicturePath = 'uploads/' . $newFileName;
    }

    // Сохранение данных пользователя в базе
    try {
        $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, gender, username, password, profile_picture) 
                               VALUES (:first_name, :last_name, :email, :phone, :gender, :username, :password, :profile_picture)");
        $stmt->execute([
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':email' => $email,
            ':phone' => $phone,
            ':gender' => $gender,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':profile_picture' => $profilePicturePath
        ]);

        echo "Регистрация прошла успешно!";
    } catch (PDOException $e) {
        die("Ошибка сохранения данных: " . $e->getMessage());
    }
}
?>
