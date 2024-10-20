<?php
include '../Product/db_connect.php';  // Include the database connection

$query = "SELECT * FROM orders";
$result = pg_query($conn, $query);

if (!$result) {
    die("Error fetching orders: " . pg_last_error());
}

echo "<h2>All Orders:</h2>";
while ($row = pg_fetch_assoc($result)) {
    echo "Order ID: " . $row['order_id'] . " - User ID: " . $row['user_id'] . " - Total Amount: " . $row['total_amount'] . " - Status: " . $row['order_status'] . "<br>";
}

pg_close($conn);
?>
