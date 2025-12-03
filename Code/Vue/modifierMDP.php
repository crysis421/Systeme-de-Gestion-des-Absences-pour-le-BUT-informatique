<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modifier votre mot de passe</title>
</head>
<body>
<h1>Réinitialiser votre Mot de passe</h1>


<?php
session_start();
if (isset($_SESSION['erreur'])) {
    echo '<p style="color:red; font-weight:bold;">' . htmlspecialchars($_SESSION['erreur']) . '</p>';
    unset($_SESSION['erreur']);
}
?>>

<form id="form" action="../Presentation/modifierMDPetudiant.php" method="post" style="background-color: #efefef; border: 1px solid #849584; border-radius: 6px; padding: 20px" >
    <input type="hidden" name="formulaire" value="formulaire1">

    <label for="Email">
        Entrer votre adresse mail :* <input type="email" name="email1" placeholder="adresse mail" required>
    </label> <br>
    <br>
    <label for="Mot de passe">
        Entrer votre nouveau mot de passe :* <input type="password" name="motDePasse" placeholder="mot de passe" required>
    </label> <br>
    <br>
    <label for="confirmation">
        Confirmer votre nouveau mot de passe :* <input type="password" name="confirmationMotDePasse" placeholder="Confirmer votre mot de passe" required onpaste="return false" ><!-- on peut ajouter oncopy="return false" oncut="return false" pour bloquer la copie et la coupure -->
    </label> <br>
    <a style="font-family: Arial; color: red; font-size: 11px;">Tous les champs marqués avec * sont obligatoires.</a><br>
    <br>
    <input   type="submit" value="valider" style=" background-color:#007BFF; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px; border-radius: 10px;">
</form>
</body>
</html>

