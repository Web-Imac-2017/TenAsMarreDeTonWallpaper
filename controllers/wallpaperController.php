<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'wallpaper.php';
require_once MODEL_DIR . 'mel.php';
require_once MODEL_DIR . 'reponse.php';
require_once MODEL_DIR . 'membre.php';

/**
* Classe : wallpaperController
* Hérite de Controller
* Utilisé à l'appel d'une route vers "api/categorie/actionName"
**/

class wallpaperController extends Controller {

    public function __construct(){
        parent::__construct();
    }

    public function add() {
        $mel = new Mel();
        $wallpaper = new Wallpaper();
        $reponse = new Reponse();
        $membre = new Membre();

        if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_FILES['image']) && !empty($_FILES['image']) && isset($_POST['categories']) && !empty($_POST['categories'])) {
                if($_SESSION['user']['moderateur'] || $_SESSION['user']['admin']) {
                    $data = $mel->add("Validé", $_SESSION['user']['id'], $_SESSION['user']['id']);
                }
                else {
                    $data = $mel->add("En attente", $_SESSION['user']['id'], NULL);
                }

                $mel_id = $data['data']['id'];

                $extensions = array('.jpg', '.jpeg', '.png', '.tiff', '.bmp', '.gif', '.JPG', '.JPEG', '.PNG', '.TIFF', '.BMP', '.GIF');
                $ext = strrchr($_FILES['image']['name'], '.');

                if ($_FILES['image']['error'] != 0) {
                    $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Problème d\'upload'];
                    echo json_encode($data);
                }

                else if (!in_array($ext, $extensions)) {
                    $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Mauvaise extension'];
                    echo json_encode($data);
                }

                else {
                    $url = 'upload/'.uniqid().$ext;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $url)) {
                        $width = getimagesize($url)[0];
                        $height = getimagesize($url)[1];
                        $format = getimagesize($url)['mime'];

                        $data = $wallpaper->add($url, $url, $mel_id, $_POST['nom'], $_POST['auteur'], $width, $height, $format);

                        $wallpaper_id = $data['data']['id'];

                        $membre->incrementer_nb_wallpapers_ajoutes($_SESSION['user']['id']);

                        $min = 0;
                        $max = 0;
                        foreach($_POST['rep'] as $key=>$rep) {
                            switch($rep) {
                                case 0: // Oui
                                    $min = 100;
                                    $max = 100;
                                    break;
                                case 1: // Eventuellement
                                    $min = 75;
                                    $max = 99;
                                    break;
                                case 2: // Peu importe
                                    $min = 50;
                                    $max = 74;
                                    break;
                                case 3: // Pas vraiment
                                    $min = 25;
                                    $max = 49;
                                    break;
                                case 4: // Non
                                    $min = 0;
                                    $max = 24;
                                    break;
                                default:
                                    $min = 0;
                                    $max = 50;
                                    break;
                            }
                            $reponse->add($key, $wallpaper_id, $min, $max);
                        }

                        $wallpaper->setCategories($wallpaper_id, $_POST['categories']);
                    }
                    else {
                        $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Problème de transfert de fichier'];
                    }
                    echo json_encode($data);
                }
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

    public function get($id) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->get($id);
        echo json_encode($data);
    }

    public function getMines($nb) {
        if(isset($_SESSION['user'])) {
            $wallpaper = new Wallpaper();
            $data = $wallpaper->getMines($_SESSION['user']['id'], $nb);
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté'];
        }
        echo json_encode($data);
    }

    public function getMostDL($nb) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->getMostDL($nb);
        echo json_encode($data);
    }

    public function getMostAP($nb) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->getMostAP($nb);
        echo json_encode($data);
    }

    public function delete($id) {
        if(isset($_SESSION['user'])) {
            if($_SESSION['user']['admin'] || $_SESSION['user']['moderateur']) {
                $wallpaper = new Wallpaper();
                $data = $wallpaper->delete($id);
            }
            else {
                $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas autorisé'];
            }
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté'];
        }
        echo json_encode($data);
    }

    public function random($nb) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->random($nb);
        echo json_encode($data);
    }

    public function getByCategorie($id) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->getByCategorie($id);
        echo json_encode($data);
    }
}