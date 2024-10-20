<?php
include 'db_connect.php';  // Include the database connection file

// Values for updating the product with ID 1
$product_id = 1;
$new_price = 200.00;

// Execute SQL query to update the product
$query = "UPDATE \"Supermarket\".products SET price = $new_price WHERE product_id = $product_id"; // Specify the Supermarket schema
$result = pg_query($conn, $query);

// Check if the query executed successfully
if (!$result) {
    die("Error updating product: " . pg_last_error());
} else {
    echo "Product price updated successfully!";
}

pg_close($conn); // Close the connection
?>
