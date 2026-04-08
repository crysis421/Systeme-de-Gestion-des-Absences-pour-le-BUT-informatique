<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../CSS/tableauDeBordResponsable/tableauDeBordResponsable.css?v=1"/>
    <link rel="stylesheet" href="../../CSS/tableauDeBordResponsable/tableauDeBordResponsable<?=$_SESSION['couleur']?>.css?v=1"/>
    <title>Gestionnaire d'absence Etu</title>
</head>
<body>
<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>
<ul id="ListeMenu">
    <a href="tableauDeBordEtu.php"><li>Tableau de bord</li></a>
    <a href="formulaireAbsence.php"><li>Rédiger justificatif d'absence</li></a>
    <a href="CompteEtu.php"><li>Compte</li></a>
</ul>
</body>
</html>