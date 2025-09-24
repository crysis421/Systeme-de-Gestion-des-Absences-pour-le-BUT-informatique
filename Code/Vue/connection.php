<?php
require_once("menuHorizontal.html");
?>
<h1>Connection à un compte</h1>

<form action="" method="post">

    <label for="Email">
        Email : <input type="email" name="email" placeholder="EMAIL" required>
    </label> <br>
    <br>
    <label for="Mot de passe">
        Mot de passe : <input type="password" name="motDePasse" placeholder="MDP" required>
    </label> <br>
    <a style="font-family: Arial; color: blue; font-size: 11px;" href="MDPoublier.php">Mot de passe oublié ?</a><br>
    <br>
    <input type="submit" value="Connexion">

</form>





