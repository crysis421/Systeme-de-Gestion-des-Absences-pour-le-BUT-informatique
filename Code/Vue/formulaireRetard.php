<?php
require 'menuHorizontalEtu.html'

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FormulaireRetard</title>
</head>
<body>
<h1>Justificatif de retard </h1>

<p><b>Important : </b> Ce formulaire doit être entièrement complété.</p>
<form action="" method="post">
    <div id="infos">
        <h2>1) Informations de l'étudiant </h2>
        <label for="nom">Nom *: </label>
        <input type="text" id="nom" name="nom" placeholder="entrer votre nom"><br>
        <br>

        <label for="prenom">Prénom *: </label>
        <input type="text" id="prenom" name="prenom" placeholder="entrer votre prénom"><br>
        <br>

        <label for="filiere">Filière/Formation *: </label>
        <select id="filiere" name="filiere">
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
        <input type="date" id="dateretard" name="dateretard">

        <label for="heurearrive"> de :</label>
        <input type="time" id="heurearrive" name="heurearrive"><br>
        <br>

        <p>Pour des raison de:</p>
        <input type="radio" id="sante" name="motif">
        <label for="sante"> santé</label><br>

        <input type="radio" id="inscriptions" name="motif">
        <label for="inscriptions">Inscription</label><br>

        <input type="radio" id="personnel" name="motif">
        <label for="personnel">raisons personnelles</label><br>

        <input type="radio" id="transport" name="motif">
        <label for="transport">transport</label><br>

        <input type="radio" id="autres" name="motif">
        <label for="autres">autres</label><br>
        <br>
        <label for="preciserAutre">Commentaires :</label><br>
        <textarea id="preciserAutre" style="width: 700px; height: 100px;"></textarea>
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
        <input type="text" id="nom3" name="nom3" placeholder="nom et prenom ">
        <p> Déclare sur l'honneur que les faits décrits ci‑dessus sont exacts et que les pièces justificatives fournies sont authentiques.</p>

        <label for="dateSignature"><b>Fait le : </b> </label>
        <input type="date" id="dateSignature" name="dateSignature"><br>
        <br>
        <label for="lieuSignature"><b>A : </b> </label>
        <input type="texte" id="lieuSignature" name="lieuSignature"><br>
        <br>
        <br>
        <p><b>Je signe :</b></p>
        <label for="oui"><b>oui:</b></label>
        <input type="radio" id="oui" name="signer">
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