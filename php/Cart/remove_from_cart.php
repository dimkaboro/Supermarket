<?php
include 'db_connect.php';  // Include the database connection

$cart_item_id = 1;  // ID of the cart item to be removed

// SQL query to remove the item from cart
$query = "DELETE FROM cart_items WHERE cart_item_id = $cart_item_id";
$result = pg_query($conn, $query);  // Execute the query

if (!$result) {
    die("Error removing product from cart: " . pg_last_error());  // Check for errors
} else {
    echo "Product successfully removed from cart!";
}

pg_close($conn);  // Close the database connection
?>
