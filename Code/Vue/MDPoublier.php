
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/connect.css">
    <title>mot de passe oublié</title>
</head>
<body>
<header>
    <?php require 'menuHorizontalEtu.html' ?>
</header>
<main style="background-color: white">
    <h1 style="text-align: center;">Réinitialiser votre mot de passe</h1>

    <div id="container">
        <form id="form" action="" method="post">
            <label style="display: block; text-align: center; font-size: 20px" for="mail">
                <b>entrer votre adresse mail :</b> <br>
                <br>
                <input type="email" id="mail" name="mail" placeholder="entrer votre adresse mail" style="width :300px" required>
            </label><br>
            <br>
            <div >
                <input type="submit" value="recevoir un mot de passe par mail" >
            </div>
        </form>
        <div >
            <a href="Connexion.php"><button>retour</button></a>
        </div>

    </div>
</main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>