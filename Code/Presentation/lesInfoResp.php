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
$grapheSemestreR = $model->getAbsenceRessourceSemestre($semestreR);

$alerteM[] = '';
$alerteC[] = '';
foreach ($matiere as $mat):
    if ($model->alerteCours($mat) > 10) {
        array_push($alerteM,"<p>Il a un problème d'absences pour la matière de $mat</p>");
    }
    endforeach;

foreach ($eleve as $el):
    if ($model->alerteEleve($el['nom'], $el['prenom']) > 10) {
        array_push($alerteC,"<p>Il a un problème d'absences pour l'élève qui s'appelle ", $el['nom'], " ", $el['prenom'], "</p>");
    }
endforeach;

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