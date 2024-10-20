<?php
include 'db_connect.php';  // Include the database connection file

// Values for adding a new product
$name = 'New Product';
$price = 150.00;

// Execute SQL query to add a new product
$query = "INSERT INTO \"Supermarket\".products (product_name, price) VALUES ('$name', $price)"; // Specify the Supermarket schema
$result = pg_query($conn, $query);

// Check if the query executed successfully
if (!$result) {
    die("Error adding product: " . pg_last_error());
} else {
    echo "Product added successfully!";
}

pg_close($conn); // Close the connection
?>
