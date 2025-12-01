<?php
require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();
$matiere= $model -> getMatieres();
$eleve = $model -> getEleves();
$matiere = array_slice($matiere, 0, 5);
$eleve = array_slice($eleve, 0, 5);