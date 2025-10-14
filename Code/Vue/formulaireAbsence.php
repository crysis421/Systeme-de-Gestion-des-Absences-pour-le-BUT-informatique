<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>formulaireAbsent</title>
</head>
<body>
<header>
    <?php require 'menuHorizontalEtu.html'?>
</header>
<main>
    <div id="titre">
        <h1>Justificatif d'absence </h1>

        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>
    </div>

    <form action="recapitulatifJustificatifAbsence.php" method="post">
        <div id="infoAbsence">
            <br>
            <label for="nom2">L'étudiant :
                <input type="text" id="nom2" name="nom2" placeholder="entrer votre nom complet" required> a été absent :</label><br>
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

            <label for="motif">a été absent pour des raisons de : </label>
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
            <textarea id="preciserAutre" name="commentaire" style="width: 700px; height: 100px;" required></textarea>
            <br>
            <br>
            <h2> Justificatif </h2>
            <label for="justificatif">Inserer un justificatif :</label><br>
            <input type="file" id="justificatif" name="justificatif" accept=".pdf,image/*" />
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