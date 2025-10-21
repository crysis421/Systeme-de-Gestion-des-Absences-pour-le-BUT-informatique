<?php
$host = "iutinfo-sgbd.uphf.fr";
$user = "iutinfo474";
$password = "uwkXBERC";
$dbname = "iutinfo474";


try {
    $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "connected <br>";
    $req2 = $conn1->prepare("SELECT 
    s.idSeance,
    s.date,
    s.heureDebut,
    s.typeSeance,
    s.enseignement,
    s.salle,
    s.prof,
    s.duree,
    a.idAbsence,
    a.idEtudiant,
    a.statut,
    a.estRetard
FROM Absence a
INNER JOIN Seance s ON a.idSeance = s.idSeance
ORDER BY s.date DESC, s.heureDebut DESC, a.idEtudiant;");
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
?>