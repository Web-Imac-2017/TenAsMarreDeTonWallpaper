<?php

require('db_login.php');

class Membre extends Model {

	public function __construct() {}

	public function findMemberByLogin($login) {
		$dbh = getBdd();

	    $login = $dbh->quote($login);

		$sqlQuery = "SELECT COUNT(*) from membre WHERE login LIKE" . $login;
		$stmt = $dbh->prepare($sqlQuery);
		$stmt->execute();

		$result = $stmt->fetchAll();

		return intval($result[0][0]);

	}

	public function registerMember($login, $password, $mailAdress) {
		$dbh = getBdd();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

		if (findMemberByLogin($login) != 0) {
			$result['returnCode'] = 0;
			$result['returnMessage'] = 'Le login existe déjà';
			return ($result);
		}

	    $login = $dbh->quote($login);
		$password = sha1($password);
	    $password = $dbh->quote($password);
		$mailAdress = $dbh->quote($mailAdress);
		$sqlQuery = "INSERT INTO membre (login, psword, mail_address) VALUES (" . $login . ", " . $password .", " . $mailAdress . ") ";
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

	public function loginMember($login, $password) {
		$dbh = getBdd();

		$result = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

	    $login = $dbh->quote($login);
		$password = sha1($password);
	    $password = $dbh->quote($password);
		$sqlQuery = "SELECT * FROM membre WHERE login LIKE " . $login . " AND psword LIKE " . $password;
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