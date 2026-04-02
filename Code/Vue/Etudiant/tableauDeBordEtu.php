<?php

//Ce fichier est là pour le Tableau De Bord de l'étudiant avec un calendrier
global $couleurDuMois, $interrogationDuMois;
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}
require __DIR__ . "/menuHorizontalEtu.html";

$Y = date("Y");//On ne peut voir que notre année scolaire

//Dictionnaire pour transformer les dates originalement anglais en francais
$nomDesMois = ["January" => "Janvier",
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
    $_SESSION['year'] = $Y;
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

require __DIR__ . "/../../Presentation/getAbsenceDuMois.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/calendrier/calendrier.css">
    <link rel="stylesheet" href="../../CSS/calendrier/calendrierMarron.css">
    <title>Mon compte</title>

</head>

<a href="ReglementInterieur.php">
    <input class='boutonReglement' type="submit" name="bouton4" value="Consulter le réglement intérieur">
</a>

<details class="detailI">

    <summary>
        <p id="i" style="cursor: pointer; ">ⓘ</p>
    </summary>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <p class="refusVerouille i" id="barreInfoRefus"></p>
    <p class="i" id="texteInfoRefus">Refusé</p>

    <p class="refus i" id="barreInfoAJustif"></p>
    <p class="i" id="texteInfoAJustif">Non_Justifiée</p>

    <p class="report i" id="barreInfoAttente"></p>
    <p class="i" id="texteInfoAttente">En_Attente</p>

    <p class="valide i" id="barreInfoValide"></p>
    <p class="i" id="texteInfoValide">Justifiée ⚠:Interrogation</p>

</details>
<div class="white-box"> </div>

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
                    <div <?php echo 'class=' . $couleurDuMois[$date->format('j')]; ?>></div>
                    <form action="tableauDeBordEtu.php" method="post">
                        <input type="submit" value="01<?php if ($interrogationDuMois['1']) {
                            echo ' ⚠';
                        } ?> " name="jour"
                               id="jour">
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
                <td <?php if (date('m-d') == date_format($date, 'm-d')) { //Si c'est aujourd'hui
                    echo 'id=adj';
                } ?>>
                    <div <?php echo 'class=' . $couleurDuMois[$date->format('j')]; ?>></div>
                    <form>
                        <input type="submit" value=" <?php echo date_format($date, "d");
                        if ($interrogationDuMois[$date->format('j')]) { //Si il y a une interro
                            echo ' ⚠';
                        } ?> " name="jour" id="jour">
                    </form>
                </td>
                <?php
            }

            $mois = $date->format('m'); //Voir le mois pour ne pas faire le mois d'apres
        }
        ?>
</table>
<div id="res">
    <?php
    require __DIR__ . '/listeAbsEtu.php'; ?>
</div>


<script>
    let ajax = new XMLHttpRequest();
    let container = document.querySelector("#res")
    let mois = <?php echo $_SESSION['mois'] ?>;
    let year = <?php echo $_SESSION['year'] ?>;
    let user = <?php echo $_SESSION['user'] ?>;
    var v = 1;
    var res;

    function recupInfo(){
            if (ajax.readyState === 4 && ajax.status === 200) {
                res = ajax.responseText;
                ajax.onreadystatechange = mettreInfo
                console.log("Recup info : result="+res+"&jour=" + v + "&mois=" + mois)
                ajax.open('POST', 'listeAbsEtu.php', true)
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("result="+res+"&jour=" + v + "&mois=" + mois)
            }
    }

    function mettreInfo(){
        if (ajax.readyState === 4 && ajax.status === 200) {
            ajax.onreadystatechange = recupInfo
            container.innerHTML = ajax.responseText
        }
    }

    ajax.onreadystatechange = recupInfo

    function reset() {
        let longueur = container.children.length
        for (let i = 0; i < longueur; i++) {
            container.removeChild(container.children[0]);
        }
    }

    let boutons = document.querySelectorAll("#jour")
    for (let bouton of boutons) {
        bouton.addEventListener("click", (e) => {
            e.preventDefault()
            reset()
            v = bouton.getAttribute("Value")
            v = v[1] + v[2]
            console.log("Event :  jour=" + v + "&mois=" + mois + "&year=" + year+"&user="+user)
            ajax.open('POST', '../../Presentation/getAbsenceDunJour.php', true)
            ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax.send("jour=" + v + "&mois=" + mois + "&year=" + year+"&user="+user)
        })
    }
</script>

