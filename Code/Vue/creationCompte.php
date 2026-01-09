<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="../CSS/connect.css">

<body>
<header>
    <?php require_once("menuHorizontaleSecretaire.html"); ?>
</header>
<main>
    <h1>Création de compte</h1>
    <div id="container">

        <form id="form" action="/Presentation/creeDesComptes.php" method="post" enctype="multipart/form-data">
             <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv" required >
             <br>
            <input type="submit" value="Télécharger le CSV" name="submit" >
        </form>
        <br>
        <form id="form" action="/Presentation/creeCompte.php" method="post">
            <a style="font-family: Arial; color: yellow; font-size: 21px;">
                Tous les champs marqués avec * sont obligatoires.
            </a><br>

            <label for="Nom">
                Nom :* <input type="text" name="nom" placeholder="nom" required>
            </label> <br><br>

            <label for="Prenom">
                Prénom :* <input type="text" name="prenom" placeholder="prénom" required>
            </label> <br><br>

            <label for="Prenom2">
                Deuxième prénom : <input type="text" name="prenom2" placeholder="deuxième prénom">
            </label> <br>

            <label for="Naissance">
                Date de naissance :* <input type="date" name="datedenaissance" required>
            </label> <br><br>

            <label for="role">Rôle dans l'université :*</label><br>
            <select name="role" id="role" onchange="toggleFields()" required>
                <option value="" disabled selected>-- Choisir un rôle --</option>
                <option value="eleve">Élève</option>
                <option value="prof">Professeur</option>
                <option value="secretaire">Secrétaire</option>
                <option value="respon">Responsable pédagogique</option>
            </select>
            <br><br>

            <div id="groupeField">
                <label>Groupe :
                    <input type="text" name="groupe" placeholder="groupe">
                </label>
            </div>
            <br>

            <div id="diplomeField">
                <label>Diplôme :
                    <input type="text" name="diplome" placeholder="diplôme">
                </label>
            </div>
            <br>

            <label for="Email">
                Adresse mail :* <input type="email" name="email" placeholder="adresse mail" required>
            </label> <br><br>

            <label for="Mot de passe">
                Mot de passe :* <input type="password" name="motdepasse" placeholder="mot de passe" required>
            </label> <br><br>

            <input type="submit" value="Créer le compte">
        </form>
    </div>

    <script>
        function toggleFields() {
            const role = document.getElementById("role").value;
            const groupeField = document.getElementById("groupeField");
            const diplomeField = document.getElementById("diplomeField");

            // Rôles pour lesquels on cache les champs
            const rolesSansChamps = ["prof", "secretaire", "respon"];

            if (rolesSansChamps.includes(role)) {
                groupeField.style.display = "none";
                diplomeField.style.display = "none";
            } else {
                groupeField.style.display = "block";
                diplomeField.style.display = "block";
            }
        }
    </script>

</main>

<footer id="footer">
    <a style="color: black" href="https://www.uphf.fr/">&copy; 2025 Université Polytechnique Hauts-de-France / IUT de Maubeuge.</a>
</footer>
</body>
</html>
