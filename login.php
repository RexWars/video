<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/Exception.php';
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/PHPMailer.php';
require 'C:/Users/BOGDAN/OneDrive/Desktop/PHPMailer-master/src/SMTP.php';

// Funcție pentru generarea unui cod de verificare
function generateVerificationCode($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)))),1,$length);
}

// Conectare la baza de date (exemplu folosind PDO)
try {
    $pdo = new PDO('mysql:host=localhost;dbname=mydatabase', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Eroare de conectare la baza de date: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE username = ? AND email = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $verificationCode = generateVerificationCode();

        $sqlUpdate = "UPDATE users SET verification_code = ? WHERE username = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$verificationCode, $username]);

        session_start();
        $_SESSION['username'] = $username;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'iuoanbogdancioroiu@gmail.com';
            $mail->Password = 'gfzg nqyr qzcy hwhm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('iuoanbogdancioroiu@gmail.com', 'Mindful Support Team');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Cod de Verificare pentru Autentificare';

            $mail->Body = '
            <html>
            <body>
                <h2>Salut, ' . $username . '!</h2>
                <p>Îți mulțumim că folosești Mindful. Pentru a-ți verifica autentificarea, te rugăm să introduci următorul cod de verificare în pagina de verificare:</p>
                <p><strong style="font-size: 18px;">Codul tău de verificare este: <span style="color: #FF5733;">' . $verificationCode . '</span></strong></p>
                <p>Acest cod este valabil pentru o perioadă limitată de timp.</p>
                <p>Dacă nu ai solicitat acest cod, te rugăm să ignori acest email.</p>
                <p>Cu stimă,<br>Mindful Support Team</p>
            </body>
            </html>';

            $mail->send();
            echo 'Emailul de verificare a fost trimis cu succes!';
        } catch (Exception $e) {
            echo "Mesajul tău nu a putut fi trimis. Eroare: {$mail->ErrorInfo}";
        }

        header("Location: verify.html");
        exit();
    } else {
        echo "Nume de utilizator sau email incorect.";
    }
} else {
    echo "Cerere invalidă.";
}
?>
