<?php
session_start();
require_once __DIR__ . "/../Model/ComptesModel.php";
use Model\ComptesModel;


$bdd = new \Model\ComptesModel();
$c = $_POST['couleur'];
if($_POST['couleur'] == "Orange"){
    $c = "Rouge";
}
$bdd->modifCouleur($c,$_SESSION["user"]);
$_SESSION['couleur'] = $c;
if ($_SESSION['role'] == 'secretaire') {
    header("Location: ../Vue/Secretaire/compteSecretaire.php");
    exit();
    // ----- ELEVE -----
} else if ($_SESSION['role'] == 'eleve') {
    header("Location: ../Vue/Etudiant/CompteEtu.php");
    exit();

    // ----- RESPONSABLE -----
} else if ($_SESSION['role'] == 'respon') {
    header("Location: ../Vue/Responsable/CompteResp.php");
    exit();
} else if ($_SESSION['role'] == 'prof') {
    header("Location: ../Vue/Professeur/CompteProf.php");
}

