<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/RecapJustificatif.css">
    <title>Recapitulatif Justificatif absence</title>
</head>
<body>
<?php
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$num = $_POST['numeroE'];
$formation = $_POST['filiere'];
$annee = $_POST['annee'];
$mail = $_POST['mail'];
$tel = $_POST['numero'];


$datedebut = $_POST['datedebut'];
$heuredebut = $_POST['heuredebut'];
$fin = $_POST['fin'];
$heurefin1 = $_POST['heurefin1'];
$motif = $_POST['motif'];
$commentaire = $_POST['commentaire'];

$justificatif = $_POST['justificatif'];
$dateSignature = $_POST['dateSignature'];
$lieuSignature = $_POST['lieuSignature'];

$heuresignature = $_POST['heuresignature'];


?>
<h1 style="text-align: center"> Recapitulatif du justificatif d'absence de <?php echo "$nom"?></h1>
<div id="info">
    <p>Nom de l'étudiant : <b><?php echo "$nom"?></b></p>
    <p>Prenom de l'étudiant : <b><?php echo "$prenom"?></b></p>
    <p>Numéro d'étudiant de l'étudiant : <b><?php echo "$num"?></b></p>
    <p>Formation de l'étudiant : <b><?php echo "$formation"?></b></p>
    <p>Année d'étude de l'étudiant : <b><?php echo "$annee"?></b></p>
    <p>Adresse mail de l'étudiant : <b><?php echo "$mail"?></b></p>
    <p>Numéro de l'étudiant : <b><?php echo "$tel"?></b></p>
    <br>
</div>
<div id="autre">
    <p>Cet étudiant a été absent du : <b><?php echo "$datedebut" ?></b> à: <b><?php echo "$heuredebut"?></b> au : <b><?php echo "$fin"?></b> à : <b><?php echo "$heurefin1" ?></b> .</p><br>

    <p> Le motif de cette absence est : <b><?php echo"$motif" ?> </b>.<br><br><em>Commentaires:"<b><?php echo "$commentaire"?></b>"</em>.</p>
    <br>
    <p>Justificatif:<b><?php echo "$justificatif"?></b></p>
    <br>
    <p>Cette justification a été faite le: <b><?php echo "$dateSignature"?></b> à : <b><?php echo "$heuresignature"?></b> à: <b><?php echo "$lieuSignature"?></b></p>

</div>
<div id="bouton">
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>
</body>
</html>










