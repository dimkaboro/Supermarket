<?php
include '../Product/db_connect.php';  // Include the database connection

$user_id = $_GET['user_id'];  // Assuming user ID is passed as a query parameter

$query = "SELECT * FROM users WHERE user_id = $user_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error fetching user profile: " . pg_last_error());
}

$user = pg_fetch_assoc($result);

echo "<h2>User Profile</h2>";
echo "Username: " . $user['username'] . "<br>";
echo "Email: " . $user['email'] . "<br>";
echo "First Name: " . $user['first_name'] . "<br>";
echo "Last Name: " . $user['last_name'] . "<br>";
echo "Address: " . $user['address'] . "<br>";
echo "Phone Number: " . $user['phone_number'] . "<br>";

pg_close($conn);
?>
