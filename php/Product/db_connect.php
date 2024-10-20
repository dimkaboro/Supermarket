<?php

$host = 'localhost';        
$dbname = 'supermarket';    
$user = 'postgres';         
$password = 'Nazar1508';


$conn = pg_connect("host=$host dbname=$dbname user=$user password=$password");


if (!$conn) {
    die("Ошибка подключения к базе данных: " . pg_last_error());
} else {
    echo "Успешное подключение к базе данных!<br>";
}
?>
