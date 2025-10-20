<?php

require_once "../Model/AbsenceModel.php";
$model = new AbsenceModel();
$justificatifs = $model->getJustificatifsHistorique();

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
    <a class="pages" href="tableauDeBordRespAbsences.php"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="tableauDeBordRespRetards.php"><li>Tableau de bord des retards</li></a>
    <a class="pages" href="HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="CompteResp.html"><li>Compte</li></a>
</ul>

<!-- Historique des justificatifs traités ici ! -->
<h1><u>Historique : </u></h1>

<div class="liste-absence">
    <?php foreach ($justificatifs as $justif):
        $id = $justif['idjustificatif'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><?= htmlspecialchars($justif['nom_etudiant']) ?> <?= htmlspecialchars($justif['prenom_etudiant']) ?></a><br>
                    <small><?= htmlspecialchars($justif['matiere']) ?> — <?= htmlspecialchars($justif['date_seance']) ?> à <?= htmlspecialchars($justif['heuredebut']) ?></small>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <br/>
                        <?php if ($justif['reponse']=='accepte') {
                            $imageClass="histo-accepter";
                            $imageSource="jolan/assemblage/AccepterSymbole.png";
                            echo "Justificatif Accepté  <img class=$imageClass src=$imageSource> <br/><br/>";
                            echo "Motif de l'absence : ",$justif['cause'];
                        } else if ($justif['reponse']=='refuse') {
                            $imageClass="histo-refuser";
                            $imageSource="jolan/assemblage/RefuserSymbole.png";
                            echo "Justificatif Refusé <img class=$imageClass src=$imageSource>";
                        } else {
                            $imageClass="histo-demander";
                            $imageSource="jolan/assemblage/DemanderSymbole.png";
                            echo "Justificatif nécessitant plus de précisions <img class=$imageClass src=$imageSource>";
                        } ?>
                        <br/><br/>
                        <a class="justificatif-texte">Détails</a>
                        <details>
                            <summary>
                                <img class="oeil" src="jolan/assemblage/oeil.png" alt="Voir le justificatif">
                                <br/><br/>
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="jolan/assemblage/close.png" alt="Fermer le justificatif">
                            </label>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                            <br/><br/>
                        </details>
                    </div>

                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>