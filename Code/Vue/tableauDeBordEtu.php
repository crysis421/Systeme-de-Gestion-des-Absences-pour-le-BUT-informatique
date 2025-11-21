<?php

//Ce fichier est là pour le Tableau De Bord de l'étudiant avec un calendrier
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}
require "menuHorizontalEtu.html";

echo '<link rel="stylesheet" href="../CSS/calendrier.css" />';

$Y = date("Y");//On ne peut voir que notre année scolaire

//Dictionnaire pour transformer les dates originalement anglais en francais
$nomDesMois = [ "January" => "Janvier",
        "February" => "Février",
        "March" => "Mars",
        "April" => "Avril",
        "May" => "Mai",
        "June" => "Juin",
        "July" => "Juillet",
        "August" => "Août",
        "September" => "Septembre",
        "October" => "Octobre",
        "November" => "Novembre",
        "December" => "Décembre"];


if (!isset($_POST['mois'])) {
    $M = date("m");
    $Y = date("Y");
} else {
    $M = $_POST['mois'];
    $_SESSION['mois'] = $M;
    if ($_POST['mois'] < 8 and date('m') >= 8) {
        $Y = date("Y") + 1;
    } elseif ($_POST['mois'] >= 8 and date('m') < 8) {
        $Y = $Y - 1;
    }
    $_SESSION['year'] = $Y;
}
if (isset($_SESSION['mois'])) {
    $M = $_SESSION['mois'];
    $Y = $_SESSION['year'];
}
$_SESSION['date'] = date_create(date($Y . "-" . $M . "-01"));
$mois = date_format($_SESSION['date'], "m");

require "../Presentation/getAbsenceDunJour.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css" />
    <title>Mon compte</title>
</head>

<a href="ReglementInterieur.php">
    <input class='boutonReglement' type="submit" name="bouton4" value="Consulter le réglement intérieur">
</a>
<div class="legendeCouleurs">
    <label>Rouge = Absence non justifiée</label>
    <label>Jaune = Absence non justifiée qui demande plus des précissions</label>
    <label>Vert = Absence justifiée</label>
</div>
    <form action="tableauDeBordEtu.php" method="post">
        <label>
            Choix du mois : <input type="number" min="1" max="12" name="mois" id="mois" required>
        </label>
        <input type="submit" value="OK" name="jour">
    </form>

    <h1>
        <?php echo '<p> ' . $nomDesMois[$_SESSION['date']->format("F")] . $_SESSION['date']->format(" - Y") . ' </p>' ?>
    </h1>
    <table>
        <tr>
            <th>Lundi</th>
            <th>Mardi</th>
            <th>Mercredi</th>
            <th>Jeudi</th>
            <th>Vendredi</th>
            <th>Samedi</th>
            <th>Dimanche</th>
        </tr>
        <tr>
            <?php
            $date = clone($_SESSION['date']);
            $j = $date->format('w');
            if ($j == 0) {//Implementation plus simple quand dimanche = 7
                $j = 7;
            }
            for ($i = 0; $i < $j; $i++) {//Mettre le premier jour au bon endroit
                if ($i == $j - 1) {
                    ?>

                    <td <?php if (date('m-d') == date_format($date, 'm-01')) {
                        echo 'id=adj';
                    } ?>>
                        <form action="tableauDeBordEtu.php" method="post">
                            <input type="submit" value="01<?php if($interrogationDuMois['1']) {
                                echo ' ⚠';
                            } ?> " name="jour"
                                   id="jour" <?php if ($couleurDuMois['1'] == 'valide') {
                                echo 'class=valide';
                            } else if ($couleurDuMois['1'] == 'refus') {
                                echo 'class=refus';
                            } else if ($couleurDuMois['1'] == 'enAttente') {
                                echo 'class=enAttente';
                            }?>>
                        </form>
                    </td>
                    <?php
                } else {
                    echo '<td>  </td>';
                }
            }


            while ($mois == $M) {
                if ($date->format('D') == 'Sun') {//Passer a la ligne suivante car changement de semaine
                    echo "</tr><tr>";
                }
                date_add($date, date_interval_create_from_date_string("1 days")); //Incrementation de la date
                if ($date->format('j') != 1) { //Supprimer le 01 a la fin
                    ?>
                    <td <?php if (date('m-d') == date_format($date, 'm-d')) {
                        echo 'id=adj';
                    } ?>>
                        <div <?php echo 'class='.$couleurDuMois[$date->format('j')];?>></div>
                        <form action="tableauDeBordEtu.php" method="post">
                            <input type="submit" value=" <?php echo date_format($date, "d"); if ($interrogationDuMois[$date->format('j')]) {
                                echo ' ⚠';
                            }?> " name="jour" id="jour">
                        </form>
                    </td>
                    <?php
                }

                $mois = $date->format('m');//Voir le mois pour ne pas faire le mois d'apres
            }
            ?>
    </table>

<?php

require 'listeAbsEtu.php';
