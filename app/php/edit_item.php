<?php
session_start();

// Check if the user is an administrator
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: authorization.html"); // If not admin, redirect to authorization page
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

// Check if the product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Product ID not specified.");
}

$itemId = intval($_GET['id']);

// Fetch product data from the database
try {
    $stmt = $pdo->prepare("SELECT * FROM Zbozi WHERE ID = :id");
    $stmt->execute(['id' => $itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        die("Error: No product found with the specified ID.");
    }
} catch (PDOException $e) {
    die("Error while fetching product data: " . $e->getMessage());
}

// Update product data if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nazev = isset($_POST['nazev']) ? htmlspecialchars(trim($_POST['nazev'])) : '';
    $cena = isset($_POST['cena']) ? floatval($_POST['cena']) : 0;
    $hmotnost = isset($_POST['hmotnost']) ? floatval($_POST['hmotnost']) : 0;

    // Check if all fields are filled
    if (empty($nazev) || $cena <= 0 || $hmotnost <= 0) {
        $errorMessage = "Error: Please fill in all fields.";
    } else {
        // Update product data in the database
        try {
            $stmt = $pdo->prepare("UPDATE Zbozi SET 
                Nazev = :nazev, 
                Cena = :cena, 
                Hmotnost = :hmotnost 
                WHERE ID = :id");

            $stmt->execute([
                'nazev' => $nazev,
                'cena' => $cena,
                'hmotnost' => $hmotnost,
                'id' => $itemId
            ]);

            $successMessage = "Product data successfully updated.";
        } catch (PDOException $e) {
            $errorMessage = "Error while updating product data: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

    <!-- Main Container -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Edit Product</h1>

        <!-- Links -->
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
            <!-- Product Name -->
            <div>
                <label for="nazev" class="block text-gray-700 font-medium">Product Name:</label>
                <input type="text" id="nazev" name="nazev" 
                       value="<?php echo htmlspecialchars($item['Nazev']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Price -->
            <div>
                <label for="cena" class="block text-gray-700 font-medium">Price:</label>
                <input type="number" step="0.01" id="cena" name="cena" 
                       value="<?php echo htmlspecialchars($item['Cena']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
            </div>

            <!-- Weight -->
            <div>
                <label for="hmotnost" class="block text-gray-700 font-medium">Weight (kg):</label>
                <input type="number" step="0.001" id="hmotnost" name="hmotnost" 
                       value="<?php echo htmlspecialchars($item['Hmotnost']); ?>" 
                       class="w-full px-4 py-2 rounded-md border border-gray-300 focus:ring focus:ring-green-300 focus:outline-none"
                       required>
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
