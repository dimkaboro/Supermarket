<?php
include '../Product/db_connect.php';  // Include the database connection

$query = "SELECT * FROM users";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error fetching users: " . pg_last_error());
}

echo "<h2>All Users:</h2>";
while ($row = pg_fetch_assoc($result)) {
    echo "ID: " . $row['user_id'] . " - Username: " . $row['username'] . " - Email: " . $row['email'] . "<br>";
}

pg_close($conn);
?>
