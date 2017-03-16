<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Categorie extends Model {

    public function __construct(){
        parent::__construct();
    }

    // Renvoie toutes les catégories
    public function getAll() {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM categorie';

            try {

                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute();
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Renvoie une seule catégorie
    public function get($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM categorie WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute(array($id));
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);


                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Ajoute une catégorie
    public function add($nom) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'INSERT INTO categorie VALUES(NULL, ?)';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $bddResult = $stmt->execute([$nom]);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Catégorie ajoutée",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Supprime une catégorie
    public function delete($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'DELETE FROM categorie WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $bddResult = $stmt->execute([$id]);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Catégorie supprimée",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Modifie une catégorie    
    public function change($id, $nom) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'UPDATE categorie SET nom=? WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $bddResult = $stmt->execute([$nom, $id]);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Catégorie modifiée",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }
    
    public function search($search) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = "SELECT * FROM categorie WHERE nom LIKE CONCAT('%', ? ,'%')";

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$search]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }
     
}



?>