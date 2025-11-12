<?php
use Model\NewJustificatif;
require_once '../Model/NewJustificatif.php';
session_start();



$data = $_SESSION['formData'];


$idAbsManager = new  NewJustificatif();

$idAbsence = $idAbsManager->getIdAbsenceParSeance($data['datedebut'],$data['heuredebut'],$data['fin'],$data['heurefin1'],$data['id']);

if(empty($idAbsence)){
    $_SESSION['aEssayer'] = true;
    header('Location: ../Vue/formulaireAbsence.php');
}else{
    echo "<br>";



$cause = htmlspecialchars($data['motif']); /// jdois encore fix un ou deux truc sur ca
$commentaire = htmlspecialchars($data['commentaire']);

$idUser = $data['id'];
$cheminFichierUploade = $data['justificatif'];

try {


    ///hop la on creer un justificatif bb
    $succes = $idAbsManager->creerJustificatif(
            $idAbsence,
            $idUser,
            $cause,
            $commentaire
    );
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
            header('Location: ../Vue/formulaireAbsence.php');

        } else {
            echo "Erreur lors de la création du justificatif (littéralement)";
        }
        ?>


    </div>

</main>
</body>

</html>
<?php }