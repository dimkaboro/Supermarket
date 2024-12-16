<?php
$servername = "localhost";
$username = "user";
$password = "user_password";
$dbname = "supermarket";

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Массив соответствий ID и файлов изображений
$images = [
    1 => "fresh-tomatoes-white.jpg",
    2 => "banana.jpg",
    3 => "bread.png",
    4 => "chicken.png",
    5 => "almond_milk.png",
    6 => "eggs.png",
    7 => "chedar.png",
    8 => "orangejuice.png",
    9 => "spinach.png",
    10 => "salmon.png"
];

// Подготовка SQL-запроса
$stmt = $conn->prepare("UPDATE Zbozi SET Obrazek = ? WHERE ID = ?");

foreach ($images as $id => $filename) {
    // Путь к файлу изображения
    $imagePath = __DIR__ . "/../images/$filename";

    // Проверяем существование файла
    if (file_exists($imagePath)) {
        $imageData = file_get_contents($imagePath);
        // Привязываем данные: изображение и ID
        $stmt->bind_param("si", $imageData, $id);

        if ($stmt->execute()) {
            echo "Image successfully updated for product ID $id<br>";
        } else {
            echo "Error updating image for product ID $id: " . $stmt->error . "<br>";
        }
    } else {
        echo "Image file not found: $imagePath<br>";
    }
}

// Закрытие соединения
$stmt->close();
$conn->close();
?>
