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

// Verificăm dacă formularul a fost trimis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Validăm datele (într-un scenariu real ar trebui să facem validări suplimentare)
    $profilePic = $_FILES['profilePic']['name'];
    $targetDir = "uploads/";
    $targetFilePath = $targetDir . $profilePic;
    move_uploaded_file($_FILES['profilePic']['tmp_name'], $targetFilePath);

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $verificationCode = generateVerificationCode();

    session_start();
    $_SESSION['username'] = $username;

    $sql = "INSERT INTO users (username, password, profile_pic, email, verification_code, verified) VALUES (?, ?, ?, ?, ?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $hashedPassword, $targetFilePath, $email, $verificationCode]);

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
        $mail->Subject = 'Cod de Verificare pentru Înregistrare';

        $mail->Body = '
        <html>
        <body>
            <h2>Bun venit la Mindful, ' . $username . '!</h2>
            <p>Îți mulțumim că te-ai înregistrat la Mindful. Pentru a-ți activa contul, te rugăm să introduci următorul cod de verificare în pagina de verificare:</p>
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
}
?>
