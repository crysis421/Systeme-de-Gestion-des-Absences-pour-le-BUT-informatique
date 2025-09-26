
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

<p>Nom de l'étudiant : <b><?php echo "$nom"?></b></p>
<p>Prenom de l'étudiant : <b><?php echo "$prenom"?></b></p>
<p>Numéro d'etudiant de l'étudiant : <b><?php echo "$num"?></b></p>
<p>Formation de l'étudiant : <b><?php echo "$formation"?></b></p>
<p>Année d'étude de l'étudiant : <b><?php echo "$annee"?></b></p>
<p>Adresse mail de l'étudiant : <<b><?php echo "$mail"?></b></p>
<p>Numéro de l'étudiant : <b><?php echo "$tel"?></b></p>
<br>
<p>Cette étudiant a été absent du : <b><?php echo "$datedebut" ?></b> à: <b><?php echo "$heuredebut"?></b> au : <b><?php echo "$fin"?></b> à : <b><?php echo "$heurefin1" ?></b> .</p>
<p> le motif de cette absence est : <b><?php echo"$motif" ?> </b>.<br><em>commentaire :"<b><?php echo "$commentaire"?></b>"</em>.</p>
<br>
<p>justificatif : <b><?php echo "$justificatif"?></b></p>
<br>
<p>Cette justification a été faite le: <b><?php echo "$dateSignature"?></b> à : <b><?php echo "$heuresignature"?></b> à: <b><?php echo "$lieuSignature"?></b></p>
<div>
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>



