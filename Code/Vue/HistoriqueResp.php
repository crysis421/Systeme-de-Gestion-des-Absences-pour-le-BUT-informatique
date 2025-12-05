<?php
require_once("../Presentation/responHist.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord historique</title>
</head>
<body>

<!-- TabBar ici ! -->
<?php require('menuHorizontalResp.html'); ?>
<!-- Titre -->
<h1><u>Historique des absences : </u></h1>


<!-- Filtrage ici ! -->
<details id="details">
    <summary class="filtrer">
        <img src="/Image/filter.png" alt="Filtre" class="Filtre" height="24">
        <a class="nom"><b>Filtrer</b></a><br>
    </summary>

    <div class="filtrage">
        <form method="post" id="formFiltre">

            <div class="dateFiltre">
                <?php
                $dateDebut = '2020-01-01';
                $dateFin = date("Y-m-d");
                ?>

                <h3>Date de début</h3>
                <input type="date" id="startDate" name="dateDebut" value="<?= $dateDebut ?>">
                <h3>Date de fin</h3>
                <input type="date" id="endDate" name="dateFin" value="<?= $dateFin ?>">
            </div>

            <div class="matiereFiltre">
                <h3>Nom de la matière</h3>
                <input type="text" id="inputMatiere" name="Matière" value="">
            </div>

            <div class="eleveFiltre">
                <h3>Prénom</h3>
                <input type="text" id="inputPrenom" name="PrenomInput" value="">
                <h3>Nom</h3>
                <input type="text" id="inputNom" name="NomInput" value="">
            </div>


            <input class='bouton-filtrage' type="submit" name="boutonFiltre" value="Filtrer">

        </form>

        <br>
    </div>
</details>


<!-- Historique des absences ici ! -->
<div class="liste-absence">
    <?php foreach ($groupes as $justif):
    $id = $justif['id'];
    $commentaire = $justif['commentaire'];
    $absences = $justif['absences'];
    ?>
    <div class="element">
        <details>
            <summary class="top-layer">
                <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                <div class="description-element">
                    <small><?= htmlspecialchars($justif['description']) ?></small><br>
                    <small>Soumis le <?= htmlspecialchars($justif['datesoumission']) ?></small>
                    <?php if ($justif['reponse']=='accepte') {
                                                $imageClass="histo-accepter";
                                                $img = "/Image/justificatif.jpg";
                                                echo "Justificatif accepté <img class=$imageClass><br/>";

                                                //echo "<img class=$imageClass src=$img> <br/><br/>";
                                            } else {
                                                $imageClass="histo-refuser";
                                                $img = "/Image/justificatif.jpg";

                                                echo "Justificatif refusé <img class=$imageClass><br/>";

                                                //echo "<img class=$imageClass src=$img><br/><br/>";
                                            } ?>
                </div>

                <div class="ligne"></div>
            </summary>

            <div class="details">
                <div class="justificatif-viewer">
                    <details>
                        <summary>
                            <a class="justificatif-texte">Justificatif</a>
                            <img class="oeil" src="/Image/oeil.png" alt="Voir le justificatif">
                        </summary>

                        <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                        <label for="zoom<?= $id ?>" class="zoom-button"></label>

                        <label for="zoom<?= $id ?>" class="justificatif-close">
                            <img src="/Image/close.png" alt="Fermer le justificatif">
                        </label>

                        <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a> <br>

                        <div class="fondu-noir"></div>
                        <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                    </details>
                </div>
                <form method="post">

                    <input type="hidden" name="IDElement" value="<?= $justif['id'] ?>" >

                    <?php foreach ($absences as $abs):

                        $matiere = $abs['matiere'];
                        preg_match('#\((.*?)\)#', $matiere, $match);
                        $matiere = $match[1];
                        $matiere = explode("-", $matiere)[1];

                        $date = $abs['date'];
                        $heure = $abs['heure'];
                        $idAbsence = $abs['id'];
                        $statusAbsence = $abs['status'];?>
                            <label for="checkboxAbsence_<?= $abs['id'] ?>"><?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars(rtrim(substr($abs['heure'],0,5),')'))?> <?= htmlspecialchars($matiere)?></label> <br>
                    <?php endforeach; ?>
                    <div class="ligne2"></div>
                </form>

            </div>

        </details>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>