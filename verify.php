<?php
session_start();

// Conectare la baza de date (exemplu folosind PDO)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Eroare de conectare la baza de date: " . $e->getMessage());
}

// Verificăm dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Preluăm codul de verificare introdus de utilizator
    $verificationCode = $_POST['verificationCode'];

    // Preluăm username-ul din sesiune
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];

        // Verificăm dacă codul de verificare introdus corespunde cu cel din baza de date
        $sql = "SELECT * FROM users WHERE username = ? AND verification_code = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $verificationCode]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Codul de verificare este corect, actualizăm starea utilizatorului în baza de date
            $sqlUpdate = "UPDATE users SET verified = 1, verification_code = NULL WHERE username = ?";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$username]);

            // Ștergem variabilele de sesiune folosite pentru verificare
            unset($_SESSION['username']);

            // Redirecționăm utilizatorul către pagina de succes
            header("Location: principala.html");
            exit();
        } else {
            // Codul de verificare introdus nu este valid
            echo "Codul de verificare introdus nu este valid. Te rugăm să încerci din nou.";
        }
    } else {
        // Dacă nu există username în sesiune, redirectăm către pagina de verificare
        echo "No username in session.";
        exit();
    }
} else {
    // Dacă nu este o cerere POST, redirectăm către pagina de verificare
    echo "Invalid request.";
    exit();
}
?>
