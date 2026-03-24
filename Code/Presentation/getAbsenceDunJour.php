<?php


use Model\AbsenceEtuTB;

require __DIR__ . '/../Model/AbsenceEtuTB.php';

if (isset($_POST['jour']) and $_POST['jour'][1] != 'K') {
    $_SESSION['jour'] = $_POST['jour'][0] . $_POST['jour'][1];
}else {
    $_SESSION['jour'] = date('d');
}
if(isset($_POST['mois'])) {
    $_SESSION['mois'] = $_POST['mois'];
    $_SESSION['year'] = $_POST['year'];
    $_SESSION['user'] = $_POST['user'];
}
$user = $_SESSION['user'];

//Notre connection pour nos requêtes
$bdd = new AbsenceEtuTB();

// $result est la variable utilisée pour avoir les absences d'une journée d'un etudiant
$result = $bdd->getAbsenceDunJour($_SESSION['jour'], $user, $_SESSION['mois'], $_SESSION['year'] - 2); //ATTENTION : le -2 est juste là pour nos données qui datent de fevrier 2025
echo json_encode($result);

//Fin de notre connection
$bdd = null;
