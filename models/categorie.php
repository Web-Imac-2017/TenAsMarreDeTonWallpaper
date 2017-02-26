<?php
	require('db_connect.php');
	
	/* GESTION DES CATEGORIES */
	
	// Renvoie toutes les catégories
	function getCategories() {
		$bdd = getBdd();
		$sql = 'SELECT id AS id, nom AS nom FROM categories';
		$categories = $bdd->query($sql);
		return $categories;
	}
	
	// Renvoie la catégorie demandée
	function getCategorie($categorieID) {
		$bdd = getBdd();
		$sql = 'SELECT id AS id, nom AS nom FROM categories WHERE id =?';
		$categorie = $bdd->prepare($sql);
		$categorie->execute(array($categorieID));
		if ($categorie->rowCount() == 1)
			return $categorie->fetch();  // Accès à la première ligne de résultat
		else
			throw new Exception("Aucune catégorie ne correspond à l'identifiant '$categorieID'");
	}
	
	// Rajoute une catégorie
	function addCategorie($nom) {
		$bdd = getBdd();
		$sql = 'INSERT INTO categories(nom) VALUES(:nom)';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->execute();
	}
	
	// Supprime une catégorie
	function deleteCategorie($categorieID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM categories WHERE id = '.$categorieID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Modifie une catégorie
	function changeCategorie($categorieID, $nom) {
		$bdd = getBdd();
		$sql = 'UPDATE categories SET nom=:nom WHERE id = :categorieID';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->bindParam(':categorieID', $categorieID);
		$req->execute();
	}
	
	// Renvoie le nombre d'occurences d'une catégorie dans la table question_categorie
	function getCategorieOccurences($categorieID) {
		$bdd = getBdd();
		$sql = 'SELECT categorie_id AS id, COUNT( categorie_id ) AS nb_cat FROM questions_categories WHERE categorie_id =?';
		$req = $bdd->prepare($sql);
		$req->execute(array($categorieID));
		if ($req->rowCount() >= 1)
		{
			$occurences = $req->fetch(); // Accès à la première ligne première colone de résultat (id)
			return $occurences['nb_cat'];
		}
		else
			throw new Exception("Aucune question ne correspond à l'identifiant '$categorieID'");
	}
?>