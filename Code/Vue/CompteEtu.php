<?php
require_once '../Model/AbsenceModel.php';
session_start(); // toujours au début avant d'utiliser $_SESSION

$id = 67038774;
$user = new AbsenceModel();

$nom = $user->getNombyUser($id);
$prenom = $user->getPrenomByUser($id);
$prenom2 = $user->getPrenom2ByUser($id);
$email = $user->getEmailByUser($id);
$role = $user->getroleByUser($id);
$dateNaissance = $user->getnaissanceByUser($id);
$groupe = $user->getgroupeByUser($id);
$mdp = $user->getmotdepasseByUser($id);
$diplome = $user->getdiplomeByUser($id);

// Gestion du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['motDePasse'])) {
    $_SESSION['modif'] = [
            'email' => $_POST['email'],
            'motDePasse' => $_POST['motDePasse']
    ];
    header("Location: ../Presentation/modifierMDPetudiant.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css" />
    <title>Mon compte</title>
</head>

<body>
<header>
    <?php require 'menuHorizontalEtu.html'; ?>
</header>

<main>
    <h1>Bonjour <?php echo htmlspecialchars($prenom); ?> ! 👋</h1>

    <!-- Section du haut : Profil à gauche / Données à droite -->
    <div id="haut">
        <div id="profil">
            <details>
                <summary style="background-color: #bce6f6">

                    <img src="/Image/profil-removebg-preview.png" alt="Photo de profil">
                    <h1>Profil</h1>
                </summary>
                <p><b>Profil :</b> <?php echo htmlspecialchars($role); ?></p>

                <p><b>Nom :</b> <?php echo htmlspecialchars($nom); ?></p>
                <p><b>Prénom :</b> <?php echo htmlspecialchars($prenom); ?></p>
                <p><b>Deuxième Prénom :</b> <?php echo htmlspecialchars($prenom2); ?></p>
                <p><b>Date de Naissance :</b> <?php echo htmlspecialchars($dateNaissance); ?></p>
                <p><b>Numéro d'étudiant :</b> <?php echo htmlspecialchars($id); ?></p>
                <p><b>Email :</b> <?php echo htmlspecialchars($email); ?></p>
                <p><b>Formation :</b> <?php echo htmlspecialchars($diplome); ?> </p>
                <p><b>Groupe :</b> <?php echo htmlspecialchars($groupe); ?></p>
                <b>Mot de passe :</b><p > <?php echo htmlspecialchars($mdp); ?></p>

                <details id="modifier">
                    <summary id="modif" style="height: 20px; width: 270px">
                        <b>Modifier votre mot de passe</b>
                    </summary>
                    <h1></h1>
                    <form id="form" action="" method="post" style="background-color: #efefef; border: 1px solid #849584; border-radius: 6px; padding: 20px">
                        <label for="Email">
                            Entrer votre adresse mail :* <input type="number" name="email" placeholder="adresse mail" required>
                        </label> <br>
                        <br>
                        <label for="Mot de passe">
                            Entrer votre mot de passe :* <input type="password" name="motDePasse" placeholder="mot de passe" required>
                        </label> <br>
                        <a style="font-family: Arial; color: red; font-size: 11px;">Tous les champs marqués avec * sont obligatoires.</a><br>
                        <br>
                        <input   type="submit" value="valider" style=" background-color:#007BFF; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px; border-radius: 10px;">
                    </form>

                </details>
            </details>
        </div>

        <div id="donnees">
            <details>
                <summary style="background-color: #bce6f6">
                    <h1>Mes absences 📅</h1>
                </summary>
                <p><b>Nombre d'absences :</b> 5</p>
                <p><b>Justifiées ✅:</b> 3</p>
                <p><b>Non justifiées 🚫:</b> 2</p>
            </details>
        </div>
    <!-- Section du bas, centrée -->
        <div id="graphe">
            <details>
                <summary style="background-color: #bce6f6">
                    <h1>statistiques   📊</h1>
                </summary>
                <h2>Ajouter des diagrammes</h2>
            </details>
        </div>
    </div>
</main>

<footer id="footer">
    <a href="https://www.uphf.fr/" style="color: black; text-decoration: none;">
        &copy; 2025 Université Polytechnique Haut-de-France / IUT de Maubeuge
    </a>
</footer>
</body>
</html>


