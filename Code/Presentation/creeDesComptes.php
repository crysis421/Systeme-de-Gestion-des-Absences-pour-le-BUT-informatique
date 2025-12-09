<?php

require_once '../Model/insertDataVT.php';

use Model\insertDataVT;

//On vérifie le bon envoie du fichier et le type du fichier
if (isset($_POST["submit"]) and $_FILES["fileToUpload"]["type"] == 'text/csv') {
    $row=0;

    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        $addData = new insertDataVT(); //Nouvelle connection pour ajouter toutes les données

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Tant qu'il y a des données dans notre table

            $ligne = explode(";", $data[0]);// On separe en plusieurs element la ligne qu'on stock dans une array
            if ($ligne[1] != "Prénom") { // On skip la premiere ligne qui sont juste le nom des colonnes
                $row++;
                $addData->addUtilisateur($ligne[4],$ligne[0],$ligne[1],$ligne[2],$ligne[7],$ligne[6],$ligne[3],$ligne[5]);
            }
        }
        //On ferme la connection
        $addData = null;
        //On ferme le fichier
        fclose($handle);
    }
//Fin du traitement, on informe l'utilisateur du succès de l'insertion des données
    echo $row . " Eleve ont été téléchargées avec succès dans la base de données.";

    ?>
    <form action="/Systeme-de-Gestion-des-Absences-pour-le-BUT-informatique/Vue/creationCompte.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
} else {
    echo "Aucun fichier n’a été téléchargé ou le type de fichier n’est pas pris en charge.";
    ?>
    <form action="/Systeme-de-Gestion-des-Absences-pour-le-BUT-informatique/Vue/creationCompte.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
}




