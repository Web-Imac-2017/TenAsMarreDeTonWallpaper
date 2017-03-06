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
	* returnCode : (-2 / -1 / 0) ou 1 (échec ou succès de l'ajout)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur ajouté
	*/
	public function add() {
		$myModel = new Membre();

		if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['password2'])  && !empty($_POST['password2']) && isset($_POST['mailAdress'])  && !empty($_POST['mailAdress'])) {
			$pseudo = $_POST['pseudo'];
			$password = $_POST['password'];
			$password2 = $_POST['password2'];
			$mailAdress = $_POST['mailAdress'];

			if (strcmp($password, $password2) == 0) {
				$data = $myModel->registerMember($pseudo, $password, $mailAdress);
			}
			else {
				$data = ['returnCode' => '0', 'data' => '', 'returnMessage' => 'Les mots de passe ne sont pas identiques !'];		
			}

			echo json_encode($data);
		}
		else {
			$data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];

			echo json_encode($data);
		}
	}

	/**
	* Connexion d'un membre
	* "Renvoie" $data au format json ayant les éléments suivants
	* returnCode : (-2 / -1 / 0) ou 1 (échec ou succès de la connexion)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur connecté
	* Démarre une session en plus de cela
	*/
	public function login() {
		$myModel = new Membre();

		if (isset($_POST['pseudo']) && !empty($_POST['pseudo']) && isset($_POST['password'])  && !empty($_POST['password'])) {
			$pseudo = $_POST['pseudo'];
			$password = $_POST['password'];
			$data = $myModel->loginMember($pseudo, $password);
			
			echo json_encode($data);
		}
		else {
			$data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];

			echo json_encode($data);
		}
	}

	/**
	* Déonnexion d'un membre
	* "Renvoie" $data au format json ayant les éléments suivants
	* returnCode : 0 ou 1 (échec ou succès de la connexion)
	* returnMessage : Message accompagnant le code de retour
	* data : Contient toutes les données sur l'utilisateur connecté
	* Démarre une session en plus de cela
	*/
	public function logout() {
		session_unset();
		session_destroy();
		$_SESSION = array();
		$data = ['returnCode' => '1', 'data' => '', 'returnMessage' => 'Utilisateur déconnecté'];

		echo json_encode($data);
	}

	public function getInfo() {
		if (isset($_SESSION['user'])) {
			$data = ['returnCode' => '1', 'data' => $_SESSION['user'], 'returnMessage' => 'Utilisateur connecté'];

			echo json_encode($data);
		}
		else {
			$data = ['returnCode' => '0', 'data' => '', 'returnMessage' => 'Pas d\'utilisateur connecté'];
			echo json_encode($data);
		}
	}

	public function edit(){
		if (isset($_SESSION['user'])) {
			$id = $_SESSION['user']['id'];
			$pseudo = $_SESSION['user']['pseudo'];
			$password = $_SESSION['user']['password'];
			$mailAdress = $_SESSION['user']['mailAdress'];
			$admin = $_SESSION['user']['admin'];
			$moderateur = $_SESSION['user']['moderateur'];
		}
		if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])) {
			$pseudo = $_POST['pseudo'];
		}
		if (isset($_POST['password']) && !empty($_POST['password'])) {
			$password = $_POST['password'];	
		}
		if (isset($_POST['mailAdress']) && !empty($_POST['mailAdress'])) {
			$mailAdress = $_POST['mailAdress'];
		}
		if (isset($_POST['admin']) && !empty($_POST['admin'])) {
			$admin = $_POST['admin'];
		}
		if (isset($_POST['moderateur']) && !empty($_POST['moderateur'])) {
			$moderateur = $_POST['moderateur'];
		}

	}
}