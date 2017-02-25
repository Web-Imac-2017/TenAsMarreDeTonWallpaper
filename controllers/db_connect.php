<?php
	require('db_login.php');
	
	// Connexion à la BDD
	function getBdd() {
		// Connexion en local ou en ligne
		//$login = getLogin();
		$login = getLoginLocal();
		try
		{
			$bdd = new PDO('mysql:host='.$login[0].';dbname='.$login[1].';charset=utf8', $login[2], $login[3]);
			return $bdd;
		}
		catch(PDOException $e)
		{
			die('Erreur : '.$e->getMessage());
		}
	}
?>