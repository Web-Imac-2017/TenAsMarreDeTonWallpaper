<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Membre extends Model {

    public function __construct() {

    }

    // Retourne le nombre d'apparition du pseudo dans la base
    public function getCountOfPseudo($pseudo) {
        $bdd = Database::get();

        $sqlQuery = "SELECT COUNT(*) from membre WHERE pseudo LIKE ?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$pseudo]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return intval($result[0]["COUNT(*)"]);

    }

    // Enregistre un nouveau membre dans la base
    public function registerMember($pseudo, $password, $mailAdress) {
        $bdd = Database::get();

        $data = "";

        if ($this->getCountOfPseudo($pseudo) != 0) {

            return array("returnCode" => 0, "returnMessage" => "Pseudo existant",  "data" => $data);
        }

        $password = sha1($password);

        try {

            $sqlQuery = "INSERT INTO membre (pseudo, mdp, mail, admin, moderateur) VALUES (?, ?, ?, 0, 0)";

            try {

                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$pseudo, $password, $mailAdress]);
                $lastInsertId = $bdd->lastInsertId();

                $sqlQuery = "SELECT * FROM membre WHERE id = ?";
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$lastInsertId]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);


                $data = $bddResult[0];

                return array("returnCode" => 1, "returnMessage" => "Utilisateur enregistré",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Permet à un utilisateur de se connecter
    public function loginMember($pseudo, $password) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        $password = sha1($password);
        $sqlQuery = "SELECT * FROM membre WHERE pseudo LIKE ? AND mdp = ?";

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$pseudo, $password]);
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

    public function editMember($id, $pseudo, $password, $mailAdress) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            try {
                // Récupération mot de passe
                $sqlQuery = "SELECT mdp FROM membre WHERE id = ?";
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$id]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $password = sha1($password);

                $sqlQuery = "UPDATE membre SET pseudo = ?, mdp = ?, mail = ? WHERE id = ?";
            }
            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => "Modification échouée : " . $e->getMessage(),  "data" => $data);
            }

            // Modification
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$pseudo, $password, $mailAdress, $id]);

            // Récupération infos
            $sqlQuery = "SELECT * FROM membre WHERE id = ?";
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = $bddResult[0];

            // S'il n'y a pas de session démarrée
            session_status() == PHP_SESSION_ACTIVE ? "" : session_start();
            $_SESSION['user'] = $bddResult[0];

            return array("returnCode" => 1, "returnMessage" => "Modificaton effectuée !",  "data" => $data);
        }

        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => "Modification échouée : " . $e->getMessage(),  "data" => $data);
        }

    }

    public function deleteMember($id) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sqlQuery = "DELETE FROM membre WHERE id = ?";
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$id]);		
            $result['returnCode'] = 1;
            $result['returnMessage']  = "Supression effectuée";	
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage']  = "Supression échouée :";	
        }
    }

    // Obtenir les informations sur un membre avec son pseudo
    public function getMemberByPseudo($pseudo) {

    }

    // Obtenir les informations sur un membre avec son id
    public function getMemberById($id) {


    }
    
    // Incrémente la colonne nb_wallpapers_ajoutes
    public function incrementer_nb_wallpapers_ajoutes($id) {
        echo "In!";
        $bdd = Database::get();
        $sqlQuery = "UPDATE membre SET nb_wallpapers_ajoutes=nb_wallpapers_ajoutes+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);		
    }
    
    // Incrémente la colonne nb_questions_ajoutees
    public function incrementer_nb_questions_ajoutees($id) {
        echo "In!";
        $bdd = Database::get();
        $sqlQuery = "UPDATE membre SET nb_questions_ajoutees=nb_questions_ajoutees+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);		
    }

}