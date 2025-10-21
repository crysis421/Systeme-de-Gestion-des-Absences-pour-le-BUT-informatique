<?php

session_start();

// Vérifie que les données existent
if (!isset($_SESSION['formData'])) {
    die("Aucune donnée trouvée. Veuillez retourner au formulaire.");
}

$data = $_SESSION['formData'];
// Inclure la classe Database
require_once '../Model/AbsenceModel.php';


// Récupération des données du formulaire
$dateDebut = $data["datedebut"];
$heureDebut = $data["heuredebut"];
$dateFin  = $data["datefin"];
$heureFin  = $data["heurefin"];

// Création d’une instance du modèle
$a = new AbsenceModel();

// Appel de la méthode
$a->justifierAbsence($dateDebut);

?>


