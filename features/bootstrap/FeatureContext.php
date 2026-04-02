<?php

use Behat\Behat\Context\Context;

// On charge les fichiers manuellement
require_once __DIR__ . '/../../Code/Model/ComptesModel.php';
require_once __DIR__ . '/../../Code/Model/NewJustificatif.php';
require_once __DIR__ . '/../../Code/Model/Test.php';

class FeatureContext implements Context
{
    private $email;
    private $idEtudiant;
    private $justificatif;
    private $resultatAbsences;
    private $resultatMail = null;

    public function __construct()
    {
        // Email de test
        $this->email = "stievenardkilian@gmail.com";
    }

    /**
     * @Given je suis un etudiant avec un id
     */
    public function JeSuisEtudiant()
    {
        // On utilise le namespace complet \Model\
        $test = new \Model\Test();
        $compte = $test->connexion($this->email);

        if (!$compte) {
            throw new \Exception("Aucun compte trouvé pour l'email : " . $this->email);
        }

        // On stocke l'ID dans la propriété de la classe pour les étapes suivantes
        $this->idEtudiant = $compte['idutilisateur'];
    }

    /**
     * @When je soumets un justificatif avec tous les champs requis etant remplis invalides :dateD :heureD :dateF :heureF
     */
    public function jeSoumetsLeJustificatif($dateD, $heureD, $dateF, $heureF)
    {
        $this->justificatif = new \Model\NewJustificatif();

        // IMPORTANT : on utilise $this->idEtudiant (stocké à l'étape Given)
        // Vérifie bien que l'ordre des paramètres dans ton modèle est celui-ci !
        $this->resultatAbsences = $this->justificatif->getIdAbsenceParSeance(
            $dateD,
            $heureD,
            $dateF,
            $heureF,
            $this->idEtudiant
        );

        // On n'envoie le mail QUE SI on a trouvé des absences
        if (!empty($this->resultatAbsences)) {
            $testService = new \Model\Test();
            $this->resultatMail = $testService->envoieMailConfirmation($this->email);
        }
    }

    /**
     * @Then le systeme me signale que je n'ai pas d'absence entre ces dates
     */
    public function leSystemeSignalePasDAbsence()
    {
        if (!empty($this->resultatAbsences)) {
            // Utilise \Exception pour être sûr de ne pas dépendre d'un namespace
            throw new \Exception("Échec : Le système a trouvé des absences (" . count($this->resultatAbsences) . ") alors qu'il ne devrait pas.");
        }
    }

    /**
     * @Then le systeme ne m'envoie pas de mail de confirmation
     */
    public function leSystemeNeNotifiePas()
    {
        if ($this->resultatMail !== null) {
            throw new \Exception("Échec : Un mail a été envoyé alors qu'aucune absence n'a été détectée.");
        }
    }

    /**
     * @Then le systeme m'envoie un mail de confirmation de dêpot
     */
    public function leSystemeEnvoieMailSucces()
    {
        if ($this->resultatMail === null) {
            throw new \Exception("Échec : Le système n'a pas tenté d'envoyer de mail (résultat null).");
        }

        if ($this->resultatMail['httpcode'] !== 202) {
            throw new \Exception("Échec : Le mail a été refusé par l'API. Code HTTP reçu : " . $this->resultatMail['httpcode']);
        }
    }
}