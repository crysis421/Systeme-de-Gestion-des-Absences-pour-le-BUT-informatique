<?php
namespace Model;
use PDO;
use PDOException;
///on alloue un certain nombre de RAM pour ne pas surcharger la BDD, ainsi qu'un temps limite
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 600);

///connection classique
class Database
{
    private $host = "iutinfo-sgbd.uphf.fr";
    private $username = "iutinfo474";
    private $password = "uwkXBERC";
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $dsn = "pgsql:host={$this->host};";
            $this->conn = new PDO($dsn, $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
        return $this->conn;
    }


}

