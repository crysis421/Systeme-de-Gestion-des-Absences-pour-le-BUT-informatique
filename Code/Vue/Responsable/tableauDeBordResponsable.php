<?php
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}
require_once __DIR__ . "/../../Presentation/responTB.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/tableauDeBordResponsable/tableauDeBordResponsable.css?v=1">
    <link rel="stylesheet" href="../../CSS/tableauDeBordResponsable/tableauDeBordResponsable<?=$_SESSION['couleur']?>.css?v=1">
    <script src="../../ajax/ajaxFiltre.js"></script>
    <title>Tableau de bord absence</title>
</head>
<body>
<!-- TabBar ici ! -->
<?php require('menuHorizontalResp.php'); ?>
<!-- Notification ici ! -->
<?php
if ($titre != "" && $description != "") {
    echo <<<EOL
<div class="notification">
    <h4 class="titre-notification">$titre</h4>
    <a class="description-notification">Avec le motif "$description"</a>
</div>
EOL;
}
?>
<!-- Titre -->
<h1><u>Liste des absences à traiter : </u></h1>
<!-- Filtrage ici ! -->
<details id="details">
    <summary class="filtrer">
        <img src="/Image/filter.webp" alt="Filtre" class="Filtre" height="24">
        <a class="filtre-titre"><b>Filtrer</b></a><br>
    </summary>
    <div class="filtrage">
        <form id="formFiltre">
            <div class="dateFiltre">
                <h3>Date de début</h3>
                <input type="date" id="startDate" name="dateDebut" value="2020-01-01">
                <h3>Date de fin</h3>
                <input type="date" id="endDate" name="dateFin" value="<?= date("Y-m-d") ?>">
            </div>
            <div class="eleveFiltre">
                <h3>Prénom</h3>
                <input type="text" id="inputPrenom" name="PrenomInput">
                <h3>Nom</h3>
                <input type="text" id="inputNom" name="NomInput">
            </div>
            <div class="matiereFiltre">
                <h3>Nom de la matière</h3>
                <input type="text" id="inputMatiere" name="Matière">
            </div>
            <input class='bouton-filtrage' type="submit" value="Filtrer">
        </form>
        <br>
    </div>
</details>
<!-- Liste des absences ici ! -->
<div class="liste-absence" id="conteneurAbsences">
    <?php include "liste_absences_partielle.php";?>
</div>
<br><br>
<h1><u>Justificatifs redemandés :</u></h1>
<!-- Liste des absences ici ! -->
<div class="liste-absence-demandes">
    <?php foreach ($groupesDemandes as $justif):
        $id = $justif['id'];
        $commentaire = $justif['commentaire'];
        $absences = $justif['absences'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.webp" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['description']) ?></small>
                    </div>
                    <div class="ligne"></div>
                </summary>
                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="/Image/oeil.webp" alt="Voir le justificatif">
                            </summary>
                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="/Image/close.webp" alt="Fermer le justificatif">
                            </label>
                            <br>
                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.webp" alt="Justificatif">
                        </details>
                        <?php foreach ($absences as $abs):
                            $matiere = $abs['matiere'];
                            preg_match('#\((.*?)\)#', $matiere, $match);
                            $matiere = $match[1];
                            $matiere = explode("-", $matiere)[1];

                            $date = $abs['date'];
                            $heure = $abs['heure'];
                            $idAbsence = $abs['id'];
                            ?>
                            <a> - <?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars(rtrim(substr($abs['heure'],0,5),')'))?> <?= htmlspecialchars($matiere)?></a> <br>
                        <?php endforeach; ?>
                    </div>
                    <div class="ligne2"></div>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>