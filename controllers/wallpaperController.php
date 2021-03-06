<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'wallpaper.php';
require_once MODEL_DIR . 'mel.php';
require_once MODEL_DIR . 'reponse.php';
require_once MODEL_DIR . 'membre.php';
require_once MODEL_DIR . 'gd.php';

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

                    //echo (is_writable('upload/') ? "writable" : "not writable!").'<br>';
                    //$dirs = glob('*');
                    //print_r( $dirs);

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $url)) {
                        $width = getimagesize($url)[0];
                        $height = getimagesize($url)[1];
                        $format = getimagesize($url)['mime'];

                        $data = $wallpaper->add($url, $url, $mel_id, $_POST['nom'], $_POST['auteur'], $width, $height, $format);

                        $wallpaper_id = $data['data']['id'];

                        $membre->incrementer_nb_wallpapers_ajoutes($_SESSION['user']['id']);

                        foreach($_POST['rep'] as $key=>$rep) {
                            $reponse->add($key, $wallpaper_id, $rep[0], $rep[1]);
                            $reponse->importance($key);
                        }

                        $wallpaper->setCategories($wallpaper_id, $_POST['categories']);
                    }
                    else {
                        $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Problème de transfert de fichier !'];
                    }
                    echo json_encode($data);
                }
            }
            else {
                $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier.'];
                echo json_encode($data);
            }
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté !'];
            echo json_encode($data);
        }
    }

    public function change($id) {
        $wallpaper = new Wallpaper();
        $reponse = new Reponse();

        $data = $wallpaper->getMembre($id);
        $membre_id = $data['data']['membre_id'];

        if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            if($_SESSION['user']['moderateur'] || $_SESSION['user']['admin'] || $_SESSION['user']['id'] == $membre_id) {
                if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_POST['categories']) && !empty($_POST['categories'])) {

                    // Update table wallpaper
                    $data = $wallpaper->update($id, $_POST['nom'], $_POST['auteur']);
                    
                    // Update table reponse
                    $reponse->delete($id);
                    foreach($_POST['rep'] as $key=>$rep) {
                        $reponse->add($key, $id, $rep[0], $rep[1]);
                        $reponse->importance($key);
                    }

                    // Update table categorie_wallpaper
                    $wallpaper->deleteCategories($id);
                    $wallpaper->setCategories($id, $_POST['categories']);

                    echo json_encode($data);
                }
                else {
                    $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier.'];
                    echo json_encode($data);
                }
            }
            else {
                $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas autorisé !'];
                echo json_encode($data);
            }
        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Vous n\'êtes pas connecté !'];
            echo json_encode($data);
        }
    }

    public function get($id) {
        $wallpaper = new Wallpaper();
        $data['wallpaper'] = $wallpaper->get($id);
        $data['categories'] = $wallpaper->getCategories($id);
        $data['reponses'] = $wallpaper->getReponses($id);
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

    public function latest($nb) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->latest($nb);
        echo json_encode($data);
    }

    public function getMostAP($nb) {
        $wallpaper = new Wallpaper();
        $data = $wallpaper->getMostAP($nb);
        echo json_encode($data);
    }

    public function delete() {
        if(isset($_SESSION['user'])) {
            if($_SESSION['user']['admin'] || $_SESSION['user']['moderateur']) {
                if(isset($_POST['id']) && !empty($_POST['id'])) {
                    $wallpaper = new Wallpaper();
                    $data = $wallpaper->delete($_POST['id']);
                }
                else {
                    $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants, veuillez vérifier'];
                }
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

    /* Cette fonction prend en paramètre l'id d'un wallpaper
    *  width : la largeur voulue
    *  height : la hauteur voulue
    *  Telecharge dans le dossier "telechargement" / "download" du client le wallpaper redimensionné
    */
    public function download($wallpaperId, $width, $height) {
        $wallpaper = new Wallpaper();
        $gdObject = new Gd();

        $data = ['returnCode' => '', 'data' => '', 'returnMessage' => ''];

        // Si tous les paramètres sont OK
        if (!empty($wallpaperId) && !empty($width) && !empty($height)) {

            $urlImage = $wallpaper->getUrl($wallpaperId)['data'];
            // Si on a pu obtenir l'url
            if (!empty($urlImage)) {
                $info = $gdObject->gdcollect($urlImage);
                $fileName = explode("/", $urlImage);
                $fileName = explode(".", $fileName[1]);
                $fileName = $fileName[0] . "_" . $width . "_" . $height . "." . $fileName[1];
                $wallpaper->incrementer_nb_telechargement($wallpaperId);

                if (strcmp(strtolower($info['extension']), ".png") == 0) {
                    header('Content-Type: image/png');
                    header('Content-Disposition: attachment; filename=' . $fileName);
                    $gdObject->gdresize($info['image'], $width, $height, ".png");
                }
                else if (strcmp(strtolower($info['extension']), ".jpg") == 0 || strcmp(strtolower($info['extension']), ".jpeg") == 0){
                    header('Content-Type: image/jpg');
                    header('Content-Disposition: attachment; filename=' . $fileName);
                    $gdObject->gdresize($info['image'], $width, $height, ".jpg");
                    exit();
                }
                else {
                    $data['returnCode'] = 0;
                    $data['returnMessage'] = 'Echec : le format de l\'image n\'est pas supporté';    
                }
            }
            else {
                $data['returnCode'] = 0;
                $data['returnMessage'] = 'L\'id du wallpaper est invalide'; 
            }

        }
        else {
            $data = ['returnCode' => '-2', 'data' => '', 'returnMessage' => 'Certains paramètres sont manquants !'];
        }

        echo json_encode($data);
    }
}
