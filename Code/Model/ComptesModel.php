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

    public function __destruct()
    {
        $this->conn = null;
    }

    public function addCompte($nom, $prenom, $prenom2, $email, $motdepasse, $role, $groupe, $date, $diplome)
    {

        $sql = "INSERT INTO utilisateur(idUtilisateur,nom,prenom,prenom2,email,motdepasse,role,groupe,datedenaissance,diplome) VALUES(default,:nom,:prenom,:prenom2,:email,:motdepasse,:role,:groupe,:datenaissance,:diplome)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':prenom2', $prenom2);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':motdepasse', $motdepasse);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':groupe', $groupe);
        $stmt->bindParam(':datenaissance', $date);
        $stmt->bindParam(':diplome', $diplome);
        $stmt->execute();
        return "Le compte a été créé correctement.";
    }

    public function connectCompte($email)
    {
        $stmt = $this->conn->prepare("SELECT idUtilisateur,motdepasse,role FROM utilisateur WHERE email=:email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function modifieCompte()
    {
        $sql = "UPDATE utilisateur SET motdepasse = :motdepasse WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':motdepasse', $_POST['motdepasse']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }

    public function supprimeCompte()
    {
        $sql = "DELETE FROM utilisateur WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }
}