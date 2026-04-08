<?php
session_start();
$formData = $_SESSION['formData'] ?? [];
//on prends les infos du raccourcis calendrier mais si il n y en a pas on ne met rien
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/formulaire/formulaire.css?v=1"/>
    <link rel="stylesheet" href="../../CSS/formulaire/formulaireViolet.css?v=1"/>
    <title>Formulaire d'absence</title>
</head>
<body>
<header>
    <?php require __DIR__ . '/menuHorizontalEtu.html'; ?>
</header>
<main>
    <div id="titre">
        <h1>Justificatif d'absence</h1>
        <p id="important"><b>Important : </b>Ce formulaire doit être entièrement complété.</p>
    </div>
    <?php if ($error != ""): ?>
        <p id="erreur" style="color:red; font-weight:bold;"><?php echo $error; ?></p>
    <?php endif;
    if($_SESSION['aEssayer']) {
        echo "<h1 style='color: red'>Vous n'avez pas d'absence à justifier entre ces dates</h1>";
        $_SESSION['aEssayer'] = false;
    }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div id="infoAbsence">
            <br>
            <label for="id">Numéro d'étudiant :   <p id="autre"  style="color:#09572b ;font-size: 80%"></p><br>
                <input type="number" name="id" id="id" placeholder="Entrez votre Numero d'étudiant" onkeyup="verifierEtudiant()" value="<?php echo htmlspecialchars($_SESSION['user']); ?>" required>
            </label><br>
            <label for="">Du : <p style="color: red;display: none" id="erreur1">Veuillez d'abord remplir ce champ</p>
                <input type="date" id="debut" name="datedebut" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("20".$_GET['date'][-2].$_GET['date'][-1]."-".$_GET['date'][-7].$_GET['date'][-6]."-".$_GET['date'][-10].$_GET['date'][-9]);} else{echo htmlspecialchars($datedebut);} ?>" required>
<!--                de <input type="time" name="heuredebut" value="--><?php //if(isset($_GET['date'])){echo htmlspecialchars("08:00");} else{echo htmlspecialchars($heuredebut);} ?><!--" required>-->
                de  <p style="color: red;display: none" id="erreur2">  Veuillez d'abord remplir ce champ</p>
                <select name="heuredebut" id="heuredebut" required>
                    <?php
                    $heures = ["08:00","09:30","11:00","12:30","14:00","15:30","17:00"];
                    foreach ($heures as $heure) {
                        $selected = null;
                        // Détermine si cette option doit être sélectionnée
                        if (isset($_GET['date'])) {
                            if($heure == "08:00") {
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                        } else {
                            if($heure == $heuredebut) {
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                        }
                        echo "<option value=\"$heure\" $selected>$heure</option>";
                    }
                    ?>
                </select>
            </label><br><br>
            <p id="erreur4" style="color:red; font-weight:bold;display: none">Verifiez que les dates d'absences entrées sont correctes/cohérentes</p>
            <label>Au :  <p style="color: red;display: none" id="erreur3">   Veuillez d'abord remplir ce champ</p>
                <input type="date" id="fin" name="fin" value="<?php if(isset($_GET['date'])){echo htmlspecialchars("20".$_GET['date'][-2].$_GET['date'][-1]."-".$_GET['date'][-7].$_GET['date'][-6]."-".$_GET['date'][-10].$_GET['date'][-9]);} else{ echo htmlspecialchars($fin);} ?>" required>
<!--                à <input type="time" name="heurefin1" value="--><?php //if(isset($_GET['date'])){echo htmlspecialchars("19:00");} else{ echo htmlspecialchars($heurefin1);} ?><!--" required>-->
                à
                <select name="heurefin1" id="heurefin" required>
                    <?php
                    $heures = ["09:30","11:00","12:30","14:00","15:30","17:00","18:30"];
                    foreach ($heures as $heure) {
                        $selected = null;
                        // Détermine si cette option doit être sélectionnée
                        if (isset($_GET['date'])) {
                            if($heure == "18:30") {
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                        } else {
                            if($heure == $heurefin1) {
                                $selected = "selected";
                            }else{
                                $selected = "";
                            }
                        }
                        echo "<option value=\"$heure\" $selected>$heure</option>";
                    }
                    ?>
                </select>
            </label><br><br>
            <label>Motif :
                <select name="motif" id="motif" required>
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
            <textarea name="commentaire"><?php echo htmlspecialchars($commentaire); ?></textarea>
            <label>Ajouter un ou plusieurs justificatifs :</label><br>
            <p id="message" style="color:red;"></p>
            <input type="file" id="import" name="justificatifs[]" accept=".pdf,image/*" multiple><br>
            <p style="font-size: 20px"> La taille maximale pour des fichiers importés est de <u style="color: red" >2MO</u></p>
            <i style="font-size: 17px">Pour sélectionner plusieurs fichiers à la fois, maintiens Ctrl (ou Cmd sur Mac) pour choisir individuellement ou Shift pour sélectionner un bloc de fichiers consécutifs avant de cliquer sur “Ouvrir”.</i>
            <br><br><br>
        </div>
        <div id="signature">
            <input id="submit" type="submit" value="Valider">
            <br><br><br>
        </div>
    </form>
</main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
<script src="../../ajax/ajaxFormulaire.js"></script>
</body>
</html>