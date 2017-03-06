<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Mel extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function add($raison, $status) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        $sqlQuery = "INSERT INTO ";

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$pseudo, $password]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Connexion réussie !';

                // S'il n'y a pas de session démarrée
                session_status() == PHP_SESSION_ACTIVE ? "" : session_start();
                $_SESSION['user'] = $bddResult[0];
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