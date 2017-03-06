<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Mel extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function add($raison, $status, $membre_id) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        $sqlQuery = "INSERT INTO mise_en_ligne VALUES(NULL, ?, ?, ?, NULL)";

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$raison, $status, $membre_id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Connexion réussie !';
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la connexion : pseudo ou mot de passe incorrect !';
            }
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        return $result;
    }

}