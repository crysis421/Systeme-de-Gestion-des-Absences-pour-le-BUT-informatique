<?php
session_start();
require_once '../test/send.php';
use test\send;
$nombre = random_int(1, 10000); // génère un entier entre 1 et 100

$mail = $_POST['mail'];

$contenu = "<h3>Réinitialiser votre mot de passe</h3>
                <p>Voici votre code secret : $nombre </p>";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $mailer = new send();
    $result = $mailer->envoyerMailSendGrid($mail,'Mot de passe oublié',$contenu);
    $_SESSION['mdp'] = [
            'mail' => $mail,
            'nombre' => $nombre,
            'result' => $result,
    ];

    if ($result['httpcode'] == 202) {
        header('Location: ../Vue/MDPoublier.php');
        exit();
    }else{
        $_SESSION['erreur'] = 'erreur lors de l\'envoi du mail';
    }

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/connect.css">
    <title>mot de passe oublié</title>
</head>
<body>

<main style="background-color: white">
    <h1 style="text-align: center;">Réinitialiser votre mot de passe</h1>

    <div id="container">
        <form id="form" action="" method="post">
            <label style="display: block; text-align: center; font-size: 20px" for="mail">
                <b>entrer votre adresse mail:</b> <br>
                <br>
                <input type="email" id="mail" name="mail" placeholder="entrer votre adresse mail" style="width :300px" required>
            </label><br>
            <br>
            <div >
                <input type="submit" value="recevoir un mot de passe par mail" >
            </div>
            <br>
            <?php if (!empty($_SESSION['erreur'])): ?>
                <p style="text-align: center; color:red; font-weight:bold"><?php echo $_SESSION['erreur']; ?></p>
                <?php unset($_SESSION['erreur']); ?>
            <?php endif; ?>
        </form>
        <div >
            <a href="Connexion.php"><button>retour</button></a>
        </div>

    </div>
</main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>