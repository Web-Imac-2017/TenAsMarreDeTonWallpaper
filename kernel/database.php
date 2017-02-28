<?php

class Database {
    private static $instance = null; // instance
    
    public $base = null; // objet PDO
    
    public function __construct($config) {
        try {
            $this->base = new PDO(
                'mysql:dbname='.$config['dbname'].';host='.$config['url'], $config['user'], $config['pass'],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                     PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        }
        catch (PDOException $e) {
            if(isdebug('db'))
                echo 'Connexion échouée : ' . $e->getMessage();
            else
                echo 'Echec de connexion à la BDD.';
            exit();
        }
    }
    
    public static function get() {
        if(!self::$instance)
            self::$instance = new Database(DbConfig::$config);
        return self::$instance->base;
    }
}