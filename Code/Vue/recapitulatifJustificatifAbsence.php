<?php
require_once '../Model/AbsenceModel.php';
session_start();

if (!isset($_SESSION['formData'])) {
    header("Location: formulaireAbsence.php");
    exit();
}

$data = $_SESSION['formData'];

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
    <title>Récapitulatif des absences</title>
</head>
<body>
<h1 style="text-align: center">
    Récapitulatif du justificatif d'absence de <?php echo htmlspecialchars($prenom); ?> <?php echo htmlspecialchars($nom); ?>
</h1>

<div id="main" >
    <div id="info">
        <p><b id="gras">Période d'absence :</b> du <?php echo htmlspecialchars($data['datedebut']); ?> à <?php echo htmlspecialchars($data['heuredebut']); ?>
            au <?php echo htmlspecialchars($data['fin']); ?> à <?php echo htmlspecialchars($data['heurefin1']); ?></p>

        <p><b id="gras">Motif :</b> <?php echo htmlspecialchars($data['motif']); ?></p>
        <p><b id="gras">Commentaires :</b> <?php echo nl2br(htmlspecialchars($data['commentaire'])); ?></p>
        <p><b id="gras">Justificatif :</b>
            <?php if ($data['justificatif'] != ""): ?>
                <a href="<?php echo htmlspecialchars($data['justificatif']); ?>" target="_blank">Voir le justificatif...</a>
            <?php else: ?>
                Aucun justificatif fourni
            <?php endif; ?>
        </p>
    </div>
    <br>
    <div id="but">
        <button id="back" onclick="history.back()">⬅️ Retour</button>
        <!-- Formulaire pour envoyer les données -->
        <form action="../Presentation/SoumettreJustificatif" method="post">
            <button type="submit" id="send">Envoyer justificatif ➡️</button>
        </form>
    </div>
</div>
</body>
</html>


















