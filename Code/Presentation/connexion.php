<?php

session_start();
require_once __DIR__ . "/../Model/ComptesModel.php";
use Model\ComptesModel;
$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $motdepasse = $_POST['motDePasse'];
    $bdd = new ComptesModel();
    $res = $bdd->connectCompte($email);

    if (password_verify($motdepasse, $res['motdepasse'])) {
        $_SESSION["user"] = $res['idutilisateur'];
        $_SESSION["couleur"] = $res['couleur'];
        $_SESSION["role"] = $res['role'];
//endroit pour rediriger en fonction du role du user
        // ----- SECRETAIRE -----
        if ($res['role'] == 'secretaire') {
            header("Location: ../Vue/Secretaire/formulaireVT.php");
            exit();
            // ----- ELEVE -----
        } else if ($res['role'] == 'eleve') {
            header("Location: ../Vue/Etudiant/tableauDeBordEtu.php");
            exit();

            // ----- RESPONSABLE -----
        } else if ($res['role'] == 'respon') {
            header("Location: ../Vue/Responsable/tableauDeBordResponsable.php");
            exit();
        } else if ($res['role'] == 'prof') {
            header("Location: ../Vue/Professeur/tableauDeBordProf.php");
        }
    } else {
        $_SESSION["erreur"] = "Mot de passe incorrect";
        header("Location: ../Vue/Connexion.php");
        exit();
    }
} else {
    require __DIR__ . '/../Vue/Connexion.php';
}