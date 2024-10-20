<?php
include 'db_connect.php';  // Include the database connection

$cart_id = 1;  // ID of the user's cart
$product_id = 2;  // ID of the product to be added
$quantity = 3;  // Quantity to add

// SQL query to add product to cart
$query = "INSERT INTO cart_items (cart_id, product_id, quantity, added_at) VALUES ($cart_id, $product_id, $quantity, NOW())";
$result = pg_query($conn, $query);  // Execute the query

if (!$result) {
    die("Error adding product to cart: " . pg_last_error());  // Check for errors
} else {
    echo "Product successfully added to cart!";
}

pg_close($conn);  // Close the database connection
?>
