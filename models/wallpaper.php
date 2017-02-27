<?php
	require('db_connect.php');
	
	// Renvoie les informations de tous les wallpapers
	function getWallpapers() {
		$bdd = getBdd();
		$sql = 'SELECT * FROM wallpaper';
		$wallpapers = $bdd->prepare($sql);
		$wallpapers->execute();

		return $wallpapers;
	}
	
	// Renvoie les informations d'un seul wallpaper
	function getWallpaper($wallpaperID) {
		$bdd = getBdd();
		$sql = 'SELECT * FROM wallpaper WHERE id=?';
		$wallpaper = $bdd->prepare($sql);
		$wallpaper->execute(array($wallpaperID));
		if ($wallpaper->rowCount() == 1)
			return $wallpaper->fetch();  // Accès à la première ligne de résultat
		else
			throw new Exception("Aucune wallpaper ne correspond à l'identifiant '$wallpaperID'");
	}
	
	// Ajoute un nouveau wallpaper
	function addWallpaper($url, $estapparent, $categories) {
		$bdd = getBdd();
		$sql = 'INSERT INTO wallpaper VALUES(NULL, ?, ?)';
		$req = $bdd->prepare($sql);
		$req->execute(array($url, $estapparent));

		$id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
		setWallpaperCategories($id, $categories); // on associe le wallpaper aux différentes catégories
	}

	// Renvoie l'id du dernier wallpaper inséré
	function getIdLastWallpaper() {
		$bdd = getBdd();
		$sql = 'SELECT id FROM wallpaper ORDER BY id DESC LIMIT 1';
		$req = $bdd->prepare($sql);
		$req->execute();
		$id = $req->fetch();
		
		return $id['id'];
	}
	
	// Supprime un wallpaper
	function deleteWallpaper($wallpaperID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM wallpaper WHERE id=?';
		$req = $bdd->prepare($sql);
		$req->execute(array($wallpaperID));
	}

	// Associe des catégories à un wallpaper
	function setWallpaperCategories($wallpaperID, $categories) {
		foreach ($categories as $cat) {
			$bdd = getBdd();
			$sql = 'INSERT INTO WallpaperCategories VALUES(?,?)';
			$req = $bdd->prepare($sql);
			$req->execute(array($wallpaperID, $cat));
		}
	}
	
	// Modifie un wallpaper
	function changewallpaper($wallpaperID, $url, $estapparent, $categories) {
		$bdd = getBdd();
		$sql = 'UPDATE Wallpaper SET url=?, estapparent=? WHERE id=?';
		$req = $bdd->prepare($sql);
		$req->execute(array($url, $estapparent, $wallpaperID));

		deleteWallpaperCategories($wallpaperID);
		setWallpaperCategories($wallpaperID, $categories);
	}
	
	// Supprimer toutes les catégories d'un wallpaper
	function deleteWallpaperCategories($wallpaperID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM WallpaperCategories WHERE wallpaper_id=?';
		$req = $bdd->prepare($sql);
		$req->execute(array($wallpaperID));
	}	

	// Renvoie toutes les catégories
	function getCategories() {
		$bdd = getBdd();
		$sql = 'SELECT * FROM Categorie';
		$categories = $bdd->prepare($sql);
		$categories->execute();

		return $categories;
	}

	// Renvoie les catégories d'un wallpaper
	function getWallpaperCategories($wallpaperID) {
		$bdd = getBdd();
		$sql = 'SELECT * FROM WallpaperCategories INNER JOIN categorie ON categorie_id = Categorie.id WHERE wallpaper_id=?';
		$categories = $bdd->prepare($sql);
		$categories->execute(array($wallpaperID));

		return $categories;
	}






?>