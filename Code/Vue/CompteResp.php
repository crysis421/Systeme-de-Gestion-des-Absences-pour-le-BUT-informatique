<?php session_start();

if(!isset($_SESSION["user"])){
header('Location: ../Vue/Connexion.php');
}

require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();
$matiere= $model -> getMatieres();
$eleve = $model -> getEleves();
$matiere = array_slice($matiere, 0, 5);
$eleve = array_slice($eleve, 0, 5);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css" />
    <title>Gestionnaire d'absence</title>
</head>
<body>

<?php require('menuHorizontalResp.html'); ?>

<h1>Compte</h1>
<div class="alertes" id="alertes">
    <?php foreach ($matiere as $mat):
        $alerteM = $model -> alerteCours($mat);
        if ($alerteM > 10){
            echo "<p>Il a un problème d'absences pour la matière de $mat</p>";
        }

    endforeach; ?>
    <br/>
    <?php foreach($eleve as $el):
        $alerteC = $model -> alerteEleve($el['nom'],$el['prenom']);
        if ($alerteC > 10){
            echo "<p>Il a un problème d'absences pour l'élève qui s'appelle ",$el['nom']," ",$el['prenom'],"</p>";
        }
    endforeach; ?>
    <br/>
</div>

<form action="Connexion.php" name="Deconnexion">
    <input type="submit" value="Déconnexion" style="background-color:#bf0000;
    color:black;
    border: 2px solid #00aa00;
    border-radius: 10px;
    padding : 7px 15px 10px 10px;
    font-size: 20px; position:absolute; left:750px;" >
</form>
</body>
</html>