<?php
include 'db_connect.php';  // Include the database connection

$search_term = 'example';  // Assuming the search term is 'example'
$query = "SELECT * FROM products WHERE product_name ILIKE '%$search_term%' OR decription ILIKE '%$search_term%'";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error executing search query: " . pg_last_error());
}

echo "<h2>Search Results:</h2>";
while ($row = pg_fetch_assoc($result)) {
    echo "ID: " . $row['product_id'] . " - Name: " . $row['product_name'] . " - Price: " . $row['price'] . "<br>";
}

pg_close($conn);
?>
