<?php
require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();

if(date('m') > 7){
    $grapheAnnee = $model->getAbsenceCoursProf(date('Y')-2,date('Y')-1,$_SESSION['user']); //TODO les absences sont en 2024
}else{
    $grapheAnnee = $model->getAbsenceCoursProf(date('Y')-1,date('Y'),$_SESSION['user']);
}
$grapheSemestre = $model->getAbsenceCoursSemestreProf($semestre,$_SESSION['user']);
$grapheSemestreR = $model->getAbsenceRessourceSemestreProf($semestreR,$_SESSION['user']);

$model = null;

$i = 0;
$nbFoisAnnee[] = ' ';
$nbFoisSemestre[] = ' ';
$nbFoisSemestreR[] = ' ';
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

$i=0;
foreach ($grapheSemestreR as $row) {
    $i = $i + $row['count'];
}
foreach ($grapheSemestreR as $key=>$row) {
    array_push($nbFoisSemestreR, $row['count']);
    $grapheSemestreR[$key]['count'] = $row['count']*100/$i;
}