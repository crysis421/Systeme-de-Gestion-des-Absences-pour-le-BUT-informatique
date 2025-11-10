<?php

require_once '../Model/insertDataVT.php';

use Model\insertDataVT;

//On vérifie le bon envoie du fichier et le type du fichier
if (isset($_POST["submit"]) and $_FILES["fileToUpload"]["type"] == 'text/csv') {

    $row = 0; //Nombre de ligne du CSV
    $nbUtilisateur = 0;//Nombre d'utilisateurs créé
    $nbCour = 0;//Nombre de cours créé

    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        $addData = new insertDataVT(); //Nouvelle connection pour ajouter toutes les données
        $dejaPresent = $addData->getUtilisateurAndCours(); //Fonction pour obtenir tous les utilisateurs et les cours deja present dans la base

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { //Tant qu'il y a des données dans notre table

            $ligne = explode(";", $data[0]);// On separe en plusieurs element la ligne qu'on stock dans une array

            if ($ligne[1] != "Prénom") { // On skip la premiere ligne qui sont juste le nom des colonnes
                $row++;
                //Si l'utilisateur n'existe pas dans la base, alors on rajoute cet utilisateur dans la base
                if (!in_array($ligne[4], array_column($dejaPresent, "idutilisateur"))) {
                    $addData->addUtilisateur($ligne[4], $ligne[0], $ligne[1], $ligne[2], $ligne[4], $ligne[20], null, $ligne[5]);
                    $dejaPresent["idutilisateur"][] = $ligne[4];//On évite de le rajouter plusieurs fois
                    $nbUtilisateur++;
                }
                //Si le cours n'existe pas dans la base, alors on rajoute ce cours dans la base
                if (!in_array($ligne[13], array_column($dejaPresent, "idcours"))) {
                    $addData->addCour($ligne[13], 2, $ligne[12]);
                    $dejaPresent["idcours"][] = $ligne[13];
                    $nbCour++;
                }
                //Enfin, on ajoute tout le reste des données
                $addData->addDataVT($ligne[4], $ligne[8], $ligne[9], $ligne[10], $ligne[11], $ligne[13], $ligne[14], $ligne[17], $ligne[18], $ligne[21], $ligne[22], $ligne[23], $ligne[16], $ligne[19]);
            }
        }
        //On ferme la connection
        $addData = null;
        //On ferme le fichier
        fclose($handle);
    }
    //Fin du traitement, on informe l'utilisateur du succès de l'insertion des données
    echo $row . " Absences ont été téléchargées avec succès dans la base de données.<br> Dont " . $nbUtilisateur . " nouveaux utilisateurs et " . $nbCour . " nouveaux cours.";

    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
} else {
    echo "Aucun fichier n’a été téléchargé ou le type de fichier n’est pas pris en charge.";
    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
}




