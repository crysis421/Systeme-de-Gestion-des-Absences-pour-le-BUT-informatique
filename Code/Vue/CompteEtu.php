<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css" />
    <title>Mon compte</title>
</head>

<body>
<header>
    <?php require 'menuHorizontalEtu.html'; ?>
</header>

<main>
    <h1>Bonjour Christian ! 👋</h1>

    <!-- Section du haut : Profil à gauche / Données à droite -->
    <div id="haut">
        <div id="profil">
            <details>
                <summary>
                    <h1>Profil</h1>
                    <img src="/Image/profil-removebg-preview.png" alt="Photo de profil">
                </summary>

                <p><b>Nom :</b> Dupont</p>
                <p><b>Prénom :</b> Christian</p>
                <p><b>Numéro d'étudiant :</b> 123456</p>
                <p><b>Email :</b> christian@example.com</p>
                <p><b>Formation :</b> BUT Informatique</p>
                <p><b>Groupe :</b> A2</p>
                <details id="modifier">
                    <summary id="modif">
                        <b>Modifier votre mot de passe</b>
                    </summary>
                    <h1></h1>
                    <form id="form" action="" method="post" style="background-color: #efefef; border: 1px solid #849584; border-radius: 6px; padding: 20px">
                        <label for="Email">
                            Entrer votre adresse mail :* <input type="email" name="email" placeholder="adresse mail" required>
                        </label> <br>
                        <br>
                        <label for="Mot de passe">
                            Entrer votre mot de passe :* <input type="password" name="motDePasse" placeholder="mot de passe" required>
                        </label> <br>
                        <a style="font-family: Arial; color: red; font-size: 11px;">Tous les champs marqués avec * sont obligatoires.</a><br>
                        <br>
                        <input   type="submit" value="valider">
                    </form>
                </details>
            </details>
        </div>

        <div id="donnees">
            <details>
                <summary>
                    <h1>Mes absences</h1>
                </summary>
                <p><b>Nombre d'absences :</b> 5</p>
                <p><b>Justifiées :</b> 3</p>
                <p><b>Non justifiées :</b> 2</p>
            </details>
        </div>
    <!-- Section du bas, centrée -->
        <div id="graphe">
            <details>
                <summary>
                    <h1>statistiques   📊</h1>
                </summary>
                <h2>Ajouter des diagrammes</h2>
            </details>
        </div>
    </div>
</main>

<footer id="footer">
    <a href="https://www.uphf.fr/" style="color: black; text-decoration: none;">
        &copy; 2025 Université Polytechnique Haut-de-France / IUT de Maubeuge
    </a>
</footer>
</body>
</html>
