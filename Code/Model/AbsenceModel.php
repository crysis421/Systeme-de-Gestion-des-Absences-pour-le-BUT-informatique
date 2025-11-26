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


    ///fonction pour aller chercher les infos d'un user avec son total d'absences
    public function getUser($id){
        $stmt = $this->conn->prepare("SELECT nom,prenom,prenom2,email,motdepasse,role,groupe,datedenaissance,diplome,count(idAbsence) as totalabsences FROM utilisateur left join absence on utilisateur.idUtilisateur = absence.idEtudiant WHERE idUtilisateur = :id group by nom,prenom,prenom2,email,motdepasse,role,groupe,datedenaissance,diplome;");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    ///fonction dont l'utilité est de check si la liste d'absence est vide
    public function traiterAbsences(array $absenceIds, string $decision, string $commentaire) {
        if (empty($absenceIds)) return;

    }

    ///fonction pour le responsable, les decisions qui seront prises
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

//fonction de test
    public function CHECKSIENATTENTE($idJustificatif) {
        $sql = "
            SELECT attente FROM traitementjustificatif WHERE idJustificatif = :idJustificatif
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idJustificatif', $idJustificatif, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

///aller chercher toutes les infos concernant un justificatif
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

///aller chercher des infos d'un justificatif mais en moindre detail
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

///:aller chercher les justificatifs qui sont redemandés
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

///    aller prendre les justificatifs qui doivent etre traités
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

    ///fonction pour les differents filtrages pour e responsable, on creer la fonction et la fin on met un "sufixe" a cette requete pour appliquer le filtrage
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

    ///aller chercher les infos des decisions d responsable, les justificatifs acceptés, refusés
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

    ///meme type de filtrage mais pour le cote historique
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




    //Cette fonction nous permet de recupérer les infos d'une absence d'un etudiant a un jour précis , utilisé pour le tableau de bord de l'etudiant
    public function getAbsenceDunJour($date,$idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,estRetard,heureDebut,prof,duree,enseignement,typeSeance,salle,controle FROM absence JOIN Seance using(idSeance) WHERE idEtudiant = :idEtudiant and extract('Days' from Seance.date) = :d and extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":d", $date);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    ///la fonction nous permet de recuérer les infos des absences d'un étudiant sur un mois precis
    public function getAbsenceDunMois($idEtudiant,$mois,$year) {
        $stmt = $this->conn->prepare("SELECT statut,extract('Days' from Seance.date),controle FROM absence JOIN Seance using(idSeance) where extract('Months' from Seance.date) = :m and extract('Years' from Seance.date) = :year and idEtudiant = :idEtudiant");
        $stmt->bindParam(":idEtudiant", $idEtudiant);
        $stmt->bindParam(":m", $mois);
        $stmt->bindParam(":year", $year);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // aller récupérer l'émail de l'utilisateur.
    public function getEmailbyUser($id) {
        $sql = "SELECT email FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null; // retourne juste le nom ou null si non trouvé
    }

    ///aller chercher le nom d'un user
    public function getNombyUser($id) {
        $sql = "SELECT nom FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['nom'] : null; // retourne juste le nom ou null si non trouvé
    }
///aller chercher le prenom d'un user
    public function getPrenomByUser($id) {
        $sql = "SELECT prenom FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['prenom'] : null; // retourne juste le prénom ou null si non trouvé
    }
//aller chercher le MDP d'un user
    public function getmotdepasseByUser($id)
    {
        $sql = "SELECT motDePasse FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['motdepasse'] : null; // retourne l'émail ou null si non trouvé
    }
///aller modifier le MDP d'un user avec un hash
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

///pour avoir le nombre d'absences d'un etudiant
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
///prendre le nombre d'absences refusées
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
///prendre le nombre d'absences en "report"
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

    public function getImageJustificatifs($nom,$prenom, $matiere, $date, $heure){
        $sql = "
            SELECT 
            fj.pathJustificatif

            FROM fichierjustificatif fj
            JOIN absenceetjustificatif aj ON fj.idJustificatif = aj.idJustificatif
            JOIN absence a ON aj.idAbsence = a.idAbsence
            JOIN utilisateur u ON a.idEtudiant = u.idUtilisateur
            JOIN seance s ON a.idSeance = s.idSeance
            JOIN cours c ON s.idCours = c.idCours
            LEFT JOIN traitementjustificatif t ON fj.idJustificatif = t.idJustificatif
            WHERE u.nom = :nom
            AND u.prenom = :prenom
            AND c.matiere = :matiere
            AND u.date = :date
            AND s.heuredebut = :heure
            AND t.attente = FALSE 

        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->bindParam(":matiere", $matiere);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":heure", $heure);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

