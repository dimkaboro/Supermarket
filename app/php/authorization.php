<?php
// Zahájení relace před jakýmkoli výstupem na obrazovku
session_start();

// Kontrola, že skript authorization.php byl spuštěn správně
echo "Skript authorization.php byl spuštěn!<br>";

// Připojení k databázi
$dsn = 'mysql:host=db;dbname=supermarket;charset=utf8'; 
$username = 'user';
$password = 'user_password';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Připojení k databázi bylo úspěšné!<br>";
} catch (PDOException $e) {
    die("Chyba připojení k databázi: " . $e->getMessage());
}

// Kontrola dat z formuláře
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "Formulář byl odeslán!<br>";

    // Očištění dat
    $usernameOrEmail = isset($_POST['identifier']) ? htmlspecialchars(trim($_POST['identifier'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (empty($usernameOrEmail) || empty($password)) {
        die("Chyba: Prosím, vyplňte všechna povinná pole.");
    }

    echo "Data z formuláře byla přijata. Kontrola uživatele v databázi...<br>";

    // Kontrola uživatele v databázi
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $usernameOrEmail, 'email' => $usernameOrEmail]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                // Autorizace úspěšná
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['first_name'] = $user['first_name'];
                echo "Autorizace úspěšná! Vítejte, " . htmlspecialchars($user['first_name']) . "!";
                echo "<br>Úspěšně jste se přihlásili do svého účtu.";
            } else {
                // Nesprávné heslo
                die("Chyba: Nesprávné heslo.");
            }
        } else {
            // Uživatel nenalezen
            die("Chyba: Uživatel s tímto jménem nebo emailem nebyl nalezen.");
        }
    } catch (PDOException $e) {
        die("Chyba při kontrole dat: " . $e->getMessage());
    }
} else {
    echo "Data z formuláře nebyla odeslána!";
}
?>