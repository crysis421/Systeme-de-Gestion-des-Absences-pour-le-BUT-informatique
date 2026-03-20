<?php
require_once __DIR__ . "/../Model/AbsenceModel.php";

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = isset($_POST['PrenomInput']) ? $_POST['PrenomInput'] . '%' : null;
$nom = isset($_POST['NomInput']) ? $_POST['NomInput'] . '%' : null;

$model = new AbsenceModel();

if (!empty($dateDebut) || !empty($dateFin) || !empty($matiere) || !empty($prenom) || !empty($nom)) {
    $justificatifs = $model->getJustificatifsAttenteFiltre($dateDebut,$dateFin,$matiere,$nom,$prenom);
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
            'description' => '',
            'absences' => [],
            'datesoumission' => $justif['datesoumission'],
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

foreach ($groupes as $justif):
    ?>

    <div class="element">
        <details>
            <summary class="top-layer">
                <img src="/Image/profil_default.webp" class="image-utilisateur" height="24">

                <a class="nom">
                    <b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b>
                </a>

                <div class="description-element">
                    <small>Soumis le <?= htmlspecialchars($justif['datesoumission']) ?></small>
                </div>

                <div class="ligne"></div>
            </summary>

            <div class="details">
                <?php foreach ($justif['absences'] as $abs): ?>
                    <a>
                        <?= htmlspecialchars($abs['date']) ?>
                        <?= htmlspecialchars(substr($abs['heure'],0,5)) ?>
                        <?= htmlspecialchars($abs['matiere']) ?>
                    </a>
                    <br>
                <?php endforeach; ?>
            </div>

        </details>
    </div>

<?php endforeach; ?>