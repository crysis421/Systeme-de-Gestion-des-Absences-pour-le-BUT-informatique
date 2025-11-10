<?php
session_start();

if (isset($_POST['index'])) {
    $index = (int)$_POST['index'];

    if (isset($_SESSION['formDataRetard']['justificatifs'][$index])) {
        $fichierASupprimer = $_SESSION['formDataRetard']['justificatifs'][$index];

        if (file_exists($fichierASupprimer)) {
            unlink($fichierASupprimer);
        }

        unset($_SESSION['formDataRetard']['justificatifs'][$index]);

        // Réindexer le tableau
        $_SESSION['formDataRetard']['justificatifs'] = array_values($_SESSION['formDataRetard']['justificatifs']);
    }

    header("Location: formulaireRetard.php");
    exit();
} else {
    echo "<p>Aucun fichier sélectionné.</p>";
    echo "<a href='recapitulatifJustificatifRetard.php'>⬅️ Retour</a>";
}

?>
