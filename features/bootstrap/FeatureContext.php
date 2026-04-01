<?php

use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\When;
use Behat\Step\Then;
use Model\Test;
use Model\ComptesModel;
use Model\NewJustificatif;
use PHPUnit\Framework\Assert;

class FeatureContext implements Context {
    public $test;
    public $email;
    public $model;
    public $justificatif;
    public $resultatAbsences;
    private $resultatMail = null; // Initialisé à null pour vérifier l'absence d'envoi
    public function __construct() {
        // Email de test utilisé pour la session
        $this->email = "moimoiTEST@test.fr";
    }

    /**
     * @Given je suis un etudiant avec un id
     */
    public function JeSuisEtudiant() {
        $this->model = new ComptesModel();
        $compte = $this->model->connectCompte($this->email);
        Assert::assertNotEmpty($compte, "Impossible de connecter l'étudiant.");
    }
    /**
     * @When je soumets un justificatif avec tous les champs requis etant remplis invalides :dateD :heureD :dateF :heureF
     */
    public function jeSoumetsLeJustificatif($dateD, $heureD, $dateF, $heureF) {
        $justificatif = new NewJustificatif();
        // On récupère la liste des IDs d'absences
        $this->resultatAbsences = $justificatif->getIdAbsenceParSeance($dateD, $heureD, $dateF, $heureF);

        // LOGIQUE CONDITIONNELLE :
        // On n'envoie le mail QUE SI on a trouvé des absences
        if (!empty($this->resultatAbsences)) {
            $testService = new Test();
            $listeIds = implode(', ', $this->resultatAbsences);
            $this->resultatMail = $testService->envoieMailConfirmation($this->email, $listeIds);
        }
    }

    /**
     * @Then le systeme me signale que je n'ai pas d'absence entre ces dates
     */
    public function leSystemeSignalePasDAbsence() {
        // Succès si la liste est vide
        Assert::assertEmpty($this->resultatAbsences, "Le système a trouvé des absences alors qu'il ne devrait pas.");
    }

    /**
     * @Then le systeme ne m'envoie pas de mail de confirmation
     */
    public function leSystemeNeNotifiePas() {
        // Succès si resultatMail est resté à null
        Assert::assertNull($this->resultatMail, "Un mail a été envoyé alors qu'aucune absence n'a été trouvée.");
    }

    /**
     * @Then le systeme m'envoie un mail de confirmation de dêpot
     */
    public function leSystemeEnvoieMailSucces() {
        // Succès si on a un code 202
        Assert::assertNotNull($this->resultatMail, "Le mail n'a pas été envoyé.");
        Assert::assertEquals(202, $this->resultatMail['httpcode']);
    }
}