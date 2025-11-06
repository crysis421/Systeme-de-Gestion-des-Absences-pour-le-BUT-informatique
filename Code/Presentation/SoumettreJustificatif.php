<?php
use Model\NewJustificatif;

session_start();



$data = $_SESSION['formData'];
$idUtilisateur = (int)$_SESSION['id_utilisateur']; // L'ID de l'étudiant


$idAbsence = 4963; // guys jsp comment recup l'id de labsence encore mais ca arrive

$cause = htmlspecialchars($data['motif']); /// jdois encore fix un ou deux truc sur ca
$commentaire = htmlspecialchars($data['commentaire']);


$cheminFichierUploade = $data['justificatif'];

require_once '../Model/NewJustificatif.php';

try {
    $justificatifManager = new NewJustificatif();


    ///hop la on creer un justificatif bb
    $succes = $justificatifManager->creerJustificatif(
            $idAbsence,
            $idUtilisateur,
            $cause,
            $commentaire
    );

    if ($succes !== false) {
        echo "Justificatif envoyé avec succès !";
        unset($_SESSION['formData']);

    } else {
        echo "Erreur lors de la création du justificatif (littéralement)";
    }
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

        <a href="https://pokemondb.net/pokedex/reshiram"><img src="https://img.pokemondb.net/sprites/black-white/normal/reshiram.png" alt="Reshiram"></a>

    </div>

</main>
</body>

</html>