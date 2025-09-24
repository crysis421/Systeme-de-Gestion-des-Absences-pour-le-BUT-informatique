<?php
require 'menuHorizontalEtu.html'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mot de passe oublié</title>
</head>
<body>

<h1 style="text-align: center;">Réinitialiser votre mot de passe</h1>
<br>
<br>
<br>
<form action="" method="post">
<label style="display: block; text-align: center; font-size: 20px" for="mail">
    <b>entrer votre adresse mail:</b> <br>
    <br>
    <input type="email" id="mail" name="mail" placeholder="entrer votre adresse mail" style="width :300px" required>
</label><br>
    <br>
    <div style="text-align: center">
        <input type="submit" value="recevoir un mot de passe par mail" >
    </div>
</form>
<br>
<br>
<br>
<div style="padding-left: 500px">
    <a href="connection.php"><button>retour</button></a>
</div>

</body>
</html>