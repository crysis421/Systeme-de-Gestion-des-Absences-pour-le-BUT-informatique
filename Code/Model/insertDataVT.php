<?php
//
//namespace Model;
//
//use mysql_xdevapi\Exception;
//
//class insertDataVT
//{
//    public static function addDataVT($identifiant, $date, $heure, $duree, $type, $matiere, $idMatiere, $enseignement, $idEnseignement, $absent, $justification, $motif, $commentaire, $groupe, $salle, $prof, $controle)
//    {
//        try {
//            if (strlen($heure)==4){
//                $day = date_create(date($date[6].$date[7].$date[8].$date[9]."-".$date[3].$date[4]."-" .$date[0].$date[1]." ".$heure[0].":".$heure[2].$heure[3].":s"));
//            }else{
//                $day = date_create(date($date[6].$date[7].$date[8].$date[9]."-".$date[3].$date[4]."-" .$date[0].$date[1]." ".$heure[0].$heure[1].":".$heure[3].$heure[4].":s"));
//            }
//
//            if ($controle == 'Oui'){
//                $c = true;
//            }else{
//                $c = false;
//            }
//
//
//            $conn1 =
//            $conn1->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//
//            $req2 = $conn1->prepare("INSERT INTO Seance values(default,:date,:type,:enseignement,:salle,:prof,:controle,:duree);");
//            $req2->bindParam(":date", $day);
//            $req2->bindParam(":type", $type);
//            $req2->bindParam(":enseignement", $enseignement);
//            $req2->bindParam(":salle", $salle);
//            $req2->bindParam(':prof', $prof);
//            $req2->bindParam(':controle', $c);
//            $req2->bindParam(':duree', $duree);
//            $req2->execute();
//
//            $req2 = $conn1->prepare("INSERT INTO Absence values(default,:seance,:status,:idEtu);");
//            $req2->bindParam(":seance", $idMatiere);
//            $req2->bindParam(":status", $justification);
//            $req2->bindParam(":idEtu", $identifiant);
//            $req2->execute();
//
//            $req2 = $conn1->prepare("INSERT INTO Justificatif values(default,:motif,null,:commentaire,false,null);");
//            $req2->bindParam(":motif", $motif);
//            $req2->bindParam(":commentaire", $commentaire);
//            $req2->bindParam(":idEtu", $identifiant);
//            $req2->execute();
//
//;
//        } catch (Exception $e) {
//            echo $e->getMessage();
//        }
//    }
//
//
//}