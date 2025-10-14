<?php
session_start();
require "menuHorizontalEtu.html";

echo '<link rel="stylesheet" href="../CSS/calendrier.css" />';

$_SESSION['user'] = 42049956;

$Y = date("Y");//On ne peut voir que notre année scolaire

if (!isset($_POST['mois']) ) {
    $M = date("m");
}else {
    $M = $_POST['mois'];
    $_SESSION['mois'] = $M;
    if ($_POST['mois'] < 8 and date('m') >= 8) {
        $Y = date("Y") + 1;
    } elseif ($_POST['mois'] >= 8 and date('m') < 8) {
        $Y = $Y - 1;
    }
}
if(isset($_SESSION['mois'])){
    $M = $_SESSION['mois'];
}
$_SESSION['date'] = date_create(date($Y . "-" . $M . "-01"));
$mois = date_format($_SESSION['date'], "m");

?>
    <form action="tableauDeBordEtu.php" method="post">
        <label>
            Choix du mois : <input type="number" min="1" max="12" name="mois" id="mois" required>
        </label>
        <input type="submit" value="OK" name="jour">
    </form>

    <h1> <?php echo '<p>'.$_SESSION['date']->format("F - Y").'</p>' ?> </h1>
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
                    if (date('m-d') == date_format($date, 'm-01')) {
                        ?>
                        <td id="adj">
                            <form action="tableauDeBordEtu.php" method="post">
                                <input type="submit" value="01" name="jour" id="jour">
                            </form>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td>
                            <form action="tableauDeBordEtu.php" method="post">
                                <input type="submit" value="01" name="jour" id="jour">
                            </form>
                        </td>
                        <?php
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
                    if (date('m-d') == date_format($date, 'm-d')) {
                        ?>
                        <td id="adj">
                            <form action="tableauDeBordEtu.php" method="post">
                                <input type="submit" value=" <?php echo date_format($date, "d") ?> " name="jour" id="jour">
                            </form>
                        </td>
                        <?php
                    } else {
                        ?>
                        <td>
                            <form action="tableauDeBordEtu.php" method="post">
                                <input type="submit" value="<?php echo date_format($date, "d") ?>" name="jour" id="jour">
                            </form>
                        </td>
                        <?php
                    }
                }
                $mois = $date->format('m'); //Voir le mois pour ne pas faire le mois d'apres
            }
            ?>
    </table>

<?php
require '../Presentation/getAbsenceDunJour.php';
