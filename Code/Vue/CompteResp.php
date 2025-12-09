<?php
use Vue\Camembert;
require_once "../Model/AbsenceModel.php";
require_once '../test/send.php';
use test\send;
require('Camembert.php');
session_start();

if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}

if(!isset($_POST['Semestre'])){
    if(!isset($_SESSION['semestre'])){
        $semestre = 1;
    }else{
        $semestre = $_SESSION['semestre'];
    }
}else{
    $_SESSION['semestre']  = $_POST['Semestre'];
    $semestre = $_SESSION['semestre'];
}

if(!isset($_POST['SemestreR'])){
    if(!isset($_SESSION['semestreR'])){
        $semestreR = 1;
    }else{
        $semestreR = $_SESSION['semestreR'];
    }
}else{
    $_SESSION['semestreR']  = $_POST['SemestreR'];
    $semestreR = $_SESSION['semestreR'];
}
require_once("../Presentation/lesInfoResp.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rappel"])){
    $model = new AbsenceModel();
    $mailer = new send();
    $resultat = $model->getEmailAttendu();
    foreach ($resultat as $result) {

        $contenu = "<h1>Notification de rappel concernant votre Justificatif</h1>
                <p>Vous avez plusieurs absences non justifiées ou non-validées qui sont en attente de justification.</p>
                <p>Veuillez-vous contecter à votre de compte de gestion d'absence pour en savoir plus</p>";
        $result = $mailer->envoyerMailSendGrid($result,'Rappel justificatif absence',$contenu);
    }
}

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
            <ol class="graphe">
                <li class="lesGraphes">
                    <?php
                    if(array_key_last($nbFoisAnnee) == 0){
                        echo "Pas d'absence cette année";
                    }else{Camembert::afficher($grapheAnnee,$nbFoisAnnee,"Absence de cette année");} ?>
                </li>
                <li class="lesGraphes">
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
                    <?php
                    if(array_key_last($nbFoisSemestre) == 0){
                        echo "Pas d'absence ce semestre";
                    }else{
                        Camembert::afficher($grapheSemestre,$nbFoisSemestre,"Absence de ce semestre");} ?>
                </li>
                <li class="lesGraphes">
                    <form action="CompteResp.php" method="post">
                        <select name="SemestreR">
                            <option value="1" <?php if($semestreR==1){echo 'selected';} ?>>Semestre 1</option>
                            <option value="2" <?php if($semestreR==2){echo 'selected';} ?>>Semestre 2</option>
                            <option value="3" <?php if($semestreR==3){echo 'selected';} ?>>Semestre 3</option>
                            <option value="4" <?php if($semestreR==4){echo 'selected';} ?>>Semestre 4</option>
                            <option value="5" <?php if($semestreR==5){echo 'selected';} ?>>Semestre 5</option>
                            <option value="6" <?php if($semestreR==6){echo 'selected';} ?>>Semestre 6</option>
                        </select>
                        <br>
                        <input type="submit" name="bouton4" value="Envoyer">
                    </form>
                    <?php
                    if(array_key_last($nbFoisSemestreR) == 0){
                        echo "Pas d'absence ce semestre";
                    }else{ Camembert::afficher($grapheSemestreR,$nbFoisSemestreR,"Absence de ce semestre");} ?>
                </li>
            </ol>

        </details>
    </div>
    <form action="Connexion.php" name="Deconnexion">
        <input type="submit" value="Déconnexion" style="background-color:#bf0000;
    color:black;
    border: 2px solid #00aa00;
    border-radius: 10px;
    padding : 7px 15px 10px 10px;
    font-size: 20px; position:absolute; left:750px;">
    </form>

    <div class="alertes" id="alertes">
        <br><br>
        <?php foreach ($alerteM as $f):
            echo $f;
        endforeach; ?>
        <br/>
        <?php foreach ($alerteC as $f):
            echo $f;
        endforeach; ?>
        <br/>
    </div>
    <form action="" method="post" name="Rappel">
        <input type="submit" name="rappel" value="Rappel justification" style="background-color:#c7d685;
    color:black;
    border: 2px solid #00aa00;
    border-radius: 10px;
    padding : 7px 15px 10px 10px;
    font-size: 20px; position:absolute; left:750px;">
    </form>


</body>
</html>