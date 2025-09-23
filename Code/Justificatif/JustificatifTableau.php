<?php
enum TYPE {
    case ACCEPTE;
    case REFUSE;
    case DEMANDE;
}

$type = TYPE::DEMANDE;
if (isset($_POST["decision"])) {
    $type = match($_POST["decision"]) {
        "accepte" => TYPE::ACCEPTE,
        "refuse"  => TYPE::REFUSE,
        "demande" => TYPE::DEMANDE,
        default   => TYPE::DEMANDE
    };
}
?>



<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="Justificatif.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des absences - Justificatif</title>

</head>
<body>

<div class="onglets">
    <div class="onglet actif">Tableau de bord</div>
    <div class="onglet">Historique des décisions</div>
    <div class="onglet">...</div>
    <!-- je mettrai après en bien ! -->
    <hr/>
</div>

<div class="container">
    <h1>Absences :</h1>



    <div class="absence-list">
        <h2>Liste des absences</h2>

        <div class="deroulant">
            <details open>
                <summary>
                    <img src="utilisateur.png" alt="avatar" class="image-utilisateur" height="24">
                    <a class="nom">NOM Prénom</a>
                </summary>
                <a class="justificatif-viewer">
                    salut
                </a> <br>
                <a class="decision-finale">
                    Décision finale
                </a> <br>
                <form method="post">
                    <button type="submit" name="decision" value="accepte">
                        <img src="marquer.png" alt="Valider" class="validate-button" height="32">
                    </button>
                    <button type="submit" name="decision" value="refuse">
                        <img src="faux.png" alt="Refuser" class="refuse-button" height="28">
                    </button>
                    <button type="submit" name="decision" value="demande">
                        <img src="interdit.png" alt="Demande" class="ask-button" height="24">
                    </button>
                </form>
                <?php
                echo match($type) {
                    TYPE::ACCEPTE => '<a style="color:green;">✅ Accepté</a>',
                    TYPE::REFUSE  => '<a style="color:red;">❌ Refusé</a>',
                    TYPE::DEMANDE => '<a style="color:orange;">🕐 En attente</a>',
                };
                ?>
                <!-- check quel element afficher en php-->
            </details>
        </div>
    </div>
</div>
</body>
</html>