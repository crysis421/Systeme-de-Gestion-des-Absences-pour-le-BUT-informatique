<?php
require_once '../test/mail.php';
use test\mail;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = $_POST["email"];
    $contenu = "<h3>Confirmation de Dépôt de votre justificatif</h3>
                <p>Votre Justificatif a bien été envoyé</p>";

    $mailer = new mail();

    $result = $mailer->envoyer($mail,'Confirmation de depot de justificatif',$contenu);
    if ($result) {
        echo "<p>Votre mail a été envoyé avec succès !</p>";
    } else {
        echo "<p>Échec de l'envoi du mail.</p>";
    }
}


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de contact</title>
</head>
<body>
<h2>Contactez-nous</h2>
<form action="" method="POST">
    <label>Votre nom :</label><br>
    <input type="text" name="name" required><br><br>

    <label>Votre email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Message :</label><br>
    <textarea name="message" required></textarea><br><br>

    <button type="submit">Envoyer';</button>
</form>
</body>
</html>
