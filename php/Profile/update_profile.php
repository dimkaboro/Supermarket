<?php
include 'db_connect.php';  // Include the database connection

$user_id = 1;  // ID of the user to be updated
$new_address = '123 Main St, New City';

// SQL query to update user profile
$query = "UPDATE users SET address = '$new_address', updated_at = NOW() WHERE user_id = $user_id";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error updating profile: " . pg_last_error());  // Check for errors
} else {
    echo "Profile successfully updated!";
}

pg_close($conn);
?>
