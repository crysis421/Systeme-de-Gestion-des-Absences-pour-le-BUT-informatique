<?php

require_once '../Model/insertDataVT.php';

use Model\insertDataVT;

if (isset($_POST["submit"])) {
    $row = 0;
    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        $addData = new insertDataVT();
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $row++;
            $ligne = explode(";", $data[0]);
            if ($ligne[1] != "Prénom") {
                $addData->addUtilisateur($ligne[4], $ligne[0], $ligne[1], $ligne[2], $ligne[4], $ligne[20], null, $ligne[5]);
                $addData->addCour($ligne[13], 2, $ligne[12]);
                $addData->addDataVT($ligne[4], $ligne[8], $ligne[9], $ligne[10], $ligne[11], $ligne[13], $ligne[14], $ligne[17], $ligne[18], $ligne[21], $ligne[22], $ligne[23], $ligne[16]);
            }
        }
        fclose($handle);
    }
    echo $row . " Absence on été uploader avec succes dans la base";
    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
} else {
    echo "Aucun fichier n'a été uploadé.";
    ?>
    <form action="../Vue/formulaireVT.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
    <?php
}




