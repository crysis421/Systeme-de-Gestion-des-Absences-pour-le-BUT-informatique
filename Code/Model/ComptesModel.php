<?php

require_once "Database.php";

class AbsenceModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function creationCompte()
    {
        $sql = "INSERT INTO utilisateur VALUES(:nom,:prenom,:prenom2,:email,:motdepasse,:role,:groupe,:datenaissance,:diplome)";
    }

    public function connexionCompte(){
        $sql = "SELECT email, motdepasse FROM utilisateur WHERE email = :email AND motdepasse = :motdepasse";
        $result = $this->conn = query($sql);

        if ($result->num_rows = 1) {
            if ($result.["role"] == "prof") {
                header("Location:../Vue/CompteResp.php");
            }
            elseif ($result.["role"] == "eleve") {
                header("Location:Eleve.php");
            }
        } else {
            echo "Identifiant ou mot de passe incorrect";
        }

    }

    public function modifieCompte(){
        $sql = "UPDATE utilisateur SET motdepasse = :motdepasse WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    }

}
