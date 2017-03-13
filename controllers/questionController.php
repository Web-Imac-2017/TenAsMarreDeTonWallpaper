<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'question.php';

class questionController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function getAll() {
        $question = new Question();
        $data = $question->getAll();
        echo json_encode($data);
    }

    public function get($id) {
        $question = new Question();
        $data = $question->get($id);
        echo json_encode($data);
    }

    public function add() {
        $question = new Question();
        $data = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];
        if (!(isset($_SESSION['user']) && !empty($_SESSION['user']))) {
        	$data = ['returnCode' => '0', 'data' => '', 'returnMessage' => 'Vous n\'etes pas connecté !'];	
        }
        else {
        	if (isset($_POST['q_courte']) && !empty($_POST['q_courte']) && isset($_POST['q_longue']) && !empty($_POST['q_longue']) && isset($_POST['importance']) && !empty($_POST['importance']) && isset($_POST['categories']) && !empty($_POST['categories'])) {
        		$q_courte = $_POST['q_courte'];
        		$q_longue = $_POST['q_longue'];
        		$importance = $_POST['importance'];
        		$categories = $_POST['categories'];
        		$idUser = $_SESSION['user']['id'];
        		$data = $question->add($q_courte, $q_longue, $importance, $idUser, $categories);	
        	}
			else {
				$data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];
			}
        }

        echo json_encode($data);
    }

    public function delete() {
        $question = new Question();
        if (!(isset($_SESSION['user']) && ($_SESSION['user']['moderateur'] == 1 || $_SESSION['user']['admin'] == 1))) {
        	$data = ['returnCode' => '0', 'data' => '', 'returnMessage' => 'Vous n\'etes pas connecté !'];	
        }
        else {
        	if (isset($_POST['id']) && !empty($_POST['id'])) {
        		$data = $question->delete($_POST['id']);
        	}
			else {
				$data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];
			}

        }
        echo json_encode($data);

    }
}