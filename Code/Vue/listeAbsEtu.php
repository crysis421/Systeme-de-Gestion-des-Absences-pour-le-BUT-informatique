<?php
//Ce fichier est là pour le Tableau De Bord de l'étudiant
echo '<link rel="stylesheet" href="../CSS/tableauDeBordResponsable.css">';

if (empty($result)) {
    echo "<p>Aucune absence n’a été enregistrée à votre nom pour la journée </p>";
} else {
    ?>
    <main><br><br></main>
    <form action="formulaireAbsence.php" method="get">
        <input type="submit"
               value="Justifier les absences du <?php echo $_SESSION['jour'] . "/" . date_format($_SESSION['date'], "m/y"); ?>"
               id="jourbutton" name="date">
    </form>

    <?php
    foreach ($result as $absence):
        ?>
        <div class="element" <?php if ($absence['statut'] == "valide") {
            echo 'id=valide';
        } else if ($absence['statut'] == "report") {
            echo 'id=enAttente';
        } else {
            echo 'id=refus';
        } ?>>
            <details>
                <summary class="top-layer">
                    <ol id="maListe">
                        <img src="../Image/profil_default.png" alt="avatar"
                             class="image-utilisateur" height="24">
                        <a class="nom"><b><?= htmlspecialchars($absence['prof']);
                            if ($absence['estretard']) {
                                echo " (Retard)";
                            } ?></a><br>
                        <li class="elementDeListe">
                            <div <?php echo 'class=' . $absence['statut']; ?> id="liste"></div>
                        </li>
                        <li class="elementDeListe">
                            <?php if ($absence['controle']) {
                                ?>
                                <small id="intero">⚠ Interrogation</small>
                                <?php
                            } ?>
                            <div class="description-element">
                                <small><?= htmlspecialchars($absence['enseignement']) ?></small>
                                <br><small><?= htmlspecialchars(date($_SESSION["jour"] . '/ ' . $_SESSION['mois'])) ?>
                                    à <?= htmlspecialchars($absence['heuredebut']) ?></small>
                            </div>
                        </li>
                    </ol>
                    <div class="ligne" id="maLigne"></div>
                </summary>
            </details>
        </div>
    <?php endforeach;
} ?>
<main><br></main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de
        Maubeuge.</a>
</footer>
