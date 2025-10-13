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
    $mail->Host = 'smtp.mailjet.com'; // Serveur SMTP Mailjet
    $mail->SMTPAuth = true;
    $mail->Username = '2a850caaaa2a49c9073dd6344da067cd';  // Remplace par ta clé publique Mailjet
    $mail->Password = '3d770ab2035aa5708ad6c077beb4be2e';  // Remplace par ta clé secrète Mailjet
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    //Recipients
    $mail->setFrom('stievenardkilian@gmail.com', 'test'); // L'email doit être vérifié dans Mailjet
    $mail->addAddress('stievenardkilian@gmail.com');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Justification absence';
    $mail->Body    = $message;

    $mail->send();
    echo 'Message envoyé avec succès !';
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}

