<?php
// Initialisation des variables
session_start();

$formDataRetard = $_SESSION['formDataRetard'] ?? [];

$id = $formDataRetard['id'] ?? '';
$prenom = $formDataRetard['prenom'] ?? '';
$dateretard = $formDataRetard['dateretard'] ?? '';
$heurearrive = $formDataRetard['heurearrive'] ?? '';
$cours = $formDataRetard['cours'] ?? '';
$motif = $formDataRetard['motif'] ?? '';
$preciserAutre = $formDataRetard['preciserAutre'] ?? '';
$justificatifs = $formDataRetard['justificatifs'] ?? [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $id = $_POST['id'];
    $prenom = $_POST['prenom'];
    $dateretard = $_POST['dateretard'];
    $heurearrive = $_POST['heurearrive'];
    $cours = $_POST['cours'];
    $motif = $_POST['motif'];
    $preciserAutre = $_POST['preciserAutre'];

    // Gestion de l’upload multiple
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (isset($_FILES['justificatifs'])) {
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        foreach ($_FILES['justificatifs']['tmp_name'] as $index => $tmpName) {
            if ($_FILES['justificatifs']['error'][$index] === 0) {
                $fileType = $_FILES['justificatifs']['type'][$index];
                if (in_array($fileType, $allowedTypes)) {
                    $fileName = basename($_FILES['justificatifs']['name'][$index]);
                    $targetPath = $uploadDir . time() . "_" . $fileName;
                    if (move_uploaded_file($tmpName, $targetPath)) {
                        $justificatifs[] = $targetPath;
                    }
                } else {
                    $error = "Un ou plusieurs fichiers ont un type non autorisé (PDF, JPEG, PNG, GIF uniquement).";
                    break;
                }
            }
        }
    }

    // Si aucune erreur, on enregistre dans la session et on redirige
    if ($error == "") {

        $_SESSION['formDataRetard'] = [
                'id' => $id,
                'prenom' => $prenom,
                'dateretard' => $dateretard,
                'heurearrive' => $heurearrive,
                'cours' => $cours,
                'motif' => $motif,
                'preciserAutre' => $preciserAutre,
                'justificatifs' => $justificatifs
        ];
        header("Location: recapitulatifJustificatifRetard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/formulaire.css" />
    <title>Formulaire de Retard</title>
</head>
<body>
<header>
    <?php require 'menuHorizontalEtu.html'; ?>
</header>

<main>
    <div id="titre">
        <h1>Justificatif de retard</h1>
        <p id="important"><b>Important :</b> Ce formulaire doit être entièrement complété.</p>
    </div>

    <?php if ($error != ""): ?>
        <p id="erreur" style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div id="infos">
            <br>
            <label for="id">Numéro d'étudiant *:</label>
            <input type="number" id="id" name="id" placeholder="Entrez votre numéro d'étudiant"
                   value="<?php echo htmlspecialchars($id); ?>" required><br><br>
        </div>

        <div id="inforetard">
            <label for="dateretard">Date du jour :</label>
            <input type="date" id="dateretard" name="dateretard"
                   value="<?php echo htmlspecialchars($dateretard); ?>" required>

            <label for="heurearrive">Heure d'arrivée :</label>
            <input type="time" id="heurearrive" name="heurearrive"
                   value="<?php echo htmlspecialchars($heurearrive); ?>" required><br><br>

            <label for="cours">Matière :</label>
            <input type="text" id="cours" name="cours"
                   value="<?php echo htmlspecialchars($cours); ?>" required>

            <label for="motif">Motif du retard :</label>
            <select id="motif" name="motif" required>
                <option value="Problème de santé" <?php if($motif=="Problème de santé") echo "selected"; ?>>Problème de santé</option>
                <option value="transport" <?php if($motif=="transport") echo "selected"; ?>>Transport</option>
                <option value="cours de conduite obligatoire" <?php if($motif=="cours de conduite obligatoire") echo "selected"; ?>>Cours de conduite obligatoire</option>
                <option value="Rendez vous chez le médecin" <?php if($motif=="Rendez vous chez le médecin") echo "selected"; ?>>Rendez-vous chez le médecin</option>
                <option value="aucune raison valable" <?php if($motif=="aucune raison valable") echo "selected"; ?>>Aucune raison valable</option>
                <option value="autres" <?php if($motif=="autres") echo "selected"; ?>>Autres</option>
            </select><br><br>

            <label for="preciserAutre">Commentaires :</label><br>
            <textarea id="preciserAutre" name="preciserAutre"
                      style="max-height: 500px; max-width: 800px; min-height: 70px; min-width: 600px;"
            ><?php echo htmlspecialchars($preciserAutre); ?></textarea><br><br>

            <label for="justificatifs">Insérer un ou plusieurs justificatifs :</label><br>
            <input type="file" id="justificatifs" name="justificatifs[]" accept=".pdf,image/*" multiple><br><br>
        </div>

        <div id="signature">
            <input type="submit" value="Valider">
        </div>
    </form>
</main>

<footer id="footer">
    <a style="color:black;" href="https://www.uphf.fr/">
        &copy; 2025 Université Polytechnique Hauts-de-France / IUT de Maubeuge
    </a>
</footer>
</body>
</html>
