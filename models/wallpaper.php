<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Wallpaper extends Model {

    public function __construct(){
        parent::__construct();
    }

    // Renvoie les informations de tous les wallpapers
    public function getAll() {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];
        $sqlQuery = 'SELECT * FROM wallpaper';

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute();
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Connexion réussie !';
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la connexion : pseudo ou mot de passe incorrect !';
            }
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        return $result;
    }

    // Renvoie les informations d'un seul wallpaper
    public function get($id) {        
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];
        $sqlQuery = 'SELECT * FROM wallpaper WHERE id=?';

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Connexion réussie !';
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la connexion : pseudo ou mot de passe incorrect !';
            }
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        return $result;
    }

    // Ajoute un nouveau wallpaper
    public function add($url, $url_thumb, $mel_id, $nom, $auteur, $width, $height, $extension, $categories) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];
        $sqlQuery = 'INSERT INTO wallpaper VALUES(NULL, ?, ?, ?, ?, ?, ?, ?)';

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$url, $url_thumb, $mel_id, $nom, $auteur, $width, $height, $extension, date("Y-m-d"), 0, 0]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Connexion réussie !';
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la connexion : pseudo ou mot de passe incorrect !';
            }
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        $id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
        setWallpaperCategories($id, $categories); // on associe le wallpaper aux différentes catégories

        return $result;

    }

    // Renvoie l'id du dernier wallpaper inséré
    public function getIdLastWallpaper() {
        $bdd = Database::get();
        $sql = 'SELECT id FROM wallpaper ORDER BY id DESC LIMIT 1';
        $req = $bdd->prepare($sql);
        $req->execute();
        $id = $req->fetch();

        return $id['id'];
    }

    // Supprime un wallpaper
    public function deleteWallpaper($wallpaperID) {
        $bdd = Database::get();
        $sql = 'DELETE FROM wallpaper WHERE id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($wallpaperID));
    }

    // Associe des catégories à un wallpaper
    public function setWallpaperCategories($wallpaperID, $categories) {
        foreach ($categories as $cat) {
            $bdd = Database::get();
            $sql = 'INSERT INTO WallpaperCategories VALUES(?,?)';
            $req = $bdd->prepare($sql);
            $req->execute(array($wallpaperID, $cat));
        }
    }

    // Modifie un wallpaper
    public function changewallpaper($wallpaperID, $url, $estapparent, $categories) {
        $bdd = Database::get();
        $sql = 'UPDATE Wallpaper SET url=?, estapparent=? WHERE id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($url, $estapparent, $wallpaperID));

        deleteWallpaperCategories($wallpaperID);
        setWallpaperCategories($wallpaperID, $categories);
    }

    // Supprimer toutes les catégories d'un wallpaper
    public function deleteWallpaperCategories($wallpaperID) {
        $bdd = Database::get();
        $sql = 'DELETE FROM WallpaperCategories WHERE wallpaper_id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($wallpaperID));
    }	

    // Renvoie les catégories d'un wallpaper
    public function getWallpaperCategories($id) {
        $bdd = Database::get();
        $sql = 'SELECT * FROM WallpaperCategories INNER JOIN categorie ON categorie_id = Categorie.id WHERE wallpaper_id=?';
        $data['categories'] = $bdd->prepare($sql);
        $data['categories']->execute(array($id));

        return json_encode($data);
    }

}