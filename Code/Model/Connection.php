<?php
$host = "iutinfo-sgbd.uphf.fr";
$user = "iutinfo474";
$password = "uwkXBERC";
$dbname = "iutinfo474";


try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "connected <br>";
    $req2 = $conn1->prepare("select * from Utilisateur;");
    $req2->execute();
    $r = $req2->fetchAll(PDO::FETCH_ASSOC);
    foreach($r as $row){
        foreach($row as $key => $value){
            echo $key.": ".$value."<br>";
        }
        echo "<br>";
    }
}catch (PDOException $e){
    echo $e->getMessage();
}