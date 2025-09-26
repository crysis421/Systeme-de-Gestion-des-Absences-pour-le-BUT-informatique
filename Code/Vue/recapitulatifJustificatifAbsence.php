
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


?>


<h1 style="text-align: center"> Recapitulatif justificatif d'absence </h1>

<p><b>Nom de l'étudiant : </b><?php echo "$nom"?></p>
<p><b>Prenom de l'étudiant : </b><?php echo "$prenom"?></p>
<p><b>Numéro d'etudiant de l'étudiant : </b><?php echo "$num"?></p>
<p><b>Formation de l'étudiant : </b><?php echo "$formation"?></p>
<p><b>Année d'étude de l'étudiant : </b><?php echo "$annee"?></p>
<p><b>Adresse mail de l'étudiant : </b><?php echo "$mail"?></p>
<p><b>Numéro de l'étudiant : </b><?php echo "$tel"?></p>
<br>
<p>Cette étudiant a été absent du : <?php echo "$datedebut" ?> à: <?php echo "$heuredebut"?> au : <?php echo "$fin"?> à : <?php echo "$heurefin1" ?> .</p>
<p> le motif de cette absence est : <?php echo"$motif" ?> (<?php echo"$commentaire" ?>).</p>
<br>
<br>
<div>
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>



