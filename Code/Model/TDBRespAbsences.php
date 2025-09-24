<?php
session_start();
include("../Vue/tableauDeBordRespAbsences.html");
require_once "Database.php";

if (!empty($_POST["toggle1"])) {
    $sql = "SELECT * FROM traitementJustificatif WHERE attente=1";
    $resultat = $this->conn->query($sql);

    if ($resultat->num_rows > 0) {
        while ($row = $resultat->fetch_assoc()) {
            echo "ID : " . $row['nom'] . " - Nom : " . $row['prenom'] . "<br>";
        }
    }
}

?>