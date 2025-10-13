<?php

require_once "../../../Model/AbsenceModel.php";

$model = new AbsenceModel();


//$justificatifs = $model->getJustificatifsAttenteFiltre("2024-02-01", "2025-02-01", "INFFIS2-EXPLOITATION","Delahaye", "Éléonore");

$justificatifs = $model->getJustificatifsAttente();
$justificatifs = array_slice($justificatifs, 0, 10);

$titre = "";
$description = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $IDElement = isset($_POST['IDElement']) ? $_POST['IDElement'] : null;
    $choix = isset($_POST['toggle']) ? $_POST['toggle'] : null;
    $motif = isset($_POST['motifs']) ? $_POST['motifs'] : null;
    $refus = isset($_POST['motif_refus']) ? $_POST['motif_refus'] : null;
    $demande = isset($_POST['motif_demande']) ? $_POST['motif_demande'] : null;


    if($choix == "accepte"){
        $titre = "Accepté !";
        $description = $motif;
    }

    if ($choix == "refuse"){
        $titre = "Refusé !";
        $description = $refus;
        if(strlen($refus) > 10) {
            $description = substr($refus,0,10) . "...";
        }
    }

    if ($choix == "demande"){
        $titre = "Demandé !";
        $description = $demande;
        if(strlen($demande) > 10) {
            $description = substr($demande,0,10) . "...";
        }
    }

}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Main.css">
    <title>Tableau de bord absence</title>
</head>
<body>

<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>

<!-- TabBar ici ! -->
<ul>
    <a class="pages" href="../../tableauDeBordRespAbsences.php"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="../../tableauDeBordRespRetards.php"><li>Tableau de bord des retards</li></a>
    <a class="pages" href="../../HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="../../CompteResp.html"><li>Compte</li></a>
</ul>

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

<!-- Filtrage ici ! -->
<div class="filtrage">
    <form method="post">

        <details>
            <summary>
                <h2>Filtrer par date</h2>
                <?php
                $dateDebut = date("Y") . '-01-01';
                $dateFin = date("Y-m-d");
                ?>
            </summary>
            <h3>Date de début</h3>
            <input type="date" id="startDate" name="dateDebut" value="<?= $dateDebut ?>">
            <h3>Date de fin</h3>
            <input type="date" id="endDate" name="dateFin" value="<?= $dateFin ?>">
        </details>


        <details>
            <summary>
                <h2>Filtrer par matière</h2>
            </summary>
            <h3>Nom de la matière</h3>
            <input type="text" id="inputMatiere" name="Matière" value="">
        </details>


        <details>
            <summary>
                <h2>Filtrer par élève</h2>
            </summary>
            <h3>Prénom</h3>
            <input type="text" id="inputPrenom" name="Prenom Input" value="">
            <h3>Nom</h3>
            <input type="text" id="inputNom" name="Nom Input" value="">
        </details>


        <input class='bouton-filtrage' type="submit" name="boutonFiltre" value="Filtrer">

    </form>
</div>

<!-- Liste des absences ici ! -->
<h1><u>Absences : </u></h1>

<div class="liste-absence">
    <?php foreach ($justificatifs as $justif):
        $id = $justif['idjustificatif'];
        $commentaire = $justif['commentaire_justificatif'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom_etudiant']) ?> <?= htmlspecialchars($justif['prenom_etudiant']) ?></a></b><br>

                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['matiere']) ?></small>
                        <br><small><?= htmlspecialchars($justif['date_seance']) ?> à <?= htmlspecialchars($justif['heuredebut']) ?></small>
                    </div>

                    <div class="ligne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="close.png" alt="Fermer le justificatif">
                            </label>

                            <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="justificatif.jpg" alt="Justificatif">
                        </details>
                    </div>

                    <form method="post">
                        <a class="decision-finale">Décision finale</a>

                        <input type="radio" id="toggle1_<?= $id ?>" name="toggle" value="accepte" style="display: none;">
                        <label for="toggle1_<?= $id ?>" class="label-accepter"></label>

                        <input type="radio" id="toggle2_<?= $id ?>" name="toggle" value="refuse" style="display: none;">
                        <label for="toggle2_<?= $id ?>" class="label-refuser"></label>

                        <input type="radio" id="toggle3_<?= $id ?>" name="toggle" value="demande" style="display: none;">
                        <label for="toggle3_<?= $id ?>" class="label-demander"></label>

                        <input type="hidden" id="IDElement_<?= $id ?>" value="<?= $id ?>" name="IDElement">

                        <br><br>

                        <div class="texte-accepter">
                            <select name="motifs" id="motif-absence-<?= $id ?>">
                                <option value="">--Choisissez une option--</option>
                                <option value="transport">Transport</option>
                                <option value="malade">Malade</option>
                                <option value="etc">...</option>
                            </select>
                            <br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-refuser">
                            Motif du refus : <br><br>
                            <textarea name="motif_refus" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-demander">
                            Motif de la demande : <br><br>
                            <textarea name="motif_demande" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>
                    </form>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>