<?php
require_once "../Model/ComptesModel.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $prenom2 = $_POST['prenom2'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];
    $role = $_POST['role'];
    $groupe = $_POST['groupe'];
    $datedenaissance = $_POST['datedenaissance'];
    $diplome = $_POST['diplome'];

    $model = new ComptesModel();
    try {
        $model->addCompte($nom, $prenom, $prenom2, $email, password_hash($motdepasse, PASSWORD_DEFAULT), $role, $groupe, $datedenaissance, $diplome);
        echo 'Le compte a bien été enregistré';
    } catch (Exception $e) {
        echo $e->getMessage();
    }?>
    <form action="../Systeme-de-Gestion-des-Absences-pour-le-BUT-informatique/Vue/creationCompte.php" method="post" enctype="multipart/form-data">>
        <input type="submit" value="OK" name="OK">
    </form>
<?php
} else {
    require '../Vue/menuHorizontal.html';
}
