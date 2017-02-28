<?php

require_once "./kernel/controller.php";

class Welcome extends Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index() {
        $data['title'] = 'Accueil';
        return $data;
	}
}