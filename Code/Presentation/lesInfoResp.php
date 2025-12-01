<?php
require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();
$matiere= $model -> getMatieres();
$eleve = $model -> getEleves();
$matiere = array_slice($matiere, 0, 5);
$eleve = array_slice($eleve, 0, 5);

if(date('m') > 7){
    $grapheAnnee = $model->getAbsenceCours(date('Y')-2,date('Y')-1); //TODO les absences sont en 2024
}else{
    $grapheAnnee = $model->getAbsenceCours(date('Y')-1,date('Y'));
}
$grapheSemestre = $model->getAbsenceCoursSemestre($semestre);


$i = 0;
$nbFoisAnnee[] = ' ';
$nbFoisSemestre[] = ' ';
foreach ($grapheAnnee as $row) {
    $i = $i + $row['count'];
}
foreach ($grapheAnnee as $key=>$row) {
    array_push($nbFoisAnnee, $row['count']);
    $grapheAnnee[$key]['count'] = $row['count']*100/$i;
}

$i=0;
foreach ($grapheSemestre as $ro) {
    $i = $i + $ro['count'];
}
foreach ($grapheSemestre as $key=>$ro) {
    array_push($nbFoisSemestre, $ro['count']);
    $grapheSemestre[$key]['count'] = $ro['count']*100/$i;
}