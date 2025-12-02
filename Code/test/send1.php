<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'christekanimangal@gmail.com';
        $mail->Password = 'jyynfnxgjnwubumf';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // EXPÉDITEUR / DESTINATAIRE
        $mail->setFrom('christekanimangal@gmail.com', 'Nom Expéditeur');
        $mail->addAddress('Christian.EkaniManga@uphf.fr'); // où tu reçois le message

        // CONTENU
        $mail->isHTML(true);
        $mail->Subject = "Nouveau message depuis le formulaire";
        $mail->Body    = "
            <h3>Nouveau message de $name</h3>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Message :</strong><br>$message</p>
        ";
        $mail->SMTPOptions = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];


        $mail->send();
        echo "Message envoyé avec succès !";
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode non autorisée.";
}
