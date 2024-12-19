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

// Check if the product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Product ID not specified.");
}

$itemId = intval($_GET['id']);

// Check if the product exists in the database
try {
    $stmt = $pdo->prepare("SELECT * FROM Zbozi WHERE ID = :id");
    $stmt->execute(['id' => $itemId]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        die("Error: No product found with the specified ID.");
    }
} catch (PDOException $e) {
    die("Error while verifying product: " . $e->getMessage());
}

// If the user confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Delete the product from the database
        $stmt = $pdo->prepare("DELETE FROM Zbozi WHERE ID = :id");
        $stmt->execute(['id' => $itemId]);

        // Redirect to the admin panel with a success message
        header("Location: admin_panel.php?message=Product successfully deleted.");
        exit();
    } catch (PDOException $e) {
        die("Error while deleting product: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Product</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

    <!-- Main Container -->
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-md w-full text-center">
        <h1 class="text-2xl font-bold text-red-600 mb-6">Delete Product</h1>
        
        <!-- Confirmation Message -->
        <p class="text-gray-700 mb-6">
            Are you sure you want to delete the product 
            <strong class="text-gray-900">
                <?php echo htmlspecialchars($item['Nazev']); ?>
            </strong>?
        </p>

        <!-- Form with Buttons -->
        <form method="POST" class="flex flex-col gap-4">
            <!-- Delete Confirmation Button -->
            <button type="submit" 
                    class="w-full bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 transition">
                Yes, Delete
            </button>
            <!-- Cancel Button -->
            <a href="admin_panel.php" 
               class="w-full bg-gray-200 text-gray-700 py-2 px-4 rounded-md hover:bg-gray-300 transition">
                No, Return to Admin Panel
            </a>
        </form>
    </div>

</body>
</html>
