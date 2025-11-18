<?php

use Model\Database;

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

    public function getUser($id){
        $stmt = $this->conn->prepare("SELECT nom,prenom,prenom2,email,motdepasse,role,groupe,datedenaissance,diplome,count(idAbsence) as totalabsences FROM utilisateur left join absence on utilisateur.idUtilisateur = absence.idEtudiant WHERE idUtilisateur = :id group by nom,prenom,prenom2,email,motdepasse,role,groupe,datedenaissance,diplome;");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function traiterAbsences(array $absenceIds, string $decision, string $commentaire) {
        if (empty($absenceIds)) return;

    }

    public function traiterJustificatif($idJustificatif, $decision, $attente, $commentaire = null, $cause = null)
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
        $update->bindValue(':attente', (bool)$attente, PDO::PARAM_BOOL);
        $update->bindValue(':reponse', $decision);
        $update->bindValue(':commentaire', $commentaire);
        $update->bindValue(':cause', $cause);
        $update->execute();
    }


    public function CHECKSIENATTENTE($idJustificatif) {
        $sql = "
            SELECT attente FROM traitementjustificatif WHERE idJustificatif = :idJustificatif
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idJustificatif', $idJustificatif, PDO::PARAM_INT);
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

    public function getJustificatifsDemande()
    {
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
            a.idAbsence AS id_absence,
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
            $sql .= " AND c.matiere ILIKE :matiere";
            $params[':matiere'] = "%$matiere%";
        }

        if (!empty($nom)) {
            $sql .= " AND u.nom ILIKE :nom";
            $params[':nom'] = "$nom";
        }

        if (!empty($prenom)) {
            $sql .= " AND u.prenom ILIKE :prenom";
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

    public function getJustificatifsHistoriqueFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom) {
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
        WHERE t.attente = FALSE
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

    public function getJustificatifsValides($dateDebut, $dateFin, $matiere, $nom, $prenom) {
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
        WHERE t.attente = FALSE && a.statut = valide
        ";
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
        $stmt = $this->conn->prepare("SELECT statut,extract('Days' from Seance.date),controle FROM absence JOIN Seance using(idSeance) where extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year and idEtudiant = :idEtudiant");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNombyUser($id) {
        $sql = "SELECT nom FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nom'] : null; // retourne juste le nom ou null si non trouvé
    }

    public function getPrenomByUser($id) {
        $sql = "SELECT prenom FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['prenom'] : null; // retourne juste le prénom ou null si non trouvé
    }

    public function getmotdepasseByUser($id)
    {
        $sql = "SELECT motDePasse FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['motdepasse'] : null; // retourne l'émail ou null si non trouvé
    }

    public function ModifierMDP($email, $mdp)
    {
        // Hash du mot de passe
        $hashedMdp = password_hash($mdp, PASSWORD_DEFAULT);

        $sql = "UPDATE Utilisateur SET motDePasse = :mdp WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":mdp", $hashedMdp, PDO::PARAM_STR);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);

        $stmt->execute();

        return "Le mot de passe a bien été modifié";
    }


    public function getNombreAbsencesJustifie($idEtudiant)
    {
        $sql = "SELECT COUNT(*) AS totalAbsences 
            FROM Absence 
            WHERE idEtudiant = :idEtudiant 
              AND statut = 'valide'";  // absences justifiées

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idEtudiant', $idEtudiant, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['totalabsences'] : 0;
    }

    public function getNombreAbsencesRefus($idEtudiant)
    {
        $sql = "SELECT COUNT(*) AS totalAbsences 
            FROM Absence 
            WHERE idEtudiant = :idEtudiant 
              AND statut = 'refus'";  // ou le statut que tu considères comme absence

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idEtudiant', $idEtudiant, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['totalabsences'] : 0;
    }

    public function getNombreAbsencesEnAttente($idEtudiant)
    {
        $sql = "SELECT COUNT(*) AS totalAbsences 
            FROM Absence 
            WHERE idEtudiant = :idEtudiant 
              AND statut = 'report'";  // ou le statut que tu considères comme absence

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':idEtudiant', $idEtudiant, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? (int)$result['totalabsences'] : 0;
    }

    public function inser()
    {

    }

    public function grapheDeAnnee($annee,$idEtudiant){
        $stmt = $this->conn->prepare("select extract('Months' from date),count(*) as total from Seance left join Absence using(idSeance) where idEtudiant = :etu and extract('Years' from Seance.date) = :year group by extract('Months' from date);");
        $stmt->bindParam(":etu", $idEtudiant);
        $stmt->bindParam(":year", $annee);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

