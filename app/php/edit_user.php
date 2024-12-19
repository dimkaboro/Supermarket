<?php
session_start();

// Check if the user is an administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: authorization.html"); // If not admin, redirect to authorization page
    exit();
}

// Database connection
$dsn = 'mysql:host=db;dbname=supermarket;charset=utf8';
$username = 'user';
$password = 'user_password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage());
}

// Check if the user ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: User ID not specified.");
}

$userId = intval($_GET['id']);

// Fetch user data from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Error: No user found with the specified ID.");
    }
} catch (PDOException $e) {
    die("Error while fetching user data: " . $e->getMessage());
}

// Update user data if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
    $lastName = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $usernameInput = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $role = isset($_POST['role']) ? htmlspecialchars(trim($_POST['role'])) : ''; // Added role field

    // Check if all fields are filled
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($usernameInput) || empty($role)) {
        $errorMessage = "Error: Please fill in all fields.";
    } else {
        // Update user data in the database
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
                'username' => $usernameInput,
                'role' => $role, // Added role
                'id' => $userId
            ]);

            $successMessage = "User data successfully updated.";
        } catch (PDOException $e) {
            $errorMessage = "Error while updating user data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <!-- Main Container -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit User</h1>

        <!-- Link to Admin Panel -->
        <a href="admin_panel.php" class="text-green-500 hover:text-green-600 mb-4 block text-center">Return to Admin Panel</a>

        <!-- Error/Success Messages -->
        <?php if (isset($errorMessage)): ?>
            <p class="text-red-500 mb-4"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <?php if (isset($successMessage)): ?>
            <p class="text-green-500 mb-4"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>

        <!-- Edit Form -->
        <form method="POST" class="space-y-4">
            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-gray-700 font-medium">First Name:</label>
                <input type="text" id="first_name" name="first_name" 
                       value="<?php echo htmlspecialchars($user['first_name']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Last Name -->
            <div>
                <label for="last_name" class="block text-gray-700 font-medium">Last Name:</label>
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

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-gray-700 font-medium">Phone:</label>
                <input type="tel" id="phone" name="phone" 
                       value="<?php echo htmlspecialchars($user['phone']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Gender -->
            <div>
                <label for="gender" class="block text-gray-700 font-medium">Gender:</label>
                <select id="gender" name="gender" 
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                        required>
                    <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <!-- Username -->
            <div>
                <label for="username" class="block text-gray-700 font-medium">Username:</label>
                <input type="text" id="username" name="username" 
                       value="<?php echo htmlspecialchars($user['username']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-gray-700 font-medium">Role:</label>
                <select id="role" name="role" 
                        class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                        required>
                    <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center mt-6">
                <a href="admin_panel.php" 
                   class="text-gray-600 hover:text-gray-900">Return to Admin Panel</a>
                <button type="submit" 
                        class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-600 transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

</body>
</html>
