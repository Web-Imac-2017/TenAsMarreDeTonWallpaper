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

	// Enregistre un nouveau membre dans la bse
	public function registerMember($pseudo, $password, $mailAdress) {
		$bdd = Database::get();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

		if ($this->getCountOfPseudo($pseudo) != 0) {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Le pseudo existe déjà';
			return ($result);
		}

		$password = sha1($password);


		$sqlQuery = "INSERT INTO membre (pseudo, mdp, mail, admin, moderateur) VALUES (?, ?, ?, 0, 0)";
		$stmt = $bdd->prepare($sqlQuery);
		$success = $stmt->execute([$pseudo, $password, $mailAdress]);

		$lastInsertId = $bdd->lastInsertId();

		$sqlQuery = "SELECT * FROM membre WHERE id = ?";
		$stmt = $bdd->prepare($sqlQuery);
		$stmt->execute([$lastInsertId]);
		$bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

		var_dump($bddResult);

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

		$password = sha1($password);
		$sqlQuery = "SELECT * FROM membre WHERE pseudo LIKE ? AND mdp LIKE ?";
		$stmt = $bdd->prepare($sqlQuery);
		$success = $stmt->execute([$pseudo, $password]);
		$bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if ($success) {
			$result['returnCode'] = 1;
			$result['returnMessage'] = 'Connexion réussie !';
			$result['data'] = $bddResult[0];
		}
		else {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Echec de la requête';	// Changer pour le message de PDO	
		}

		// S'il n'y a pas de session démarrée
		session_status() == PHP_SESSION_ACTIVE ? "" : session_start();

		return $result;
	}

	// Obtenir les informations sur un membre avec son pseudo
	public function getMemberByPseudo($pseudo) {

	}

	// Obtenir les informations sur un membre avec son id
	public function getMemberById($id) {

	}

}
/*
$membre = new Membre();
$membre->getCountOfPseudo("David");
$membre->registerMember("raoul", "raoul", "raoul");
var_dump($membre->loginMember("raoul", "raoul"));
var_dump(session_status() == PHP_SESSION_ACTIVE ? 1 : 0);*/