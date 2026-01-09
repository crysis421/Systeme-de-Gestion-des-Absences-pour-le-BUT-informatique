<?php
use Vue\Camembert;
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
require_once("../Presentation/lesInfoProf.php");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css"/>
    <title>Gestionnaire d'absence</title>
</head>
<body>

<?php require('menuHorizontalProf.html'); ?>
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
                    <form action="CompteProf.php" method="post">
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
                    <form action="CompteProf.php" method="post">
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
        <input id="deconnexion" type="submit" value="Déconnexion" style=" position:absolute; margin-left:50%;margin-right: 50%">
    </form>

</body>
</html>