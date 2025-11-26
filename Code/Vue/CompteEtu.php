<?php
session_start();
if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}

require_once '../Presentation/lesInfoEtu.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css"/>
    <title>Mon compte</title>
</head>

<body>
<header>
    <?php require 'menuHorizontalEtu.html'; ?>
</header>

<main>
    <div style="display: flex">
        <form style="width: 10%" id="form" action="https://mail.uphf.fr/#1" method="post">
            <input type="submit" value="votre boite mail" style="background-color:#bfff00; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px 10px 10px; border-radius: 10px;margin-left: 10px">
        </form>
        <h1 style=" width: 90%">Bonjour <?php echo htmlspecialchars($prenom); ?> ! 👋</h1>
        <form style="width: 10%" id="form" action="Connexion.php" method="post">
            <input type="submit" value="Déconnexion"
                   style="background-color:#bf0000; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px 10px 10px; border-radius: 10px;">
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
                    <form id="form" action="../Presentation/modifierMDPetudiant.php" method="post"
                          style="background-color: #efefef; border: 1px solid #849584; border-radius: 6px; padding: 20px">
                        <label for="Email">
                            Entrer votre adresse mail :* <input type="email" name="email" placeholder="adresse mail"
                                                                required>
                        </label> <br>
                        <br>
                        <label for="Mot de passe">
                            Entrer votre mot de passe :* <input type="password" name="motDePasse"
                                                                placeholder="mot de passe" required>
                        </label> <br>
                        <a style="font-family: Arial; color: red; font-size: 11px;">Tous les champs marqués avec * sont
                            obligatoires.</a><br>
                        <br>
                        <input type="submit" value="valider"
                               style=" background-color:#007BFF; color: black; border-color: #00aa00; border: 2px; border-style: solid;font-size: 20px; padding: 7px 15px; border-radius: 10px;">
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
                <p><b>Non justifiées 🚫:</b> .............<?php echo htmlspecialchars($refus); ?></p>
                <p><b>En attente de confirmation 🔔:</b> .............<?php echo htmlspecialchars($autre); ?></p>
            </details>
        </div>
        <!-- Section du bas, centrée -->
        <div id="graphe">
            <details id="stat">
                <summary style="background-color: #bce6f6">
                    <h1>Statistiques 📊</h1>
                </summary>
                <section id="camembert">
                    <h4>Vos Absences</h4>
                    <svg width="200" height="200">
                        <circle cx="100" cy="100" r="80" fill="none" stroke="#ddd" stroke-width="5"/>
                        <?php $cx = 100; // centre
                        $cy = 100; // centre
                        $r = 88;   // rayon
                        $startAngle = 0;

                        $color = '00003F';
                        $colorUtilise[] = '';
                        $i = 0;

                        foreach ($graphe as $segment) {
                            $i = $i +1;
                            $value = $segment['count'];
                            $color = dechex((hexdec($color) + hexdec('AEFEAFE')) % hexdec('FFFFFF'));
                            $c = '#'.str_pad($color, 6, '0', STR_PAD_LEFT);
                            array_push($colorUtilise,$c);
                            $angle = $value * 3.599999999999; // 360° * fraction, pas de 3.6 car ça ne forme pas un cercle complet s'il n'y a qu'une seule absence.

                            $endAngle = $startAngle + $angle;
                            $startX = $cx + $r * cos(deg2rad($startAngle));
                            $startY = $cy + $r * sin(deg2rad($startAngle));
                            $endX = $cx + $r * cos(deg2rad($endAngle));
                            $endY = $cy + $r * sin(deg2rad($endAngle));

                            $largeArcFlag = ($angle > 180) ? 1 : 0;

                            echo "<path d='M$cx,$cy L$startX,$startY A$r,$r 0 $largeArcFlag,1 $endX,$endY Z' fill=$c />";

                            $startAngle = $endAngle;
                        } ?>
                    </svg>
                    <ol>
                        <?php foreach ($graphe as $key=>$nom) { ?>
                        <li id="li">
                            <div style="width:5px; height:5px; background-color:<?=$colorUtilise[$key+1]?>; border-radius:50%;"></div>
                            <p><?='~ '.$nom['label']?></p>
                            <p id="pourcentage"><?=round($nom['count']).'%'?></p>
                        </li>
                        <?php } ?>
                    </ol>
                </section>
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


