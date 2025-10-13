<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/RecapJustificatif.css">
    <title>Recapitulatif Justificatif retard</title>
</head>
<body>
<?php
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$dateretard = $_POST['dateretard'];
$heurearrive = $_POST['heurearrive'];
$cours = $_POST['cours'];
$motif = $_POST['motif'];
$preciserAutre = $_POST['preciserAutre'];
$justificatif = $_POST['justificatif'];





?>

<h1 style="text-align: center"> Recapitulatif justificatif de retard de <?php echo "$nom"?></h1>
<div id="info">
    <p> L'étudiant : <b><?php echo "$nom"?></b> <b><?php echo "$prenom"?></b> est arrivé en retard ce <b><?php echo "$dateretard"?></b> à <b><?php echo "$heurearrive"?></b> au cours de <b><?php echo "$cours"?></b> .</p>
    <p>Le motif de cette absence est : <b> <?php echo "$motif"?></b> <br>
        <em>Commentaire :"<b><?php echo "$preciserAutre"?></b>"</em>.</p><br>
    <br><p>Justificatif : <b><?php echo "$justificatif"?></b></p>
    <br>
    <br>
</div>

<div id="bouton">
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>
</body>
</html>