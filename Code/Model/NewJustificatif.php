<?php

namespace Model;

use PDOException;

require_once "Database.php";



class NewJustificatif
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    ///insert dans justificatif les donnees saisis par l'etudiant et creer un nv traitementJustificatif pour la cause mais en valeur par defaut pour le reste
    public function createNewJustificatif($idAbsence, $commentaire,$datesoumission,$cause,$path,$heureDebutCours,$idJustificatif)
    {
        try {

            $req = $this->conn->prepare("INSERT INTO Justificatif(datesoumission, commentaire_absence,verrouille) values($datesoumission,$commentaire,false)");
            $req->execute();

            $req = null;

            $req = $this->conn->prepare("INSERT INTO absenceetjustificatif(idJustificatif,idAbsence) values($idJustificatif,$idAbsence)");
            $req->execute();
            $req = null;
        }

        catch(PDOException $e){
            echo $e->getMessage();
        }
    }


}