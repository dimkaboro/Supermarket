<?php
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: authorization.html");
    exit();
}

// Подключение к базе данных (настройте свои данные)
$dsn = 'mysql:host=db;dbname=supermarket;charset=utf8';
$db_username = 'user';
$db_password = 'user_password';

try {
    $pdo = new PDO($dsn, $db_username, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

$user_id = $_SESSION['user_id'];

// Если форма отправлена, обрабатываем обновление данных
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $gender = isset($_POST['gender']) ? trim($_POST['gender']) : '';

    // Проверка и загрузка файла (если был загружен)
    $profile_picture_path = null;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowed_extensions = ['jpg', 'jpeg', 'png'];
        $file_info = pathinfo($_FILES['profile_picture']['name']);
        $extension = strtolower($file_info['extension']);
        if (in_array($extension, $allowed_extensions)) {
            // Генерируем уникальное имя файла
            $new_filename = uniqid('profile_', true) . '.' . $extension;
            $upload_dir = __DIR__ . '/uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $target_path = $upload_dir . $new_filename;
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
                $profile_picture_path = 'uploads/' . $new_filename;
            }
        }
    }

    // Обновление данных пользователя в базе
    $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, phone = :phone, gender = :gender";
    if ($profile_picture_path !== null) {
        $sql .= ", profile_picture = :profile_picture";
    }
    $sql .= " WHERE id = :id";

    $params = [
        ':first_name' => $first_name,
        ':last_name' => $last_name,
        ':email' => $email,
        ':phone' => $phone,
        ':gender' => $gender,
        ':id' => $user_id
    ];

    if ($profile_picture_path !== null) {
        $params[':profile_picture'] = $profile_picture_path;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // После обновления перенаправляем обратно на профиль
    header("Location: profile.php");
    exit();
}

// Если форма не отправлена, извлекаем текущие данные пользователя, включая роль
$stmt = $pdo->prepare("SELECT first_name, last_name, email, phone, gender, profile_picture, role FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("Пользователь не найден в базе данных.");
}

// Устанавливаем путь к изображению профиля
$profile_picture = $user['profile_picture'] ? $user['profile_picture'] : '../app/images/defaultpicture.png';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile</title>
  <link rel="stylesheet" href="../css/profile.css">
</head>
<body>
  <div class="container">
    <h1>User Profile</h1>
    <div class="profile-box">
      <div class="profile-picture">
        <img id="profileImage" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
      </div>
      <div class="profile-details">
        <p><strong>First Name:</strong> <span id="profileFirstName"><?php echo htmlspecialchars($user['first_name']); ?></span></p>
        <p><strong>Last Name:</strong> <span id="profileLastName"><?php echo htmlspecialchars($user['last_name']); ?></span></p>
        <p><strong>Email:</strong> <span id="profileEmail"><?php echo htmlspecialchars($user['email']); ?></span></p>
        <p><strong>Phone:</strong> <span id="profilePhone"><?php echo htmlspecialchars($user['phone']); ?></span></p>
        <p><strong>Gender:</strong> <span id="profileGender">
          <?php
          if ($user['gender'] === 'male') {
              echo "Male";
          } elseif ($user['gender'] === 'female') {
              echo "Female";
          } elseif ($user['gender'] === 'other') {
              echo "Other";
          } else {
              echo "";
          }
          ?>
        </span></p>
      </div>
    </div>
    
    <?php if ($user['role'] === 'admin'): ?>
      <div class="admin-button-container" style="text-align: center; margin-top: 20px;">
        <a href="admin_panel.php" class="admin-button" style="padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
          Admin Panel
        </a>
      </div>
    <?php endif; ?>
    
    <h2>Edit Personal Information</h2>
    <form id="editProfileForm" action="profile.php" method="POST" enctype="multipart/form-data">
      <label for="firstName">First Name:</label>
      <input type="text" id="firstName" name="first_name" required value="<?php echo htmlspecialchars($user['first_name']); ?>">
      <span id="nameError" class="error-message"></span>

      <label for="lastName">Last Name:</label>
      <input type="text" id="lastName" name="last_name" required value="<?php echo htmlspecialchars($user['last_name']); ?>">
      <span id="lastNameError" class="error-message"></span>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($user['email']); ?>">
      <span id="emailError" class="error-message"></span>

      <label for="phone">Phone:</label>
      <input type="tel" id="phone" name="phone" required pattern="^\+?\d{10,13}$" placeholder="+420123456789" value="<?php echo htmlspecialchars($user['phone']); ?>">
      <span id="phoneError" class="error-message"></span>

      <label for="gender">Gender:</label>
      <select id="gender" name="gender" required>
        <option value="">--Select Gender--</option>
        <option value="male" <?php echo ($user['gender'] === 'male') ? 'selected' : ''; ?>>Male</option>
        <option value="female" <?php echo ($user['gender'] === 'female') ? 'selected' : ''; ?>>Female</option>
        <option value="other" <?php echo ($user['gender'] === 'other') ? 'selected' : ''; ?>>Other</option>
      </select>
      <span id="genderError" class="error-message"></span>

      <label for="profilePicture">Profile Picture:</label>
      <input type="file" id="profilePicture" name="profile_picture" accept="image/jpeg, image/png">
      <span id="profilePictureError" class="error-message"></span>

      <button type="submit">Save Changes</button>
      <a href="indexUser.php">Back</a>
    </form>
  </div>

  <script src="../js/validation.js"></script>
  <script src="../js/profile.js"></script>
</body>
</html>
