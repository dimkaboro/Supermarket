<?php
include 'db_connect.php';  // Include the database connection

$category_id = 1;  // ID of the category to be updated
$new_category_name = 'Updated Category';

$query = "UPDATE categories SET category_name = '$new_category_name' WHERE category_id = $category_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error updating category: " . pg_last_error());
} else {
    echo "Category successfully updated!";
}

pg_close($conn);
?>
