<?php
include 'db_connect.php';  // Include the database connection file

// Value of the product ID to be deleted
$product_id = 1;

// Execute SQL query to delete the product
$query = "DELETE FROM \"Supermarket\".products WHERE product_id = $product_id"; // Specify the Supermarket schema
$result = pg_query($conn, $query);

// Check if the query executed successfully
if (!$result) {
    die("Error deleting product: " . pg_last_error());
} else {
    echo "Product deleted successfully!";
}

pg_close($conn); // Close the connection
?>
