<?php
include 'db_connect.php';  // Include the database connection

$sender_id = 1;  // ID of the sender
$receiver_id = 2;  // ID of the receiver
$content = 'Hello, how are you?';
$encrypted_body = base64_encode($content);  // Encrypt the message content

// SQL query to insert the message
$query = "INSERT INTO message (sender_id, reciever_id, content, encrypted_body, sent_at, is_read) VALUES ($sender_id, $receiver_id, '$content', '$encrypted_body', NOW(), false)";
$result = pg_query($conn, $query);  // Execute the query

if (!$result) {
    die("Error sending message: " . pg_last_error());  // Check for errors
} else {
    echo "Message successfully sent!";
}

pg_close($conn);  // Close the database connection
?>
