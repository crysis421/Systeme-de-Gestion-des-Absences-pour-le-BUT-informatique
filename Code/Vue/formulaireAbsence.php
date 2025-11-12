<?php
session_start();

$formData = $_SESSION['formData'] ?? [];

$id = $formData['id'] ?? '';
$datedebut = $formData['datedebut'] ?? '';
$heuredebut = $formData['heuredebut'] ?? '';
$fin = $formData['fin'] ?? '';
$heurefin1 = $formData['heurefin1'] ?? '';
$motif = $formData['motif'] ?? '';
$commentaire = $formData['commentaire'] ?? '';
$justificatifs = $formData['justificatifs'] ?? [];

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $datedebut = $_POST['datedebut'];
    $heuredebut = $_POST['heuredebut'];
    $fin = $_POST['fin'];
    $heurefin1 = $_POST['heurefin1'];
    $motif = $_POST['motif'];
    $commentaire = $_POST['commentaire'];

    $debut = strtotime("$datedebut $heuredebut");
    $finAbsence = strtotime("$fin $heurefin1");
    if ($finAbsence < $debut) {
        $error = "Erreur : vérifiez votre période d'absence.";
    }

// Définition du dossier où seront enregistrés les fichiers uploadés
    $uploadDir = 'uploads/';

// Vérifie si le dossier "uploads" existe déjà
// Si ce n'est pas le cas, on le crée avec les permissions 0777 (lecture/écriture/exécution pour tous)
// Le paramètre "true" permet de créer aussi les sous-dossiers manquants s'il y en a
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

// Si la variable $justificatifs n'existe pas encore, on l'initialise comme un tableau vide
// Cela permettra d'y stocker les chemins des fichiers uploadés
    if (!isset($justificatifs)) $justificatifs = [];

// Vérifie si des fichiers ont été envoyés via le champ de formulaire "justificatifs"
    if (isset($_FILES['justificatifs'])) {

        // Définit la liste des types de fichiers autorisés : PDF et images
        $allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'];

        // Parcourt tous les fichiers envoyés un par un
        // $_FILES['justificatifs']['tmp_name'] contient les fichiers temporaires créés par PHP
        foreach ($_FILES['justificatifs']['tmp_name'] as $index => $tmpName) {

            // Vérifie que le fichier a bien été envoyé sans erreur
            if ($_FILES['justificatifs']['error'][$index] === 0) {

                // Récupère le type MIME du fichier (ex: image/jpeg, application/pdf)
                $fileType = $_FILES['justificatifs']['type'][$index];

                // Vérifie que le type du fichier fait partie des types autorisés
                if (in_array($fileType, $allowedTypes)) {

                    // Récupère le nom d’origine du fichier (sans le chemin complet)
                    $fileName = basename($_FILES['justificatifs']['name'][$index]);

                    // Construit le chemin complet où sera enregistré le fichier
                    // On ajoute time() (le timestamp actuel) pour éviter les collisions de noms
                    $targetPath = $uploadDir . time() . "_" . $fileName;

                    // Déplace le fichier depuis son dossier temporaire vers le dossier "uploads"
                    // move_uploaded_file() retourne true si le déplacement a réussi
                    if (move_uploaded_file($tmpName, $targetPath)) {

                        // Si tout s’est bien passé, on ajoute le chemin du fichier au tableau des justificatifs
                        $justificatifs[] = $targetPath;
                    }
                } else {
                    // Si le type du fichier n’est pas autorisé, on crée un message d’erreur
                    // et on arrête la boucle
                    $error = "Un ou plusieurs fichiers ont un type non autorisé.";
                    break;
                }
            }
        }
    }


    if ($error == "") {
        $_SESSION['formData'] = [
                'id' => $id,
                'datedebut' => $datedebut,
                'heuredebut' => $heuredebut,
                'fin' => $fin,
                'heurefin1' => $heurefin1,
                'motif' => $motif,
                'commentaire' => $commentaire,
                'justificatifs' => $justificatifs
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
        <h1>Justificatif d'absence </h1>
        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>

    </div>
    <?php if ($error != ""): ?>
        <p id="erreur" style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif;
    if($_SESSION['aEssayer']) {
        echo "<h1>Vous n'avez pas d'absence entre ces dates</h1>";
        $_SESSION['aEssayer'] = false;
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div id="infoAbsence">
            <br>
            <label for="id">Numéro d'étudiant :
                <input type="number" name="id" id="id" placeholder="Entrer votre Numero d'étudiant" value="<?php echo htmlspecialchars($_SESSION['user']); ?>" required>
            </label><br><br>

            <label for="">Du :
                <input type="date" name="datedebut" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("20".$_GET['date'][-2].$_GET['date'][-1]."-".$_GET['date'][-5].$_GET['date'][-4]."-".$_GET['date'][-8].$_GET['date'][-7]);} else{echo htmlspecialchars($datedebut);} ?>" required>
                de <input type="time" name="heuredebut" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("08:00");} else{echo htmlspecialchars($heuredebut);} ?>" required>
            </label><br><br>

            <label>Au :
                <input type="date" name="fin" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("20".$_GET['date'][-2].$_GET['date'][-1]."-".$_GET['date'][-5].$_GET['date'][-4]."-".$_GET['date'][-8].$_GET['date'][-7]);} else{ echo htmlspecialchars($fin);} ?>" required>
                à <input type="time" name="heurefin1" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("19:00");} else{ echo htmlspecialchars($heurefin1);} ?>" required>
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
            <textarea name="commentaire" style="max-height: 500px; max-width: 800px ; min-height: 70px; min-width: 600px width: 700px; height: 100px;" required><?php echo htmlspecialchars($commentaire); ?></textarea><br><br>

            <label>Ajouter un ou plusieurs justificatifs :</label><br>
            <input type="file" name="justificatifs[]" accept=".pdf,image/*" multiple><br><br>
            <i style="font-size: 17px">Pour sélectionner plusieurs fichiers à la fois, maintiens Ctrl (ou Cmd sur Mac) pour choisir individuellement ou Shift pour sélectionner un bloc de fichiers consécutifs avant de cliquer sur “Ouvrir”.</i>

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

