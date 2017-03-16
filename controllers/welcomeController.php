<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'wallpaper.php';
require_once MODEL_DIR . 'question.php';
require_once MODEL_DIR . 'categorie.php';
require_once MODEL_DIR . 'membre.php';

class WelcomeController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function search($search) {  
        $wallpaper = new Wallpaper();
        $question = new Question();
        $categorie = new Categorie();
        $membre = new Membre();

        $data['wallpaper'] = $wallpaper->search($search);
        $data['question'] = $question->search($search);
        $data['categorie'] = $categorie->search($search);
        $data['membre'] = $membre->search($search);

        echo json_encode($data);
    }
}