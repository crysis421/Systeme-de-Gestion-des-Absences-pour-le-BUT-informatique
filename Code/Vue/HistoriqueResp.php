<?php


require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();
$justificatifs = $model->getJustificatifsHistorique();

//$titre = "";
//$description = "";


//if ($_SERVER["REQUEST_METHOD"] === "POST") {
//    $choix = $_POST['toggle'] ?? null;

//    $motif = $_POST['motifs'] ?? null;

//    $refus = $_POST['motif_refus'] ?? null;
//    $demande = $_POST['motif_demande'] ?? null;

//    if($choix == "accepte"){
//        $titre = "Accepté !";
//        $description = $motif;
//    }

//    if ($choix == "refuse"){
//        $titre = "Refusé !";
//        $description = $refus;
//        if(strlen($demande) > 10) {
//            $description = substr($description,0,10) . "...";
//        }
//    }

//    if ($choix == "demande"){
//        $titre = "Demandé !";
//        $description = $demande;
//        if(strlen($demande) > 10) {
//            $description = substr($description,0,10) . "...";
//        }
//    }
//}



?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="jolan/assemblage/Main.css">
    <title>Historique</title>
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


<!-- Liste des absences ici ! -->
<h1><u>Historique : </u></h1>

<div class="liste-absence">
    <?php foreach ($justificatifs as $justif): ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><?= ($justif['nom_etudiant']) ?> <?= ($justif['prenom_etudiant']) ?></a><br>
                    <small><?= ($justif['matiere']) ?> — <?= ($justif['date_seance']) ?> à <?= ($justif['heureDebut']) ?></small>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="jolan/assemblage/oeil.png" alt="Voir le justificatif">
                            </summary>
                            <div class="fondu-noir"></div>
                            <!-- mettre chemin vers le fichier img ou autre du justificatif -->
                            <img class="justificatif-image-big" src="jolan/assemblage/justificatif.jpg" alt="Justicatif">
                        </details>
                    </div>

                    <form method="post">
                        <a class="decision-finale">Décision finale</a>

                        <br><br>

                        <div id="texte1">
                            <select name="motifs" id="motif-absence">
                                <option value="">--Choisissez une option--</option>
                                <option value="transport">Transport</option>
                                <option value="malade">Malade</option>
                                <option value="etc">...</option>
                            </select>
                            <br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div id="texte2">
                            Motif du refus : <br><br>
                            <textarea name="motif_refus" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div id="texte3">
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
