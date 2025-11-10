<?php


//require_once "../Model/ComptesModel.php";
//$model = new ComptesModel();

//$compte = $model->addCompte();


//$nom = $prenom = $prenom2 = $email = $motdepasse = $role = $groupe = $datedenaissance = $diplome = "";

//if ($_SERVER["REQUEST_METHOD"] == "POST") {
  //  $nom = $_POST['nom'];
//    $prenom = $_POST['prenom'];
//    $prenom2= $_POST['prenom2'];
//    $email = $_POST['email'];
//    $motdepasse = $_POST['motdepasse'];
//    $role = $_POST['role'];
//    $groupe = $_POST['groupe'];
//    $datedenaissance = $_POST['datedenaissance'];
//    $diplome = $_POST['diplome'];

    //session_start();
    //$_SESSION['formData'] = [
    //    'nom' => $nom,
    //    'prenom' => $prenom,
    //    'prenom2' => $prenom2,
    //    'email' => $email,
    //    'motdepasse' => $motdepasse,
    //    'role' => $role,
    //    'groupe' => $groupe,
    //    'datedenaissance' => $datedenaissance,
    //    'diplome' => $diplome,
    //];
    //header("Location: recapitulatifJustificatifAbsence.php");
    //exit();
//}

?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="../CSS/connect.css">

<body>
<header>
    <?php require_once("menuHorizontal.html"); ?>
</header>
<main>
    <h1>Création de compte</h1>
    <div id="container">

        <form id="form" action="" method="post">
            <label for="Nom">
                Entrer votre nom :* <input type="text" name="nom" placeholder="nom" required>
            </label> <br>
            <br>
            <label for="Prenom">
                Entrer votre prénom :* <input type="text" name="prenom" placeholder="prénom" required>
            </label> <br>
            <br>
            <label for="Prenom2">
                Entrer votre si vous en avez votre deuxième prénom : <input type="text" name="prenom2" placeholder="deuxième prénom">
            </label> <br>
            <br>
            <label for="Role">
                Entrer votre role dans l'université :* <input type="text" name="role" placeholder="role" required>
            </label> <br>
            <br>
            <label for="Groupe">
                Entrer votre groupe : <input type="text" name="groupe" placeholder="groupe">
            </label> <br>
            <br>
            <label for="Naissance">
                Entrer votre date de naissance : <input type="text" name="datedenaissance" placeholder="date de naissance">
            </label> <br>
            <br>
            <label for="Diplome">
                Entrer votre diplome : <input type="text" name="diplome" placeholder="diplome">
            </label> <br>
            <br>
            <label for="Email">
                Entrer votre adresse mail :* <input type="email" name="email" placeholder="adresse mail" required>
            </label> <br>
            <br>
            <label for="Mot de passe">
                Entrer votre mot de passe :* <input type="password" name="motdepasse" placeholder="mot de passe" required>
            </label> <br>
            <a style="font-family: Arial; color: yellow; font-size: 11px;">Tous les champs marqués avec * sont obligatoires</a><br>
            <br>
            <input type="submit" value="Créez votre compte">
        </form>
    </div>
</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de Maubeuge.</a>
</footer>
</body>
</html>