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
        <h1>Justificatif de retard </h1>

        <p id="important"><b>Important : </b>  Ce formulaire doit être complété et accompagné des pièces justificatives (certificat médical, convocation, attestation, etc.) selon le motif choisi. Remettez-le au secrétariat de votre composante ou envoyez-le par courriel à l'adresse indiquée par votre université.</p>
    </div>
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
            <input  type="number" id="numeroE" name="numeroE" placeholder="entrer votre numéro d'étudiant" required><br>
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
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>