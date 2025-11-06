<?php
session_start();
require_once '../Model/AbsenceModel.php';

if (!isset($_SESSION['modif'])) {
    die("Aucune donnée trouvée. Veuillez retourner au formulaire.");
}

$data = $_SESSION['modif'];
$email = $data['email'];
$mdp = $data['motDePasse'];

$a = new AbsenceModel();
$message = $a->ModifierMDP($email, $mdp);

echo $message;

// Supprimer la session après utilisation pour la sécurité
unset($_SESSION['modif']);
?>



