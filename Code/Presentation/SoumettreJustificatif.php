<?php

use Model\NewJustificatif;

session_start();



require_once '../Model/NewJustificatif.php';
require '../Vue/formulaireAbsence.php';

//$idAbsence = filter_input(INPUT_POST, 'id_absence', FILTER_VALIDATE_INT);
//$cause = htmlspecialchars($_POST['motif']);
//$commentaire = htmlspecialchars($_POST['commentaire']);
//$idUtilisateur = $_SESSION['id_utilisateur']; // L'ID de l'étudiant qui soumet

$cheminFichierUploade = null; // Par defaut aucun fichier


///pour test pas touche !!!

$commentaire = 'a';
$idUtilisateur = 3;
$motif = 'malade';
$idAbsence = '10';
$cause = 'malade';




/// On regarde si un fichier a été soumis et s'il n'y a pas eu d'erreur
if (isset($_FILES['justificatif']) && $_FILES['justificatif']['error'] === UPLOAD_ERR_OK) {

    $uploadDir = 'uploads/justificatifs/';

    $fileInfo = pathinfo($_FILES['justificatif']['name']);
    $extension = $fileInfo['extension'];
    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'pdf'];

    if (in_array(strtolower($extension), $extensionsAutorisees)) {
        $nomFichierUnique = $idUtilisateur . '_' . $idAbsence . '_' . time() . '.' . $extension;
        $cheminFichierUploade = $uploadDir . $nomFichierUnique;


    }
}

try {
    $justificatifManager = new NewJustificatif();

    $succes = $justificatifManager->creerJustificatif(
        $idAbsence,
        $idUtilisateur,
        $cause,
        $commentaire,
        $cheminFichierUploade
    );

} catch (PDOException $e) {

    exit;
}



?>



<p>allo</p>
<a href="https://pokemondb.net/pokedex/reshiram"><img src="https://img.pokemondb.net/sprites/black-white/normal/reshiram.png" alt="Reshiram"></a>

