<?php
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}
require_once '../Model/AbsenceModel.php';


$model = new AbsenceModel();


require_once '../Presentation/lesInfoEtu.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="../CSS/compte.css">
    <title>compte secretaire</title>
</head>


<body>
<header>
    <?php require 'menuHorizontaleSecretaire.html'; ?>
</header>
<div style="display: flex; gap: 50%">
    <h1 style="margin-left: 1%">Compte secretaire</h1>
    <form style="margin-top: 1%" action="Connexion.php">
        <input type="submit" id="deconnexion" value="Deconnection">
    </form>
</div>


<div style="display: flex; margin-left: 10%;margin-right: 10%">
    <div id="profil" style="width: 100%">
        <details>
            <summary style="background-color: #bce6f6">


                <img src="/Image/profil-removebg-preview.png" alt="Photo de profil">
                <h1>Profil</h1>
            </summary>
            <div style="display: flex; gap: 50%">
                <div>
                    <p><b>Profil :</b> <?php echo htmlspecialchars($role); ?></p>
                    <p><b>Date de Naissance :</b> <?php echo htmlspecialchars($dateNaissance); ?></p>
                    <p><b>Email :</b> <?php echo htmlspecialchars($email); ?></p>
                </div>
                <div>
                    <p><b>Nom :</b> <?php echo htmlspecialchars($nom); ?></p>
                    <p><b>Prénom :</b> <?php echo htmlspecialchars($prenom); ?></p>
                    <p><b>Deuxième Prénom :</b> <?php echo htmlspecialchars($prenom2); ?></p>
                </div>
            </div>
            <details >
                <summary onclick="annuler()" id="modif" style="height: 20px; width: auto; text-align: center">
                    <b id="annule">Modifier votre mot de passe</b>
                </summary>
                <h1></h1>
                <form action="../Presentation/modifierMDPetudiant.php" method="post" >
                    <input type="hidden" name="formulaire" value="formulaire3">
                    <?php
                    if (isset($_SESSION['erreur'])) {
                        echo '<p style="color:red; font-weight:bold;">' . htmlspecialchars($_SESSION['erreur']) . '</p>';
                        unset($_SESSION['erreur']);
                    }
                    ?>


                    <label for="Email">
                        Entrer votre adresse mail :* <input type="email" name="email3" placeholder="adresse mail"
                                                            required>
                    </label> <br>
                    <br>
                    <label for="Mot de passe">
                        Entrer votre mot de passe :* <input type="password" name="motDePasse3"
                                                            placeholder="mot de passe" required>
                    </label> <br>
                    <br>
                    <label for="confirmation">
                        Confirmer votre nouveau mot de passe :* <input type="password" name="confirmationMotDePasse3" placeholder="Confirmer votre mot de passe" required onpaste="return false" ><!-- on peut ajouter oncopy="return false" oncut="return false" pour bloquer la copie et la coupure -->
                    </label> <br>
                    <a style="font-family: Arial; color: red; font-size: 11px;">Tous les champs marqués avec * sont
                        obligatoires.</a><br>
                    <br>
                    <input style="justify-content: center" id="valider" type="submit" value="valider">
                </form>
            </details>
        </details>
    </div>
</div>


</body>
</html>
<script>
    // Fonction d'affichage/masquage
    function annuler() {
        const element = document.getElementById("annule");
        if (element.innerHTML === "Modifier votre mot de passe") {
            element.innerHTML = "Annuler la modification "
        } else {
            element.innerHTML = "Modifier votre mot de passe"
        }
    }

</script>