<?php
namespace Model;

use PDO;
use PDOException;

require_once "Database.php";

class Etudiants
{
    private $conn;

    public function __construct()
    {
        //Pour ne pas submerger notre base de donnée avec des ouvertures de connexions à chaque choix de date par un étudiant, on utilise une connexion unique.
        $database = DatabaseSingleton::getInstance();
        $this->conn = $database->getConnection();
    }

    public function __destruct(){
        $this->conn = null;
    }

    //Cette fonction recupere l'ensemble des étudiants
    public function getAllEleves($idEtudiant)
    {
        $stmt = $this->conn->prepare("SELECT * FROM utilisateur WHERE role = 'eleve'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}