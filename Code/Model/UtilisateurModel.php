<?php

require_once "Database.php";

class UtilisateurModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM Utilisateur";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM Utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function add($nom, $prenom, $email, $mdp, $role, $groupe, $dateNaissance, $composante, $diplome)
    {
        $sql = "INSERT INTO Utilisateur 
            (nom, prenom, email, motDePasse, role, groupe, dateDeNaissance, composante, diplome)
            VALUES (:nom, :prenom, :email, :mdp, :role, :groupe, :dateNaissance, :composante, :diplome)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ":nom" => $nom,
            ":prenom" => $prenom,
            ":email" => $email,
            ":mdp" => password_hash($mdp, PASSWORD_BCRYPT), // hash password 🔒
            ":role" => $role,
            ":groupe" => $groupe,
            ":dateNaissance" => $dateNaissance,
            ":composante" => $composante,
            ":diplome" => $diplome
        ]);
    }


}

