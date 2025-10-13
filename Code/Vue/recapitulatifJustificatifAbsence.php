<?php
$nom = $_POST['nom2'];

$datedebut = $_POST['datedebut'];
$heuredebut = $_POST['heuredebut'];
$fin = $_POST['fin'];
$heurefin1 = $_POST['heurefin1'];
$motif = $_POST['motif'];
$commentaire = $_POST['commentaire'];

$justificatif = $_POST['justificatif'];

$date_debut_ts = strtotime($datedebut . ' ' . $heuredebut);
$date_fin_ts = strtotime($fin . ' ' . $heurefin1);

if ($date_fin_ts < $date_debut_ts) {
    echo "<h1>ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!
ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!
ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!
ERROR!!!!!!!!!!!!!!!!!!!!ERROR!!!!!!!!!!!!!!!!!!!!</h1>";
    echo "<p1>veillez entrer une date correcte...</p1>";
}else {
    echo ('<h1 style="text-align: center"> Recapitulatif du justificatif d absence de ' . htmlspecialchars($nom) . ' </h1>');
    echo '
<div id="autre">
    <p>L\'étudiant <b>' . htmlspecialchars($nom) . '</b> a été absent du : <b>' . htmlspecialchars($datedebut) . '</b> à : <b>' . htmlspecialchars($heuredebut) . '</b> au : <b>' . htmlspecialchars($fin) . '</b> à : <b>' . htmlspecialchars($heurefin1) . '</b>.</p><br>
    
    <p>Le motif de cette absence est : <b>' . htmlspecialchars($motif) . '</b>.<br><br><em>Commentaires : "<b>' . htmlspecialchars($commentaire) . '</b>"</em>.</p>
    <br>
    <p>Justificatif : <b>' . htmlspecialchars($justificatif) . '</b></p>
    <br>
</div>';


    echo ('<div id="bouton">
    <a href="formulaireAbsence.php"><button>retour</button></a>
    <button style="margin-left: 50px;">envoyer</button>
</div>');
}
?>












