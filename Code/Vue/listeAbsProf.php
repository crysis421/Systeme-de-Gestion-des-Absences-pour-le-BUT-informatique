<?php
//Ce fichier est là pour le Tableau De Bord du prof
echo '<link rel="stylesheet" href="../CSS/tableauDeBordResponsable.css">';

if (empty($resultatJour)) {
    echo "<p>Aucun étudiant n’a besoin de rattraper une évaluation.</p>";
} else {
    $lesCours[] = ' '
    ?>
    <main><br><br></main>
    <?php
    foreach ($resultatJour as $cours):
        if(!in_array($cours['enseignement'].$cours['heuredebut'],$lesCours)){
            array_push($lesCours,$cours['enseignement'].$cours['heuredebut']);
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <ol id="maListe">
                        <a class="nom"><b><?= htmlspecialchars($cours['enseignement']) ?> à <?= htmlspecialchars($cours['heuredebut']) ?></a><br>
                        <li class="elementDeListe">
                            <div <?php echo 'class=' . $cours['statut']; ?> id="liste"></div>
                        </li>
                        <li class="elementDeListe">
                            <div class="description-element">
                                <?php foreach ($resultatJour as $jour){ ?>
                                    <?php if($jour['enseignement'].$jour['heuredebut']==$cours['enseignement'].$cours['heuredebut']){ ?>
                                <small><?=htmlspecialchars($jour['email'])?> </small>
                                <br>
                                <?php }} ?>
                            </div>
                            <div class="ligne" id="maLigne"></div>
                        </li>
                    </ol>
                </summary>
            </details>
        </div>
    <?php }endforeach;
} ?>
<main><br></main>
<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université polytechnique Haut de France/ IUT de
        Maubeuge.</a>
</footer>
