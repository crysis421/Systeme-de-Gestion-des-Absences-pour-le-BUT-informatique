<?php
session_start();
if(!isset($_SESSION["user"])){
    header('Location: ../Vue/Connexion.php');
}
require_once "../Presentation/responTB.php"
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/tableauDeBordResponsable.css">
    <title>Tableau de bord absence</title>
</head>
<body>

<!-- TabBar ici ! -->
<?php require('menuHorizontalResp.html'); ?>

<!-- Notification ici ! -->
<?php
if ($titre != "" && $description != "") {
    echo <<<EOL
<div class="notification">
    <h4 class="titre-notification">$titre</h4>
    <a class="description-notification">Avec le motif "$description"</a>
</div>
EOL;
}
?>

<!-- Titre -->
<h1><u>Liste des absences à traiter : <?php $nbtotal = new absenceModel();
                                            $nbtotal->getNombreAJustifier(); ?> </u></h1>

<!-- Filtrage ici ! -->
<details id="details">
    <summary class="filtrer">
        <img src="/Image/filter.png" alt="Filtre" class="Filtre" height="24">
        <a class="filtre-titre"><b>Filtrer</b></a><br>
    </summary>

    <div class="filtrage">
        <form method="post" id="formFiltre">

            <div class="dateFiltre">
                <?php
                $dateDebut = '2020-01-01';
                $dateFin = date("Y-m-d");
                ?>

                <h3>Date de début</h3>
                <input type="date" id="startDate" name="dateDebut" value="<?= $dateDebut ?>">
                <h3>Date de fin</h3>
                <input type="date" id="endDate" name="dateFin" value="<?= $dateFin ?>">
            </div>

            <div class="matiereFiltre">
                <h3>Nom de la matière</h3>
                <input type="text" id="inputMatiere" name="Matière" value="">
            </div>

            <div class="eleveFiltre">
                <h3>Prénom</h3>
                <input type="text" id="inputPrenom" name="PrenomInput" value="">
                <h3>Nom</h3>
                <input type="text" id="inputNom" name="NomInput" value="">
            </div>


            <input class='bouton-filtrage' type="submit" name="boutonFiltre" value="Filtrer">

        </form>

        <br>
    </div>
</details>


<!-- Liste des absences ici ! -->
<div class="liste-absence">
    <?php foreach ($groupes as $justif):
        $id = $justif['id'];
        $commentaire = $justif['commentaire'];
        $absences = $justif['absences'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['description']) ?></small><br>
                        <small>Soumis le <?= htmlspecialchars($justif['datesoumission']) ?></small>
                    </div>

                    <div class="ligne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="/Image/oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="/Image/close.png" alt="Fermer le justificatif">
                            </label>

                            <br><a><b>Commentaire :</b><br> <?php echo $commentaire ?></a> <br>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                        </details>
                    </div>
                    <form method="post">

                        <input type="hidden" name="IDElement" value="<?= $justif['id'] ?>" >

                        <?php foreach ($absences as $abs):

                            $matiere = $abs['matiere'];
                            preg_match('#\((.*?)\)#', $matiere, $match);
                            $matiere = $match[1];
                            $matiere = explode("-", $matiere)[1];

                            $date = $abs['date'];
                            $heure = $abs['heure'];
                            $idAbsence = $abs['id'];
                            $statusAbsence = $abs['status'];
                            if($statusAbsence != 'report') continue;
                            ?>
                            <input type="checkbox" name="checkboxAbsence[]" value="<?= $abs['id'] ?>" id="checkboxAbsence_<?= $abs['id'] ?>" checked>
                            <label for="checkboxAbsence_<?= $abs['id'] ?>"><?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars(rtrim(substr($abs['heure'],0,5),')'))?> <?= htmlspecialchars($matiere)?></label> <br>
                        <?php endforeach; ?>

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
                        <div class="ligne2"></div>
                    </form>

                </div>

            </details>
        </div>
    <?php endforeach; ?>
</div>
<br><br>


<h1><u>Justificatifs redemandés :</u></h1>
<!-- Liste des absences ici ! -->
<div class="liste-absence-demandes">
    <?php foreach ($groupesDemandes as $justif):
        $id = $justif['id'];
        $commentaire = $justif['commentaire'];
        $absences = $justif['absences'];
        ?>
        <div class="element">
            <details>
                <summary class="top-layer">
                    <img src="/Image/profil_default.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom"><b><?= htmlspecialchars($justif['nom']) ?> <?= htmlspecialchars($justif['prenom']) ?></b></a><br>
                    <div class="description-element">
                        <small><?= htmlspecialchars($justif['description']) ?></small>
                    </div>

                    <div class="ligne"></div>
                </summary>

                <div class="details">
                    <div class="justificatif-viewer">
                        <details>
                            <summary>
                                <a class="justificatif-texte">Justificatif</a>
                                <img class="oeil" src="/Image/oeil.png" alt="Voir le justificatif">
                            </summary>

                            <input type="checkbox" id="zoom<?= $id ?>" name="zoom" style="display: none;">
                            <label for="zoom<?= $id ?>" class="zoom-button"></label>

                            <label for="zoom<?= $id ?>" class="justificatif-close">
                                <img src="/Image/close.png" alt="Fermer le justificatif">
                            </label>

                            <br>

                            <div class="fondu-noir"></div>
                            <img class="justificatif-image-big" src="/Image/justificatif.jpg" alt="Justificatif">
                        </details>
                        <?php foreach ($absences as $abs):

                            $matiere = $abs['matiere'];
                            preg_match('#\((.*?)\)#', $matiere, $match);
                            $matiere = $match[1];
                            $matiere = explode("-", $matiere)[1];

                            $date = $abs['date'];
                            $heure = $abs['heure'];
                            $idAbsence = $abs['id'];
                            ?>
                            <a> - <?= htmlspecialchars($abs['date'])?> <?= htmlspecialchars(rtrim(substr($abs['heure'],0,5),')'))?> <?= htmlspecialchars($matiere)?></a> <br>
                        <?php endforeach; ?>
                    </div>
                    <div class="ligne2"></div>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</div>


</body>
</html>