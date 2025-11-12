<?php
use Model\NewJustificatif;
require_once '../Model/NewJustificatif.php';
session_start();

// Vérifie que les données de session existent
$data = $_SESSION['formData'] ?? null;
if (!$data) {
    die("Aucune donnée de formulaire trouvée. Retournez au formulaire.");
}

// 🔹 Informations de base
$idUser = (int)$data['id'];
$cause = htmlspecialchars($data['motif']);
$commentaire = htmlspecialchars($data['commentaire'] ?? '');
$justificatifs = $data['justificatifs'] ?? [];

// 🔹 Initialisation du gestionnaire
$justificatifManager = new NewJustificatif();

// 🔹 ID d'absence fixe
$idAbsence = 10733;

// 🔹 Création du justificatif
try {
    $succes = $justificatifManager->creerJustificatif(
            $idAbsence,
            $idUser,
            $cause,
            $commentaire,
            $justificatifs // <-- on insère directement les chemins relatifs
    );

    if ($succes) {
        unset($_SESSION['formData']); // supprime la session après succès
    }

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>Formulaire d'absence</title>
</head>
<body>
<header>
    <?php require '../Vue/menuHorizontalEtu.html'; ?>
</header>
<main>
    <div id="titre">
        <?php if ($succes) : ?>
            <p>✅ Justificatif envoyé avec succès !</p>
        <?php else : ?>
            <p>❌ Erreur lors de la création du justificatif.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($justificatifs)) : ?>
        <h3>Fichiers enregistrés :</h3>
        <ul>
            <?php foreach ($justificatifs as $path): ?>
                <?php
                // On récupère juste le nom du fichier
                $fileName = basename($path);
                // Chemin relatif au web depuis ce script
                $webPath = "../uploads/" . $fileName;
                ?>
                <li>
                    <a href="<?php echo htmlspecialchars($webPath); ?>" target="_blank">
                        <?php echo htmlspecialchars($fileName); ?>
                    </a>
                    <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $fileName)) : ?>
                        <!-- Affichage direct de l'image en miniature -->
                        <br>
                        <img src="<?php echo htmlspecialchars($webPath); ?>" alt="<?php echo htmlspecialchars($fileName); ?>" style="max-width:200px; margin-top:5px;">
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
</body>
</html>

