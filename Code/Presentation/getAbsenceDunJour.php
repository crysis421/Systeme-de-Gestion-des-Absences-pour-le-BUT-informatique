<?php

require '../Model/AbsenceModel.php';

if(!isset($_SESSION['jour']) or $_SESSION['jour']=="OK" or $_POST['jour']=="OK"){
    $_SESSION['jour'] = date('d');
}else{
    $_SESSION['jour'] = $_POST['jour'];
}
$user = $_SESSION['user'];

//Notre connection pour nos requetes
$bdd = new AbsenceModel();

// $resultat est la variable pour avoir toute les absences sur un mois
$resultat = $bdd->getAbsenceDunMois($_SESSION['user'], $_SESSION['mois'], $_SESSION['year'] - 2);// ATTENTION le -2 pour avoir des données

// $result est la variable utiliser pour avoir les absences d'une journée d'un etudiant
$result = $bdd->getAbsenceDunJour($_SESSION['jour'],$user,$_SESSION['mois'],$_SESSION['year']-2); //ATTENTION : le -2 est juste la pour nos donnée qui date de fevrier 2025

//Fin de notre connection
$bdd = null;

// $couleurDuMois est la variable qui utilise les données de $resultat pour informé a la vue de quelle couleur sont les chiffres dans le calendrier
$couleurDuMois = [];

//Ici on parcours chaque jour du mois pour savoir si oui ou non il y a eu des absences , et si elle sont justifier ou non
//On a un ordre de priorité , le rouge est le plus important et le vert le moins important
for ($i = 0; $i <= 31; $i++) {
    foreach ($resultat as $absence) {
        if ($absence['extract'] == $i) {
            if ($absence['statut'] == 'refus') {
                $couleurDuMois[$i] = $absence['statut'];
            }else if($absence['statut'] == 'enAttente' and $couleurDuMois[$i] != 'refus'){
                $couleurDuMois[$i] = $absence['statut'];
            }else if($couleurDuMois[$i] != 'refus' and $couleurDuMois[$i] != 'enAttente'){
                $couleurDuMois[$i] = $absence['statut'];
            }
        }
    }
}


