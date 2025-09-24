<?php

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Main.css">
    <title>Tableau de bord absence</title>
</head>
<body>

<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>

<!-- TabBar ici ! -->
<ul>
    <a class="pages" href="tableauDeBordRespAbsences.html"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="tableauDeBordRespRetards.html"><li>Tableau de bord des retards</li></a>
    <a class="pages" href="HistoriqueResp.php"><li>Historique</li></a>
    <a class="pages" href="CompteResp.php"><li>Compte</li></a>
</ul>

<!-- Notification ici ! -->
<div class="notification">
    <h4 class="titre-notification">Titre</h4>
    <a class="description-notification">Notification envoyée !</a>
</div>


<!-- Liste des absences ici ! -->
<h1><u>Absences : </u></h1>

<div class="liste-absence">


    <div class="element">
        <div class="justificatif-viewer">

            <details>
                <summary>
                    <a class="justificatif-texte">Justificatif</a>
                    <img class="oeil" src="oeil.png" alt="Voir le justificatif">
                </summary>
                <details>
                    <summary>
                        <img class="justificatif-image" src="justificatif.jpg" alt="Justicatif">
                        <img class="justificatif-close" src="close.png" alt="Fermer le justificatif">
                    </summary>
                    <div class="fondu-noir"></div>
                    <img class="justificatif-image-big" src="justificatif.jpg" alt="Justicatif">
                </details>

            </details>
        </div>
    </div>


    <details open>
        <summary>
            <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24"> <a class="nom">NOM Prénom </a><br>
            <a class="infos">

                Formation <br>
                Jour de l'absence <br>
                Créneau Horaire</a>
        </summary>
        <a class="justificatif-viewer">
            salut (justificatif ici)
        </a> <br>
        <a class="decision-finale">
            Décision finale :
        </a> <br><br><br>
        <form method="post">

            <input type="radio" id="toggle1" name="toggle" style="display: none;">
            <label for="toggle1" id="label_accepter"></label>


            <input type="radio" id="toggle2" name="toggle" style="display: none;">
            <label for="toggle2" id="label_refuser"></label>

            <input type="radio" id="toggle3" name="toggle" style="display: none;">
            <label for="toggle3" id="label_demander"></label>

            <br><br>

            <div id="texte1">
                Raison : (liste déroulante ici)
            </div>

            <div id="texte2">
                Motif du refus : <br><br>
                <textarea rows="4" cols="50"></textarea>
            </div>

            <div id="texte3">
                Motif de la demande : <br><br>
                <textarea rows="4" cols="50"></textarea>
            </div>


        </form>


        <!-- check quel element afficher en php-->
    </details>
    <br><br>
    <input type="submit" name="bouton4">Envoyer</input>

</div>

</body>
</html>
