<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name    = htmlspecialchars($_POST['name']);
    $email   = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);

    try {
        // CONFIG SMTP (exemple Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'ton_email@gmail.com';
        $mail->Password   = 'mot_de_passe_application';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // EXPÉDITEUR / DESTINATAIRE
        $mail->setFrom($email, $name);
        $mail->addAddress('ton_email@gmail.com'); // où tu reçois le message

        // CONTENU
        $mail->isHTML(true);
        $mail->Subject = "Nouveau message depuis le formulaire";
        $mail->Body    = "
            <h3>Nouveau message de $name</h3>
            <p><strong>Email :</strong> $email</p>
            <p><strong>Message :</strong><br>$message</p>
        ";
        $mail->AltBody = strip_tags($message);

        $mail->send();
        echo "Message envoyé avec succès !";
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
} else {
    echo "Méthode non autorisée.";
}
