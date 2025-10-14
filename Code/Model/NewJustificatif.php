<?php

namespace Model;

use PDOException;

require_once "Database.php";
use Database;
use mysql_xdevapi\Exception;
use PDO;

class NewJustificatif
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    ///insert dans justificatif les donnees saisis par l'etudiant et creer un nv traitementJustificatif pour la cause mais en valeur par defaut pour le reste

    public function creerJustificatif(int $idAbsence, int $idUtilisateur, string $cause, ?string $commentaire = null, ?string $cheminFichier = null): int|false
    {


        try {
            ///Insérer dans la table Justificatif
            $sqlJustificatif = "INSERT INTO justificatif (datesoumission, commentaire_absence, verrouille) VALUES (NOW(), :commentaire, 0)";
            $stmtJustificatif = $this->conn->prepare($sqlJustificatif);
            $stmtJustificatif->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmtJustificatif->execute();

            // Récupérer l'ID du justificatif qui vient d'être créé
            $idJustificatif = (int)$this->conn->lastInsertId();

            if (!$idJustificatif) {
                throw new Exception("Impossible de créer l'entrée dans la table justificatif.");
            }

            //nsérer le fichier justificatif
            if ($cheminFichier !== null) {
                $sqlFichier = "INSERT INTO fichierjustificatif (pathjustificatif, idjustificatif) VALUES (:path, :idjustificatif)";
                $stmtFichier = $this->conn->prepare($sqlFichier);
                $stmtFichier->bindValue(':path', $cheminFichier, PDO::PARAM_STR);
                $stmtFichier->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
                $stmtFichier->execute();
            }

            //  Lier l'absence et le justificatif
            $sqlAbsenceEtJustificatif = "INSERT INTO absenceetjustificatif (idabsence, idjustificatif) VALUES (:idabsence, :idjustificatif)";
            $stmtAbsenceEtJustificatif = $this->conn->prepare($sqlAbsenceEtJustificatif);
            $stmtAbsenceEtJustificatif->bindValue(':idabsence', $idAbsence, PDO::PARAM_INT);
            $stmtAbsenceEtJustificatif->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtAbsenceEtJustificatif->execute();

            // Créer l'entrée initiale dans traitementjustificatif
            // On initialise le traitement avec la cause en attente et la date actuelle pour pouvoir mettre la cause rentrée par l'étudiant etc, le reste est null/default value
            $sqlTraitement = "INSERT INTO traitementjustificatif (attente, date, cause, idjustificatif, idutilisateur) VALUES (1, NOW(), :cause, :idjustificatif, :idutilisateur)";
            $stmtTraitement = $this->conn->prepare($sqlTraitement);
            $stmtTraitement->bindValue(':cause', $cause, PDO::PARAM_STR);
            $stmtTraitement->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtTraitement->bindValue(':idutilisateur', $idUtilisateur, PDO::PARAM_INT); // ID de l'étudiant
            $stmtTraitement->execute();

            return $idJustificatif;

        } catch (Exception $e) {
            return false;
        }
    }



}