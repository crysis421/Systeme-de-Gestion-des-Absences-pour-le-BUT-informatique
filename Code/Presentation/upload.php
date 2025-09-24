
<?php
if(isset($_POST["submit"])) {
    $row = 1;
    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            echo "<p> $num champs à la ligne $row: <br /></p>\n";
            $row++;
            $ligne = explode(";",$data[0]);
            foreach ($ligne as $value) {
                echo " $value ;";
            }
            echo "<br />";



        }
        fclose($handle);
    }
}else{
    echo "Aucun fichier n'a ete uploader.";
}
?>


