<?php
if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $filename = basename($_FILES['image']['name']);
    $target_file = $upload_dir . $filename;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
        echo "File uploaded successfully.";
        
        // Add image path to the database
        include 'db_connect.php';
        $product_id = 1;  // Assuming we have a product to link the image to
        $query = "INSERT INTO images (filepath, uploaded_at) VALUES ('$target_file', NOW())";
        $result = pg_query($conn, $query);

        if (!$result) {
            die("Error inserting image path to database: " . pg_last_error());
        } else {
            echo "Image path successfully saved to database.";
        }
        
        pg_close($conn);
    } else {
        echo "Error uploading file.";
    }
} else {
    echo "Error with file upload.";
}
?>
 