<?php
$dsn = 'mysql:host=sql310.infinityfree.com;dbname=if0_37950136_supermarket;charset=utf8';
$db_username = 'if0_37950136';
$db_password = 'tGgX9jy15tX1VF';


try {
    $pdo = new PDO($dsn, $db_username, $db_password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "Connected successfully";
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}
