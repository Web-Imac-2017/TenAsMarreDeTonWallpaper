<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Membre extends Model {

	public function __construct() {
		
	}

	// Retourne le nombre d'apparition du pseudo dans la base
	public function getCountOfPseudo($pseudo) {
		$bdd = Database::get();

	    $pseudo = $bdd->quote($pseudo);

		$sqlQuery = "SELECT COUNT(*) from membre WHERE pseudo LIKE" . $pseudo;
		$stmt = $bdd->prepare($sqlQuery);
		$stmt->execute();

		$result = $stmt->fetchAll();

		return intval($result[0][0]);

	}

	// Enregistre un nouveau membre dans la bse
	public function registerMember($pseudo, $password, $mailAdress) {
		$bdd = Database::get();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

		if ($this->getCountOfPseudo($pseudo) != 0) {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Le pseudo existe déjà';
			return ($result);
		}

	    $pseudo = $bdd->quote($pseudo);
		$password = sha1($password);
	    $password = $bdd->quote($password);
		$mailAdress = $bdd->quote($mailAdress);
		$sqlQuery = "INSERT INTO membre (pseudo, mdp, mail, admin, moderateur) VALUES (" . $pseudo . ", " . $password .", " . $mailAdress . ", 0, 0) ";
		$stmt = $bdd->prepare($sqlQuery);
		$success = $stmt->execute();

		$sqlQuery = "SELECT * FROM membre WHERE id = (SELECT MAX(id) FROM membre)";
		$stmt = $bdd->prepare($sqlQuery);
		$stmt->execute();
		$bddResult = $stmt->fetchAll();

		if ($success) {
			$result['returnCode'] = 1;
			$result['returnMessage'] = 'Utilisateur enregistré !';
			$result['data'] = $bddResult[0];
		}
		else {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Echec de la requête';	// Changer pour le message de PDO	
		}

		return $result;
	}

	// Permet à un utilisateur de se connecter
	public function loginMember($pseudo, $password) {
		$bdd = Database::get();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

	    $pseudo = $bdd->quote($pseudo);
		$password = sha1($password);
	    $password = $bdd->quote($password);
		$sqlQuery = "SELECT * FROM membre WHERE pseudo LIKE " . $pseudo . " AND mdp LIKE " . $password;
		$stmt = $bdd->prepare($sqlQuery);
		$success = $stmt->execute();
		$bddResult = $stmt->fetchAll();

		if ($success) {
			$result['returnCode'] = 1;
			$result['returnMessage'] = 'Connexion réussie !';
			$result['data'] = $bddResult[0];
		}
		else {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Echec de la requête';	// Changer pour le message de PDO	
		}

		return $result;
	}

	// Obtenir les informations sur un membre avec son pseudo
	public function getMemberByPseudo($pseudo) {

	}

	// Obtenir les informations sur un membre avec son id
	public function getMemberById($id) {

	}


	public function logoutMember() {
		session_destroy();
	}

}