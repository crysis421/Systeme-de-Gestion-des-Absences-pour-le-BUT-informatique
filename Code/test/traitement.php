<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Récupération des données du formulaire
$nom = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.uphf.fr';
    $mail->SMTPAuth   = true;
    $mail->Username   = ''; // souvent ton email complet
    $mail->Password   = '';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;


    //Recipients
    $mail->setFrom('', 'ekani'); // L'email doit être vérifié dans Mailjet
    $mail->addAddress('');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Justification absence';
    $mail->Body    = "
        <strong>Nom :</strong> $nom <br>
        <strong>Email :</strong> $email <br>
        <strong>Message :</strong> <p>$message</p>
    ";

    $mail->send();
    echo 'Message envoyé avec succès !';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}
