<?php

namespace Model;

use PDO;
use PDOException;

ini_set('memory_limit', '512M');
ini_set('max_execution_time', 600);

class DatabaseSingleton
{

    private static $instance; //Instance unique
    private $pdo = null;

    //Les id de connexion
    private $host = "iutinfo-sgbd.uphf.fr";
    private $username = "iutinfo474";
    private $password = "uwkXBERC";
    private function __construct() //En private, car on ne veut qu'une seule instance de l'objet
    {
        try {
            $dsn = "pgsql:host=$this->host;";
            $this->pdo = new PDO($dsn, $this->username, $this->password);

            $this->pdo->setAttribute(PDO::ATTR_PERSISTENT, TRUE);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Erreur de connexion : " . $e->getMessage();
        }
    }

    //Point d'accès global à l'instance unique
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    //Accès à l'objet PDO
    public function getConnection() {
        return $this->pdo;
    }

}