<?php
session_start();

// Check if the user is an administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../html/authorization.html"); // If not admin, redirect to authorization page
    exit();
}

// Database connection
$dsn = 'mysql:host=sql310.infinityfree.com;dbname=if0_37950136_supermarket;charset=utf8'; // Specify host and database name
$username = 'if0_37950136'; // Your MySQL Username
$db_password = 'tGgX9jy15tX1VF'; // Your MySQL Password

try {
    // Corrected: use $db_password instead of $password
    $pdo = new PDO($dsn, $username, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; // Removed or commented out for security
} catch (PDOException $e) {
    die("Database connection error: " . $e->getMessage()); // Connection error
}

// Handle the form submission if it was sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = isset($_POST['first_name']) ? htmlspecialchars(trim($_POST['first_name'])) : '';
    $lastName = isset($_POST['last_name']) ? htmlspecialchars(trim($_POST['last_name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $gender = isset($_POST['gender']) ? htmlspecialchars(trim($_POST['gender'])) : '';
    $usernameInput = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $passwordInput = isset($_POST['password']) ? password_hash(trim($_POST['password']), PASSWORD_BCRYPT) : '';

    // Check if all fields are filled
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($usernameInput) || empty($passwordInput)) {
        $errorMessage = "Error: Please fill in all fields.";
    } else {
        // Add the user to the database
        try {
            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, gender, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phone, $gender, $usernameInput, $passwordInput]);

            header("Location: admin_panel.php");
            exit();
        } catch (PDOException $e) {
            $errorMessage = "Error adding user: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="admin_panel.php" class="text-green-600 hover:text-green-500 font-bold text-lg">
                ← Back to Admin Panel
            </a>
            <h1 class="text-2xl font-bold text-green-600">Add User</h1>
        </div>
    </header>

    <!-- User Addition Form -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">User Addition Form</h2>
            
            <!-- Error or Success Messages -->
            <?php if (isset($errorMessage)): ?>
                <p class="text-red-500 text-sm text-center mb-4"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <?php if (isset($successMessage)): ?>
                <p class="text-green-500 text-sm text-center mb-4"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" class="space-y-4">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name:</label>
                    <input type="text" name="first_name" id="first_name" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                    <input type="email" name="email" id="email" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number:</label>
                    <input type="tel" name="phone" id="phone" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender:</label>
                    <select name="gender" id="gender" required 
                            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username:</label>
                    <input type="text" name="username" id="username" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-4 text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
