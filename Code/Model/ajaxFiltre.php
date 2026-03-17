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

    $dates = array_column($groupes[$id]['absences'], 'date');
    if (!empty($dates)) {
        sort($dates);
        $nbAbs = count($dates);
        $dStart = $dates[0];
        $dEnd = end($dates);

// ofao
        $nombreAbs = 1;
        foreach ($groupes[$id]['absences'] as $abs) {
            $statusAbsence = $abs['status'];
            if ($statusAbsence != 'report') continue;
            $nombreAbs++;
        }

        $groupes[$id]['description'] = $nbAbs . " absence" . ($nbAbs > 1 ? "s" : "") . ", du $dStart au $dEnd";
    }
}

if (empty($groupes)) {
    echo "<p>Aucun résultat trouvé.</p>";
    exit;
}

foreach ($groupes as $justif):
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
                    <small><?= htmlspecialchars($justif['description']) ?></small> <br>
                    <small>Soumis le <?= htmlspecialchars($justif['datesoumission']) ?></small>
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
                        <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a> <br>
                        <div class="fondu-noir"></div>
                        <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                    </details>
                </div>

                <form method="post">
                    <input type="hidden" name="IDElement" value="<?= $id ?>" >

                    <?php foreach ($absences as $abs):

                        $matiere = $abs['matiere'];
                        preg_match('#\((.*?)\)#', $matiere, $match);
                        $matiere = $match[1];
                        $matiere = explode("-", $matiere)[1];
                        ?>
                        <input type="checkbox" name="checkboxAbsence[]" value="<?= $abs['id'] ?>" id="checkboxAbsence_<?= $abs['id'] ?>" checked>
                        <label for="checkboxAbsence_<?= $abs['id'] ?>">
                            <?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars(substr($abs['heure'],0,5))?> <?= htmlspecialchars($matiere)?>
                        </label> <br>
                    <?php endforeach; ?>

                    <a class="decision-finale">Décision finale</a>
                    <!-- Boutons radios de décision -->
                    <input type="radio" id="toggle1_<?= $id ?>" name="toggle" value="accepte" style="display: none;">
                    <label for="toggle1_<?= $id ?>" class="label-accepter"></label>

                    <input type="radio" id="toggle2_<?= $id ?>" name="toggle" value="refuse" style="display: none;">
                    <label for="toggle2_<?= $id ?>" class="label-refuser"></label>

                    <input type="radio" id="toggle3_<?= $id ?>" name="toggle" value="demande" style="display: none;">
                    <label for="toggle3_<?= $id ?>" class="label-demander"></label>

                    <br><br>
                    <div class="texte-accepter">
                        <select name="motifs">
                            <option value="transport">Transport</option>
                            <option value="malade">Malade</option>
                        </select>
                        <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                    </div>

                    <div class="texte-refuser">
                        Motif du refus : <textarea name="motif_refus"></textarea>
                        <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                    </div>

                    <div class="texte-demander">
                        Motif de la demande : <textarea name="motif_demande"></textarea>
                        <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                    </div>
                    <div class="ligne2"></div>
                </form>
            </div>
        </details>
    </div>
<?php endforeach; ?>