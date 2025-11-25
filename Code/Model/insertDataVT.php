<?php

namespace Model;

//Traitement des données d'une table CSV, en particulier celle de VT.
//Elle transfère toutes les données sur la base

require_once "Database.php";

use Model\Database;
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

    //Cette fonction nous permet de savoir quel cours et utilisateur sont deja dans la base
    public function getUtilisateurAndCours():array
    {
        $stmt = $this->conn->prepare("SELECT DISTINCT idUtilisateur,concat(nom,' ',prenom) as prof,idCours FROM utilisateur left join Cours on utilisateur.idUtilisateur = Cours.idProf;");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Cette fonction ajoute un utilisateur s'il n'est pas deja créé, on lui donne un mdp provisoire qui sera récupéré par la secretaire.
    public function addUtilisateur($identifiant, $nom, $prenom, $prenom2, $email, $groupe, $dateDeNaissance, $diplome): void
    {
        try {
            $mdp = password_hash("unMDP", PASSWORD_DEFAULT);

            $req2 = $this->conn->prepare("INSERT INTO Utilisateur (idUtilisateur, nom, prenom, prenom2, email, motDePasse, role, groupe, dateDeNaissance, diplome) values(:id,:nom,:prenom,:prenom2,:email,:mdp,'eleve',:groupe,:dateDeNaissance,:diplome)");
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

    public function addProf($prof){
        $i = 0;
        $nom ='';
        while ($prof[$i] != ' '){
            $nom = $nom.$prof[$i];
            $i++;
        }
        $i++;
        while (isset($prof[$i])){
            $prenom = $prenom.$prof[$i];
            $i++;
        }
        $email = $nom.'.'.$prenom.'@uphf.fr';
        $mdp = password_hash("unMDP", PASSWORD_DEFAULT);
        $req2 = $this->conn->prepare("INSERT INTO Utilisateur (idUtilisateur, nom, prenom, prenom2, email, motDePasse, role, groupe, dateDeNaissance, diplome) values(default,:nom,:prenom,null,:email,:mdp,'prof',null,null,null)");
        $req2->bindParam(':nom', $nom);
        $req2->bindParam(':prenom', $prenom);
        $req2->bindParam(':email', $email);
        $req2->bindParam(':mdp', $mdp);
        $req2->execute();

    }

    //cette fonction ajoute un cours s'il n'est pas encore présent
    public function addCour($idCour, $idResponsable, $matiere): void
    {
        try {
            $req2 = $this->conn->prepare("INSERT INTO Cours (idCours, matiere, idProf) values (:idC,:matiere,:idR)");
            $req2->bindParam(':idC', $idCour);
            $req2->bindParam(':matiere', $matiere);
            $req2->bindParam(':idR', $idResponsable);
            $req2->execute();
            $req2 = null;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }

    }

    //La fonction principale pour ajout de toutes les autres données
    public function addDataVT($identifiant, $date, $heure, $duree, $type, $idMatiere, $enseignement, $justification, $motif, $salle, $prof, $controle, $retard, $commentaire): void
    {
        try {
            //Régler les problèmes de synchronisation des termes, exemple 01/01/2025 → 2025/01/01, ou encore 8H10 → 8:10:00
            //Ceci ne peut que se faire ici
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

            //Insertion des données, ici ajout d'une seance

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


        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}