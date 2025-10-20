<?php

require '../Model/AbsenceModel.php';

echo '<link rel="stylesheet" href="../Vue/tableauDeBordResponsable.css">';

if(!isset($_SESSION['jour']) or $_SESSION['jour']=="OK" or $_POST['jour']=="OK"){
    $_SESSION['jour'] = date('d');
}else{
    $_SESSION['jour'] = $_POST['jour'];
}
$user = $_SESSION['user'];


$bdd = new AbsenceModel();
$result = $bdd->getAbsenceDunJour($_SESSION['jour'],$user,$_SESSION['mois']);
if(empty($result)){
    echo "Aucune absence n’a été enregistrée à votre nom pour la journée du ".$_SESSION['jour'].date_format($_SESSION['date'],'  F');
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
                        <br><small><?= htmlspecialchars(date($_SESSION["jour"].'/'.$_SESSION['mois'])) ?> à <?= htmlspecialchars($absence['heuredebut']) ?></small>
                    </div>

                    <div class="ligne" id="maLigne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="../Vue/jolan/assemblage/oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="../Vue/jolan/assemblage/close.png" alt="Fermer le justificatif">
                            </label>

                            <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="../Vue/jolan/assemblage/justificatif.jpg" alt="Justificatif">
                        </details>
                    </div>

                    <form method="post">
                        <a class="decision-finale">Décision finale</a>

                        <input type="radio" id="toggle1_<?= $id ?>" name="toggle" value="accepte" style="display: none;">
                        <label for="toggle1_<?= $id ?>" class="label-accepter"></label>

                        <input type="radio" id="toggle2_<?= $id ?>" name="toggle" value="refuse" style="display: none;">
                        <label for="toggle2_<?= $id ?>" class="label-refuser"></label>

                        <input type="radio" id="toggle3_<?= $id ?>" name="toggle" value="demande" style="display: none;">
                        <label for="toggle3_<?= $id ?>" class="label-demander"></label>

                        <input type="hidden" id="IDElement_<?= $id ?>" value="<?= $id ?>" name="IDElement">

                        <br><br>

                        <div class="texte-accepter">
                            <select name="motifs" id="motif-absence-<?= $id ?>">
                                <option value="">--Choisissez une option--</option>
                                <option value="transport">Transport</option>
                                <option value="malade">Malade</option>
                                <option value="etc">...</option>
                            </select>
                            <br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-refuser">
                            Motif du refus : <br><br>
                            <textarea name="motif_refus" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>

                        <div class="texte-demander">
                            Motif de la demande : <br><br>
                            <textarea name="motif_demande" rows="4" cols="50"></textarea>
                            <br><br>
                            <input class='bouton-envoye' type="submit" name="bouton4" value="Envoyer">
                            <br><br>
                        </div>
                    </form>
                </div>
            </details>
        </div>
    <?php endforeach;
}

