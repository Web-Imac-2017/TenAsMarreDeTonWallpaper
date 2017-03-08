<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Categorie extends Model {

    public function __construct(){
        parent::__construct();
    }
	
	/* GESTION DES CATEGORIES */
	
	// Renvoie toutes les catégories
	public function getCategories() {
		$bdd = Database::get();
		$sql = 'SELECT id AS id, nom AS nom FROM categorie';
		$categories = $bdd->query($sql);
		return $categories;
	}
	
	// Renvoie la catégorie demandée
	public function getCategorie($categorieID) {
		$bdd = Database::get();
		$sql = 'SELECT id AS id, nom AS nom FROM categorie WHERE id =?';
		$categorie = $bdd->prepare($sql);
		$categorie->execute(array($categorieID));
		if ($categorie->rowCount() == 1)
			return array("returnCode" => 1, "returnMessage" => $e->getMessage(),  "data" => $categorie->fetch());  // Accès à la première ligne de résultat
		else
		{
			throw new Exception("Aucune catégorie ne correspond à l'identifiant '$categorieID'");
			return array("returnCode" => -1, "returnMessage" => "Aucune catégorie ne correspond à l'identifiant ".$categorieID,  "data" => $categorie->fetch());
		}
	}
	
	// Rajoute une catégorie
	public function addCategorie($nom) {
		$bdd = Database::get();
		$sql = 'INSERT INTO categorie(nom) VALUES(:nom)';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->execute();
	}
	
	// Supprime une catégorie
	public function deleteCategorie($categorieID) {
		$bdd = Database::get();
		$sql = 'DELETE FROM categorie WHERE id = '.$categorieID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Modifie une catégorie
	public function changeCategorie($categorieID, $nom) {
		$bdd = Database::get();
		$sql = 'UPDATE categorie SET nom=:nom WHERE id = :categorieID';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->bindParam(':categorieID', $categorieID);
		$req->execute();
	}
	
	/*// Renvoie le nombre d'occurences d'une catégorie dans la table question_categorie
	function getCategorieOccurences($categorieID) {
		$bdd = Database::get();
		$sql = 'SELECT categorie_id AS id, COUNT( categorie_id ) AS nb_cat FROM categorie_question WHERE categorie_id =?';
		$req = $bdd->prepare($sql);
		$req->execute(array($categorieID));
		if ($req->rowCount() >= 1)
		{
			$occurences = $req->fetch(); // Accès à la première ligne première colone de résultat (id)
			return $occurences['nb_cat'];
		}
		else
		{
			throw new Exception("Aucune question ne correspond à l'identifiant '$categorieID'");
		}
	}*/
}
?>