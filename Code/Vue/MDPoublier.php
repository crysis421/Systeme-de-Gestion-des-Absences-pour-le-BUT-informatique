<?php
session_start();
if (!isset($_SESSION['mdp'])) {
    header("Location: MDPoublierEtudiant.php");
    exit();
}
$data = $_SESSION['mdp'];
$code = $data['nombre'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($code == $_POST['code']){
        header("Location: modifierMDP.php");
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
                <b>entrer votre Code secret reçu dans votre boite mail  :</b> <br>
                <br>
                <input type="number"  name="code"  style="width :300px" required>
            </label><br>
            <br>
            <div >
                <input type="submit" value="valider" >
            </div>
        </form>
        <div >
            <a href="MDPoublierEtudiant.php"><button>retour</button></a>
        </div>

    </div>
</main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>