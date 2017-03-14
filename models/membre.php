<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Membre extends Model {

    public function __construct() {

    }

    // Renvoie tous les membres
    public function getAll() {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM membre';

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

            return array("returnCode" => 0, "returnMessage" => "Pseudo déjà existant",  "data" => $data);
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        try {

            $sqlQuery = "INSERT INTO membre VALUES (NULL, ?, ?, 0, 0, ?, '', 0, 0)";

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

        $sqlQuery = "SELECT * FROM membre WHERE pseudo LIKE ?";

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$pseudo]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($bddResult)) {

                $verification = password_verify($password, $bddResult[0]['mdp']);

                if ($verification) {

                    $result['data'] = $bddResult[0];
                    $result['returnCode'] = 1;
                    $result['returnMessage'] = 'Connexion réussie !';

                    // S'il n'y a pas de session démarrée
                    session_status() == PHP_SESSION_ACTIVE ? "" : session_start();
                    $_SESSION['user'] = $bddResult[0];

                }
                else {
                    $result['returnCode'] = 0;
                    $result['returnMessage'] = 'Echec de la connexion : mot de passe incorrect !';

                }
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la connexion : pseudo inexistant !';
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

    // Supprime un membre
    public function delete($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'DELETE FROM membre WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $bddResult = $stmt->execute([$id]);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Membre supprimé",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Obtenir les informations sur un membre avec son pseudo
    public function getMemberByPseudo($pseudo) {

    }

    // Obtenir les informations sur un membre avec son id
    public function get($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM membre WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$id]);
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

    // Incrémente la colonne nb_wallpapers_ajoutes
    public function incrementer_nb_wallpapers_ajoutes($id) {
        $bdd = Database::get();
        $sqlQuery = "UPDATE membre SET nb_wallpapers_ajoutes=nb_wallpapers_ajoutes+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);		
    }

    // Incrémente la colonne nb_questions_ajoutees
    public function incrementer_nb_questions_ajoutees($id) {
        $bdd = Database::get();
        $sqlQuery = "UPDATE membre SET nb_questions_ajoutees=nb_questions_ajoutees+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);	
    }

}