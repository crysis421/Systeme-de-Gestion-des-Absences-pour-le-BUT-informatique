<?php
use Model\NewJustificatif;
require_once '../Model/NewJustificatif.php';
session_start();

// Vérifie que les données de session existent
$data = $_SESSION['formData'] ?? null;
if (!$data) {
    die("Aucune donnée de formulaire trouvée. Retournez au formulaire.");
}


$data = $_SESSION['formData'];


$idAbsManager = new  NewJustificatif();

$idAbsence = $idAbsManager->getIdAbsenceParSeance($data['datedebut'],$data['heuredebut'],$data['fin'],$data['heurefin1'],$data['id']);

if(empty($idAbsence)){
    $_SESSION['aEssayer'] = true;
    header('Location: ../Vue/formulaireAbsence.php');
}else{
    echo "<br>";

// 🔹 Informations de base
$idUser = (int)$data['id'];
$cause = htmlspecialchars($data['motif']);
$commentaire = htmlspecialchars($data['commentaire'] ?? '');
$justificatifs = $data['justificatifs'] ?? [];

// 🔹 Initialisation du gestionnaire
$justificatifManager = new NewJustificatif();

    ///hop la on creer un justificatif bb
    $succes = $idAbsManager->creerJustificatif(
            $idAbsence,
            $idUser,
            $cause,
            $commentaire,
            $justificatifs // <-- on insère directement les chemins relatifs
    );

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
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