<?php

require_once '../Model/insertDataVT.php';

use Model\insertDataVT;

//On verifie le bon envoie du fichier et le type du fichier
if (isset($_POST["submit"]) and $_FILES["fileToUpload"]["type"] == 'text/csv') {

    $row = 0; //Nombre de ligne du CSV
    $nbUtilisateur = 0;//Nombre d'utilisateur créé
    $nbCour = 0;//Nombre de cour créé

    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        $addData = new insertDataVT(); //Nouvelle connection pour ajouter toute les données
        $dejaPresent = $addData->getUtilisateurAndCours(); //Fonction pour obtenir tous les utilisateurs et les cours deja present dans la base
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $ligne = explode(";", $data[0]);
            if ($ligne[1] != "Prénom") {
                $row++;
                if (!in_array($ligne[4], array_column($dejaPresent, "idutilisateur"))) {
                    $addData->addUtilisateur($ligne[4], $ligne[0], $ligne[1], $ligne[2], $ligne[4], $ligne[20], null, $ligne[5]);
                    $dejaPresent["idutilisateur"] = $ligne[4];
                    $nbUtilisateur++;
                }
                if (!in_array($ligne[13], array_column($dejaPresent, "idcours"))) {
                    $addData->addCour($ligne[13], 2, $ligne[12]);
                    $dejaPresent["idcours"] = $ligne[13];
                    $nbCour++;
                }
                $addData->addDataVT($ligne[4], $ligne[8], $ligne[9], $ligne[10], $ligne[11], $ligne[13], $ligne[14], $ligne[17], $ligne[18], $ligne[21], $ligne[22], $ligne[23], $ligne[16], $ligne[19]);
            }
        }
        fclose($handle);
    }
    echo $row . " absences ont été téléchargées avec succès dans la base de données.<br> Dont " . $nbUtilisateur . " nouveaux utilisateurs et " . $nbCour . " nouveaux cours.";

    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
} else {
    echo "Aucun fichier n’a été téléchargé ou le type de fichier n’est pas supporté.";
    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
}




