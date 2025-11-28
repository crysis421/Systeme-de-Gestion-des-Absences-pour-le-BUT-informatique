<?php
require_once '../test/send.php';
use test\send;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contenu = "<h3>Confirmation de Dépôt de votre justificatif</h3>
                <p>Votre Justificatif a bien été envoyé</p>";
    $mailer = new send();

    $result = $mailer->envoyerMailSendGrid($_POST['email'],'Confirmation de depot de justificatif',$contenu);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>for</title>
    <form action="" method="post">
        <input type="email" name="email"><br>
        <input type="submit" >
    </form>

</head>
<body>

</body>
</html>