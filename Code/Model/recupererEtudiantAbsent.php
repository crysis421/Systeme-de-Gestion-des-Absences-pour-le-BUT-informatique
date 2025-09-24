<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trouver un étudiant dans la base de donées</title>
</head>
<div id="titre"> <h1> Trouver un étudiant absent</h1></div>

<div id="infos">
    <form method="post" action="">
        <label for="identifiant">
            enter l'identifiant de l'étudiants rechercher :
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
    $i = 0;
    foreach($resultat as $row){
        foreach ($row as $e){
            echo "$labels[$i]  $e </br>";
            $i = $i + 1;
        }
    }

    ?>
</div>
<body>
</body>
</html>




