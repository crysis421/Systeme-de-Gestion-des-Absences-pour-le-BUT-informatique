<link rel="stylesheet" href="../CSS/connect.css">

<body>
<header>
    <?php require_once("menuHorizontalEtu.html"); ?>
</header>
<main>
    <h1>Connection à un compte</h1>
    <div id="container">

        <form id="form" action="" method="post">
            <label for="Email">
                Entrer votre adresse mail : <input type="email" name="email" placeholder="adresse mail" required>
            </label> <br>
            <br>
            <label for="Mot de passe">
                Entrer votre mot de passe : <input type="password" name="motDePasse" placeholder="mot de passe" required>
            </label> <br>
            <a style="font-family: Arial; color: yellow; font-size: 11px;" href="MDPoublier.php">Mot de passe oublié ?</a><br>
            <br>
            <input   type="submit" value="Connexion">
        </form>
    </div>
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>








