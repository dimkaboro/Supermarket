<?php
include 'db_connect.php';  // Include the database connection file

// Execute SQL query to get all products
$query = "SELECT * FROM \"Supermarket\".products"; // Specify the Supermarket schema
$result = pg_query($conn, $query);

// Check if the query executed successfully
if (!$result) {
    die("Error executing query: " . pg_last_error());
}

// Display the list of products
echo "<h2>Product List:</h2>";
while ($row = pg_fetch_assoc($result)) {
    echo "ID: " . $row['product_id'] . " - Name: " . $row['product_name'] . " - Price: " . $row['price'] . "<br>";
}

pg_close($conn); // Close the connection
?>
