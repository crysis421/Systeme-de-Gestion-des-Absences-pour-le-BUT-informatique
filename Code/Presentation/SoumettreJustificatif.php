<?php

use Model\NewJustificatif;

session_start();

require '../Model/Connection.php';
require '../Vue/formulaireAbsence.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Accès non autorisé.");
}

$idAbsence = filter_input(INPUT_POST, 'id_absence', FILTER_VALIDATE_INT);
$cause = htmlspecialchars($_POST['motif']);
$commentaire = htmlspecialchars($_POST['commentaire']);
$idUtilisateur = $_SESSION['id_utilisateur']; // L'ID de l'étudiant qui soumet

$cheminFichierUploade = null; // Par défaut, aucun fichier

/// On vérifie si un fichier a été soumis et s'il n'y a pas eu d'erreur
if (isset($_FILES['justificatif']) && $_FILES['justificatif']['error'] === UPLOAD_ERR_OK) {

    $uploadDir = 'uploads/justificatifs/'; /// A CREER GUYS !!

    $fileInfo = pathinfo($_FILES['justificatif']['name']);
    $extension = $fileInfo['extension'];
    $extensionsAutorisees = ['jpg', 'jpeg', 'png', 'pdf'];

    if (in_array(strtolower($extension), $extensionsAutorisees)) {
        $nomFichierUnique = $idUtilisateur . '_' . $idAbsence . '_' . time() . '.' . $extension;
        $cheminFichierUploade = $uploadDir . $nomFichierUnique;

        if (!move_uploaded_file($_FILES['justificatif']['tmp_name'], $cheminFichierUploade)) {
            header('Location: formulaire_justificatif.php?erreur=upload');
            exit;
        }
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

<a href="https://pokemondb.net/pokedex/reshiram"><img src="https://img.pokemondb.net/sprites/black-white/normal/reshiram.png" alt="Reshiram"></a>
