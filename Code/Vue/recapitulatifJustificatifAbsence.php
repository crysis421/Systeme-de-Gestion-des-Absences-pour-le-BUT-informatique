<?php
session_start();
if (!isset($_SESSION['formData'])) {
    header("Location: formulaireAbsent.php");
    exit();
}

$data = $_SESSION['formData'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>Récapitulatif des absences</title>
</head>
<body>
<h1 style="text-align: center">
    Récapitulatif du justificatif d'absence de <?php echo htmlspecialchars($data['nom2']); ?>
</h1>

<div style="margin: 20px;">
    <p><b>Période d'absence :</b> du <?php echo htmlspecialchars($data['datedebut']); ?> à <?php echo htmlspecialchars($data['heuredebut']); ?>
        au <?php echo htmlspecialchars($data['fin']); ?> à <?php echo htmlspecialchars($data['heurefin1']); ?></p>

    <p><b>Motif :</b> <?php echo htmlspecialchars($data['motif']); ?></p>
    <p><b>Commentaires :</b> <?php echo nl2br(htmlspecialchars($data['commentaire'])); ?></p>
    <p><b>Justificatif :</b>
        <?php if ($data['justificatif'] != ""): ?>
            <a href="<?php echo htmlspecialchars($data['justificatif']); ?>" target="_blank">Voir le justificatif...</a>
        <?php else: ?>
            Aucun justificatif
        <?php endif; ?>
    </p>
</div>
</body>
</html>


















