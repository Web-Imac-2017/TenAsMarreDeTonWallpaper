<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'categorie.php';

class categorieController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function add() {
        $categorie = new Categorie();

        if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            if(isset($_POST['nom']) && !empty($_POST['nom'])) {
                if($_SESSION['user']['moderateur'] || $_SESSION['user']['admin']) {
                    $data = $categorie->add($_POST['nom']);
                }
                else {
                    $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas autorisé'];
                }
            }
            else {
                $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants'];
            }
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté'];
        }

        echo json_encode($data);
    }

    public function getAll() {
        $categorie = new Categorie();
        $data = $categorie->getAll();
        echo json_encode($data);
    }

    public function get($id) {
        $categorie = new Categorie();
        $data = $categorie->get($id);
        echo json_encode($data);
    }

    public function delete($id) {
        $categorie = new Categorie();
        $data = $categorie->delete($id);
        echo json_encode($data);
    }

}