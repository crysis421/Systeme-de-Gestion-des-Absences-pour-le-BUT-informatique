<?php
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}
require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = $_POST['PrenomInput'].'%' ?? null;
$nom = $_POST['NomInput'].'%' ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["boutonFiltre"])) {
    if (!empty($dateDebut) || !empty($dateFin) || !empty($matiere) || !empty($prenom) || !empty($nom)) {
        $justificatifs = $model->getJustificatifsAttenteFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom);
    }
    else $justificatifs = $model->getJustificatifsAttente();

} else {
    $justificatifs = $model->getJustificatifsAttente();
}

$justificatifsDemande = $model->getJustificatifsDemande();

$groupes = [];
foreach($justificatifs as $justif) {
    $id = $justif['idjustificatif'];
    if(!isset($groupes[$id])){
        $groupes[$id] = [
            'id' => $id,
            'commentaire' => $justif['commentaire_justificatif'],
            'nom' => $justif['nom_etudiant'],
            'prenom' => $justif['prenom_etudiant'],
            'description' => '',
            'absences' => []
        ];
    }

    $groupes[$id]['absences'][] = [
        'id' => $justif['id_absence'],
        'matiere' => $justif['matiere'],
        'date' => $justif['date_seance'],
        'heure' => $justif['heuredebut']
    ];

    // pour la desc
    $dates = array_column($groupes[$id]['absences'], 'date');
    sort($dates);

    $nbAbs = count($dates);
    $dateDebut = $dates[0];
    $dateFin = end($dates);

    $groupes[$id]['description'] = "$nbAbs absence" . ($nbAbs > 1 ? "s" : "") . ", du $dateDebut au $dateFin";
}

$titre = "";
$description = "";

