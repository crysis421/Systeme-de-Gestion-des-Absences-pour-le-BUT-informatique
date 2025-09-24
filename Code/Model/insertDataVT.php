<?php

namespace Model;

use mysql_xdevapi\Exception;

class insertDataVT
{
    public static function addDataVT($identifiant, $date, $heure, $duree, $type, $idMatiere, $enseignement, $justification, $motif, $commentaire, $salle, $prof, $controle): void
    {
        try {
            //Regler les problemes de synchronisation des termes , exemple 01/01/2025 -> 2025/01/01, ou encore 8H10 -> 8:10:00
            $date = date_create(date($date[6] . $date[7] . $date[8] . $date[9] . "-" . $date[3] . $date[4] . "-" . $date[0] . $date[1]));
            if (strlen($heure) == 4) {
                $heure = date_create(date($heure[0] . ":" . $heure[2] . $heure[3] . ":s"));
            } else {
                $heure = date_create(date($heure[0] . $heure[1] . ":" . $heure[3] . $heure[4] . ":s"));
            }

            if ($controle == 'Oui') {
                $c = true;
            } else {
                $c = false;
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
            $req2->bindParam(':controle', $c);
            $req2->bindParam(':duree', $duree);
            $req2->bindParam(':date', $date);
            $idSeance = $req2->execute();
            $req2 = null;


            $req2 = $conn1->prepare("INSERT INTO Absence(idAbsence, idSeance, idEtudiant, statut) values(default,:seance,:idEtu,:statut)  returning idAbsence;");
            $req2->bindParam(":seance", $idSeance);
            $req2->bindParam(":status", $justification);
            $req2->bindParam(":idEtu", $identifiant);
            $idAbsence = $req2->execute();
            $req2 = null;

            if ($motif != "?") {
                $req2 = $conn1->prepare("ALTER TYPE cause_absence ADD VALUE if not exists ':cause';");
                $req2->bindParam(':cause', $motif);
                $req2->execute();
                $req2 = null;

                $req2 = $conn1->prepare("INSERT INTO Justificatif(idJustificatif, idAbsence, cause, dateSoumission, commentaire, verrouille, pathFichier) values(default,:idAbsence,:motif,CURRENT_DATE,:commentaire,false,null);");
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


}