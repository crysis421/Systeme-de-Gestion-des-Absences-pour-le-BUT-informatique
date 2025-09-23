<?php require "menuHorizontal.html";
echo '<link rel="stylesheet" href="calendrier.css" />';

$Y = 2025;//Choix de l'année
$M = 9;//Choix du mois
$date = date_create(date($Y."-".$M ."-01"));
$mois = date_format($date,"m");

?>
<h1> <?php echo  $date->format("F - Y") ?> </h1>
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
for ($i = 0; $i < $j; $i++){//Mettre le premier jour au bon endroit
    if ($i == $j-1){
        echo '<td> 01 </td>';
    }else{
        echo '<td>  </td>';
    }
}


while($mois==$M){
    if ($date->format('D')=='Sun'){//Passer a la ligne suivante car changement de semaine
        echo "</tr><tr>";
    }
    date_add($date, date_interval_create_from_date_string("1 days")); //Incrementation de la date
    if ($date->format('j')!=1){ //Supprimer le 01 a la fin
        echo "<td>". date_format($date, "d")."</td>";
    }
    $mois = $date->format('m'); //Voir le mois pour ne pas faire le mois d'apres
}
?>
</table>

