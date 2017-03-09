<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Mel extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function add($statut, $membre_id, $moderateur_id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'INSERT INTO mise_en_ligne VALUES(NULL, "", ?, ?, ?)';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$statut, $membre_id, $moderateur_id]);
                $lastInsertId = $bdd->lastInsertId();

                $sqlQuery = "SELECT * FROM mise_en_ligne WHERE id=?";
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$lastInsertId]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult[0];

                return array("returnCode" => 1, "returnMessage" => "Mise en ligne effectuée",  "data" => $data);
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