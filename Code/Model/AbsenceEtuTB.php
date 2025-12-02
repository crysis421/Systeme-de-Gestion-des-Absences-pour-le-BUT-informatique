<?php
namespace Model;
use PDO;

require_once "DatabaseSingleton.php";
//Classe pour la récupération de données pour le Tableau de bord de l'étudiant
class AbsenceEtuTB
{
    private $conn;

    public function __construct()
    {
        //Pour ne pas submerger notre base de donnée avec des ouvertures de connexions à chaque choix de date par un étudiant, on utilise une connexion unique. //il est trop malin celui qui a faire ca muehehe
        $database = DatabaseSingleton::getInstance();
        $this->conn = $database->getConnection();
    }

    public function __destruct(){
        $this->conn = null;
    }

    //Cette fonction nous permet de récupérer les infos d'une absence d'un étudiant à un jour précis
    public function getAbsenceDunJour($date,$idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,estRetard,heureDebut,prof,duree,enseignement,controle,verrouille FROM absence JOIN Seance using(idSeance) WHERE idEtudiant = :idEtudiant and extract('Days' from Seance.date) = :d and extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":d", $date);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cette fonction nous permet de récupérer les infos du mois d'un étudiant
    public function getAbsenceDunMois($idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,extract('Days' from Seance.date),controle FROM absence JOIN Seance using(idSeance) where extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year and idEtudiant = :idEtudiant");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cette fonction nous permet de récupérer le nom et le prenom d'un professeur grace a son identifiant, en effet dans la table Seance les profs sont sous la forme "NOM PRENOM"
    private function getProf($idProf){
        $stmt = $this->conn->prepare("select concat(nom,' ',prenom) as p from utilisateur where idUtilisateur=:p");
        $stmt->bindParam(":p", $idProf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['p'];
    }

    //Cette fonction nous permet d'avoir tous les jours où il y a eu une absence justifiée à un contrôle, utile pour créer le calendrier du prof.
    public function getAbsenceControleDunMois($mois,$year,$idProf) {
        $idProf = $this->getProf($idProf);
        $stmt = $this->conn->prepare("SELECT extract('Days' from Seance.date) as date FROM absence JOIN Seance using(idSeance) where statut = 'valide' and extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year and controle and prof = :p group by extract('Days' from Seance.date);");
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->bindParam(":p", $idProf);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cette fonction nous permet d'avoir quel étudiant a été absent à quel cours a une date précise, toujours utile pour le tableau de bord du prof.
    public function getAbsenceControleDunJour($jour,$mois,$year,$idProf) {
        $idProf = $this->getProf($idProf);
        $stmt = $this->conn->prepare("select email,heureDebut,enseignement,duree from Absence join Seance using (idSeance) join Utilisateur on absence.idEtudiant = Utilisateur.idUtilisateur where controle and prof = :id and statut='valide' and extract('Days' from Seance.date) = :d and extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year order by heureDebut ASC;");
        $stmt->bindParam(":id", $idProf);
        $stmt->bindParam(":d", $jour);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}