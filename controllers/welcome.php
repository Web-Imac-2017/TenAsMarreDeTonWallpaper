<?php

require_once "../kernel/controller.php";

class Welcome extends Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index() {
        $data['title'] = 'Accueil';
        
        // Récupère les 3 wallpapers les plus populaires
		$bdd = Database::get();
		$sql = 'SELECT * FROM wallpaper ORDER BY compteur DESC LIMIT 3';
		$data['3_populaires'] = $bdd->prepare($sql);
		$data['3_populaires']->execute(array($id));
        
        return json_encode($data);
	}
}