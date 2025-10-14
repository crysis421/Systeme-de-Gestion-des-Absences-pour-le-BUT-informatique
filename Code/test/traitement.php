<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-master/src/Exception.php';
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';

// Récupération des données du formulaire
$nom = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Serveur SMTP Mailjet
    $mail->SMTPAuth = true;
    $mail->Username = 'christekanimanga@gmail.com';  // Remplace par ta clé publique Mailjet
    $mail->Password = 'lkkrvhmqecifvxvc';  // Remplace par ta clé secrète Mailjet
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('christekanimanga@gmail.com', 'test'); // L'email doit être vérifié dans Mailjet
    $mail->addAddress('christekanimanga@gmail.com');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Justification absence';
    $mail->Body    = "Nom : $nom<br>Email : $email<br>Message : $message";

    $mail->send();
    echo 'Message envoyé avec succès !';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}

