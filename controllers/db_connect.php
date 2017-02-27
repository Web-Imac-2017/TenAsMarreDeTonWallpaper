<?php
	require('db_login.php');
	
	// Connexion à la BDD
	function getBdd() {
		// Connexion en local ou en ligne
		//$login = getLogin();
		$login = getLoginLocal();
		try
		{
			$bdd = new PDO('mysql:host='.$login['url'].';dbname='.$login['dbname'].';charset=utf8', $login['user'], $login['pass']);
		}
		catch(PDOException $e)
		{
			die('Erreur : '.$e->getMessage());
		}
		return $bdd;
	}
?>