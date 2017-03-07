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
*/

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
            if(isset($_POST['nom']) && !empty($_POST['nom']) && isset($_FILES['image']) && !empty($_FILES['image'])) {
                if($_SESSION['user']['moderateur'] || $_SESSION['user']['admin'])
                    $mel->add("Validé", $_SESSION['user']['id'], $_SESSION['user']['id']);
                else
                    $mel->add("En attente", $_SESSION['user']['id'], 0);
                
                $mel_id = $mel->lastInsertId();

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
                        
                        $membre->incrementer_nb_wallpapers_ajoutes($_SESSION['user']['id']);
                        
//                        $wallpaper_id = $wallpaper->lastInsertId();
//                        foreach($_POST['reponses'] as $rep) {
//                            $reponse->add($question_id, $wallpaper_id, $rep['val_min'], $rep['val_max']);
//                        }
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
}