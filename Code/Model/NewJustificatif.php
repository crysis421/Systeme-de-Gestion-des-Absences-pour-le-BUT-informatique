<?php

namespace Model;

use PDOException;

require_once "Database.php";

use PDO;

class NewJustificatif
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


    ///insert dans justificatif les donnees saisis par l'etudiant et creer un nv traitementJustificatif pour la cause mais en valeur par defaut pour le reste

    public function creerJustificatif($idAbsence, int $idUtilisateur, string $cause, ?string $commentaire = null): int|false
    {


        try {
            ///Insérer dans la table Justificatif
            echo 'LA ';
            $sqlJustificatif = "INSERT INTO justificatif (idjustificatif,datesoumission, commentaire_absence, verrouille) VALUES (default ,NOW(), :commentaire, false)";
            $stmtJustificatif = $this->conn->prepare($sqlJustificatif);
            $stmtJustificatif->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmtJustificatif->execute();
            echo 'LA ';


            // Récupérer l'ID du justificatif qui vient d'être créé
            $idJustificatif = (int)$this->conn->lastInsertId();
            echo 'LA ';

            if (!$idJustificatif) {
                echo "y a pas l id justificatif bb";
                exit;
            }


            //  Lier l'absence et le justificatif
            foreach($idAbsence[0] as $i){
                echo 'LAV ';
                $sqlAbsenceEtJustificatif = "INSERT INTO absenceetjustificatif (idabsence, idjustificatif) VALUES (:idabsence, :idjustificatif)";
                $stmtAbsenceEtJustificatif = $this->conn->prepare($sqlAbsenceEtJustificatif);
                $stmtAbsenceEtJustificatif->bindValue(':idabsence', $i, PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->execute();
                echo 'LAM ';

                $this->changeStatut($i);
                echo 'LAB ';
            }
            echo 'LA ';


            // Créer l'entrée initiale dans traitementjustificatif
            /// On commence le traitement avec la cause en attente et la date actuelle pour pouvoir mettre la cause rentrée par l'étudiant etc, le reste est null/default value
            $sqlTraitement = "INSERT INTO traitementjustificatif (idtraitement,attente, date, cause, idjustificatif, idutilisateur) VALUES (default,true, NOW(), :cause, :idjustificatif, :idutilisateur)";
            $stmtTraitement = $this->conn->prepare($sqlTraitement);
            $stmtTraitement->bindValue(':cause', $cause, PDO::PARAM_STR);
            $stmtTraitement->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtTraitement->bindValue(':idutilisateur', $idUtilisateur, PDO::PARAM_INT); // ID de l'étudiant
            $stmtTraitement->execute();
            echo 'LA ';

//            $sqlficher = "INSERT INTO fichierjustificatif (idjustificatif,pathjustificatif) VALUES (:idjustificatif, ../../jspbb)";
//            $stmtfichier =  $this->conn->prepare($sqlficher);
//            $stmtfichier->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
//            $stmtfichier->execute();

            return true;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function getIdAbsenceParSeance($datedebut, $heuredebut,$datefin,$heurefin, $idEtudiant) {
        $sql = "select idAbsence from Absence join Seance using(idSeance) where statut='refus' and :dateDebut <= date and :dateFin >= date and :heureDebut <= heureDebut and :heureFin-duree >= heureDebut and idEtudiant=:idEtu;";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idEtu", $idEtudiant);
        $stmt->bindParam(":dateDebut", $datedebut);
        $stmt->bindParam(":heureDebut", $heuredebut);
        $stmt->bindParam(":dateFin", $datefin);
        $stmt->bindParam(":heureFin", $heurefin);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function changeStatut($idAbsence) {
        $var = 'report';
        $stmt = $this->conn->prepare('UPDATE Absence SET statut = :report WHERE idAbsence = :abs');
        $stmt->bindParam(':abs', $idAbsence);
        $stmt->bindParam(':report', $var);
        $stmt->execute();
    }





}