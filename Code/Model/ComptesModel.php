<?php

use Model\Database;

require_once "Database.php";

class ComptesModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function __destruct(){
        $this->conn = null;
    }

    public function addCompte()
    {
        $sql = "INSERT INTO utilisateur VALUES(:nom,:prenom,:prenom2,:email,:motdepasse,:role,:groupe,:datenaissance,:diplome)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':prenom', $_POST['prenom']);
        $stmt->bindParam(':prenom2', $_POST['prenom2']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':motdepasse', $_POST['motdepasse']);
        $stmt->bindParam(':role', $_POST['role']);
        $stmt->bindParam(':groupe', $_POST['groupe']);
        $stmt->bindParam(':datenaissance', $_POST['datenaissance']);
        $stmt->bindParam(':diplome', $_POST['diplome']);
        $stmt->execute();
        return "Le compte a été créé correctement.";
    }

    public function connectCompte(){
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        if (!$_POST['email']=="" && !$_POST['pass']==""){
            $req = $this->conn->prepare("SELECT * FROM utilisateur WHERE email=$email and motdepasse=$pass");
            $rep=$req->fetch();
            if ($rep['id']!=false) {
                echo "Vous êtes connecté";
                sleep(5);
                if ($rep['role']=="prof") {
                    header("location:CompteEtu.php?id=$rep[id]");
                }
                else {
                    header("location:CompteResp.html?id=$rep[id]");
                }
            } else {
                echo "Email ou mot de passe incorrect";
            }
        }
    }

    public function modifieCompte(){
        $sql = "UPDATE utilisateur SET motdepasse = :motdepasse WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':motdepasse', $_POST['motdepasse']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }

    public function supprimeCompte(){
        $sql = "DELETE FROM utilisateur WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }
}