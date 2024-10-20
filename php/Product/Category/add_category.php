<?php
include 'db_connect.php';  // Include the database connection

$category_name = 'New Category';
$super_category_id = 0;  // Assuming 0 for no super category

$query = "INSERT INTO categories (category_name, super_category_id) VALUES ('$category_name', $super_category_id)";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error adding category: " . pg_last_error());
} else {
    echo "Category successfully added!";
}

pg_close($conn);
?>
