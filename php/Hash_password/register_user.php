<?php
include 'db_connect.php';  // Include the database connection

$username = 'new_user';
$email = 'user@example.com';
$password = 'password123';
$password_hash = password_hash($password, PASSWORD_BCRYPT);  // Hash the password

$first_name = 'John';
$last_name = 'Doe';

// SQL query to insert user information
$query = "INSERT INTO users (username, email, password_hash, first_name, last_name, created_at) VALUES ('$username', '$email', '$password_hash', '$first_name', '$last_name', NOW())";
$result = pg_query($conn, $query);  // Execute the query

if (!$result) {
    die("Error registering user: " . pg_last_error());  // Check for errors
} else {
    echo "User successfully registered!";
}

pg_close($conn);  // Close the database connection
?>
