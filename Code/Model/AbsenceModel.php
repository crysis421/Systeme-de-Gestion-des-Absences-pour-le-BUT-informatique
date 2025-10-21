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

    public function __destruct(){
        $this->conn = null;
    }

    public function traiterJustificatif($idJustificatif, $decision, $attente, $commentaire = '', $cause = '')
    {
        $update = $this->conn->prepare("
                UPDATE traitementjustificatif
                SET attente = :attente,
                    reponse = :reponse,
                    commentaire_validation = :commentaire,
                    cause = :cause,
                    date = NOW()
                WHERE idJustificatif = :id
            ");

        $update->bindValue(':id', $idJustificatif, PDO::PARAM_INT);
        $update->bindValue(':attente', (bool)$attente, PDO::PARAM_INT);
        $update->bindValue(':reponse', $decision);
        $update->bindValue(':commentaire', $commentaire);
        $update->bindValue(':cause', $cause);
        $update->execute();
    }


    public function CHECKSIENATTENTE() {
        $sql = "
            SELECT attente FROM traitementjustificatif WHERE attente = TRUE and reponse = 'enAttente'
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getJustificatifDetails($idJustificatif) {
        $sql = "
            SELECT 
            j.idJustificatif,
            j.datesoumission AS date_soumission,
            j.commentaire_absence AS commentaire_justificatif,
            j.verrouille,
            
            t.idTraitement,
            t.attente,
            t.reponse,
            t.commentaire_validation AS commentaire_traitement,
            t.date AS date_traitement,
            t.cause,
            
            u.idUtilisateur,
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            
            a.idAbsence,
            a.statut AS statut_absence,
            s.date AS date_seance,
            s.heuredebut,
            s.typeseance AS type_seance,
            c.matiere
                
            FROM justificatif j
            JOIN absenceetjustificatif aj ON j.idJustificatif = aj.idJustificatif
            JOIN absence a ON aj.idAbsence = a.idAbsence
            JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
            JOIN seance s ON a.idSeance = s.idSeance
            JOIN cours c ON s.idCours = c.idCours
            LEFT JOIN traitementjustificatif t ON j.idJustificatif = t.idJustificatif
            WHERE j.idJustificatif = :idJustificatif
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idJustificatif', $idJustificatif, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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
        $result = $stmt->fetchAll();
        if(empty($result)){
            echo "aucune absence trouvé pour cette identifiant";
        }
        return $result;
    }

    public function getJustificatifsAttente() {
        $sql = "
        SELECT 
            j.idJustificatif,
            j.datesoumission,
            j.commentaire_absence AS commentaire_justificatif,
            j.verrouille,
            u.idUtilisateur,
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            a.idAbsence,
            a.statut AS statut_absence,
            s.date AS date_seance,
            s.heuredebut,
            s.typeseance AS typeSeance,
            c.matiere,
            t.idTraitement,
            t.attente,
            t.reponse,
            t.commentaire_validation AS commentaire_traitement
        FROM justificatif j
        JOIN absenceetjustificatif aj ON j.idJustificatif = aj.idJustificatif
        JOIN absence a ON aj.idAbsence = a.idAbsence
        JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
        JOIN seance s ON a.idSeance = s.idSeance
        JOIN cours c ON s.idCours = c.idCours
            LEFT JOIN traitementjustificatif t ON j.idJustificatif = t.idJustificatif
            WHERE t.attente = TRUE and reponse = 'enAttente'
        ORDER BY j.dateSoumission DESC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJustificatifsDemande() {
        $sql = "
        SELECT 
            j.idJustificatif,
            j.datesoumission,
            j.commentaire_absence AS commentaire_justificatif,
            j.verrouille,
            u.idUtilisateur,
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            a.idAbsence,
            a.statut AS statut_absence,
            s.date AS date_seance,
            s.heuredebut,
            s.typeseance AS typeSeance,
            c.matiere,
            t.idTraitement,
            t.attente,
            t.reponse,
            t.commentaire_validation AS commentaire_traitement
        FROM justificatif j
        JOIN absenceetjustificatif aj ON j.idJustificatif = aj.idJustificatif
        JOIN absence a ON aj.idAbsence = a.idAbsence
        JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
        JOIN seance s ON a.idSeance = s.idSeance
        JOIN cours c ON s.idCours = c.idCours
            LEFT JOIN traitementjustificatif t ON j.idJustificatif = t.idJustificatif
            WHERE t.attente = FALSE and reponse = 'enAttente'
        ORDER BY j.dateSoumission DESC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJustificatifsAttenteFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom) {
        $sql = "
        SELECT 
            j.idJustificatif,
            j.datesoumission,
            j.commentaire_absence AS commentaire_justificatif,
            j.verrouille,
            u.idUtilisateur,
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            a.idAbsence,
            a.statut AS statut_absence,
            s.date AS date_seance,
            s.heuredebut,
            s.typeseance AS typeSeance,
            c.matiere,
            t.idTraitement,
            t.attente,
            t.reponse,
            t.commentaire_validation AS commentaire_traitement
        FROM justificatif j
        JOIN absenceetjustificatif aj ON j.idJustificatif = aj.idJustificatif
        JOIN absence a ON aj.idAbsence = a.idAbsence
        JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
        JOIN seance s ON a.idSeance = s.idSeance
        JOIN cours c ON s.idCours = c.idCours
        LEFT JOIN traitementjustificatif t ON j.idJustificatif = t.idJustificatif
        WHERE t.attente = TRUE
        ";

        $params = [];

        //le .= c +=
        if (!empty($dateDebut) && !empty($dateFin)) {
            $sql .= " AND s.date BETWEEN :dateDebut AND :dateFin";
            $params[':dateDebut'] = $dateDebut;
            $params[':dateFin'] = $dateFin;
        }

        // % et % comme ça juste un bout de la matière ça marche genre R2.03
        if (!empty($matiere)) {
            $sql .= " AND c.matiere LIKE :matiere";
            $params[':matiere'] = "%$matiere%";
        }

        if (!empty($nom)) {
            $sql .= " AND u.nom LIKE :nom";
            $params[':nom'] = "$nom";
        }

        if (!empty($prenom)) {
            $sql .= " AND u.prenom LIKE :prenom";
            $params[':prenom'] = "$prenom";
        }

        $sql .= " ORDER BY j.dateSoumission DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJustificatifsHistorique() {
        $sql = "
        SELECT 
            j.idJustificatif,
            j.datesoumission,
            j.commentaire_absence AS commentaire_justificatif,
            j.verrouille,
            u.idUtilisateur,
            u.nom AS nom_etudiant,
            u.prenom AS prenom_etudiant,
            a.idAbsence,
            a.statut AS statut_absence,
            s.date AS date_seance,
            s.heuredebut,
            s.typeseance AS typeSeance,
            c.matiere,
            t.idTraitement,
            t.attente,
            t.reponse,
            t.cause,
            t.commentaire_validation AS commentaire_traitement
        FROM justificatif j
        JOIN absenceetjustificatif aj ON j.idJustificatif = aj.idJustificatif
        JOIN absence a ON aj.idAbsence = a.idAbsence
        JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
        JOIN seance s ON a.idSeance = s.idSeance
        JOIN cours c ON s.idCours = c.idCours
            LEFT JOIN traitementjustificatif t ON j.idJustificatif = t.idJustificatif
            WHERE t.attente = FALSE 
        ORDER BY j.dateSoumission DESC
    ";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cette fonction nous permet de recupérer les infos d'une absence d'un etudiant a un jour précis , utiliser pour le tableau de bord de l'etudiant
    public function getAbsenceDunJour($date,$idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,estRetard,heureDebut,prof,duree,enseignement,typeSeance,salle,controle FROM absence JOIN Seance using(idSeance) WHERE idEtudiant = :idEtudiant and extract('Days' from Seance.date) = :d and extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":d", $date);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAbsenceDunMois($idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,extract('Days' from Seance.date) FROM absence JOIN Seance using(idSeance) where extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year and idEtudiant = :idEtudiant");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function justifierAbsence($dateDebut)
    {
        if (!$this->conn) {
            echo "❌ Connexion non établie<br>";
            return false;
        }

        try {
            $sql = "UPDATE Absence a
                SET statut = 'valide'
                FROM Seance s
                WHERE a.idSeance = s.idSeance
                  AND s.date >= :dateDebut";

            $stmt = $this->conn->prepare($sql);

            // Liaison du paramètre date
            $stmt->bindParam(':dateDebut', $dateDebut);

            $stmt->execute();

            $count = $stmt->rowCount();
            echo "✅ $count absences mises à jour à partir du $dateDebut avec le statut 'en attente'<br>";

            return true;

        } catch (PDOException $e) {
            echo "❌ Erreur SQL : " . $e->getMessage();
            return false;
        }
    }
}

