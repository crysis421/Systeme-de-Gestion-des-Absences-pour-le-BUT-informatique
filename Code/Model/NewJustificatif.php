<?php

namespace Model;

use PDO;
use PDOException;

require_once "Database.php";

class NewJustificatif
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }


    public function creerJustificatif(int $idAbsence, int $idUtilisateur, string $cause, ?string $commentaire = null,array $justificatifs = [],): int|false
    {
        try {
            ///Insérer dans la table Justificatif
            $sqlJustificatif = "INSERT INTO justificatif (idjustificatif,datesoumission, commentaire_absence, verrouille) VALUES (default ,NOW(), :commentaire, false)";
            $stmtJustificatif = $this->conn->prepare($sqlJustificatif);
            $stmtJustificatif->bindValue(':commentaire', $commentaire, PDO::PARAM_STR);
            $stmtJustificatif->execute();


            // Récupérer l'ID du justificatif qui vient d'être créé
            $idJustificatif = (int)$this->conn->lastInsertId();

            if (!$idJustificatif) {
                echo "y a pas l id justificatif bb";
                exit;
            }


            //  Lier l'absence et le justificatif

            $sqlAbsenceEtJustificatif = "INSERT INTO absenceetjustificatif (idabsence, idjustificatif) VALUES (:idabsence, :idjustificatif)";
            $stmtAbsenceEtJustificatif = $this->conn->prepare($sqlAbsenceEtJustificatif);
            $stmtAbsenceEtJustificatif->bindValue(':idabsence', $idAbsence, PDO::PARAM_INT);
            $stmtAbsenceEtJustificatif->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtAbsenceEtJustificatif->execute();


            // Créer l'entrée initiale dans traitementjustificatif
            /// On commence le traitement avec la cause en attente et la date actuelle pour pouvoir mettre la cause rentrée par l'étudiant etc, le reste est null/default value
            $sqlTraitement = "INSERT INTO traitementjustificatif (idtraitement,attente, date, cause, idjustificatif, idutilisateur) VALUES (default,true, NOW(), :cause, :idjustificatif, :idutilisateur)";
            $stmtTraitement = $this->conn->prepare($sqlTraitement);
            $stmtTraitement->bindValue(':cause', $cause, PDO::PARAM_STR);
            $stmtTraitement->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtTraitement->bindValue(':idutilisateur', $idUtilisateur, PDO::PARAM_INT); // ID de l'étudiant
            $stmtTraitement->execute();
            // 4️⃣ Enregistrer les fichiers justificatifs (si présents)
            if (!empty($justificatifs)) {
                $sqlFichier = "INSERT INTO fichierjustificatif (pathJustificatif, idJustificatif)
                           VALUES (:path, :idjustificatif)";
                $stmtFichier = $this->conn->prepare($sqlFichier);

                foreach ($justificatifs as $path) {
                    $stmtFichier->execute([
                        ':path' => $path,
                        ':idjustificatif' => $idJustificatif
                    ]);
                }
            }

            return true;

        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage(); // 👈 temporaire pour debug
            return false;
        }
    }


    public function getIdAbsenceParSeance($datedebut, $heuredebut, $idEtudiant) {
        $sql = "SELECT a.idAbsence
            FROM absence AS a
            JOIN seance AS s ON a.idseance = s.idseance
            WHERE a.idEtudiant = :idEtudiant
              AND s.date = :datedebut
              AND s.heuredebut = :heuredebut";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":idEtudiant", $idEtudiant, PDO::PARAM_INT);
        $stmt->bindParam(":datedebut", $datedebut);
        $stmt->bindParam(":heuredebut", $heuredebut);

        $stmt->execute();

        $idAbsence = $stmt->fetchColumn();

        return $idAbsence;
    }
}
