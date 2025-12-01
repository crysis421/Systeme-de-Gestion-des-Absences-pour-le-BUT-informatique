<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire de contact</title>
</head>
<body>
<h2>Contactez-nous</h2>
<form action="send.php" method="POST">
    <label>Votre nom :</label><br>
    <input type="text" name="name" required><br><br>

    <label>Votre email :</label><br>
    <input type="email" name="email" required><br><br>

    <label>Message :</label><br>
    <textarea name="message" required></textarea><br><br>

    <button type="submit">Envoyer</button>
</form>
</body>
</html>
