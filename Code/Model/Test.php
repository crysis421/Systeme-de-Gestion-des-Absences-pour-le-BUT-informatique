<?php
namespace Model;
use test\send;
require_once __DIR__ . '/../test/send.php';
require_once __DIR__ . "/ComptesModel.php";

class Test {
    public $email;
    public $result;
    public $model;
    public $mailer;

    public function __construct() {
        $this->model = new ComptesModel();
        $this->email = "stievenardkilian@gmail.com";
        $this->mailer = new send();
    }

    public function connexion($email){
        return $this->model->connectCompte($this->email);
    }

    public function envoieMailConfirmation($email) {
        // On construit le contenu ici pour injecter les IDs dynamiquement
        $contenu = "<h1>Confirmation de Dépôt</h1>";
        $sujet = 'Confirmation de depot de justificatif';

        // Appel à la méthode de ton service d'envoi
        $this->result = $this->mailer->envoyerMailSendGrid($email, $sujet, $contenu);
        return $this->result;
    }
}