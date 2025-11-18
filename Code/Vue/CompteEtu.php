<?php
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}

require_once '../Presentation/lesInfoEtu.php';
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
    <div style="display: flex">
        <h1 style=" width: 90%">Bonjour <?php echo htmlspecialchars($prenom); ?> ! 👋</h1>
        <form style="width: 10%" id="form" action="connexionEtudiant.php" method="post">
            <input type="submit" value="Déconnexion" style="background-color:#bf0000; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px 10px 10px; border-radius: 10px;">
        </form>
    </div>


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

                <details id="modifier">
                    <summary onclick="annuler()" id="modif" style="height: 20px; width: 270px">
                        <b id="annuler">Modifier votre mot de passe</b>
                    </summary>
                    <h1></h1>
                    <form id="form" action="../Presentation/modifierMDPetudiant.php" method="post" style="background-color: #efefef; border: 1px solid #849584; border-radius: 6px; padding: 20px">
                        <label for="Email">
                            Entrer votre adresse mail :* <input type="email" name="email" placeholder="adresse mail" required>
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
                <p><b>Nombre d'absences :</b> .............<?php echo htmlspecialchars($total); ?></p>
                <p><b>Justifiées ✅:</b> .............<?php echo htmlspecialchars($valide); ?></p>
                <p><b>Non justifiées 🚫:</b> .............<?php echo htmlspecialchars($nonjustife); ?></p>
                <p><b>Non validé 🚫:</b> .............<?php echo htmlspecialchars($refus); ?></p>
                <p><b>Autres justificatifs demandés 🔔:</b> .............<?php echo htmlspecialchars($autre); ?></p>
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

<script>
    // Fonction d'affichage/masquage
    function annuler() {
        const element = document.getElementById("annuler");
        if (element.innerHTML === "Modifier votre mot de passe") {
            element.innerHTML = "Annuler la modification "
        } else {
            element.innerHTML = "Modifier votre mot de passe"
        }
    }

</script>


