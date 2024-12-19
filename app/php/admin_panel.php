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

// Fetch data from the users table
$stmt = $pdo->query("SELECT * FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch data from the Zbozi table
$stmt = $pdo->query("SELECT * FROM Zbozi");
$zbozi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white shadow-md relative z-10">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="../php/indexUser.php">
                <img src="../images/logoo.png" alt="MixMart Logo" class="h-12 w-auto">
            </a>
            <h1 class="text-2xl font-bold text-green-600">Admin Panel</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        <!-- Users Table -->
        <section class="mb-12 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-700">Users</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">First Name</th>
                            <th class="p-2">Last Name</th>
                            <th class="p-2">Email</th>
                            <th class="p-2">Phone Number</th>
                            <th class="p-2">Role</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition">
                            <td class="p-2 text-center"><?php echo htmlspecialchars($user['id']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['first_name']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['last_name']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($user['phone']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($user['role']); ?></td>
                            <td class="p-2 text-center">
                                <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                                <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Products Table -->
        <section class="mb-12 bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-gray-700">Products</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300 text-sm">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="p-2">ID</th>
                            <th class="p-2">Name</th>
                            <th class="p-2">Price</th>
                            <th class="p-2">Weight</th>
                            <th class="p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($zbozi as $item): ?>
                        <tr class="odd:bg-gray-100 even:bg-white hover:bg-gray-200 transition">
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['ID']); ?></td>
                            <td class="p-2"><?php echo htmlspecialchars($item['Nazev']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['Cena']); ?></td>
                            <td class="p-2 text-center"><?php echo htmlspecialchars($item['Hmotnost']); ?></td>
                            <td class="p-2 text-center">
                                <a href="edit_item.php?id=<?php echo $item['ID']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a> |
                                <a href="delete_item.php?id=<?php echo $item['ID']; ?>" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Add Links -->
        <div class="flex justify-center gap-6 mt-6">
            <a href="add_user.php" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition">Add User</a>
            <a href="add_item.php" class="bg-green-500 text-white px-4 py-2 rounded-full hover:bg-green-600 transition">Add Product</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-8">
        <div class="container mx-auto px-4 py-6 text-center text-gray-600">
            <p>© 2024 MixMart. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
