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

    public function __destruct()
    {
        $this->conn = null;
    }


    public function creerJustificatif($idAbsence, int $idUtilisateur, string $cause, ?string $commentaire = null,array $justificatifs = [],): int|false
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
            foreach($idAbsence as $i){
                echo 'LAV ';
                $sqlAbsenceEtJustificatif = "INSERT INTO absenceetjustificatif (idabsence, idjustificatif) VALUES (:idabsence, :idjustificatif)";
                $stmtAbsenceEtJustificatif = $this->conn->prepare($sqlAbsenceEtJustificatif);
                $stmtAbsenceEtJustificatif->bindValue(':idabsence', $i['idabsence'], PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->execute();


                $this->changeStatut($i['idabsence']);

            }


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



    ///cette fonction pour prendre seulement les absences qui nous interessent (seance par seance)
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


    ///changer le statut... litterallement le nom de la fonction breffffff
    public function changeStatut($idAbsence) {
        $var = 'report';
        $stmt = $this->conn->prepare('UPDATE Absence SET statut = :report WHERE idAbsence = :abs');
        $stmt->bindParam(':abs', $idAbsence);
        $stmt->bindParam(':report', $var);
        $stmt->execute();
    }





}