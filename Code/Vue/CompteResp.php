<?php
use Vue\Camembert;
require_once "../Model/AbsenceModel.php";
require_once '../test/send.php';
use test\send;
require('Camembert.php');
session_start();

if (!isset($_SESSION["user"])) {
    header('Location: ../Vue/Connexion.php');
}

if(!isset($_POST['Semestre'])){
    if(!isset($_SESSION['semestre'])){
        $semestre = 1;
    }else{
        $semestre = $_SESSION['semestre'];
    }
}else{
    $_SESSION['semestre']  = $_POST['Semestre'];
    $semestre = $_SESSION['semestre'];
}

if(!isset($_POST['SemestreR'])){
    if(!isset($_SESSION['semestreR'])){
        $semestreR = 1;
    }else{
        $semestreR = $_SESSION['semestreR'];
    }
}else{
    $_SESSION['semestreR']  = $_POST['SemestreR'];
    $semestreR = $_SESSION['semestreR'];
}
require_once("../Presentation/lesInfoResp.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["rappel"])){
    $model = new AbsenceModel();
    $mailer = new send();
    $resultat = $model->getEmailAttendu();
    $res = 0;
//    $email = $resultat;
//    $contenu = "<h1>Notification de rappel concernant vos Justificatifs</h1>
//                <p>Vous avez plusieurs absences non justifiées ou non-validées qui sont en attente de justification.</p>
//                <p>Veuillez-vous contecter à votre de compte de gestion d'absence pour en savoir plus...</p>";
//    $result = $mailer->envoyerMailSendGrid($email,'Rappel justificatif absence',$contenu);
//    if($result){
//        $_SESSION['alerte'] = "tous les rappels ont bien été envoyés!!!!!!!";
//    }else{
//        $_SESSION['alerte'] = "Erreur lors de l'envoi des rappels!!!!!!!!!";
//    }

    if (sizeof($resultat)>0){
        foreach ($resultat as $result) {
            $email = $result['email'];
            $contenu = "<h1>Notification de rappel concernant vos Justificatifs</h1>
                    <p>Vous avez plusieurs absences non justifiées ou non-validées qui sont en attente de justification.</p>
                    <p>Veuillez-vous contecter à votre de compte de gestion d'absence pour en savoir plus...</p>";
            $result = $mailer->envoyerMailSendGrid($email,'Rappel justificatif absence',$contenu);
            if ($result['httpcode'] == 202) {
                $res += 1;
            }else{
                $res += 0;
            }
        }
        if($res==sizeof($resultat)){
            $_SESSION['alerte'] = "tous les rappels ont bien été envoyés!!!!!!!";
        }else{
            $_SESSION['alerte'] = "Erreur lors de l'envoi des rappels!!!!!!!!!";
        }
    }else{
        $_SESSION['alerte'] = "Aucun justificatif n'est attendu!!!!!!!!!";
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/compte.css"/>
    <title>Gestionnaire d'absence</title>
</head>
<body>

<?php require('menuHorizontalResp.html'); ?>
<main>
    <h1>Compte</h1>

    <div id="graphe">
        <details id="stat">
            <summary style="background-color: #bce6f6">
                <h1>Statistiques 📊</h1>
            </summary>
            <ol class="graphe">
                <li class="lesGraphes">
                    <?php
                    if(array_key_last($nbFoisAnnee) == 0){
                        echo "Pas d'absence cette année";
                    }else{Camembert::afficher($grapheAnnee,$nbFoisAnnee,"Absence de cette année");} ?>
                </li>
                <li class="lesGraphes">
                <form action="CompteResp.php" method="post">
                    <select name="Semestre">
                        <option value="1" <?php if($semestre==1){echo 'selected';} ?>>Semestre 1</option>
                        <option value="2" <?php if($semestre==2){echo 'selected';} ?>>Semestre 2</option>
                        <option value="3" <?php if($semestre==3){echo 'selected';} ?>>Semestre 3</option>
                        <option value="4" <?php if($semestre==4){echo 'selected';} ?>>Semestre 4</option>
                        <option value="5" <?php if($semestre==5){echo 'selected';} ?>>Semestre 5</option>
                        <option value="6" <?php if($semestre==6){echo 'selected';} ?>>Semestre 6</option>
                    </select>
                    <br>
                    <input type="submit" name="bouton4" value="Envoyer">
                </form>
                    <?php
                    if(array_key_last($nbFoisSemestre) == 0){
                        echo "Pas d'absence ce semestre";
                    }else{
                        Camembert::afficher($grapheSemestre,$nbFoisSemestre,"Absence de ce semestre");} ?>
                </li>
                <li class="lesGraphes">
                    <form action="CompteResp.php" method="post">
                        <select name="SemestreR">
                            <option value="1" <?php if($semestreR==1){echo 'selected';} ?>>Semestre 1</option>
                            <option value="2" <?php if($semestreR==2){echo 'selected';} ?>>Semestre 2</option>
                            <option value="3" <?php if($semestreR==3){echo 'selected';} ?>>Semestre 3</option>
                            <option value="4" <?php if($semestreR==4){echo 'selected';} ?>>Semestre 4</option>
                            <option value="5" <?php if($semestreR==5){echo 'selected';} ?>>Semestre 5</option>
                            <option value="6" <?php if($semestreR==6){echo 'selected';} ?>>Semestre 6</option>
                        </select>
                        <br>
                        <input type="submit" name="bouton4" value="Envoyer">
                    </form>
                    <?php
                    if(array_key_last($nbFoisSemestreR) == 0){
                        echo "Pas d'absence ce semestre";
                    }else{ Camembert::afficher($grapheSemestreR,$nbFoisSemestreR,"Absence de ce semestre");} ?>
                </li>
            </ol>

        </details>
    </div>
    <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:30px;">
        <form style="width: 20%" action="Connexion.php" name="Deconnexion" ">
            <input type="submit" id="deconnexion" value="Déconnexion">
        </form>

        <div style="width: 60%" class="alertes" id="alertes">
            <br><br>
            <?php foreach ($alerteM as $f):
                echo $f;
            endforeach; ?>
            <br/>
            <?php foreach ($alerteC as $f):
                echo $f;
            endforeach; ?>
            <br/>
        </div>
        <div style="width:20%; text-align:center;">
            <form style="width: 100%" action="" method="post" name="Rappel" id="rappel">
                <input type="submit" name="rappel" id="Rappeljustification" value="Rappel justification">
                <div id="messageHover">
                    Envoie un rappel pour demander une justification
                </div>
                <?php
                if (isset($_SESSION['alerte'])) {
                    ?>
                    <p style="color:#5c1e1e; font-weight:bold; margin-top:15px;">
                        <?= htmlspecialchars($_SESSION['alerte']) ?>
                    </p>

                    <form style="width:100%" action="CompteResp.php" method="post" >
                        <input type="submit" value="OK" style="width:20%;background-color: gray;color: #3a2323; font-size: 80%;cursor:pointer;">
                    </form>
                    <?php
                    unset($_SESSION['alerte']);
                }
                ?>

            </form>
        </div>
    </div>
    <script>
        const bouton = document.getElementById("Rappeljustification");
        const message = document.getElementById("messageHover");

        bouton.addEventListener("mouseenter", () => {
            message.style.opacity = "1";
            message.style.transform = "translateY(0)";
        });

        bouton.addEventListener("mouseleave", () => {
            message.style.opacity = "0";
            message.style.transform = "translateY(5px)";
        });
        bouton.addEventListener("mousemove", (e) => {
            message.style.left = e.pageX + 12 + "px";
            message.style.top  = e.pageY + 12 + "px";
        });
    </script>

</main>
</body>
</html>