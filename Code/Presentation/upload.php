<?php
if (isset($_POST["submit"])) {
    $row = 0;
    if (($handle = fopen($_FILES["fileToUpload"]["tmp_name"], "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            echo "<p> $num champs à la ligne $row: <br /></p>\n";
            $row++;
            $ligne = explode(";", $data[0]);
            if ($ligne[1] == "Prénom") {
                echo "Nop";
            } else{
                addUtilisateur($ligne[4],$ligne[0],$ligne[1],$ligne[2],$ligne[0],$ligne[20],null,$ligne[6],$ligne[5]);
                addCour($ligne[13],2,$ligne[12]);
                addDataVT($ligne[4],$ligne[8],$ligne[9],$ligne[10],$ligne[11],$ligne[13],$ligne[14],$ligne[17],$ligne[18],$ligne[19],$ligne[21],$ligne[22],$ligne[23]);
            }
        }
        fclose($handle);
    }
} else {
    echo "Aucun fichier n'a été uploadé.";
}

function addUtilisateur($identifiant, $nom, $prenom, $prenom2, $email, $groupe, $dateDeNaissance, $composante, $diplome)
    {
        try {
            $host = "iutinfo-sgbd.uphf.fr";
            $user = "iutinfo474";
            $password = "uwkXBERC";
            $dbname = "iutinfo474";

            $mdp = password_hash("unMDP", PASSWORD_DEFAULT);
            $role = 'eleve';


            $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
            $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $req2 = $conn1->prepare("INSERT INTO Utilisateur (idUtilisateur, nom, prenom, prenom2, email, motDePasse, role, groupe, dateDeNaissance, composante, diplome) values(:id,:nom,:prenom,:prenom2,:email,:mdp,:role,:groupe,:dateDeNaissance,:composante,:diplome) on conflict do nothing ;");
            $req2->bindParam(':id', $identifiant);
            $req2->bindParam(':nom', $nom);
            $req2->bindParam(':prenom', $prenom);
            $req2->bindParam(':prenom2', $prenom2);
            $req2->bindParam(':email', $email);
            $req2->bindParam(':mdp', $mdp);
            $req2->bindParam(':role', $role);
            $req2->bindParam(':groupe', $groupe);
            $req2->bindParam(':dateDeNaissance', $dateDeNaissance);
            $req2->bindParam(':composante', $composante);
            $req2->bindParam(':diplome', $diplome);
            $req2->execute();
            $req2 = null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

function addCour($idCour,$idResponsable,$matiere){
    try{
        $host = "iutinfo-sgbd.uphf.fr";
        $user = "iutinfo474";
        $password = "uwkXBERC";
        $dbname = "iutinfo474";


        $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
        $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $req2 = $conn1->prepare("INSERT INTO Cours (idCours, matiere, idResponsable) values (:idC,:matiere,:idR) on conflict do nothing;");
        $req2->bindParam(':idC', $idCour);
        $req2->bindParam(':matiere', $matiere);
        $req2->bindParam(':idR', $idResponsable);
        $req2->execute();
        $req2 = null;

    }catch(PDOException $e){
        echo $e->getMessage();
    }

}
function addDataVT($identifiant, $date, $heure, $duree, $type, $idMatiere, $enseignement, $justification, $motif, $commentaire, $salle, $prof, $controle): void
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

            $host = "iutinfo-sgbd.uphf.fr";
            $user = "iutinfo474";
            $password = "uwkXBERC";
            $dbname = "iutinfo474";


            $conn1 = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
            $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $req2 = $conn1->prepare("INSERT INTO Seance(idSeance, idCours, heureDebut, typeSeance, enseignement, salle, prof, controle, duree, date) values(default,:idMatiere,:heure,:type,:enseignement,:salle,:prof,:controle,:duree,:date) returning idSeance;");
            $req2->bindParam(':idMatiere', $idMatiere);
            $req2->bindParam(":heure", $heure);
            $req2->bindParam(":type", $type);
            $req2->bindParam(":enseignement", $enseignement);
            $req2->bindParam(":salle", $salle);
            $req2->bindParam(':prof', $prof);
            $req2->bindParam(':controle', $controle);
            $req2->bindParam(':duree', $duree);
            $req2->bindParam(':date', $date);
            $idSeance = $req2->execute();
            echo "<br>".$idSeance;
            $req2 = null;



            $req2 = $conn1->prepare("INSERT INTO Absence(idAbsence, idSeance, idEtudiant, statut) values(default,:seance,:idEtu,:status) returning idAbsence;");
            $req2->bindParam(":seance", $idSeance);
            $req2->bindParam(":status", $justification);
            $req2->bindParam(":idEtu", $identifiant);
            $idAbsence = $req2->execute();
            $req2 = null;

            if ($motif != "?") {
                $req2 = $conn1->prepare("INSERT INTO Justificatif(idJustificatif, idAbsence, cause, dateSoumission, commentaire, verrouille, pathJustificatif) values(default,:idAbsence,:motif,CURRENT_DATE,:commentaire,false,null);");
                $req2->bindParam(":motif", $motif);
                $req2->bindParam(":idAbsence", $idAbsence);
                $req2->bindParam(":commentaire", $commentaire);
                $req2->execute();
                $req2 = null;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
?>


