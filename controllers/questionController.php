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

}