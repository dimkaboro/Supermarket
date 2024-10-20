<?php
include 'db_connect.php';  // Include the database connection

$category_id = 1;  // ID of the category to be deleted

$query = "DELETE FROM categories WHERE category_id = $category_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error deleting category: " . pg_last_error());
} else {
    echo "Category successfully deleted!";
}

pg_close($conn);
?>
