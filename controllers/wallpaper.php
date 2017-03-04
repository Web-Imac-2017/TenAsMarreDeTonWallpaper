<?php

require_once "./kernel/database.php";

class Wallpaper extends Controller {

	public function __construct(){
		parent::__construct();
	}

    // Renvoie les informations de tous les wallpapers
	public function getAll() {
        $bdd = Database::get(); // A vérifier si cela fonctionne
		$sql = 'SELECT * FROM wallpaper';
		$data['content'] = $bdd->prepare($sql);
		$data['content']->execute();
        
        return json_encode($data);
	}
    
    
	// Renvoie les informations d'un seul wallpaper
	public function get($id) {
		$bdd = Database::get();
		$sql = 'SELECT * FROM wallpaper WHERE id=?';
		$data['content'] = $bdd->prepare($sql);
		$data['content']->execute(array($id));
		if ($data['content']->rowCount() == 1)
			$data['content']->fetch();  // Accès à la première ligne de résultat
		else
			throw new Exception("Aucune wallpaper ne correspond à l'identifiant '$wallpaperID'");
        return json_encode($data);
	}
	
	// Ajoute un nouveau wallpaper
	public function add() {
		$bdd = Database::get();
		$sql = 'INSERT INTO wallpaper VALUES(NULL, ?, ?)';
		$req = $bdd->prepare($sql);
		$req->execute(array($_POST['url'], 0));

		$id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
		setWallpaperCategories($id, $categories); // on associe le wallpaper aux différentes catégories
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
	
	





?>