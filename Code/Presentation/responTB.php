<?php
require_once "../Model/AbsenceModel.php";
require_once '../test/send.php';
use test\send;


$dateDebut = $_POST['dateDebut'] ?? null;
$dateFin = $_POST['dateFin'] ?? null;
$matiere = $_POST['Matière'] ?? null;
$prenom = isset($_POST['PrenomInput']) ? $_POST['PrenomInput'] . '%' : null;
$nom = isset($_POST['NomInput']) ? $_POST['NomInput'] . '%' : null;
$model = new AbsenceModel();
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["boutonFiltre"])) {
    if (!empty($dateDebut) || !empty($dateFin) || !empty($matiere) || !empty($prenom) || !empty($nom)) {
        $justificatifs = $model->getJustificatifsAttenteFiltre($dateDebut, $dateFin, $matiere, $nom, $prenom);
    } else $justificatifs = $model->getJustificatifsAttente();

} else {
    $justificatifs = $model->getJustificatifsAttente();
}

$justificatifsDemande = $model->getJustificatifsDemande();

$groupes = [];
foreach ($justificatifs as $justif) {
    $id = $justif['idjustificatif'];
    if (!isset($groupes[$id])) {
        $groupes[$id] = [
            'id' => $id,
            'commentaire' => $justif['commentaire_traitement'],
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

// pour la desc
    $dates = array_column($groupes[$id]['absences'], 'date');
    if (!empty($dates)) {
        sort($dates);
        $nbAbs = count($dates);
        $dStart = $dates[0];
        $dEnd = end($dates);

// ofao
        $nombreAbs = 0;
        foreach ($groupes[$id]['absences'] as $abs) {
            $statusAbsence = $abs['status'];
            if ($statusAbsence != 'report') continue;
            $nombreAbs++;
        }

        $groupes[$id]['description'] = $nombreAbs . " absence" . ($nbAbs > 1 ? "s" : "") . ", du $dStart au $dEnd";
    }
}

$groupesDemandes = [];
foreach ($justificatifsDemande as $justif) {
    $id = $justif['idjustificatif'];
    if (!isset($groupesDemandes[$id])) {
        $groupesDemandes[$id] = [
            'id' => $id,
            'commentaire' => $justif['commentaire_traitement'],
            'nom' => $justif['nom_etudiant'],
            'prenom' => $justif['prenom_etudiant'],
            'description' => '',
            'absences' => []
        ];
    }

    $groupesDemandes[$id]['absences'][] = [
        'id' => $justif['id_absence'],
        'matiere' => $justif['matiere'],
        'date' => $justif['date_seance'],
        'heure' => $justif['heuredebut']
    ];

// pour la desc
    $dates = array_column($groupesDemandes[$id]['absences'], 'date');
    if (!empty($dates)) {
        sort($dates);
        $nbAbs = count($dates);
        $dStart = $dates[0];
        $dEnd = end($dates);

        $groupesDemandes[$id]['description'] = "$dStart au $dEnd";
    }
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

        $idUser = $model->getIdUserByIdJustificatif($IDElement);

        $mail = $model->getEmailbyUser($idUser);


        $contenu = "<h1>Notification de traitement de votre Justificatif</h1>
                <p>Votre Justificatif N° $IDElement a bien été traité par le responsable.</p>
                <p>Veuillez-vous contecter à votre de compte de gestion d'absence pour voir quelle décision a été prise</p>";
        $mailer = new send();

        $result = $mailer->envoyerMailSendGrid($mail,'Traitement justificatif absence',$contenu);


        if ($choix == "accepte") {
            $titre = "Accepté !";
            $description = $motif;
            $model->traiterAbsences($IDElement, $checkboxAbsence, 'valide', $motif);
        }

        if ($choix == "refuse") {
            $titre = "Refusé !";
            $description = $refus;
            if (strlen($refus) > 10) {
                $description = substr($refus, 0, 10) . "...";
            }
            $model->traiterAbsences($IDElement, $checkboxAbsence, 'refus', $motif);
        }

        if ($choix == "demande") {
            $titre = "Demandé !";
            $description = $demande;
            if (strlen($demande) > 10) {
                $description = substr($demande, 0, 10) . "...";
            }
            $model->traiterAbsences($IDElement, $checkboxAbsence, 'report', $motif);
        }
    }
}
$model = null;

?>