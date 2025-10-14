<?php
// Initialisation des variables
$nom2 = $datedebut = $heuredebut = $fin = $heurefin1 = $motif = $commentaire = $signer = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom2 = $_POST['nom2'];
    $datedebut = $_POST['datedebut'];
    $heuredebut = $_POST['heuredebut'];
    $fin = $_POST['fin'];
    $heurefin1 = $_POST['heurefin1'];
    $motif = $_POST['motif'];
    $commentaire = $_POST['commentaire'];
    $signer = isset($_POST['signer']) ? $_POST['signer'] : "";

    // Vérifier date + heure
    $debut = strtotime("$datedebut $heuredebut");
    $finAbsence = strtotime("$fin $heurefin1");
    if ($finAbsence < $debut) {
        $error = "Erreur : la date et l'heure de fin doivent être supérieures ou égales à la date et l'heure de début.";
    }

    // Vérifier upload
    if (isset($_FILES['justificatif']) && $_FILES['justificatif']['error'] == 0) {
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['justificatif']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            $error = "Type de fichier non autorisé. Seuls PDF et images sont acceptés.";
        } else {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $justificatifFile = $uploadDir . basename($_FILES['justificatif']['name']);
            if (!move_uploaded_file($_FILES['justificatif']['tmp_name'], $justificatifFile)) {
                $error = "Erreur lors de l'upload du justificatif.";
            }
        }
    } else {
        $justificatifFile = '';
    }

    // Si pas d'erreur, redirection vers récap
    if ($error == "") {
        // Passer les données via session
        session_start();
        $_SESSION['formData'] = [
                'nom2' => $nom2,
                'datedebut' => $datedebut,
                'heuredebut' => $heuredebut,
                'fin' => $fin,
                'heurefin1' => $heurefin1,
                'motif' => $motif,
                'commentaire' => $commentaire,
                'justificatif' => $justificatifFile
        ];
        header("Location: recapitulatifJustificatifAbsence.php");
        exit();
    }
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
    <?php require 'menuHorizontalEtu.html'; ?>
</header>
<main>
    <div id="titre">
        <h1>Justificatif de retard </h1>
        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>

    </div>
    <?php if ($error != ""): ?>
        <p id="erreur" style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <div id="infoAbsence">
            <br>
            <label for="nom2">L'étudiant :
                <input type="text" name="nom2" id="nom2" value="<?php echo htmlspecialchars($nom2); ?>" required>
            </label><br><br>

            <label for="">Du :
                <input type="date" name="datedebut" value="<?php echo htmlspecialchars($datedebut); ?>" required>
                de <input type="time" name="heuredebut" value="<?php echo htmlspecialchars($heuredebut); ?>" required>
            </label><br><br>

            <label>Au :
                <input type="date" name="fin" value="<?php echo htmlspecialchars($fin); ?>" required>
                à <input type="time" name="heurefin1" value="<?php echo htmlspecialchars($heurefin1); ?>" required>
            </label><br><br>

            <label>Motif :
                <select name="motif" required>
                    <option value="Problème de santé" <?php if($motif=="Problème de santé") echo "selected"; ?>>Problème de santé</option>
                    <option value="transport" <?php if($motif=="transport") echo "selected"; ?>>Problème de transport</option>
                    <option value="problèmes d'inscription" <?php if($motif=="problèmes d'inscription") echo "selected"; ?>>Problèmes d'inscription</option>
                    <option value="cours de conduite obligatoire" <?php if($motif=="cours de conduite obligatoire") echo "selected"; ?>>Cours de conduite obligatoire</option>
                    <option value="Rendez vous chez le medicin" <?php if($motif=="Rendez vous chez le medicin") echo "selected"; ?>>Rendez vous chez le médecin</option>
                    <option value="aucune raison valable" <?php if($motif=="aucune raison valable") echo "selected"; ?>>Aucune raison valable</option>
                    <option value="autres" <?php if($motif=="autres") echo "selected"; ?>>Autres</option>
                </select>
            </label><br><br>


            <label>Commentaires :</label><br>
            <textarea name="commentaire" style="width: 700px; height: 100px;" required><?php echo htmlspecialchars($commentaire); ?></textarea><br><br>

            <label>Inserer un justificatif :</label>
            <input type="file" name="justificatif" accept=".pdf,image/*"><br><br>
            <br>
            <br>
            <br>
        </div>
        <div id="signature">
            <input type="submit" value="Valider">
            <br>
            <br>
            <br>
        </div>
    </form>
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>

