<?php
require_once '../Model/AbsenceModel.php';
session_start(); // toujours au début avant d'utiliser $_SESSION

$id = $_SESSION['user'];
$user = new AbsenceModel();

$result = $user->getUser($id);
$nom = $result["nom"];
$prenom = $result["prenom"];
$prenom2 = $result["prenom2"];
$email = $result["email"];
$role = $result["role"];
$groupe = $result["groupe"];
$datedenaissance = $result["datedenaissance"];
$diplome = $result["diplome"];

$total = $result["totalabsences"];
$valide = $user->getNombreAbsencesJustifie($id);
$refus = $user->getNombreAbsencesRefus($id);
$autre = $user->getNombreAbsencesEnAttente($id);

if(date('m') > 7){
    $graphe = $user->getAbsenceDeLannee(date('Y')-2,date('Y')-1,$id); //TODO les absences sont en 2024
}else{
    $graphe = $user->getAbsenceDeLannee(date('Y')-3,date('Y')-2,$id);
}
$i = 0;
$nbFois[] = ' ';
foreach ($graphe as $row) {
    $i = $i + $row['count'];
}
foreach ($graphe as $key=>$row) {
    array_push($nbFois, $row['count']);
    $graphe[$key]['count'] = $row['count']*100/$i;
}

$nonjustife =$total - $valide;
// Gestion du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['motDePasse'])) {
    $_SESSION['modif'] = [
        'email' => $_POST['email'],
        'motDePasse' => $_POST['motDePasse']
    ];
    header("Location: ../Presentation/modifierMDPetudiant.php");
    exit();
}
?>
