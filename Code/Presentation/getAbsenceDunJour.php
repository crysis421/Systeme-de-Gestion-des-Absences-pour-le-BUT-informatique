<?php

require '../Model/AbsenceModel.php';

echo '<link rel="stylesheet" href="../Vue/jolan/assemblage/Main.css">';

if(!isset($_SESSION['jour']) or $_SESSION['jour']=="OK" or $_POST['jour']=="OK"){
    $_SESSION['jour'] = date('d');
}else{
    $_SESSION['jour'] = $_POST['jour'];
}
$user = $_SESSION['user'];


$bdd = new AbsenceModel();
$resultat = $bdd->getAbsenceDunMois($_SESSION['user'], $_SESSION['mois'], $_SESSION['year'] - 2);// ATTENTION le -2 pour avoir des données

$couleurDuMois = [];

for ($i = 0; $i <= 31; $i++) {
    foreach ($resultat as $absence) {
        if ($absence['extract'] == $i) {
            if ($couleurDuMois[$i] != 'valide') {
                $couleurDuMois[$i] = $absence['statut'];
            }
        }
    }
}
$result = $bdd->getAbsenceDunJour($_SESSION['jour'],$user,$_SESSION['mois'],$_SESSION['year']-2); //ATTENTION : le -2 est juste la pour nos donnée qui date de fevrier 2025


