<?php
session_start();
require_once '../Model/AbsenceModel.php';

if (!isset($_POST['motDePasse'])) {
    die("Aucune donnée trouvée. Veuillez retourner au formulaire.");
}

$email = $_POST['email'];
$mdp = $_POST['motDePasse'];

$a = new AbsenceModel();
$message = $a->ModifierMDP($email, $mdp);

header('Location: ../Vue/connexionEtudiant.php');

?>



