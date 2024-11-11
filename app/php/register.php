<?php
// Připojení k databázi přes Docker
$dsn = 'mysql:host=db;dbname=supermarket;charset=utf8'; // Použití názvu kontejneru databáze Docker jako hostitele (db)
$username = 'user';
$password = 'user_password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Připojení k databázi bylo úspěšné!<br>"; // Zpráva pro testování připojení
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}

// Funkce pro očištění zadaných dat
function cleanInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Validace dat z formuláře
$firstName = isset($_POST['first_name']) ? cleanInput($_POST['first_name']) : '';
$lastName = isset($_POST['last_name']) ? cleanInput($_POST['last_name']) : '';
$email = isset($_POST['email']) ? cleanInput($_POST['email']) : '';
$phone = isset($_POST['phone']) ? cleanInput($_POST['phone']) : '';
$gender = isset($_POST['gender']) ? cleanInput($_POST['gender']) : '';
$username = isset($_POST['username']) ? cleanInput($_POST['username']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : ''; // Heslo bude hashováno před uložením

// Kontrola přítomnosti všech povinných polí
if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($gender) || empty($username) || empty($password)) {
    die("Prosím, vyplňte všechna povinná pole.");
}

// Kontrola unikátnosti emailu
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
if ($stmt->fetchColumn() > 0) {
    die("Tento email je již používán. Vyberte jiný.");
}

// Kontrola unikátnosti uživatelského jména
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
if ($stmt->fetchColumn() > 0) {
    die("Toto uživatelské jméno je již používáno. Vyberte jiné.");
}

// Hashování hesla
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Nahrání profilového obrázku
$targetDir = __DIR__ . "/uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$profilePicture = $_FILES['profile_picture'];
$imageFileType = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));

// Kontrola typu souboru
$allowedTypes = ['jpg', 'jpeg', 'png'];
if (!in_array($imageFileType, $allowedTypes)) {
    die("Povolené formáty souborů: JPG, JPEG, PNG.");
}

// Uložení obrázku
$targetFile = $targetDir . uniqid() . "." . $imageFileType;
if (!move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
    die("Chyba při nahrávání profilového obrázku.");
}

// Relativní cesta k obrázku pro uložení do databáze
$relativeTargetFile = "uploads/" . basename($targetFile);

// Uložení dat uživatele do databáze
try {
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, gender, username, password, profile_picture) 
                           VALUES (:first_name, :last_name, :email, :phone, :gender, :username, :password, :profile_picture)");
    $stmt->execute([
        ':first_name' => $firstName,
        ':last_name' => $lastName,
        ':email' => $email,
        ':phone' => $phone,
        ':gender' => $gender,
        ':username' => $username,
        ':password' => $hashedPassword,
        ':profile_picture' => $relativeTargetFile // Uložení relativní cesty k obrázku
    ]);

    echo "Registrace byla úspěšná!";
} catch (PDOException $e) {
    die("Chyba při ukládání dat: " . $e->getMessage());
}
?>