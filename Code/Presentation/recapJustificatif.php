<?php
require_once '../Model/AbsenceModel.php';
session_start();

//si on ne peut recuperer les infos on redirige vers le formulaire
if (!isset($_SESSION['formData'])) {
    header("Location: formulaireAbsence.php");
    exit();
}

//et la on prends les variables on connait a force...
$data = $_SESSION['formData'];
$justificatifs = $data['justificatifs'] ?? [];
$id = $data['id'];
$user = new AbsenceModel();
$nom = $user->getNombyUser($id);
$prenom = $user->getPrenomByUser($id);
$user = null;