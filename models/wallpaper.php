
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
	function addWallpaper($url, $nb_apparition, $categories) {
		$bdd = getBdd();
		$sql = 'INSERT INTO wallpaper VALUES(NULL, ?, ?)';
		$req = $bdd->prepare($sql);
		$req->execute(array($url, $nb_apparition));

		$id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
		setcategorie_wallpaper($id, $categories); // on associe le wallpaper aux différentes catégories
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
	function setcategorie_wallpaper($wallpaperID, $categories) {
		foreach ($categories as $cat) {
			$bdd = getBdd();
			$sql = 'INSERT INTO categorie_wallpaper VALUES(?,?)';
			$req = $bdd->prepare($sql);
			$req->execute(array($wallpaperID, $cat));
		}
	}
	
	// Modifie un wallpaper
	function changewallpaper($wallpaperID, $url, $nb_apparition, $categories) {
		$bdd = getBdd();
		$sql = 'UPDATE wallpaper SET url=?, nb_apparition=? WHERE id=?';
		$req = $bdd->prepare($sql);
		$req->execute(array($url, $nb_apparition, $wallpaperID));

		deletecategorie_wallpaper($wallpaperID);
		setcategorie_wallpaper($wallpaperID, $categories);
	}
	
	// Supprimer toutes les catégories d'un wallpaper
	function deletecategorie_wallpaper($wallpaperID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM categorie_wallpaper WHERE wallpaper_id=?';
		$req = $bdd->prepare($sql);
		$req->execute(array($wallpaperID));
	}

	// Renvoie toutes les catégories
	function getcategories() {
		$bdd = getBdd();
		$sql = 'SELECT * FROM categorie';
		$categories = $bdd->prepare($sql);
		$categories->execute();

		return $categories;
	}

	// Renvoie les catégories d'un wallpaper
	function getcategorie_wallpaper($wallpaperID) {
		$bdd = getBdd();
		$sql = 'SELECT * FROM categorie_wallpaper INNER JOIN categorie ON categorie_id = categorie.id WHERE wallpaper_id=?';
		$categories = $bdd->prepare($sql);
		$categories->execute(array($wallpaperID));

		return $categories;
	}

	function tirage_question() {
		
	}

	function tirage_wallpapers($reponse) {

		/* 

		SELECT * FROM wallpaper
		INNER JOIN question, categorie, wallpaper_categories et question_categories

		WHERE categorie=$reponse

		ORDER BY popularite DESC LIMIT 10

		puis tirer au sort 3 wallpapers parmi les 10


		*/

	}

?>