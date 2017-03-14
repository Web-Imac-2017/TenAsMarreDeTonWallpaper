<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'question.php';
require_once MODEL_DIR . 'mel.php';
require_once MODEL_DIR . 'membre.php';

class questionController extends Controller {

    public function __construct() {
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
        $mel = new Mel();
        $membre = new Membre();

        if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            if(isset($_POST['q_courte']) && !empty($_POST['q_courte']) && isset($_POST['q_longue']) && !empty($_POST['q_longue']) && isset($_POST['categories']) && !empty($_POST['categories'])) {

                if($_SESSION['user']['moderateur'] || $_SESSION['user']['admin']) {
                    $data = $mel->add("Validé", $_SESSION['user']['id'], $_SESSION['user']['id']);
                }
                else {
                    $data = $mel->add("En attente", $_SESSION['user']['id'], NULL);
                }

                $mel_id = $data['data']['id'];
                echo "Mise en ligne id : ".$mel_id;
                
                $data = $question->add($_POST['q_courte'], $_POST['q_longue'], $mel_id);

                $question_id = $data['data']['id'];

                $membre->incrementer_nb_questions_ajoutees($_SESSION['user']['id']);

                $question->setCategories($question_id, $_POST['categories']);

            }
            else {
                $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];
                echo json_encode($data);
            }
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté'];
            echo json_encode($data);
        }
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

    public function latest($nb) {
        $question = new Question();
        $data = $question->latest($nb);
        echo json_encode($data);
    }
}