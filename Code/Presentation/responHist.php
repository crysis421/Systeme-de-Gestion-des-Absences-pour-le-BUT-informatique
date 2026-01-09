<?php
require_once "../Model/AbsenceModel.php";

$model = new AbsenceModel();

$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = $_POST['PrenomInput'] ?? null;
$nom = $_POST['NomInput'] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["boutonFiltre"])) {
    if (!empty($dateDebut) || !empty($dateFin) || !empty($matiere) || !empty($prenom) || !empty($nom)) {
        $justificatifs = $model->getJustificatifsHistoriqueFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom);
    }
    else $justificatifs = $model->getJustificatifsHistorique();

} else {
    $justificatifs = $model->getJustificatifsHistorique();
}
$model = null;

$groupes = [];
foreach($justificatifs as $justif) {
    $id = $justif['idjustificatif'];
    if(!isset($groupes[$id])){
        $groupes[$id] = [
            'id' => $id,
            'commentaire' => $justif['commentaire_traitement'],
            'nom' => $justif['nom_etudiant'],
            'prenom' => $justif['prenom_etudiant'],
            'description' => '',
            'absences' => [],
            'datesoumission' => $justif['datesoumission'],
            'reponse'=> $justif['reponse'],
        ];
    }

    $groupes[$id]['absences'][] = [
        'id' => $justif['id_absence'],
        'matiere' => $justif['matiere'],
        'date' => $justif['date_seance'],
        'heure' => $justif['heuredebut'],
        'status' => $justif['statut_absence']
    ];

    // pour la desc
    $dates = array_column($groupes[$id]['absences'], 'date');
    if(!empty($dates)) {
        sort($dates);
        $nbAbs = count($dates);
        $dStart = $dates[0];
        $dEnd = end($dates);

        // ofao
        $nombreAbs = 0;
        foreach ($groupes[$id]['absences'] as $abs){
            $statusAbsence = $abs['status'];
            $nombreAbs++;
        }

        $groupes[$id]['description'] = $nombreAbs . " absence" . ($nbAbs > 1 ? "s" : "") . ", du $dStart au $dEnd";
    }
}

$justificatifs = array_slice($justificatifs, 0, 10);

?>