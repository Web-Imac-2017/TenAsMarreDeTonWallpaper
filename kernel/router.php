<?php

// Page router.php
require_once 'controllers/ControleurName.php';

$str_json = file_get_contents('php://input');
$obj = json_decode($str_json);

class Router {

	// A titre d'exemple ; il y aura autant de variables que de controlleurs
	private $ctrlControllerName;

	public function __construct() {
	    $this->ctrlControllerName = new ControllerName();
	}

	public function routerRequete() {
		foreach($obj as $requeteName) {
			if ($requeteName == 'name') {
				// do things ..
			}
		}
	}

}