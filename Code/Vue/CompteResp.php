<?php
use Vue\Camembert;
require('Camembert.php');
session_start();

if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}

$semestre  = $_POST['Semestre'];
if(!isset($_POST['Semestre'])){
    $semestre = 1;
}
require_once("../Presentation/lesInfoResp.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css"/>
    <title>Gestionnaire d'absence</title>
</head>
<body>

<?php require('menuHorizontalResp.html'); ?>
<main>
    <h1>Compte</h1>

    <div id="graphe">
        <details id="stat">
            <summary style="background-color: #bce6f6">
                <h1>Statistiques 📊</h1>
            </summary>
            <?php Camembert::afficher($grapheAnnee,$nbFoisAnnee,"Absence de cette année"); ?>
            <form action="CompteResp.php" method="post">
                <select name="Semestre">
                    <option value="1" <?php if($semestre==1){echo 'selected';} ?>>Semestre 1</option>
                    <option value="2" <?php if($semestre==2){echo 'selected';} ?>>Semestre 2</option>
                    <option value="3" <?php if($semestre==3){echo 'selected';} ?>>Semestre 3</option>
                    <option value="4" <?php if($semestre==4){echo 'selected';} ?>>Semestre 4</option>
                    <option value="5" <?php if($semestre==5){echo 'selected';} ?>>Semestre 5</option>
                    <option value="6" <?php if($semestre==6){echo 'selected';} ?>>Semestre 6</option>
                </select>
                <br>
                <input type="submit" name="bouton4" value="Envoyer">
            </form>
            <?php Camembert::afficher($grapheSemestre,$nbFoisSemestre,"Absence de ce semestre"); ?>

        </details>
    </div>

    <div class="alertes" id="alertes">
        <?php foreach ($matiere as $mat):
            $alerteM = $model->alerteCours($mat);
            if ($alerteM > 10) {
                echo "<p>Il a un problème d'absences pour la matière de $mat</p>";
            }

        endforeach; ?>
        <br/>
        <?php foreach ($eleve as $el):
            $alerteC = $model->alerteEleve($el['nom'], $el['prenom']);
            if ($alerteC > 10) {
                echo "<p>Il a un problème d'absences pour l'élève qui s'appelle ", $el['nom'], " ", $el['prenom'], "</p>";
            }
        endforeach; ?>
        <br/>
    </div>

    <form action="Connexion.php" name="Deconnexion">
        <input type="submit" value="Déconnexion" style="background-color:#bf0000;
    color:black;
    border: 2px solid #00aa00;
    border-radius: 10px;
    padding : 7px 15px 10px 10px;
    font-size: 20px; position:absolute; left:750px;">
    </form>

</body>
</html>