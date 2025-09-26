<?php require "menuHorizontalEtu.html";
session_start();
echo '<link rel="stylesheet" href="../CSS/calendrier.css" />';
$Y =date("Y");

if (!isset($_POST['mois'])) { //On ne peut voir que notre année scolaire
    $M = date("m");
} else {
    $M = $_POST['mois'];
    if($_POST['mois'] < 8 and date('m') >= 8){
        $Y = date("Y") + 1;
    }elseif($_POST['mois'] >= 8 and date('m') < 8){
        $Y = $Y - 1;
    }
}
$date = date_create(date($Y . "-" . $M . "-01"));
$mois = date_format($date, "m");

?>
<form action="tableauDeBordEtu.php" method="post">
    <label>
        Choix du mois : <input type="number" min="1" max="12" name="mois" id="mois" required>
    </label>
    <input type="submit" value="Ok">
</form>

<h1> <?php echo $date->format("F - Y") ?> </h1>
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

        $j = $date->format('w');
        if ($j == 0) {//Implementation plus simple quand dimanche = 7
            $j = 7;
        }
        for ($i = 0; $i < $j; $i++) {//Mettre le premier jour au bon endroit
            if ($i == $j - 1) {
                if (date('m-d')==date_format($date,'m-01')){
                    echo '<td id="adj"> 01 <td>';
                }else{
                    echo '<td> 01 </td>';
                }
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
                if (date('m-d')==date_format($date,'m-d')){
                    echo "<td id='adj'>" . date_format($date, "d") . "</td>";
                }else{
                    echo "<td>" . date_format($date, "d") . "</td>";
                }
            }
            $mois = $date->format('m'); //Voir le mois pour ne pas faire le mois d'apres
        }
        ?>
</table>

<?php

