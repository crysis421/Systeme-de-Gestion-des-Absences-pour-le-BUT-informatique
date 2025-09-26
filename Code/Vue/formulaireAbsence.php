<?php
require 'menuHorizontalEtu.html'

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>formulaireAbsent</title>
</head>
<body>
<h1>Justificatif d'absence </h1>

<p><b>Important : </b> Ce formulaire doit être complété et accompagné des pièces justificatives (certificat médical, convocation, attestation, etc.) selon le motif choisi. Remettez-le au secrétariat de votre composante ou envoyez-le par courriel à l'adresse indiquée par votre université.</p>
<form action="recapitulatifJustificatifAbsence.php" method="post">
<div id="infos">
    <h2>1) Informations de l'étudiant </h2>
    <label for="nom">Nom *: </label>
    <input type="text" id="nom" name="nom" placeholder="entrer votre nom" required><br>
    <br>

    <label for="prenom">Prénom *: </label>
    <input type="text" id="prenom" name="prenom" placeholder="entrer votre prénom" required><br>
    <br>

    <label for="numeroE">Votre numéro d'étudiant *: </label>
    <input style="width: 250px" type="text" id="numeroE" name="numeroE" placeholder="entrer votre numéro d'étudiant" required><br>
    <br>

    <label for="filiere">Filière/Formation *: </label>
    <input type="text" id="filiere" name="filiere" placeholder="entrer votre filière" required><br>
    <br>

    <label for="annee">Année d'études *: </label>
    <input type="text" id="annee" name="annee" placeholder="1ere année " required><br>
    <br>

    <label for="mail">Adresse mail *: </label>
    <input type="email" id="mail" name="mail" placeholder="entrer votre adresse mail" required><br>
    <br>

    <label for="numero">Numéro de téléphone : </label>
    <input type="tel" id="numero" name="numero" placeholder="entrer votre votre numéro de téléphone" required><br>
    <br>
</div>

<div id="infoAbsence">

    <h2>2) Informations à propos de l'absence</h2>

    <label for="nom2">L'étudiant
    <input type="text" id="nom2" name="nom2" required> a été absent :</label><br>
    <br>
    <label for="datedebut"> Du :</label>
    <input type="date" id="datedebut" name="datedebut" required>

    <label for="heuredebut"> de :</label>
    <input type="time" id="heuredebut" name="heuredebut" required><br>
    <br>
    <label for="fin">Au :</label>
    <input type="date" id="fin" name="fin" required>

    <label for="heurefin1"> à :</label>
    <input type="time" id="heurefin1" name="heurefin1" required><br>
    <br>

    <label for="motif">pour des raisons de : </label>
    <select id="motif" name="motif" required>
        <option value="sante" >santé</option>
        <option value="transport">transport</option>
        <option value="inscription">problèmes d'inscription</option>
        <option value="conduiteObligatoire" >cours de conduite obligatoire</option>
        <option value="medecin">Rendez vous chez le medicin</option>
        <option value="aucune raison valable">aucune raison valable</option>
        <option value="autres">Autres</option>

    </select><br>
    <br>
    <label for="preciserAutre">Commentaires :</label><br>
    <textarea id="preciserAutre" name="commentaire" style="width: 700px; height: 100px;" required></textarea>
    <br>
    <br>
    <h2>3) Justificatif </h2>
    <label for="justificatif">Inserer un justificatif :</label><br>
    <input type="file" id="justificatif" name="justificatif" accept=".pdf,image/*" />
    <br>
    <br>
    <br>
</div>
<div id="signature">
    <label for="nom3">Je soussigné(e) (Nom, Prénom) :</label>
    <input type="text" id="nom3" name="nom3" placeholder="nom et prenom " required>
    <p> Déclare sur l'honneur que les faits décrits ci‑dessus sont exacts et que les pièces justificatives fournies sont authentiques.</p>

    <label for="dateSignature"><b>Fait le : </b> </label>
    <input type="date" id="dateSignature" name="dateSignature" required><br>
    <br>
    <label for="lieuSignature"><b>A : </b> </label>
    <input type="text" id="lieuSignature" name="lieuSignature" required><br>
    <br>
    <br>
    <p><b>Je signe :</b></p>
    <label for="oui" ><b>oui:</b></label>
    <input type="radio" id="oui" name="signer" required>
    <label for="non"><b>non:</b></label>
    <input type="radio" id="non" name="signer">
    <br>
    <br>
    <input type="submit" value="valider">
    <br>
    <br>

</div>
</form>
</body>
</html>