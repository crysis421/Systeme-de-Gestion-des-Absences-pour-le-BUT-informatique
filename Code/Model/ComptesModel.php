<?php

use Model\Database;

require_once "Database.php";

class ComptesModel
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function __destruct(){
        $this->conn = null;
    }

    public function creationCompte()
    {
        $sql = "INSERT INTO utilisateur VALUES(:nom,:prenom,:prenom2,:email,:motdepasse,:role,:groupe,:datenaissance,:diplome)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nom', $_POST['nom']);
        $stmt->bindParam(':prenom', $_POST['prenom']);
        $stmt->bindParam(':prenom2', $_POST['prenom2']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->bindParam(':motdepasse', $_POST['motdepasse']);
        $stmt->bindParam(':role', $_POST['role']);
        $stmt->bindParam(':groupe', $_POST['groupe']);
        $stmt->bindParam(':datenaissance', $_POST['datenaissance']);
        $stmt->bindParam(':diplome', $_POST['diplome']);
        $stmt->execute();
    }

    public function connexionCompte(){
        $sql = "SELECT email, motdepasse FROM utilisateur WHERE email = :email AND motdepasse = :motdepasse";
        $result = $this->conn = query($sql);

        if ($result->num_rows = 1) {
            if ($result.["role"] == "prof") {
                header("Location:../Vue/CompteResp.php");
            }
            elseif ($result.["role"] == "eleve") {
                header("Location:Eleve.php");
            }
        } else {
            echo "Identifiant ou mot de passe incorrect";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);


        if (!$_POST['name']=="" && !$_POST['pass']=="")
        {
            $name = $_POST['name'];
            $pass = md5($_POST['pass']);

            $connect = pg_connect("host=localhost port=5432 dbname=madbpg user=postgres password=****");

            echo $name;

            $sql= "SELECT rolpassword FROM pg_roles WHERE rolname like '$name';";
            echo $sql."</br>";
            $sql = pg_query($this->conn, $sql);

            $row = pg_fetch_array($sql, 0, PGSQL_NUM);


            $pass_sql= $row[0];
            if ($pass_sql == $pass)
            {if (!$_POST['name']=="" && !$_POST['pass']=="")
        {
            $name = $_POST['name'];
            $pass = md5($_POST['pass']);

            $connect = pg_connect("host=localhost port=5432 dbname=madbpg user=postgres password=****");

            echo $name;

            $sql= "SELECT rolpassword FROM pg_roles WHERE rolname like '$name';";
            echo $sql."</br>";
            if (!$_POST['name']=="" && !$_POST['pass']=="")
        {
            $name = $_POST['name'];
            $pass = md5($_POST['pass']);

            $connect = pg_connect("host=localhost port=5432 dbname=madbpg user=postgres password=****");

            echo $name;

            $sql= "SELECT rolpassword FROM pg_roles WHERE rolname like '$name';";
            echo $sql."</br>";
            $sql = pg_query($this->conn, $sql);

            $row = pg_fetch_array($sql, 0, PGSQL_NUM);


            $pass_sql= $row[0];
            if ($pass_sql == $pass)
            $sql = pg_query($this->conn, $sql);

            $row = pg_fetch_array($sql, 0, PGSQL_NUM);

                $_SESSION['name'] = $name;
                $msg = 'Vous êtes correctement indentifié';
            }
            else {
                $msg = 'Votre nom ou votre mot de passe est incorrect<br />';
                $msg .= '<a href="/Vue/Connexion.php">Retour</a>';}

            pg_close();}
        else {
            $msg = 'Votre nom et/ou votre mot de passe n\'est pas renseigné<br />';
            $msg .= '<a href="/index.php">Retour</a>';}

        //on affiche le resultat
        echo $msg;

    }

    public function modifieCompte(){
        $sql = "UPDATE utilisateur SET motdepasse = :motdepasse WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':motdepasse', $_POST['motdepasse']);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }

    public function supprimeCompte(){
        $sql = "DELETE FROM utilisateur WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $_POST['email']);
        $stmt->execute();
    }
}