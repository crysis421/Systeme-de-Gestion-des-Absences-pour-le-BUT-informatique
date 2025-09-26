<?php
session_start();
include "../Model/Database.php";
$connect = new Database();
$conn = $connect->getConnection();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="../CSS/TraitementJustificatifs.css">
    <link rel="stylesheet" href="../CSS/styleMenuHorizontal.css">
</head>
<body>
<a href="https://ent.uphf.fr/uPortal/f/Accueil/normal/render.uP"><img src="https://ent.uphf.fr/uphf/images/ent-logo.svg" alt="Logo de l'IUT"></a>

<ul>
    <a class="pages" href="tableauDeBordRespAbsences.php"><li>Tableau de bord des absences</li></a>
    <a class="pages" href="tableauDeBordRespRetards.html"><li>Tableau de bord des retards</li></a>
    <a class="pages" href="HistoriqueResp.html"><li>Historique</li></a>
    <a class="pages" href="CompteResp.html"><li>Compte</li></a>
</ul>


<div class="absence-list">
    <h2>Liste des absences</h2>

    <div class="deroulant">
        <details open>
            <summary>
                <img src="../Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24"> <a class="nom">NOM Prénom </a><br>
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
            <form method="post" action="tableauDeBordRespAbsences.php">

                <input type="radio" id="toggle1" name="toggle" style="display: none;">
                <label for="toggle1" id="label_accepter"></label>


                <input type="radio" id="toggle2" name="toggle" style="display: none;">
                <label for="toggle2" id="label_refuser"></label>

                <input type="radio" id="toggle3" name="toggle" style="display: none;">
                <label for="toggle3" id="label_demander"></label>

                <br><br>

                <div id="texte1">
                    <label for="raison"></label>
                    Raison :
                    <select name="raison" id="raison" required>
                        <option value="volvo">Choisir parmi les options suivantes</option>
                        <?php
                        $sql1 = "SELECT cause FROM traitementjustificatif";
                        $result1 = $this->conn->query($sql1);

                        if ($result1->num_rows > 0) {
                            while($row = $result1->fetch_assoc()) {
                                echo '<option value="$row">'.$row.'</option>';
                            }
                        }
                        ?>

                        <option value="saab">Saab</option>
                        <option value="opel">Opel</option>
                        <option value="audi">Audi</option>
                    </select>
                </div>

                <div id="texte2">
                    Motif du refus : <br><br>
                    <label>
                        <textarea rows="4" cols="50" required></textarea>
                    </label>
                </div>

                <div id="texte3">
                    Motif de la demande : <br><br>
                    <label>
                        <textarea rows="4" cols="50" required></textarea>
                    </label>
                </div>

                <br><br>
                <input type="submit" name="bouton4" value="Envoyer">
            </form>
        </details>
    </div>
</div>
</body>
</html>