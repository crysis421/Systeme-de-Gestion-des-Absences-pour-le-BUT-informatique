<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trouver une absence dans la base de donées</title>
</head>
<div id="titre"> <h1> Trouver une absence</h1></div>

<div id="infos">
    <form method="post" action="">
        <label for="identifiant">
            enter l'identifiant de l'absence rechercher :
            <input id="identifiant" name="identifiant" type="number" required>
        </label><br>
        <br>
        <input type="submit" value="valider">
    </form>
</div>
<br>
<div>
    <?php

    require_once "AbsenceModel.php";

    $identifiant = $_POST["identifiant"];

    $a = new AbsenceModel();

    $resultat = $a->getByUser($identifiant);

    $labels = ["<b>identifiant :</b>", "<b>statut :</b>", "<b>date :</b>", "<b>heure Debut :</b>", "<b>type Seance :</b>", "<b>matiere :</b>"];
    foreach($resultat as $row){
        $i = 0;
        foreach ($row as $e){
            echo "$labels[$i]  $e </br>";
            $i = $i + 1;
        }
        echo "</br>";
    }

    ?>
</div>
<body>
</body>
</html>




