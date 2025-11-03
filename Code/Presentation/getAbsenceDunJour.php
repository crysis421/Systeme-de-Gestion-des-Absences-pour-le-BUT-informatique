<?php


use Model\AbsenceEtuTB;

require '../Model/AbsenceEtuTB.php';

if (isset($_POST['jour']) and $_POST['jour'][1] != 'K') {
    $_SESSION['jour'] = $_POST['jour'][1] . $_POST['jour'][2];
}else {
    $_SESSION['jour'] = date('d');
}
$user = $_SESSION['user'];

//Notre connection pour nos requetes
$bdd = new AbsenceEtuTB();


// $resultat est la variable pour avoir toutes les absences sur un mois
$resultat = $bdd->getAbsenceDunMois($_SESSION['user'], $_SESSION['mois'], $_SESSION['year'] - 2);// ATTENTION le -2 pour avoir des données

// $result est la variable utilisée pour avoir les absences d'une journée d'un etudiant
$result = $bdd->getAbsenceDunJour($_SESSION['jour'], $user, $_SESSION['mois'], $_SESSION['year'] - 2); //ATTENTION : le -2 est juste là pour nos données qui datent de fevrier 2025

//Fin de notre connection
$bdd = null;

// $couleurDuMois est la variable qui utilise les données de $resultat pour informer à la vue de quelle couleur sont les chiffres dans le calendrier
$couleurDuMois = [];
// $couleurDuMois est la variable qui utilise les données de $resultat pour informer à la vue s'il y a des interrogations dans le calendrier
$interrogationDuMois = [];

//Ici, on parcourt chaque jour du mois pour savoir si oui ou non, il y a eu des absences, et si elles sont justifiées ou non
//On a un ordre de priorité, le rouge est le plus important et le vert le moins important.
for ($i = 0; $i <= 31; $i++) {
    foreach ($resultat as $absence) {
        if ($absence['extract'] == $i) {
            //Les couleurs
            if ($absence['statut'] == 'refus') {
                $couleurDuMois[$i] = $absence['statut'];
            } else if ($absence['statut'] == 'enAttente' and $couleurDuMois[$i] != 'refus') {
                $couleurDuMois[$i] = $absence['statut'];
            } else if ($couleurDuMois[$i] != 'refus' and $couleurDuMois[$i] != 'enAttente') {
                $couleurDuMois[$i] = $absence['statut'];
            }
            //Les Interrogations
            $interrogationDuMois[$i] = $absence['controle'];

        }
    }
}


