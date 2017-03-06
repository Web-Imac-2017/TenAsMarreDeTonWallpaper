<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'mel.php';

/**
* Classe : melController
* Hérite de Controller
* Utilisé à l'appel d'une route vers "api/mel/actionName"
*/

class melController extends Controller {

	public function __construct(){
		parent::__construct();
	}

	public function add() {
		$myModel = new Mel();

		if (isset($_POST['raison']) && !empty($_POST['raison']) && isset($_POST['statut']) && !empty($_POST['statut']) && isset($_SESSION['user'])  && !empty($_SESSION['user'])) {
			$raison = $_POST['raison'];
			$statut = $_POST['statut'];
			$membre_id = $_SESSION['user']['id'];
			$data = $myModel->add($raison, $statut, $membre_id);

			echo json_encode($data);
		}
		else {
			$data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];

			echo json_encode($data);
		}
	}

}