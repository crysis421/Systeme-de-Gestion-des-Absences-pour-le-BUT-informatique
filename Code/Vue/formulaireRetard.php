<?php
require 'menuHorizontalEtu.html'

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formuaireAbsence.css" />
    <title>FormulaireRetard</title>
</head>
<body>
<h1>Justificatif de retard </h1>

<p><b>Important : </b> Ce formulaire doit être entièrement complété.</p>
<form action="recaptitultifJustificatifRetard.php" method="post">
    <div id="infos">
        <h2>1) Informations de l'étudiant </h2>
        <label for="nom">Nom *: </label>
        <input type="text" id="nom" name="nom" placeholder="entrer votre nom" required><br>
        <br>

        <label for="prenom">Prénom *: </label>
        <input type="text" id="prenom" name="prenom" placeholder="entrer votre prénom" required><br>
        <br>

        <label for="filiere">Filière/Formation *: </label>
        <select id="filiere" name="filiere" required>
            <option value="BUT1INFOA" >BUT INFO 1 A</option>
            <option value="BUT1INFOB">BUT INFO 1 B</option>
            <option value="BUT1INFOC">BUT INFO 1 C</option>
            <option value="BUT2INFOFIA" >BUT INFO 2 FI A</option>
            <option value="BUT2INFOFIB">BUT INFO 2 FI B</option>
            <option value="BUT2INFOFA">BUT INFO 2 FA</option>
            <option value="BUT3INFOFI">BUT 3 INFO FI</option>
            <option value="BUT3INFOFA">BUT 3 INFO FA</option>

            <option value="BUT1INFOA" >BUT MP 1 </option>
            <option value="BUT1INFOB">BUT MP 2 </option>
            <option value="BUT1INFOC">BUT MP 3 </option>
        </select><br>

    </div>

    <div id="inforetard">

        <h2>2) Informations à propos du retard</h2>


        <label for="dateretard"> Date du jour :</label>
        <input type="date" id="dateretard" name="dateretard" required>

        <label for="heurearrive"> heure d'arrivée :</label>
        <input type="time" id="heurearrive" name="heurearrive" required><br>
        <br>
        <label for="cours"> Matière :</label>
        <input type="text" id="cours" name="cours" required>

        <label for="motif">est arrivée en retard pour des raisons de : </label>
        <select id="motif" name="motif" required>
            <option value="Problème de santé" >Problème de santé</option>
            <option value="transport">Problème de transport</option>
            <option value="Problème de transport">problèmes d'inscription</option>
            <option value="cours de conduite obligatoire" >cours de conduite obligatoire</option>
            <option value="Rendez vous chez le medicin">Rendez vous chez le medicin</option>
            <option value="aucune raison valable">aucune raison valable</option>
            <option value="autres">Autres</option>

        </select><br>
        <br>

        <label for="preciserAutre">Commentaires :</label><br>
        <textarea id="preciserAutre" name="preciserAutre" style="width: 700px; height: 100px;"></textarea>
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
        <p> Déclarez vous sur l'honneur que les faits décrits ci‑dessus sont exacts et que les pièces justificatives fournies sont authentiques ?<br>
            <label for="oui"><b>oui:</b></label>
            <input type="radio" id="oui" name="signer" required>
        </p>
        <br>
        <br>
        <label for="dateSignature"><b>Fait le : </b> </label>
        <input type="date" id="dateSignature" name="dateSignature" required>
        <label from="heuresignature"> <b>à quelle heure ? :</b></label>
        <input id="heuresignature" type="time" name="heuresignature"><br>
        <br>
        <label for="lieuSignature"><b>A : </b> </label>
        <input type="text" id="lieuSignature" name="lieuSignature" required><br>
        <br>
        <br>
        <br>
        <input type="submit" value="valider">
        <br>
        <br>
    </div>
</form>
</body>
</html>