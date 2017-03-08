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

    public function getMines($nb) {
        if(isset($_SESSION['user'])) {
            $question = new Question();
            $data = $question->getMines($_SESSION['user']['id'], $nb);
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté'];
        }
        echo json_encode($data);
    }

}