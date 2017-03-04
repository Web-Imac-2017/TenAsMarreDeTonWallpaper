<?php

require('db_login.php');

class Membre extends Model {

	public function __construct() {}

	public function findMemberBypseudo($pseudo) {
		$dbh = getBdd();

	    $pseudo = $dbh->quote($pseudo);

		$sqlQuery = "SELECT COUNT(*) from membre WHERE pseudo LIKE" . $pseudo;
		$stmt = $dbh->prepare($sqlQuery);
		$stmt->execute();

		$result = $stmt->fetchAll();

		return intval($result[0][0]);

	}

	public function registerMember($pseudo, $password, $mailAdress) {
		$dbh = getBdd();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

		if (findMemberBypseudo($pseudo) != 0) {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Le pseudo existe déjà';
			return ($result);
		}

	    $pseudo = $dbh->quote($pseudo);
		$password = sha1($password);
	    $password = $dbh->quote($password);
		$mailAdress = $dbh->quote($mailAdress);
		$sqlQuery = "INSERT INTO membre (pseudo, mdp, mail) VALUES (" . $pseudo . ", " . $password .", " . $mailAdress . ") ";
		$stmt = $dbh->prepare($sqlQuery);
		$success = $stmt->execute();

		$sqlQuery = "SELECT * FROM membre WHERE id = MAX(id) ";
		$stmt = $dbh->prepare($sqlQuery);
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

	public function pseudoMember($pseudo, $password) {
		$dbh = getBdd();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

	    $pseudo = $dbh->quote($pseudo);
		$password = sha1($password);
	    $password = $dbh->quote($password);
		$sqlQuery = "SELECT * FROM membre WHERE pseudo LIKE " . $pseudo . " AND mdp LIKE " . $password;
		$stmt = $dbh->prepare($sqlQuery);
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

	public function logoutMember() {
		session_destroy();
	}

}