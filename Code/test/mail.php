<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

class mail
{
    public function __construct() {}

    public function envoyerMail($destinataire, $contenu, $sujet = "Test PHPMailer Gmail") {
        $mail = new PHPMailer(true);

        try {
            // Debug pour voir exactement les erreurs
            $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            // Configuration SMTP Gmail
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'christekanimangal@gmail.com';
            $mail->Password   = 'yflpaacfyqaymqft';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Expéditeur et destinataire
            $mail->setFrom('christekanimangal@gmail.com', 'Nom Expéditeur');
            $mail->addAddress($destinataire);

            // Contenu
            $mail->isHTML(true);
            $mail->Subject = $sujet;
            $mail->Body    = $contenu;


            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->send();
            return true;

        } catch (Exception $e) {
            echo "Erreur PHPMailer : {$mail->ErrorInfo}";
            return false;
        }
    }
}

// Exemple d'utilisation
$destinataire = "Christian.EkaniManga@uphf.fr";
$contenu = "<h1>Bonjour !</h1><p>Voici un test d'envoi de mail.</p>";

$mailer = new mail();
if ($mailer->envoyerMail($destinataire, $contenu)) {
    echo "Mail envoyé avec succès !";
} else {
    echo "Échec de l'envoi du mail.";
}
