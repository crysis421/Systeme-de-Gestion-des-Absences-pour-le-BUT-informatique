
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../CSS/connect.css">

<body>
<header>
    <?php
    session_destroy();
    session_start();
    $_SESSION["user"] = null;
    $erreur = "";
    require_once("menuHorizontal.html");
    ?>
</header>
<main>
    <h1>Connection à un compte</h1>

    <div id="container">
        <form id="form" action="../Presentation/connexion.php" method="post">


            <label for="Email">
                Adresse mail : <input type="email" name="email" placeholder="adresse mail" required>
            </label> <br>
            <br>
            <label for="Mot de passe">
                Mot de passe : <input type="password" name="motDePasse" placeholder="mot de passe" required>
            </label> <br>
            <label>
                <?php
                // Affichage d'un message d'erreur s'il existe
                if (isset($_SESSION["erreur"])) {
                    echo "<p id='erreur' style='color:red; font-weight:bold;'>".$_SESSION["erreur"]."</p>";
                    unset($_SESSION["erreur"]); // On le supprime après affichage
                }
                ?>
            </label>
            <a style="font-family: Arial; color: yellow; font-size: 11px;" href="MDPoublier.php">Mot de passe oublié ?</a><br>
            <br>
            <input   type="submit" value="Connexion">
            <br>
            <br>

        </form>
    </div>
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>






