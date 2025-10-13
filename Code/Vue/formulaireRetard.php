<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>FormulaireRetard</title>
</head>
<body>
<header>
    <?php require 'menuHorizontalEtu.html' ?>
</header>
<main>
    <div id="titre">
        <h1>Justificatif de retard </h1>

        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>
    </div>
<form action="recaptitultifJustificatifRetard.php" method="post">
    <div id="infos">
        <br>
        <label for="nom">Nom *: </label>
        <input type="text" id="nom" name="nom" placeholder="entrer votre nom" required><br>
        <br>

        <label for="prenom">Prénom *: </label>
        <input type="text" id="prenom" name="prenom" placeholder="entrer votre prénom" required><br>
        <br>

    </div>

    <div id="inforetard">

        <label for="dateretard"> Date du jour :</label>
        <input type="date" id="dateretard" name="dateretard" required>

        <label for="heurearrive"> heure d'arrivée :</label>
        <input type="time" id="heurearrive" name="heurearrive" required><br>
        <br>
        <label for="cours"> Matière :</label>
        <input type="text" id="cours" name="cours" required>

        <label for="motif">Motif du retard : </label>
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

        <h2> Justificatif </h2>
        <label for="justificatif">Inserer un justificatif :</label><br>
        <input type="file" id="justificatif" name="justificatif" accept=".pdf,image/*" />
        <br>
        <br>
        <br>
    </div>
    <div id="signature">
        <p> Déclarez-vous sur l'honneur que les faits décrits ci‑dessus sont exacts et que les pièces justificatives fournies sont authentiques ?<br>
            <label for="oui"><b>Oui:</b></label>
            <input type="radio" id="oui" name="signer" required>
        </p>
        <br>
        <input type="submit" value="valider">
        <br>
        <br>
    </div>
</form>
</main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>