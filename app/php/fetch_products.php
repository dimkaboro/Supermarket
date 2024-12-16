<?php
$servername = "localhost";
$username = "user";
$password = "user_password";
$dbname = "supermarket";

// Заголовок JSON
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе данных
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed: " . $conn->connect_error]);
    exit;
}


$sql = "SELECT ID, Nazev, Cena, Hmotnost, Obrazek FROM Zbozi";
$result = $conn->query($sql);

if ($result) {
    $products = [];
    while ($row = $result->fetch_assoc()) {

        $imagePath = !empty($row['Obrazek']) ? "/app/images/" . $row['Obrazek'] : null;

        // Формируем массив
        $products[] = [
            "ID" => $row["ID"],
            "Nazev" => $row["Nazev"],
            "Cena" => $row["Cena"],
            "Hmotnost" => $row["Hmotnost"],
            "ImagePath" => $row["Obrazek"] // Извлекаем путь напрямую из колонки
        ];
    }

    // Возвращаем JSON
    echo json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
