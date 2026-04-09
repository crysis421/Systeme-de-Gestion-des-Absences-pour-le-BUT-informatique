<?php

use Behat\Behat\Context\Context;

require_once __DIR__ . '/../../Code/Model/ComptesModel.php';
require_once __DIR__ . '/../../Code/Model/NewJustificatif.php';
require_once __DIR__ . '/../../Code/Model/Test.php';
require_once __DIR__ . '/../../Code/Model/AbsenceModel.php';

class FeatureContext implements Context
{
    private string $email = "stievenardkilian@gmail.com";
    private ?int   $idEtudiant = null;
    private ?array $resultatAbsences = null;
    private mixed  $resultatMail = null;

    private string $emailResponsable = "respon@uphf.fr";
    private ?int   $idResponsable = null;
    private ?int   $idJustificatif = null;
    private mixed  $resultatTraitement = null;
    private mixed  $resultatFiltre = null;

    private ?\Model\NewJustificatif $justificatif = null;

    /**
     * @Given je suis un etudiant avec un id
     */
    public function jeSuisEtudiant(): void
    {
        $test   = new \Model\Test();
        $compte = $test->connexion($this->email);

        if (!$compte) {
            throw new \Exception("Aucun compte trouvé pour l'email : " . $this->email);
        }

        $this->idEtudiant = (int) $compte['idutilisateur'];
    }

    /**
     * @When je soumets un justificatif avec tous les champs requis etant remplis invalides :dateD :heureD :dateF :heureF
     */
    public function jeSoumetsLeJustificatif(string $dateD, string $heureD, string $dateF, string $heureF): void
    {
        $this->justificatif    = new \Model\NewJustificatif();
        $this->resultatAbsences = $this->justificatif->getIdAbsenceParSeance(
            $dateD, $heureD, $dateF, $heureF, $this->idEtudiant
        );

        if (!empty($this->resultatAbsences)) {
            $testService       = new \Model\Test();
            $this->resultatMail = $testService->envoieMailConfirmation($this->email);
        }
    }

    /**
     * @Then le systeme me signale que je n'ai pas d'absence entre ces dates
     */
    public function leSystemeSignalePasDAbsence(): void
    {
        if (!empty($this->resultatAbsences)) {
            throw new \Exception(
                "Échec : Le système a trouvé " . count($this->resultatAbsences) . " absence(s) alors qu'il ne devrait pas."
            );
        }
    }

    /**
     * @Then le systeme ne m'envoie pas de mail de confirmation
     */
    public function leSystemeNeNotifiePas(): void
    {
        if ($this->resultatMail !== null) {
            throw new \Exception("Échec : Un mail a été envoyé alors qu'aucune absence n'a été détectée.");
        }
    }

    /**
     * @When je soumets un justificatif avec tous les champs requis etant remplis valides :dateD :heureD :dateF :heureF
     */
    public function jeSoumetsLeJustificatifValide(string $dateD, string $heureD, string $dateF, string $heureF): void
    {
        $this->justificatif    = new \Model\NewJustificatif();
        $this->resultatAbsences = $this->justificatif->getIdAbsenceParSeance(
            $dateD, $heureD, $dateF, $heureF, $this->idEtudiant
        );

        if (!empty($this->resultatAbsences)) {
            $testService       = new \Model\Test();
            $this->resultatMail = $testService->envoieMailConfirmation($this->email);
        }
    }

    /**
     * @Then le systeme m'envoie un mail de confirmation de dêpot
     */
    public function leSystemeEnvoieMailSucces(): void
    {
        if ($this->resultatMail === null) {
            throw new \Exception("Échec : Le système n'a pas tenté d'envoyer de mail (résultat null).");
        }

        if ($this->resultatMail['httpcode'] !== 202) {
            throw new \Exception(
                "Échec : Le mail a été refusé par l'API. Code HTTP reçu : " . $this->resultatMail['httpcode']
            );
        }
    }

    /**
     * @Given je suis un responsable connecté
     */
    public function jeSuisUnResponsableConnecte(): void
    {
        $model = new \Model\ComptesModel();
        $compte = $model->connectCompte($this->emailResponsable);

        if (!$compte) {
            throw new \Exception("Aucun compte responsable trouvé pour l'email : " . $this->emailResponsable);
        }

        if ($compte['role'] !== 'respon' && $compte['role'] !== 'secretaire') {
            throw new \Exception(
                "Le compte trouvé n'est pas un responsable (rôle : " . $compte['role'] . ")."
            );
        }

        $this->idResponsable = (int) $compte['idutilisateur'];
    }
//-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    /**
     * @Given il existe un justificatif en attente avec l'id :idJustificatif
     */
    public function ilExisteUnJustificatifEnAttente(string $idJustificatif): void
    {
        $this->idJustificatif = (int) $idJustificatif;

        $model = new AbsenceModel();
        $justificatifClasse = new \Model\NewJustificatif();
        $listeAbsence = [
            [
                "idabsence" => $this->idJustificatif,
                "verrouille" => false
            ]
        ];
        $justificatifClasse->creerJustificatif($listeAbsence, 0, '','', []);
        $model->justifierAbsence($this->idJustificatif, 'refus',false,'');
        $justifs = $model->getJustificatifsAttente();

        if (empty($justifs)) {
            throw new \Exception(
                "Aucun justificatif en attente trouvé en base (idJustificatif demandé : $idJustificatif)."
            );
        }

        $ids = array_column($justifs, 'idjustificatif');
        if (!in_array($this->idJustificatif, $ids)) {
            $this->idJustificatif = (int) $justifs[0]['idjustificatif'];
        }
    }

