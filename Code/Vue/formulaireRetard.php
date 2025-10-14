<?php
session_start(); // Toujours en premier

// Initialisation des variables
$nom = $prenom = $dateretard = $heurearrive = $cours = $motif = $preciserAutre = "";
$error = "";
$justificatifFile = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateretard = $_POST['dateretard'];
    $heurearrive = $_POST['heurearrive'];
    $cours = $_POST['cours'];
    $motif = $_POST['motif'];
    $preciserAutre = $_POST['preciserAutre'];

    if (isset($_FILES['justificatif']) && $_FILES['justificatif']['error'] == 0) {
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];
        $fileType = $_FILES['justificatif']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            $error = "Type de fichier non autorisé. Seuls PDF et images sont acceptés.";
        } else {
            $uploadDir = 'uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $justificatifFile = $uploadDir . uniqid() . '_' . basename($_FILES['justificatif']['name']);
            if (!move_uploaded_file($_FILES['justificatif']['tmp_name'], $justificatifFile)) {
                $error = "Erreur lors de l'upload du justificatif.";
            }
        }
    }

    if ($error == "") {
        $_SESSION['formDataRetard'] = [
                'nom' => $nom,
                'prenom' => $prenom,
                'dateretard' => $dateretard,
                'heurearrive' => $heurearrive,
                'cours' => $cours,
                'motif' => $motif,
                'preciserAutre' => $preciserAutre,
                'justificatif' => $justificatifFile
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
    <title>FormulaireRetard</title>
</head>
<body>
<header>
    <?php require 'menuHorizontalEtu.html' ?>
</header>
<main>
    <div id="titre">
        <h1>Justificatif de retard </h1>

        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>
    </div>
    <?php if ($error !=""): ?>
        <p id="erreur" style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
    <div id="infos">
        <br>
        <label for="nom">Nom *: </label>
        <input type="text" id="nom" name="nom" placeholder="entrer votre nom" value="<?php echo htmlspecialchars($nom) ?>" required><br>
        <br>

        <label for="prenom">Prénom *: </label>
        <input type="text" id="prenom" name="prenom" placeholder="entrer votre prénom" value="<?php echo htmlspecialchars($prenom) ?>" required><br>
        <br>

    </div>

    <div id="inforetard">

        <label for="dateretard"> Date du jour :</label>
        <input type="date" id="dateretard" name="dateretard" value="<?php echo htmlspecialchars($dateretard) ?>" required>

        <label for="heurearrive"> heure d'arrivée :</label>
        <input type="time" id="heurearrive" name="heurearrive" value="<?php echo htmlspecialchars($heurearrive) ?>" required><br>
        <br>
        <label for="cours"> Matière :</label>
        <input type="text" id="cours" name="cours" value="<?php echo htmlspecialchars($cours) ?>" required>

        <label for="motif">Motif du retard : </label>
        <select id="motif" name="motif" required>
            <option value="Problème de santé" <?php if($motif=="Problème de santé") echo "selected"; ?> >Problème de santé</option>
            <option value="transport" <?php if($motif=="transport") echo "selected"; ?>>transport</option>
            <option value="problème de transport" <?php if($motif=="problème de transport") echo "selected"; ?>>problème de transport</option>
            <option value="cours de conduite obligatoire" <?php if($motif=="cours de conduite obligatoire") echo "selected"; ?>>cours de conduite obligatoire</option>
            <option value="Rendez vous chez le medicin" <?php if($motif=="Rendez vous chez le medicin") echo "selected"; ?>>Rendez vous chez le medicin</option>
            <option value="aucune raison valable" <?php if($motif=="aucune raison valable") echo "selected"; ?>>aucune raison valable</option>
            <option value="autres" <?php if($motif=="autres") echo "selected"; ?>>Autres</option>

        </select><br>
        <br>

        <label for="preciserAutre">Commentaires :</label><br>
        <textarea id="preciserAutre" name="preciserAutre"  style="width: 700px; height: 100px;"><?php echo htmlspecialchars($preciserAutre) ?></textarea>
        <br>
        <br>
        <label for="justificatif">Inserer un justificatif :</label><br>
        <input type="file" id="justificatif" name="justificatif" accept=".pdf,image/*" />
        <br>
        <br>
        <br>
    </div>
    <div id="signature">
        <br>
        <input type="submit" value="valider">
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