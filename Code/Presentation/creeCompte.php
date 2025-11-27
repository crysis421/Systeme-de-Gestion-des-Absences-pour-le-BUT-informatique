<?php
require_once "../Model/ComptesModel.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Récupération sécurisée des champs
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $prenom2 = $_POST['prenom2'] ?? '';
    $email = $_POST['email'] ?? '';
    $motdepasse = $_POST['motdepasse'] ?? '';
    $role = $_POST['role'] ?? '';
    $groupe = $_POST['groupe'] ?? null;
    $datedenaissance = $_POST['datedenaissance'] ?? '';
    $diplome = $_POST['diplome'] ?? null;

    // Liste des rôles sans groupe/diplôme
    $rolesSansChamps = ["prof", "secretaire", "respon"];

    $roleTrouve = false; // drapeau

// Boucle for
    for ($i = 0; $i < count($rolesSansChamps); $i++) {
        if ($role === $rolesSansChamps[$i]) {
            $roleTrouve = true;
        }
    }
// Après la boucle on applique la modification
    if ($roleTrouve) {
        $groupe = null;
        $diplome = null;
    }

    $model = new ComptesModel();

    try {
        $model->addCompte($nom,$prenom,$prenom2,$email,password_hash($motdepasse, PASSWORD_DEFAULT),$role,$groupe,$datedenaissance,$diplome);
        echo 'Le compte a bien été enregistré';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    ?>

    <form action="../Systeme-de-Gestion-des-Absences-pour-le-BUT-informatique/Vue/creationCompte.php" method="post">
        <input type="submit" value="OK" name="OK">
    </form>

<?php
} else {
    require '../Vue/menuHorizontal.html';
}
?>

