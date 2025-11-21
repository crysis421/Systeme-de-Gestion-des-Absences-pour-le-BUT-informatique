<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // Configurer le serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'christekanimanga@gmail.com';
        $mail->Password = 'egnw xzdg afcq hplz ';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Adresses
        $mail->setFrom('christekanimanga@gmail.com', 'christian');
        $mail->addAddress('christekanimanga@gmail.com', 'christian');
        $mail->addReplyTo($_POST['email'], $_POST['name']);

        // Format et contenu
        $mail->isHTML(true);
        $mail->Subject = 'Nouveau message du formulaire';
        $mail->Body = '<h1>Message du site</h1>'
            . '<p><strong>Nom :</strong> ' . htmlspecialchars($_POST['name']) . '</p>'
            . '<p><strong>Email :</strong> ' . htmlspecialchars($_POST['email']) . '</p>'
            . '<p><strong>Message :</strong> ' . nl2br(htmlspecialchars($_POST['message'])) . '</p>';
        $mail->AltBody = "Nom : {$_POST['name']}\nEmail : {$_POST['email']}\nMessage : {$_POST['message']}";

        $mail->send();
        echo 'Message envoyé avec succès.';
    } catch (Exception $e) {
        echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
    }
}
?>
