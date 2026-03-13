<?php
require_once '../Model/AbsenceModel.php';
$numero = $_GET['numero'];
$user = new AbsenceModel();

$result = $user->getEmailbyUser($numero);
if (!$result) {
    echo "ce ne numéro n'est pas attribué";
}

?>