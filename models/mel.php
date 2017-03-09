<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Mel extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function add($statut, $membre_id, $moderateur_id) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        $sqlQuery = "INSERT INTO mise_en_ligne VALUES(NULL, NULL, ?, ?, ?)";

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$statut, $membre_id, $moderateur_id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result['data'] = $bddResult[0];
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Connexion réussie !';
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        return $result;
    }

}