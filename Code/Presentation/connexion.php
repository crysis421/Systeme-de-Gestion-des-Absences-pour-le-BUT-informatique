<?php
session_start();
require_once "../Model/ComptesModel.php";

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motdepasse = $_POST['motDePasse'];

    $bdd = new ComptesModel();

    $res = $bdd->connectCompte($email);

    if (password_verify($motdepasse, $res['motdepasse'])) {
        $_SESSION["user"] = $res['idutilisateur'];
//endroit pour rediriger en fonction du role du user
        // ----- SECRETAIRE -----
        if ($res['role'] == 'secretaire') {
            header("Location: ../Vue/formulaireVT.php");
            exit();
            // ----- ELEVE -----
        } else if ($res['role'] == 'eleve') {
            header("Location: ../Vue/tableauDeBordEtu.php");
            exit();

            // ----- RESPONSABLE -----
        } else if ($res['role'] == 'respon') {
            header("Location: ../Vue/tableauDeBordResponsable.php");
            exit();
        }else if ($res['role'] == 'prof') {
            header("Location: ../Vue/tableauDeBordProf.php");
        }
    } else {
        $_SESSION["erreur"] = "Mot de passe incorrect";
        header("Location: ../Vue/Connexion.php");
        exit();
    }
}
else {
        require '../Vue/Connexion.php';
    }