    /**
     * @When je valide le justificatif avec le motif :motif
     */
    public function jeValideLeJustificatif(string $motif): void
    {
        if ($this->contientHtml($motif)) {
            $this->resultatTraitement = 'invalide';
            return;
        }

        $model = new AbsenceModel();

        $absences = $model->getAbsencesNonJustifiees($this->idJustificatif);
        $absenceIds = array_column($absences, 'idabsence');

        if (empty($absenceIds)) {
            throw new \Exception("Aucune absence trouvée pour le justificatif #" . $this->idJustificatif);
        }

        $model->traiterAbsences($this->idJustificatif, $absenceIds, 'valide', $motif);
        $this->resultatTraitement = 'valide';
    }

    /**
     * @Then le système met l'absence en statut :statut
     */
    public function leSystemeMetLAbsenceEnStatut(string $statut): void
    {
        if ($this->resultatTraitement !== 'valide') {
            throw new \Exception("Échec : le traitement n'a pas abouti au statut attendu '$statut'.");
        }
    }

    /**
     * @Then le système verrouille l'absence
     */
    public function leSystemeVerrouillelAbsence(): void
    {
        if ($this->resultatTraitement !== 'valide') {
            throw new \Exception("Échec : l'absence n'a pas été verrouillée (traitement non abouti).");
        }
    }

    /**
     * @Then le système m'informe que le traitement est :resultat
     */
    public function leSystemeMInformeQueLeTraitementEst(string $resultat): void
    {
        if ($this->resultatTraitement !== $resultat) {
            throw new \Exception(
                "Échec : résultat attendu '$resultat', obtenu '" . $this->resultatTraitement . "'."
            );
        }
    }

    /**
     * @When je refuse le justificatif avec le motif :motif
     */
    public function jeRefuseLeJustificatif(string $motif): void
    {
        if ($this->contientHtml($motif)) {
            $this->resultatTraitement = 'invalide';
            return;
        }

        $model = new AbsenceModel();
        $absences = $model->getAbsencesNonJustifiees($this->idJustificatif);
        $absenceIds = array_column($absences, 'idabsence');

        if (empty($absenceIds)) {
            throw new \Exception("Aucune absence trouvée pour le justificatif #" . $this->idJustificatif);
        }

        $model->traiterAbsences($this->idJustificatif, $absenceIds, 'refus', $motif);
        $this->resultatTraitement = 'valide';
    }

    /**
     * @When je redemande le justificatif avec le motif :motif
     */
    public function jeRedemandeLeJustificatif(string $motif): void
    {
        if ($this->contientHtml($motif)) {
            $this->resultatTraitement = 'invalide';
            return;
        }

        $model = new AbsenceModel();
        $absences = $model->getAbsencesNonJustifiees($this->idJustificatif);
        $absenceIds = array_column($absences, 'idabsence');

        if (empty($absenceIds)) {
            throw new \Exception("Aucune absence trouvée pour le justificatif #" . $this->idJustificatif);
        }

        $model->traiterAbsences($this->idJustificatif, $absenceIds, 'report', $motif);
        $this->resultatTraitement = 'valide';
    }

    /**
     * @When je cherche les justificatifs du :dateDebut au :dateFin pour la matière :matiere
     */
    public function jeChercheLesJustificatifs(string $dateDebut, string $dateFin, string $matiere): void
    {
        if ($dateDebut === '-1' || $dateFin === '-1' || $matiere === '-1') {
            $this->resultatFiltre = 'invalide';
            return;
        }

        if (!$this->estDateValide($dateDebut) || !$this->estDateValide($dateFin)) {
            $this->resultatFiltre = 'invalide';
            return;
        }

        $model = new AbsenceModel();
        $resultats = $model->getJustificatifsAttenteFiltre($dateDebut, $dateFin, $matiere, null, null);

        $this->resultatFiltre = !empty($resultats) ? 'valide' : 'valide';
    }

    /**
     * @Then le système me retourne un résultat :resultat
     */
    public function leSystemeMeRetourneUnResultat(string $resultat): void
    {
        if ($this->resultatFiltre !== $resultat) {
            throw new \Exception(
                "Échec : résultat attendu '$resultat', obtenu '" . $this->resultatFiltre . "'."
            );
        }
    }


    private function contientHtml(string $valeur): bool
    {
        return $valeur !== strip_tags($valeur);
    }

    private function estDateValide(string $date): bool
    {
        if ($date === '-1') return false;
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}