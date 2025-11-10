<?php
require_once "../Model/ComptesModel.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motdepasse = $_POST['motDePasse'];

    $bdd = new ComptesModel();

    $res = $bdd->connectCompte($email);

    if (password_verify($motdepasse, $res['motdepasse'])) {
        $_SESSION['user'] = $res['idutilisateur']; //TODO
        if ($res['role'] == 'secretaire') {
            ?>
            <form action="../Vue/tableauDeBordEtu.php">
                Vous êtes connectée en tant qu'étudiant
                <input type="submit" value="OK">
            </form>
            <?php
        } else if ($res['role'] == 'eleve') {
            ?>
            <form action="../Vue/tableauDeBordEtu.php">
                Vous êtes connectée en tant qu'étudiant
                <input type="submit" value="OK">
            </form>
            <?php
        } else if ($res['role'] == 'respon') {
            ?>
            <form action="../Vue/tableauDeBordResponsable.php">
                Vous êtes connectée en tant que responsable
                <input type="submit" value="OK">
            </form>
            <?php
        }
    } else {
        echo 'Mot de passe invalide'; ?>
        <form action="../Vue/Connexion.php" method="post"
              enctype="multipart/form-data">>
            <input type="submit" value="OK" name="OK">
        </form>
        <?php
    }}
else {
        require '../Vue/Connexion.php';
    }