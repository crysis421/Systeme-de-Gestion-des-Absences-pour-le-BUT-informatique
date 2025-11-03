
<?php
require_once '../Model/AbsenceModel.php';
session_start();
if (!isset($_SESSION['formDataRetard'])) {
    header("Location: formulaireRetard.php");
    exit();
}

$data = $_SESSION['formDataRetard'];
$id = $data['id'];
$user = new AbsenceModel();
$nom = $user->getNombyUser($id);
$prenom = $user->getPrenomByUser($id);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/RecapJustificatif.css" />
    <title>Récapitulatif des retards</title>
</head>
<body>
<h1 style="text-align: center">
    Récapitulatif du justificatif de retard de <?php echo htmlspecialchars($prenom); ?> <?php echo htmlspecialchars($nom); ?>
</h1>

<div id="main" >
    <div id="info">
        <p>L'étudiant d'identifiant <b id="gras1"> <?php echo htmlspecialchars($data['id']); ?></b> est arrivé en retard le
            <b id="gras1"><?php echo htmlspecialchars($data['dateretard']); ?></b> à <b id="gras1"><?php echo htmlspecialchars($data['heurearrive']); ?></b>
            au cours de <b id="gras1"><?php echo htmlspecialchars($data['cours']); ?></b>.</p>

        <p><b id="gras1">Motif :</b> <?php echo htmlspecialchars($data['motif']); ?></p>
        <p><b id="gras1">Commentaires :</b> <?php echo nl2br(htmlspecialchars($data['preciserAutre'])); ?></p>
        <p><b id="gras1">Justificatif :</b>
            <?php if ($data['justificatif'] != ""): ?>
                <a href="<?php echo htmlspecialchars($data['justificatif']); ?>" target="_blank">Voir le justificatif...</a>
            <?php else: ?>
                Aucun justificatif fourni
            <?php endif; ?>
        </p>
    </div>
    <br>

    <div id="but">
        <button id="back"  onclick="history.back()">⬅️ Retour</button>
        <!-- Formulaire pour envoyer les données -->
        <form action="" method="post">
            <button type="submit" id="send">Envoyer justificatif ➡️</button>
        </form>
    </div>
</div>
</body>
</html>