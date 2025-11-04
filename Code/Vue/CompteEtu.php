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
            <img src="/Image/profil-removebg-preview.png" alt="Photo de profil">
            <h2>Profil</h2>
            <p><b>Nom :</b> Dupont</p>
            <p><b>Prénom :</b> Christian</p>
            <p><b>Numéro d'étudiant :</b> 123456</p>
            <p><b>Email :</b> christian@example.com</p>
            <p><b>Formation :</b> BUT Informatique</p>
            <p><b>Groupe :</b> A2</p>
            <button>Modifier le profil</button>
        </div>

        <div id="donnees">
            <h2>Mes absences</h2>
            <p><b>Nombre d'absences :</b> 5</p>
            <p><b>Justifiées :</b> 3</p>
            <p><b>Non justifiées :</b> 2</p>
        </div>
    </div>

    <!-- Section du bas, centrée -->
    <div id="bas">
        <div id="graphe">
            <b>Ajouter des diagrammes ici</b>
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
