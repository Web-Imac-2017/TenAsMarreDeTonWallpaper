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

			return array("returnCode" => 1, "returnMessage" => "utilisateur enregistré",  "data" => $data);
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

	public function editMember($id, $pseudo, $password, $mailAdress, $admin, $moderateur) {
		$bdd = Database::get();

		$result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

		$password = sha1($password);
		$sqlQuery = "UPDATE membre SET pseudo = ?, mdp = ?, mail = ?, admin = ?, moderateur = ? WHERE id = ?";

		try {

			$stmt = $bdd->prepare($sqlQuery);
			$success = $stmt->execute([$pseudo, $password, $mailAdress, $admin, $moderateur, $id]);

			$sqlQuery = "SELECT * FROM membre WHERE id = ?";
			$stmt = $bdd->prepare($sqlQuery);
			$stmt->execute([$id]);
			$bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

			$data = $bddResult[0];

			return array("returnCode" => 1, "returnMessage" => "Modificaton effectuée !",  "data" => $data);
		}

		catch (PDOException $e) {
			return array("returnCode" => -1, "returnMessage" => "Modification échouée : " . $e->getMessage(),  "data" => $data);
		}

	}

	// Obtenir les informations sur un membre avec son pseudo
	public function getMemberByPseudo($pseudo) {

	}

	// Obtenir les informations sur un membre avec son id
	public function getMemberById($id) {
		

	}

}

/*
    /*on suppose session actualisé avec les modifs
    function modifaccount($_SESSION,$bddpdo){
        $reponse = $bddpdo->prepare('DELETE FROM Membre 
        WHERE id=?');
        $reponse->execute(array($_SESSION['tab']['id']));
        $reponse->closeCursor();
    
        $reponse = $bddpdo->prepare('INSERT INTO Membre(id,mail,pseudo,mdp,est_modo,est_admin,est_ban)
        VALUES(:id,:mail,:pseudo,:mdp,:est_modo,:est_admin,:est_ban)');
    
        $reponse->execute(array(
            'id' => $_SESSION['tab']['id'],
            'mail' => $_SESSION['tab']['mail'],
            'pseudo' => $_SESSION['tab']['pseudo'],
            'mdp' => $_SESSION['tab']['mdp'],
            'est_modo' => $_SESSION['tab']['est_modo'],
            'est_admin' => $_SESSION['tab']['est_admin'],
            'est_ban' => $_SESSION['tab']['est_ban']
        ));
        $reponse->closeCursor();
    }

    function supaccount($id,$bddpdo){
        $reponse = $bddpdo->prepare('DELETE FROM Membre 
        WHERE id=$id');
        $reponse->closeCursor();
    }*/