//accepte, refuse, enAttente
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['bouton4'])) {

    $IDElement = isset($_POST['IDElement']) ? $_POST['IDElement'] : null;
    $choix = isset($_POST['toggle']) ? $_POST['toggle'] : null;
    $motif = isset($_POST['motifs']) ? $_POST['motifs'] : null;
    $refus = isset($_POST['motif_refus']) ? $_POST['motif_refus'] : null;
    $demande = isset($_POST['motif_demande']) ? $_POST['motif_demande'] : null;
    $checkboxAbsence = $_POST['checkboxAbsence'] ?? [];


    if (empty($checkboxAbsence)) {
        $titre = "Erreur";
        $description = "Veuillez sélectionner au moins une absence à traiter.";
    } else {
        if ($choix == "accepte") {
            $titre = "Accepté !";
            $description = $motif;
            $model->traiterAbsences($checkboxAbsence, 'valide', $motif);
        }

        if ($choix == "refuse") {
            $titre = "Refusé !";
            $description = $refus;
            if (strlen($refus) > 10) {
                $description = substr($refus, 0, 10) . "...";
            }
            $model->traiterAbsences($checkboxAbsence, 'refus', $refus);
        }

        if ($choix == "demande") {
            $titre = "Demandé !";
            $description = $demande;
            if (strlen($demande) > 10) {
                $description = substr($demande, 0, 10) . "...";
            }
            $model->traiterAbsences($checkboxAbsence, 'report', $demande);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tableauDeBordResponsable.css">
    <title>Tableau de bord absence</title>
</head>
<body>

<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>

<!-- TabBar ici ! -->
<ul>
    <a class="pages" href="tableauDeBordResponsable.php"><li>Tableau de bord</li></a>
    <a class="pages" href="HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="CompteResp.html"><li>Compte</li></a>
</ul>

<!-- Notification ici ! -->
<?php
if ($titre != "" && $description != "") {
    echo <<<EOL
<div class="notification">
    <h4 class="titre-notification">$titre</h4>
    <a class="description-notification">Avec le motif "$description"</a>
</div>
EOL;
}
?>

<!-- Titre -->
<h1><u>Liste des absences à traiter : </u></h1>

<!-- Filtrage ici ! -->
<details id="details">
    <summary class="filtrer">
        <img src="/Image/filter.png" alt="Filtre" class="Filtre" height="24">
        <a class="nom"><b>Filtrer</b></a><br>
    </summary>

    <div class="filtrage">
        <form method="post" id="formFiltre">

            <div class="dateFiltre">
                <?php
                $dateDebut = '2020-01-01';
                $dateFin = date("Y-m-d");
                ?>

                <h3>Date de début</h3>
                <input type="date" id="startDate" name="dateDebut" value="<?= $dateDebut ?>">
                <h3>Date de fin</h3>
                <input type="date" id="endDate" name="dateFin" value="<?= $dateFin ?>">
            </div>

            <div class="matiereFiltre">
                <h3>Nom de la matière</h3>
                <input type="text" id="inputMatiere" name="Matière" value="">
            </div>

            <div class="eleveFiltre">
                <h3>Prénom</h3>
                <input type="text" id="inputPrenom" name="PrenomInput" value="">
                <h3>Nom</h3>
                <input type="text" id="inputNom" name="NomInput" value="">
            </div>


            <input class='bouton-filtrage' type="submit" name="boutonFiltre" value="Filtrer">

        </form>

        <br>
    </div>
</details>


<!-- Liste des absences ici ! -->
<div class="liste-absence">
    <?php foreach ($groupes as $justif):
        $id = $justif['id'];
        $commentaire = $justif['commentaire'];
        $absences = $justif['absences'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['description']) ?></small>
                    </div>

                    <div class="ligne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="/Image/oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="/Image/close.png" alt="Fermer le justificatif">
                            </label>

                            <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                        </details>
                    </div>
                    <form method="post">

                        <input type="hidden" name="IDElement" value="<?= $justif['id'] ?>">

                        <?php foreach ($absences as $abs):
                            $matiere = rtrim(substr($abs['matiere'],-6),')');
                            $date = $abs['date'];
                            $heure = $abs['heure'];
                            $idAbsence = $abs['id'];
                            ?>
                            <input type="checkbox" name="checkboxAbsence[]" value="<?= $abs['id'] ?>" id="checkboxAbsence_<?= $abs['id'] ?>">
                            <label for="checkboxAbsence_<?= $abs['id'] ?>"><?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars($abs['heure'])?> <?= htmlspecialchars(rtrim(substr($abs['matiere'],-6),')'))?></label> <br>
                        <?php endforeach; ?>

                        <a class="decision-finale">Décision finale</a>

                        <input type="radio" id="toggle1_<?= $id ?>" name="toggle" value="accepte" style="display: none;">
                        <label for="toggle1_<?= $id ?>" class="label-accepter"></label>

                        <input type="radio" id="toggle2_<?= $id ?>" name="toggle" value="refuse" style="display: none;">
                        <label for="toggle2_<?= $id ?>" class="label-refuser"></label>

                        <input type="radio" id="toggle3_<?= $id ?>" name="toggle" value="demande" style="display: none;">
                        <label for="toggle3_<?= $id ?>" class="label-demander"></label>

                        <input type="hidden" id="IDElement_<?= $id ?>" value="<?= $id ?>" name="IDElement">

                        <br><br>

                        <div class="texte-accepter">
                            <select name="motifs" id="motif-absence-<?= $id ?>">
                                <option value="">--Choisissez une option--</option>
                                <option value="transport">Transport</option>
                                <option value="malade">Malade</option>
                                <option value="etc">...</option>
                            </select>
                            <br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-refuser">
                            Motif du refus : <br><br>
                            <textarea name="motif_refus" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-demander">
                            Motif de la demande : <br><br>
                            <textarea name="motif_demande" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>
                    </form>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>


<h1>Justificatifs redemandés</h1>
<!-- Liste des absences ici ! -->
<div class="liste-absence-demandes">
    <?php foreach ($justificatifsDemande as $justif):
        $id = $justif['idjustificatif'];
        $commentaire = $justif['commentaire_justificatif'] != '' ? $justif['commentaire_justificatif'] : $justif['cause'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom_etudiant']) ?> <?= htmlspecialchars($justif['prenom_etudiant']) ?></b></a><br>
                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['matiere']) ?></small>
                        <br><small><?= htmlspecialchars($justif['date_seance']) ?> à <?= htmlspecialchars($justif['heuredebut']) ?></small>
                    </div>

                    <div class="ligne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="/Image/oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="/Image/close.png" alt="Fermer le justificatif">
                            </label>

                            <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a>
                            <br>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                        </details>
                    </div>

                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>


</body>
</html>