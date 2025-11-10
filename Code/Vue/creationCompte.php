<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../CSS/connect.css">

<body>
<header>
    <?php require_once("menuHorizontal.html"); ?>
</header>
<main>
    <h1>Création de compte</h1>
    <div id="container">

        <form id="form" action="/Presentation/creeCompte.php" method="post">
            <a style="font-family: Arial; color: yellow; font-size: 21px;">Tous les champs marqués avec * sont
                obligatoires.</a><br>
            <label for="Nom">
                Nom :* <input type="text" name="nom" placeholder="nom" required>
            </label> <br>
            <br>
            <label for="Prenom">
                Prénom :* <input type="text" name="prenom" placeholder="prénom" required>
            </label> <br>
            <br>
            <label for="Prenom2">
                Deuxième prénom : <input type="text" name="prenom2" placeholder="deuxième prénom">
            </label> <br>
            <br>
            Role dans l'université :*<br>
            <input type="radio" name="role" id='eleve' value="eleve" checked>
            <label for="eleve">Élève</label><br>
            <input type="radio" name="role" id='prof' value="prof">
            <label for="prof">Professeur</label><br>
            <input type="radio" name="role" id='secretaire' value="secretaire">
            <label for="secretaire">Secretaire</label><br>
            <input type="radio" name="role" id='respon' value="respon">
            <label for="respon">Responsable pédagogique</label><br>
            <br>
            <label for="Groupe">
                Groupe : <input type="text" name="groupe" placeholder="groupe">
            </label> <br>
            <br>
            <label for="Naissance">
                Date de naissance :* <input type="date" name="datedenaissance" required>
            </label> <br>
            <br>
            <label for="Diplome">
                Diplome : <input type="text" name="diplome" placeholder="diplome">
            </label> <br>
            <br>
            <label for="Email">
                Adresse mail :* <input type="email" name="email" placeholder="adresse mail" required>
            </label> <br>
            <br>
            <label for="Mot de passe">
                Mot de passe :* <input type="password" name="motdepasse" placeholder="mot de passe" required>
            </label> <br>
            <br>
            <input type="submit" value="Créez le compte">
        </form>
    </div>
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de
        Maubeuge.</a>
</footer>
</body>
</html>