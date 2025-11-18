<?php

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../CSS/formulaireVT.css">
    <title>Ajout d'un FichierVT</title>
</head>

<body>
<header>
    <?php require 'menuHorizontaleSecretaire.html'; ?>
</header>
<h1>Ajout d'un fichier VT</h1>

<form action="../Presentation/upload.php" method="post" enctype="multipart/form-data">
    <label for="VT">
        FichierVT <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" required >
    </label> <br>
    <input type="submit" value="Télécharger le fichier VT" name="submit" >
</form>
<main>

</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>
