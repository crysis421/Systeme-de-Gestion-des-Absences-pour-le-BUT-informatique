<?php
session_start(); // Démarrer la session
require_once '../Model/AbsenceModel.php';

// Vérifier quel formulaire a été soumis
if (!isset($_POST['formulaire'])) {
    $_SESSION['erreur'] = "Formulaire non identifié.";
    header("Location: ../Vue/modifierMDP.php");
    exit();
}

switch ($_POST['formulaire']) {

    case 'formulaire1': // Formulaire sur modifierMDP.php
        if (!isset($_POST['email1'], $_POST['motDePasse'], $_POST['confirmationMotDePasse'])) {
            $_SESSION['erreur'] = "Données manquantes.";
            header("Location: ../Vue/modifierMDP.php");
            exit();
        }

        $email = $_POST['email1'];
        $motDePasse = $_POST['motDePasse'];
        $confirmation = $_POST['confirmationMotDePasse'];

        if ($motDePasse !== $confirmation) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
            header("Location: ../Vue/modifierMDP.php");
            exit();
        }

        // Modifier le mot de passe dans la base (en clair)
        $a = new AbsenceModel();
        $a->ModifierMDP($email, $confirmation);

        header('Location: ../Vue/Connexion.php');
        exit();

    case 'formulaire2': // Formulaire sur CompteEtu.php
        if (!isset($_POST['email1'], $_POST['motDePasse1'], $_POST['confirmationMotDePasse1'])) {
            $_SESSION['erreur'] = "Données manquantes.";
            header("Location: ../Vue/CompteEtu.php");
            exit();
        }

        $email = $_POST['email1'];
        $motDePasse = $_POST['motDePasse1'];
        $confirmation = $_POST['confirmationMotDePasse1'];

        if ($motDePasse !== $confirmation) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
            header("Location: ../Vue/CompteEtu.php");
            exit();
        }

        // Modifier le mot de passe dans la base (en clair)
        $a = new AbsenceModel();
        $a->ModifierMDP($email, $confirmation);

        header('Location: ../Vue/Connexion.php');
        exit();

    case 'formulaire3': // Formulaire sur modifierMDP.php
        if (!isset($_POST['email3'], $_POST['motDePasse3'], $_POST['confirmationMotDePasse3'])) {
            $_SESSION['erreur'] = "Données manquantes.";
            header("Location: ../Vue/compteSecretaire.php");
            exit();
        }

        $email = $_POST['email3'];
        $motDePasse = $_POST['motDePasse3'];
        $confirmation = $_POST['confirmationMotDePasse3'];

        if ($motDePasse !== $confirmation) {
            $_SESSION['erreur'] = "Les mots de passe ne correspondent pas.";
            header("Location: ../Vue/compteSecretaire.php");
            exit();
        }

        // Modifier le mot de passe dans la base (en clair)
        $a = new AbsenceModel();
        $a->ModifierMDP($email, $confirmation);

        header('Location: ../Vue/Connexion.php');
        exit();
}
?>


