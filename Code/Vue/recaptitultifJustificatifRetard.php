
<?php
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$filiere= $_POST['filiere'];
$dateretard = $_POST['dateretard'];
$heurearrive = $_POST['heurearrive'];
$cours = $_POST['cours'];
$motif = $_POST['motif'];
$preciserAutre = $_POST['preciserAutre'];
$justificatif = $_POST['justificatif'];
$dateSignature = $_POST['dateSignature'];
$heureSignature = $_POST['heuresignature'];
$lieuSignature = $_POST['lieuSignature'];




?>


<h1 style="text-align: center"> Recapitulatif justificatif de retard de <?php echo "$nom"?></h1>

<p> l'étudiant : <b><?php echo "$nom"?></b> <b><?php echo "$prenom"?></b> en <b><?php echo "$filiere"?></b>  est arrivé en retard ce <b><?php echo "$dateretard"?></b> à <b><?php echo "$heurearrive"?></b> au cours de <b><?php echo "$cours"?></b> .</p>
<p>le motif de cette absence est : <b> <?php echo "$motif"?></b> <br>
    <em>commentaire :"<b><?php echo "$preciserAutre"?></b>"</em>.</p><br>
<br><p>justificatif : <b><?php echo "$justificatif"?></b></p>
<br>
<br>
<p>Cette justification a été faite le: <b><?php echo "$dateSignature"?></b> à : <b><?php echo "$heureSignature"?></b> à : <b><?php echo "$lieuSignature"?></b></p>
<br>
<div>
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>