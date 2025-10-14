<?php
session_start();
if (!isset($_SESSION['formDataRetard'])) {
    header("Location: formulaireRetard.php");
    exit();
}

$data = $_SESSION['formDataRetard'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>Récapitulatif des retards</title>
</head>
<body>
<h1 style="text-align: center">
    Récapitulatif du justificatif de retard de <?php echo htmlspecialchars($data['nom']); ?>
</h1>

<div style="margin: 20px;">
    <p>L'étudiant <b><?php echo htmlspecialchars($data['prenom']); ?> <?php echo htmlspecialchars($data['nom']); ?></b> est arrivé en retard le
        <b><?php echo htmlspecialchars($data['dateretard']); ?></b> à <b><?php echo htmlspecialchars($data['heurearrive']); ?></b>
        au cours de <b><?php echo htmlspecialchars($data['cours']); ?></b>.</p>

    <p><b>Motif :</b> <?php echo htmlspecialchars($data['motif']); ?></p>
    <p><b>Commentaires :</b> <?php echo nl2br(htmlspecialchars($data['preciserAutre'])); ?></p>
    <p><b>Justificatif :</b>
        <?php if ($data['justificatif'] != ""): ?>
            <a href="<?php echo htmlspecialchars($data['justificatif']); ?>" target="_blank">Voir le fichier...</a>
        <?php else: ?>
            Aucun justificatif fourni
        <?php endif; ?>
    </p>
</div>
</body>
</html>