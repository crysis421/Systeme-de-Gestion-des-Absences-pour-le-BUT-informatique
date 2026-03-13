<?php
require_once "../Model/AbsenceModel.php";
$model = new AbsenceModel();

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = !empty($_POST['PrenomInput']) ? $_POST['PrenomInput'] . '%' : null;
$nom = !empty($_POST['NomInput']) ? $_POST['NomInput'] . '%' : null;

if ($dateDebut || $dateFin || $matiere || $prenom || $nom) {
    $justificatifs = $model->getJustificatifsAttenteFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom);
} else {
    $justificatifs = $model->getJustificatifsAttente();
}

$groupes = [];
foreach ($justificatifs as $justif) {
    $id = $justif['idjustificatif'];
    if (!isset($groupes[$id])) {
        $groupes[$id] = [
            'id' => $id,
            'nom' => $justif['nom_etudiant'],
            'prenom' => $justif['prenom_etudiant'],
            'commentaire' => $justif['commentaire_traitement'],
            'datesoumission' => $justif['datesoumission'],
            'absences' => []
        ];
    }
    $groupes[$id]['absences'][] = [
        'id' => $justif['id_absence'],
        'matiere' => $justif['matiere'],
        'date' => $justif['date_seance'],
        'heure' => $justif['heuredebut'],
        'status' => $justif['statut_absence']
    ];
}

if (empty($groupes)) {
    echo "<p>Aucun résultat trouvé.</p>";
    exit;
}

foreach ($groupes as $justif):
    $id = $justif['id'];
    ?>
    <div class="element">
        <details>
            <summary class="top-layer">
                <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                <div class="description-element">
                    <small>Soumis le <?= htmlspecialchars($justif['datesoumission']) ?></small>
                </div>
                <div class="ligne"></div>
            </summary>

            <div class="details">
                <form method="post">
                    <input type="hidden" name="IDElement" value="<?= $id ?>">
                    <?php foreach ($justif['absences'] as $abs): ?>
                        <input type="checkbox" name="checkboxAbsence[]" value="<?= $abs['id'] ?>" checked>
                        <label><?= htmlspecialchars($abs['date']) ?> - <?= htmlspecialchars($abs['matiere']) ?></label><br>
                    <?php endforeach; ?>

                    <a class="decision-finale">Décision finale</a>
                    <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                </form>
            </div>
        </details>
    </div>
<?php endforeach; ?>