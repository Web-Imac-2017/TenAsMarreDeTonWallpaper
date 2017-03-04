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

	/**
	* Ajout d'un nouveau membre dans l'application
	* "Renvoie" $data au format json ayant les éléments suivants
	* returnCode : 0 ou 1 (échec ou succès de l'ajout)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur ajouté
	*/
	public function add() {
		$myModel = new Membre();
		if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['mailAdress'])  && !empty($_POST['mailAdress'])) {
			$pseudo = $_POST['pseudo'];
			$password = $_POST['password'];
			$mailAdress = $_POST['mailAdress'];
			$data = $myModel->registerMember($pseudo, $password, $mailAdress);

			echo json_encode($data);
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
		if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password'])  && !empty($_POST['password'])) {
			$pseudo = $_POST['pseudo'];
			$password = $_POST['pseudo'];
			$data = $myModel->loginMember($pseudo, $password);

			echo json_encode($data);
		}
	}

	public function logout() {
		session_destroy();
		$data = ['returnCode' => '1', 'data' => '', 'returnMessage' => 'Utilisateur déconnecté'];
		echo json_encode($data);
	}

}