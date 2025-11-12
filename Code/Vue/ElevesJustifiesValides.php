<?php

require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = $_POST['PrenomInput'] ?? null;
$nom = $_POST['NomInput'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["boutonFiltre"])) {
    if (!empty($dateDebut) || !empty($dateFin) || !empty($matiere) || !empty($prenom) || !empty($nom)) {
        $justificatifs = $model->getJustificatifsHistoriqueFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom);
    }
    else $justificatifs = $model->getJustificatifsValides();

} else {
    $justificatifs = $model->getJustificatifsValides();
}

$justificatifs = array_slice($justificatifs, 0, 10);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/CSS/historiqueResp.css">
    <title>Tableau de bord historique</title>
</head>
<body>

<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>

<!-- TabBar ici ! -->
<ul>
    <a class="pages" href="tableauDeBordResponsable.php"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="CompteResp.html"><li>Compte</li></a>
</ul>

<!-- Titre -->
<h1><u>Historique des absences : </u></h1>


<!-- Filtrage ici ! -->
<details>
    <summary class="filtrer">
        <img src="/Image/filter.png" alt="Filtre" class="Filtre" height="24">
        <a class="nom"><b>Filtrer</b></a><br>
    </summary>

    <div class="filtrage">
        <form method="post">

            <input type="checkbox" id="dateFiltreur" name="dateFiltreur" class="dateFiltreur" checked />
            <label for="dateFiltreur">Filtrer par date</label>

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


            <br>
            <input type="checkbox" id="matiereFiltreur" name="matiereFiltreur" class="matiereFiltreur" checked />
            <label for="matiereFiltreur">Filtrer par matière</label>
            <div class="matiereFiltre">
                <h3>Nom de la matière</h3>
                <input type="text" id="inputMatiere" name="Matière" value="">
            </div>


            <br>
            <input type="checkbox" id="eleveFiltreur" name="eleveFiltreur" class="eleveFiltreur" checked />
            <label for="eleveFiltreur">Filtrer par élève</label>
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
    <?php foreach ($justificatifs as $justif):
        $id = $justif['idjustificatif'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><?= htmlspecialchars($justif['nom_etudiant']) ?> <?= htmlspecialchars($justif['prenom_etudiant']) ?></a><br>
                    <small><?= htmlspecialchars($justif['matiere']) ?> —
                        <?= htmlspecialchars($justif['date_seance']) ?> à <?= htmlspecialchars($justif['heuredebut']) ?> <?php if ($justif['reponse']=='accepte') {
                            $imageClass="histo-accepter";
                            $imageSource="/Image/AccepterSymbole.png";
                            echo "<img class=$imageClass src=$imageSource> <br/><br/>";
                        } else if ($justif['reponse']=='refuse') {
                            $imageClass="histo-refuser";
                            $imageSource="/Image/RefuserSymbole.png";
                            echo "<img class=$imageClass src=$imageSource>";
                        } else {
                            $imageClass="histo-demander";
                            $imageSource="/Image/DemanderSymbole.png";
                            echo "<img class=$imageClass src=$imageSource>";
                        } ?></small>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <br/>
                        <?php if ($justif['reponse']=='accepte') {
                            echo "Motif de l'absence : ",$justif['cause'],"<br/><br/>";
                        } ?>
                        <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                        <label for="zoom<?= $id ?>" class="zoom-button"></label>

                        <label for="zoom<?= $id ?>" class="justificatif-close">
                            <img src="/Image/close.png" alt="Fermer le justificatif">
                        </label>

                        <div class="fondu-noir"></div>
                        <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                        <br/><br/>
                    </div>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>