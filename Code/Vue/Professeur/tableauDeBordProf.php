<?php
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}

require('menuHorizontalProf.html');

echo '<link rel="stylesheet" href="../../CSS/calendrier.css" />';

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

if (!isset($_POST['moismoi'])) {
    $M = date("m");
    $Y = date("Y");
} else {
    $M = $_POST['moismoi'];
    $_SESSION['mois'] = $M;
    if ($_POST['moismoi'] < 8 and date('m') >= 8) {
        $Y = date("Y") + 1;
    } elseif ($_POST['moismoi'] >= 8 and date('m') < 8) {
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

require "../Presentation/getAbsenceDunControle.php";

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>

    <details>
        <summary>
            <p id="i" style="cursor: pointer; ">ⓘ</p>
        </summary>
        <p> </p>
        <p> </p>
        <p> </p>
        <p> </p>
        <p> </p>
        <p class="i" id="ir">   ⚠:Rattrapage_à_effectuer</p>

    </details>

    <form action="tableauDeBordProf.php" method="post">
        <label>
            Choix du mois : <input type="number" min="1" max="12" name="moismoi" id="moismoi" required>
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
                        <form action="tableauDeBordProf.php" method="post">
                            <input type="submit" value="01<?php if($interrogationDuMois['1']) {
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
                    <td <?php if (date('m-d') == date_format($date, 'm-d')) {
                        echo 'id=adj';
                    } ?>>
                        <form action="tableauDeBordProf.php" method="post">
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

    <div id="res">
        <?php
        require 'listeAbsProf.php'; ?>
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
                ajax.open('POST', 'listeAbsProf.php', true)
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
                ajax.open('POST', '../Presentation/getAbsenceDunControle.php', true)
                ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax.send("jour=" + v + "&mois=" + mois + "&year=" + year+"&user="+user)
            })
        }
    </script>
