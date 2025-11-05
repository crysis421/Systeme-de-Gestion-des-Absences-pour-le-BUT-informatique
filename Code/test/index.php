<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire déroulant aligné avec PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Conteneur principal pour gauche et droite */
        .haut {
            display: flex;
            justify-content: space-between; /* espace entre les deux div */
            flex: 1; /* occupe tout l’espace vertical disponible sauf le bas */
            padding: 20px;
        }

        .gauche, .droite {
            width: 45%;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 8px;
        }

        .gauche {
            background-color: #f0f8ff;
        }

        .droite {
            background-color: #fff0f5;
        }

        /* Div du bas centrée */
        .bas {
            background-color: #f8f9fa;
            text-align: center;
            padding: 15px;
            border-top: 2px solid #ccc;
        }

        /* Style des formulaires */
        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }

        input, button {
            padding: 8px;
            font-size: 1em;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            margin-top: 15px;
            background-color: #e6ffe6;
            padding: 10px;
            border: 1px solid #00aa00;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="haut">
    <div class="gauche">
        <h2>Section gauche</h2>
        <p>Contenu à gauche (par ex. présentation, texte, image...)</p>
    </div>

    <div class="droite">
        <h2>Formulaire PHP</h2>

        <?php
        $afficher_formulaire = isset($_POST['afficher_formulaire']);
        $form_envoye = isset($_POST['envoyer_formulaire']);

        if ($form_envoye) {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            echo "<div class='message'>Merci, <strong>$nom</strong> ! Votre email <strong>$email</strong> a bien été reçu.</div>";
        }
        ?>

        <form method="post" action="">
            <button type="submit" name="afficher_formulaire">
                <?= $afficher_formulaire ? "Masquer le formulaire" : "Afficher le formulaire" ?>
            </button>
        </form>

        <?php if ($afficher_formulaire && !$form_envoye): ?>
            <form method="post" action="">
                <label>Nom :</label>
                <input type="text" name="nom" required>

                <label>Email :</label>
                <input type="email" name="email" required>

                <button type="submit" name="envoyer_formulaire">Envoyer</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="bas">
    <p>&copy; 2025 - Pied de page centré</p>
</div>

</body>
</html>
