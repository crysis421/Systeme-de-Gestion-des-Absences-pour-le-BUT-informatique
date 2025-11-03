<?php

echo '<link rel="stylesheet" href="tableauDeBordResponsable.css">';

if(empty($result)){
    echo "<p>Aucune absence n’a été enregistrée à votre nom pour la journée </p>";
}else{
    foreach ($result as $absence):
        $id = 1;
        $commentaire= 'salut';
        ?>
        <div class="element" <?php if ($absence['statut']=="valide"){echo 'id=valide';}else if($absence['statut']=="report"){echo 'id=enAttente';}else{echo 'id=refus';} ?>>
            <details>
                <summary class="top-layer">
                    <img src="../Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($absence['prof'])?></a><br>

                    <div class="description-element">
                        <small><?= htmlspecialchars($absence['enseignement']) ?></small>
                        <br><small><?= htmlspecialchars(date($_SESSION["jour"].'/ '.$_SESSION['mois'])) ?> à <?= htmlspecialchars($absence['heuredebut']) ?></small>
                    </div>
                    <?php if ($absence['controle']){
                        ?>
                        <small id="intero">⚠ Interrogation</small>
                        <?php
                    }?>

                    <div class="ligne" id="maLigne"></div>
                </summary>
            </details>
        </div>
    <?php endforeach;
}