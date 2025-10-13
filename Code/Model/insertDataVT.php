<?php

namespace Model;

//Traitement des données d'une table CSV , en particulier celle de VT.
//Elle transfère toute les données sur la base
//On remarquera que beaucoup d'insert ce font en 'on conflit do nothing' pour eviter de planter si une absence/utilisateur/seance existe deja
//ce qui est très probable si on viens re-télécharger le CSV

require_once "Database.php";

use Database;
use mysql_xdevapi\Exception;
use PDO;
use PDOException;

// Cette classe est pour ajouter des données depuis un fichier VT vers notre base
class insertDataVT
{
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    //Cette fonction ajoute un utilisateur si il n'est deja pas créé , on lui donne un mdp provisoire qui sera récupéré par la secretaire
    public function addUtilisateur($identifiant, $nom, $prenom, $prenom2, $email, $groupe, $dateDeNaissance, $diplome): void
    {
        try {
            $mdp = password_hash("unMDP", PASSWORD_DEFAULT);

            $req2 = $this->conn->prepare("INSERT INTO Utilisateur (idUtilisateur, nom, prenom, prenom2, email, motDePasse, role, groupe, dateDeNaissance, diplome) values(:id,:nom,:prenom,:prenom2,:email,:mdp,'eleve',:groupe,:dateDeNaissance,:diplome) on conflict do nothing returning idUtilisateur;");
            $req2->bindParam(':id', $identifiant);
            $req2->bindParam(':nom', $nom);
            $req2->bindParam(':prenom', $prenom);
            $req2->bindParam(':prenom2', $prenom2);
            $req2->bindParam(':email', $email);
            $req2->bindParam(':mdp', $mdp);
            $req2->bindParam(':groupe', $groupe);
            $req2->bindParam(':dateDeNaissance', $dateDeNaissance);
            $req2->bindParam(':diplome', $diplome);
            $req2->execute();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    //cette fonction ajoute un cour si il n'ai pas encore présent
    public function addCour($idCour, $idResponsable, $matiere): void
    {
        try {
            $req2 = $this->conn->prepare("INSERT INTO Cours (idCours, matiere, idProf) values (:idC,:matiere,:idR) on conflict do nothing;");
            $req2->bindParam(':idC', $idCour);
            $req2->bindParam(':matiere', $matiere);
            $req2->bindParam(':idR', $idResponsable);
            $req2->execute();
            $req2 = null;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    //La fonction principal pour ajout de toute les autres données
    public function addDataVT($identifiant, $date, $heure, $duree, $type, $idMatiere, $enseignement, $justification, $motif, $salle, $prof, $controle, $retard, $commentaire): void
    {
        try {
            //Regler les problemes de synchronisation des termes , exemple 01/01/2025 -> 2025/01/01, ou encore 8H10 -> 8:10:00
            $date = $date[6] . $date[7] . $date[8] . $date[9] . "-" . $date[3] . $date[4] . "-" . $date[0] . $date[1];
            if (strlen($heure) == 4) {
                $heure = $heure[0] . ":" . $heure[2] . $heure[3] . ":00";
            } else {
                $heure = $heure[0] . $heure[1] . ":" . $heure[3] . $heure[4] . ":00";
            }
            $duree = $duree[0] . ":" . $duree[2] . $duree[3] . ":00";

            if ($controle == 'Oui') {
                $controle = 1;
            } else {
                $controle = 0;
            }

            if ($justification == "Non justifié") {
                $justification = "refus";
            } else {
                $justification = "valide";
            }

            if ($retard == "Retard") {
                $retard = 1;
            } else {
                $retard = 0;
            }

           //Insertion des données , ici ajout d'une seance

            $req2 = $this->conn->prepare("INSERT INTO Seance(idSeance, idCours, heureDebut, typeSeance, enseignement, salle, prof, controle, duree, date) values(default,:idMatiere,:heure,:type,:enseignement,:salle,:prof,:controle,:duree,:date) on conflict do nothing;");
            $req2->bindParam(':idMatiere', $idMatiere);
            $req2->bindParam(":heure", $heure);
            $req2->bindParam(":type", $type);
            $req2->bindParam(":enseignement", $enseignement);
            $req2->bindParam(":salle", $salle);
            $req2->bindParam(':prof', $prof);
            $req2->bindParam(':controle', $controle);
            $req2->bindParam(':duree', $duree);
            $req2->bindParam(':date', $date);
            $req2->execute();
            $req2 = null;

            //recuperation de l'id de la seance

            $req2 = $this->conn->prepare("Select idSeance from Seance where heureDebut = :heure and enseignement = :enseignement and salle = :salle and Seance.date = :date;");
            $req2->bindParam(":heure", $heure);
            $req2->bindParam(":enseignement", $enseignement);
            $req2->bindParam(":salle", $salle);
            $req2->bindParam(':date', $date);
            $req2->execute();
            $idSeance = $req2->fetchAll(PDO::FETCH_ASSOC);
            $idSeance = $idSeance[0];
            $idSeance = $idSeance["idseance"];
            $req2 = null;

            //Insertion d'une nouvelle absence

            $req2 = $this->conn->prepare("INSERT INTO Absence(idAbsence, idSeance, idEtudiant, statut,estRetard) values(default,:seance,:idEtu,:status,:retard) on conflict do nothing;");
            $req2->bindParam(":seance", $idSeance);
            $req2->bindParam(":status", $justification);
            $req2->bindParam(":idEtu", $identifiant);
            $req2->bindParam(":retard", $retard);
            $req2->execute();
            $req2 = null;

            //Recuperation de l'id de l'absence

            $req2 = $this->conn->prepare("Select idAbsence from Absence where idSeance = :idseance and idEtudiant = :idEtu;");
            $req2->bindParam(":idseance", $idSeance);
            $req2->bindParam(':idEtu', $identifiant);
            $req2->execute();
            $idAbsence = $req2->fetchAll(PDO::FETCH_ASSOC);
            $idAbsence = $idAbsence[0];
            $idAbsence = $idAbsence["idabsence"];
            $req2 = null;

            //Savoir si une justification est deja en cours

            $req2 = $this->conn->prepare("Select count(*) from AbsenceEtJustificatif where idAbsence = :idAbsence");
            $req2->bindParam(':idAbsence', $idAbsence);
            $req2->execute();
            $nombreJustificatif = $req2->fetchAll(PDO::FETCH_ASSOC);
            $nombreJustificatif = $nombreJustificatif[0];
            $nombreJustificatif = $nombreJustificatif["count"];
            $req2 = null;


            //Si c'est valide et qu'il n'y a pas de justification en cour
            if ($justification == "valide" && $nombreJustificatif == 0) {
                $req2 = $this->conn->prepare("INSERT INTO Justificatif(idJustificatif, dateSoumission, verrouille,commentaire_absence) values(default,CURRENT_DATE,true,:commentaire) returning idJustificatif;");
                $req2->bindParam(":commentaire", $commentaire);
                $req2->execute();
                $idjust = $req2->fetchAll(PDO::FETCH_ASSOC);
                $idjust = $idjust[0];
                $idjust = $idjust["idjustificatif"];
                $req2 = null;


                $req2 = $this->conn->prepare("INSERT INTO TraitementJustificatif(idTraitement ,attente, reponse,idUtilisateur,date,commentaire_validation,cause,idJustificatif) values(default,false,'accepte',:idUtilisateur,CURRENT_DATE,:commentaire,:motif,:idJustificatif) on conflict do nothing;");
                $req2->bindParam(":motif", $motif);
                $req2->bindParam(":commentaire", $commentaire);
                $req2->bindParam(":idUtilisateur", $identifiant);
                $req2->bindParam(":idJustificatif", $idjust);
                $req2->execute();
                $req2 = null;

                $req2 = $this->conn->prepare("insert into AbsenceEtJustificatif(idJustificatif,idAbsence) VALUES (:idJustificatif,:idAbsence)");
                $req2->bindParam(':idJustificatif', $idjust);
                $req2->bindParam(':idAbsence', $idAbsence);
                $req2->execute();
                $req2 = null;

            }else if ($nombreJustificatif == 0) {

                $req2 = $this->conn->prepare("INSERT INTO Justificatif(idJustificatif, dateSoumission, verrouille,commentaire_absence) values(default,CURRENT_TIMESTAMP,false,:commentaire) returning idJustificatif;");
                $req2->bindParam(":commentaire", $commentaire);
                $req2->execute();
                $idjust = $req2->fetchAll(PDO::FETCH_ASSOC);
                $idjust = $idjust[0];
                $idjust = $idjust["idjustificatif"];
                $req2 = null;

                $req2 = $this->conn->prepare("INSERT INTO TraitementJustificatif(idTraitement ,attente, reponse,idUtilisateur,date,commentaire_validation,cause,idJustificatif) values(default,true,'enAttente',:idUtilisateur,CURRENT_DATE,null,null,:idJustificatif) on conflict do nothing;");
                $req2->bindParam(":idUtilisateur", $identifiant);
                $req2->bindParam(":idJustificatif", $idjust);
                $req2->execute();
                $req2 = null;

                $req2 = $this->conn->prepare("insert into AbsenceEtJustificatif(idJustificatif,idAbsence) VALUES (:idJustificatif,:idAbsence)");
                $req2->bindParam(':idJustificatif', $idjust);
                $req2->bindParam(':idAbsence', $idAbsence);
                $req2->execute();
                $req2 = null;

            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}