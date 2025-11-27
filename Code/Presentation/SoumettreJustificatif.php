<?php

use Model\NewJustificatif;

require_once '../Model/NewJustificatif.php';
require_once '../test/send.php';
require_once '../Model/AbsenceModel.php';

use test\send;

session_start();

// Vérifie que les données de session existent
$data = $_SESSION['formData'] ?? null;
if (!$data) {
    die("Aucune donnée de formulaire trouvée. Retournez au formulaire.");
}

/// aller chercher la petite session
$data = $_SESSION['formData'];

//Informations de base
$idUser = (int)$data['id'];
$cause = htmlspecialchars($data['motif']);
$commentaire = htmlspecialchars($data['commentaire'] ?? '');
$justificatifs = $data['justificatifs'] ?? [];

$contenu = "<h3>Confirmation de Dépôt de votre justificatif</h3>
                <p>Votre Justificatif a bien été envoyé</p>";

/// commencer une instance de justificatif
$idAbsManager = new  NewJustificatif();

$mail = $idAbsManager->getEmailbyUser($data['id']);

///aller chercher les absences concernees
$idAbsence = $idAbsManager->getIdAbsenceParSeance($data['datedebut'], $data['heuredebut'], $data['fin'], $data['heurefin1'], $data['id']);

if (empty($idAbsence)) {
    $idAbsence = null;
    $_SESSION['aEssayer'] = true;
    header('Location: ../Vue/formulaireAbsence.php');
}
//Initialisation du gestionnaire

try {
    ///hop la on creer le justificatif bb (une fois que tout est mis en place)
    $succes = $idAbsManager->creerJustificatif(
            $idAbsence,
            $idUser,
            $cause,
            $commentaire,
            $justificatifs // on insère directement les chemins relatifs !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    );
//sinn erreur et tt.....
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
}
?>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css"/>
    <title>Formulaire d'absence</title>
</head>
<body>
<header>
    <?php require '../Vue/menuHorizontalEtu.html'; ?>
</header>
<main>
    <div id="titre">
        <?php
        if ($succes !== false) {
            $mailer = new send();

            $result = $mailer->envoyerMailSendGrid($mail, 'Confirmation de depot de justificatif', $contenu);
            echo "Justificatif envoyé avec succès !";

            unset($_SESSION['formData']);
            ?>
        <form action="../Vue/tableauDeBordEtu.php">
            <input type="submit" value="OK">
        </form>
        <?php

        } else {
            echo "Erreur lors de la création du justificatif (littéralement)";
            ?>
            <form action="../Vue/tableauDeBordEtu.php">
                <input type="submit" value="OK">
            </form>
        <?php
        }
        ?>


    </div>

</main>
</body>