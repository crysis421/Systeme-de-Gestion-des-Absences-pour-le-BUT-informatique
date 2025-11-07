<?php
session_start();

if (isset($_POST['index'])) {
    $index = (int)$_POST['index'];

    if (isset($_SESSION['formData']['justificatifs'][$index])) {
        $fichierASupprimer = $_SESSION['formData']['justificatifs'][$index];

        if (file_exists($fichierASupprimer)) {
            unlink($fichierASupprimer);
        }

        unset($_SESSION['formData']['justificatifs'][$index]);

        // Réindexer le tableau
        $_SESSION['formData']['justificatifs'] = array_values($_SESSION['formData']['justificatifs']);
    }

    header("Location: formulaireAbsence.php");
    exit();
} else {
    echo "<p>Aucun fichier sélectionné.</p>";
    echo "<a href='recapitulatifJustificatifAbsence.php'>⬅️ Retour</a>";
}

?>