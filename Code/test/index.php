<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact</title>
</head>
<body>
<h1>Connecte-toi !</h1>
<form action="traitement.php" method="post">
    <label for="name">Nom :</label>
    <input type="text" name="name" id="name" required>
    <br>

    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <br>

    <label for="message">Message :</label>
    <textarea name="message" id="message" required></textarea>
    <br><br>

    <button type="submit">Envoyer</button>
</form>
</body>
</html>

