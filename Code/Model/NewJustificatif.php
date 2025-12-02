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

//concretement, on va aller dans differentes tables pour creer un justificatif et les relier entre eux, du fichier a la seance au traitement justificatif, ils vont tous y passer
    public function creerJustificatif($idAbsence, int $idUtilisateur, string $cause, ?string $commentaire = null,array $justificatifs = []): int|false
    {
        try {
            ///Insérer dans la table Justificatif
            $sqlJustificatif = "INSERT INTO justificatif (idjustificatif,datesoumission) VALUES (default ,NOW())";
            $stmtJustificatif = $this->conn->prepare($sqlJustificatif);
            $stmtJustificatif->execute();




            // Récupérer l'ID du justificatif qui vient d'être créé
            $idJustificatif = (int)$this->conn->lastInsertId();

            if (!$idJustificatif) {
                echo "y a pas l id justificatif bb";
                exit;
            }


            //  Lier l'absence et le justificatif
            foreach($idAbsence as $i){
                $sqlAbsenceEtJustificatif = "INSERT INTO absenceetjustificatif (idabsence, idjustificatif) VALUES (:idabsence, :idjustificatif)";
                $stmtAbsenceEtJustificatif = $this->conn->prepare($sqlAbsenceEtJustificatif);
                $stmtAbsenceEtJustificatif->bindValue(':idabsence', $i['idabsence'], PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
                $stmtAbsenceEtJustificatif->execute();


                $this->changeStatut($i['idabsence'],$commentaire);

            }

            // Créer l'entrée initiale dans traitementjustificatif
            /// On commence le traitement avec la cause en attente et la date actuelle pour pouvoir mettre la cause rentrée par l'étudiant etc, le reste est null/default value
            $sqlTraitement = "INSERT INTO traitementjustificatif (idtraitement,attente, date, cause, idjustificatif, idutilisateur) VALUES (default,true, NOW(), :cause, :idjustificatif, :idutilisateur)";
            $stmtTraitement = $this->conn->prepare($sqlTraitement);
            $stmtTraitement->bindValue(':cause', $cause, PDO::PARAM_STR);
            $stmtTraitement->bindValue(':idjustificatif', $idJustificatif, PDO::PARAM_INT);
            $stmtTraitement->bindValue(':idutilisateur', $idUtilisateur, PDO::PARAM_INT); // ID de l'étudiant
            $stmtTraitement->execute();
            // Enregistrer les fichiers justificatifs (si présents)
            if (!empty($justificatifs)) {
                $sqlFichier = "INSERT INTO fichierjustificatif (pathJustificatif, idJustificatif)
                           VALUES (:path, :idjustificatif) on conflict do nothing";
                $stmtFichier = $this->conn->prepare($sqlFichier);

                foreach ($justificatifs as $path) {
                    $stmtFichier->execute([
                        ':path' => $path,
                        ':idjustificatif' => $idJustificatif
                    ]);
                }
            }

            return $idJustificatif;

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
    public function changeStatut($idAbsence,$commentaire) {
        $var = 'report';
        $stmt = $this->conn->prepare('UPDATE Absence SET statut = :report,commentaire_absence = :commentaire WHERE idAbsence = :abs');
        $stmt->bindParam(':abs', $idAbsence);
        $stmt->bindParam(':commentaire', $commentaire);
        $stmt->bindParam(':report', $var);
        $stmt->execute();
    }


    public function getEmailbyUser($id)
    {
        $sql = "SELECT email FROM utilisateur WHERE idUtilisateur = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['email'] : null; // retourne l'émail ou null si non trouvé
    }

    //public function connFTP($host,$user,$pwd,$lFile,$dFile){
    //    $ftp_host = "localhost";
    //    $ftp_user = "root";
    //    $ftp_pass = $pwd;
    //    $local_file = $lFile;
    //    $distant_file = $dFile;

    //    $conn_id = ftp_connect($ftp_host);
    //    // on se connecte en tant qu'utilisateur
    //    $login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
    //    // on active le mode passif
    //    ftp_pasv($conn_id, true);
    //    // si on est connecté avec succès, on transfère le fichier
    //    if($login_result && ftp_put($conn_id, $distant_file, $local_file, FTP_ASCII)){
    //        // si le transfert a fonctionné, on supprime le fichier local
    //        unlink($local_file);
    //    }
    //    // on clos la connexion
    //    ftp_close($conn_id);
    //}

    //public function getImageFTP(){
    //    ftp_nb_fget(FTP\Connection $ftp,
    //    resource $stream,
    //    string $remote_filename,
    //    int $mode = FTP_BINARY,
    //    int $offset = 0
    //): int
    //}

}