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

// Suppression d'un fichier si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['supprimer_index'])) {
    $index = (int)$_POST['supprimer_index'];
    if (isset($justificatifs[$index])) {
        $fichierASupprimer = $justificatifs[$index];
        if (file_exists($fichierASupprimer)) unlink($fichierASupprimer);

        unset($_SESSION['formData']['justificatifs'][$index]);
        $_SESSION['formData']['justificatifs'] = array_values($_SESSION['formData']['justificatifs']);
        $justificatifs = $_SESSION['formData']['justificatifs'];
    }
}

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


        <p><b id="gras">Justificatifs :</b></p>
        <?php if (!empty($justificatifs)): ?>
            <ul>
                <?php foreach ($justificatifs as $index => $fichier): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($fichier); ?>" target="_blank"><?php echo basename($fichier); ?></a>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="supprimer_index" value="<?php echo $index; ?>">
                            <button type="submit">🗑 Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Aucun justificatif fourni.</p>
        <?php endif; ?>

    </div>
    <br>
    <div id="but">
        <form action="formulaireAbsence.php" method="get">
            <button style="    background-color:red;
    color: white;
    border-style: solid;
    border-radius: 10px;
    border-color: #093057;
    padding: 5px 10px;
    font-size: 20px;
    margin-bottom: 20px;" type="submit">⬅️ Retour</button>
        </form>

        <form action="../Presentation/SoumettreJustificatif.php" method="post">
            <button style="    background-color:green;
    color: white;
    border-style: solid;
    border-radius: 10px;
    border-color: #093057;
    padding: 5px 10px;
    font-size: 20px;
    margin-bottom: 20px;" type="submit">Envoyer justificatif ➡️</button>
        </form>
    </div>
</div>
</body>
</html>


















