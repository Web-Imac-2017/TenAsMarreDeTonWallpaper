<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'membre.php';

/**
* Classe : membreController
* Hérite de Controller
* Utilisé à l'appel d'une route vers "api/membre/actionName"
*/

class membreController extends Controller {

	public function __construct(){
		parent::__construct();
	}


	public function index() {
        
        //echo json_encode($data);
	}

	/**
	* Ajout d'un nouveau membre dans l'application
	* "Renvoie" $data au format json ayant les éléments suivants
	* returnCode : 0 ou 1 (échec ou succès de l'ajout)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur ajouté
	*/
	public function add() {
		$myModel = new Membre();
		if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['mailAdress'])) {
			$pseudo = json_decode($_POST['pseudo']);
			$password = json_decode($_POST['pseudo']);
			$mailAdress = json_decode($_POST['pseudo']);
			$data = $myModel->registerMember($pseudo, $password, $mailAdress);

			echo json_decode($data);
		}
	}

	/**
	* Connexion d'un membre
	* "Renvoie" $data au format json ayant les éléments suivants
	* returnCode : 0 ou 1 (échec ou succès de la connexion)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur connecté
	* Démarre une session en plus de cela
	*/
	public function login() {
		$myModel = new Membre();
		if (isset($_POST['pseudo']) && isset($_POST['password'])) {
			$pseudo = json_decode($_POST['pseudo']);
			$password = json_decode($_POST['pseudo']);
			$data = $myModel->loginMember($pseudo, $password);

			echo json_decode($data);
		}
	}

}