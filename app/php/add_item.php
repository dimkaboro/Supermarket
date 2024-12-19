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

// Handle the form submission if it was sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = isset($_POST['nazev']) ? htmlspecialchars(trim($_POST['nazev'])) : '';
    $cena = isset($_POST['cena']) ? floatval($_POST['cena']) : 0;
    $hmotnost = isset($_POST['hmotnost']) ? floatval($_POST['hmotnost']) : 0;

    // Check if all fields are filled
    if (empty($nazev) || $cena <= 0 || $hmotnost <= 0) {
        $errorMessage = "Error: Please fill in all fields.";
    } else {
        // Add the product to the database
        try {
            $stmt = $pdo->prepare("INSERT INTO Zbozi (Nazev, Cena, Hmotnost) VALUES (:nazev, :cena, :hmotnost)");
            $stmt->execute([
                'nazev' => $nazev,
                'cena' => $cena,
                'hmotnost' => $hmotnost
            ]);

            $successMessage = "Product was successfully added.";
        } catch (PDOException $e) {
            $errorMessage = "Error adding product: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="admin_panel.php" class="text-green-600 hover:text-green-500 font-bold text-lg">
                ← Back to Admin Panel
            </a>
            <h1 class="text-2xl font-bold text-gray-700">Add Product</h1>
        </div>
    </header>

    <!-- Product Addition Form -->
    <main class="container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-700">Product Addition Form</h2>
            
            <!-- Error or Success Messages -->
            <?php if (isset($errorMessage)): ?>
                <p class="text-red-500 text-sm text-center mb-4"><?php echo htmlspecialchars($errorMessage); ?></p>
            <?php endif; ?>
            <?php if (isset($successMessage)): ?>
                <p class="text-green-500 text-sm text-center mb-4"><?php echo htmlspecialchars($successMessage); ?></p>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST" class="space-y-4">
                <!-- Product Name -->
                <div>
                    <label for="nazev" class="block text-sm font-medium text-gray-700">Product Name:</label>
                    <input type="text" id="nazev" name="nazev" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Price -->
                <div>
                    <label for="cena" class="block text-sm font-medium text-gray-700">Price:</label>
                    <input type="number" step="0.01" id="cena" name="cena" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Weight -->
                <div>
                    <label for="hmotnost" class="block text-sm font-medium text-gray-700">Weight (kg):</label>
                    <input type="number" step="0.001" id="hmotnost" name="hmotnost" required 
                           class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition">
                        Add Product
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
