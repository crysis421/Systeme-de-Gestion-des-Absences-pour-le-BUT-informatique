<?php
echo '
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mot de passe</title>
<style>
  .password-container {
    position: relative;
    width: 300px;
    margin: 20px 0;
  }

  label {
    display: block;
    font-weight: bold;
    color: #b4002b;
    margin-bottom: 5px;
  }

  input[type="password"] {
    width: 100%;
    padding: 10px 40px 10px 10px;
    border: 1px solid #b4002b;
    border-radius: 6px;
    font-size: 16px;
  }

  .eye-icon {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    color: #008cba;
    font-size: 20px;
    user-select: none;
  }
</style>
</head>
<body>

  <label for="password">Mot de passe :</label>
  <div class="password-container">
    <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe">
    <span class="eye-icon">👁️</span>
  </div>

</body>
</html>
';
?>
