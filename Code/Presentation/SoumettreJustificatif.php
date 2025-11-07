<?php
use Model\NewJustificatif;
require_once '../Model/NewJustificatif.php';
session_start();



$data = $_SESSION['formData'];
///$idUtilisateur = (int)$_SESSION['user']; // L'ID de l'étudiant
///$idUtilisateur = 42049956;

$idAbsManager = new  NewJustificatif();


$idAbsence = 1;
///$idAbsence = $idAbsManager->getIdAbsenceParSeance($data['datedebut'],($data['heuredebut']),($idUtilisateur)); // guys jsp comment recup l'id de labsence encore mais ca arrive

$cause = htmlspecialchars($data['motif']); /// jdois encore fix un ou deux truc sur ca
$commentaire = htmlspecialchars($data['commentaire']);

$idUser = $data['id'];
$cheminFichierUploade = $data['justificatif'];




try {
    $justificatifManager = new NewJustificatif();

    ///hop la on creer un justificatif bb
    $succes = $justificatifManager->creerJustificatif(
            $idAbsence,
            $idUser,
            $cause,
            $commentaire
    );



    ///c'est la ou ca me clcllclsckdlsmc,dkscs merde

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>Formulaire d'absence</title>
</head>
<body>
<header>
    <?php require '../Vue/menuHorizontalEtu.html'; ?>
</header>
<main>
    <div id="titre">
        <?php if ($succes !== false) {
            echo "Justificatif envoyé avec succès !";
            unset($_SESSION['formData']);

        } else {
            echo "Erreur lors de la création du justificatif (littéralement)";
        }
        ?>


    </div>

</main>
</body>

</html>