<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Reponse extends Model {

    public function __construct(){
        parent::__construct();
    }
    
    public function add($question_id, $wallpaper_id, $val_min, $val_max) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'INSERT INTO reponse VALUES(NULL, ?, ?, ?, ?)';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$question_id, $wallpaper_id, $val_min, $val_max]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult[0];

                return array("returnCode" => 1, "returnMessage" => "Réponse ajoutée",  "data" => $data);
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