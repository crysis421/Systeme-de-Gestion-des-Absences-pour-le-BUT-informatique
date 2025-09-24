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

    public function getAllAbsence()
    {
        $sql = "
            SELECT 
                a.idAbsence,
                a.statut,
                u.idUtilisateur,
                u.nom AS nom_etudiant,
                u.prenom AS prenom_etudiant,
                s.idSeance,
                s.heureDebut,
                s.date,
                s.typeSeance,
                s.enseignement,
                c.idCours,
                c.matiere
            FROM Absence a
            JOIN Utilisateur u ON a.idUtilisateur = u.idUtilisateur
            JOIN Seance s ON a.idSeance = s.idSeance
            JOIN Cours c ON s.idCours = c.idCours
            ORDER BY s.date, s.heureDebut
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByUser($idUtilisateur)
    {
        $sql = "
            SELECT 
                a.idAbsence,
                a.statut,
                s.date,
                s.heureDebut,
                s.typeSeance,
                c.matiere
            FROM Absence a
            JOIN Seance s ON a.idSeance = s.idSeance
            JOIN Cours c ON s.idCours = c.idCours
            WHERE a.idUtilisateur = :idUtilisateur
            ORDER BY s.date, s.heureDebut
        ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":idUtilisateur", $idUtilisateur, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }



    public function delete($idAbsence)
    {
        $sql = "DELETE FROM Absence WHERE idAbsence = :idAbsence";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([":idAbsence" => $idAbsence]);
    }
}

