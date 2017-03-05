<?php

// !!!! Renommer le fichier en "DbConfig.php" !!!!!

class DbConfig {

	public static $config;

	public function __construct(){

		// Informations pour se connecter à la BDD en local -- CHANGER LES PARAMETRES POUR CORRESPONDRE AVEC VOTRE BASE
		$db_url = "nomDuServeur";
		$db_user = "loginUser";
		$db_pass = "passwordUser";
		$db_dbname = "nomDeLaBaseDeDonnee";


		// Informations pour se connecter à la BDD en ligne

		Self::$config = array('url'=>$db_url, 'dbname'=>$db_dbname, 'user'=>$db_user, 'pass'=>$db_pass);
		
	}

}