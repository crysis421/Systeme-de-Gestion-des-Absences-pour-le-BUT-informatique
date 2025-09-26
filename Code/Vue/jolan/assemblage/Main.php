<?php

use Model\testDBB;

//require_once "../../../Model/testDBB.php";
//$testBDD = new TestDBB();
//$absence = $testBDD->getAbsence();
//
//for($x = 0; $x < count($absence); $x++){
//    echo $x;
//}

$titre = "";
$description = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $choix = $_POST['toggle'] ?? null;

    $motif = $_POST['motifs'] ?? null;

    $refus = $_POST['motif_refus'] ?? null;
    $demande = $_POST['motif_demande'] ?? null;

    if($choix == "accepte"){
        $titre = "Accepté !";
        $description = $motif;
    }

    if ($choix == "refuse"){
        $titre = "Refusé !";
        $description = $refus;
        if(strlen($demande) > 10) {
            $description = substr($description,0,10) . "...";
        }
    }

    if ($choix == "demande"){
        $titre = "Demandé !";
        $description = $demande;
        if(strlen($demande) > 10) {
            $description = substr($description,0,10) . "...";
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
    <a class="pages" href="tableauDeBordRespAbsences.html"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="tableauDeBordRespRetards.html"><li>Tableau de bord des retards</li></a>
    <a class="pages" href="HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="CompteResp.php"><li>Compte</li></a>
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



<!-- Liste des absences ici ! -->
<h1><u>Absences : </u></h1>

<div class="liste-absence">


    <div class="element">

        <details>
            <summary class="top-layer">
                <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24"> <a class="nom">NOM Prénom </a><br>
            </summary>
            <div class="details">
                <div class="justificatif-viewer">

                    <details>
                        <summary>
                            <a class="justificatif-texte">Justificatif</a>
                            <img class="oeil" src="oeil.png" alt="Voir le justificatif">
                        </summary>
                        <input type="checkbox" id="zoom" name="zoom" style="display: none;">
                        <label for="zoom" id="zoom-button"></label>
                        <!--                <img class="justificatif-image" src="justificatif.jpg" alt="Justicatif">-->


                        <label for="zoom" class="justificatif-close">
                            <img src="close.png" alt="Fermer le justificatif">
                        </label>


                        <div class="fondu-noir"></div>
                        <img class="justificatif-image-big" src="justificatif.jpg" alt="Justicatif">

                    </details>
                </div>
                <form method="post">
                    <a class="decision-finale">
                        Décision finale
                    </a>

                    <input type="radio" id="toggle1" name="toggle" value="accepte" style="display: none;">
                    <label for="toggle1" id="label_accepter"></label>


                    <input type="radio" id="toggle2" name="toggle" value="refuse" style="display: none;">
                    <label for="toggle2" id="label_refuser"></label>

                    <input type="radio" id="toggle3" name="toggle" value="demande" style="display: none;">
                    <label for="toggle3" id="label_demander"></label>

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
                        <br>
                        <br>
                        <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                        <br><br>
                    </div>

                    <div id="texte3">
                        Motif de la demande : <br><br>
                        <textarea name="motif_demande" rows="4" cols="50"></textarea>
                        <br>
                        <br>
                        <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                        <br><br>
                    </div>


                </form>


            </div>

            <!-- check quel element afficher en php-->
        </details>

    </div>

</div>

</body>
</html>
