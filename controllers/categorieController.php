<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'categorie.php';

/**
* Classe : categorieController
* Hérite de Controller
* Utilisé à l'appel d'une route vers "api/categorie/actionName"
*/

class categorieController extends Controller {

	public function __construct(){
		parent::__construct();
	}

}