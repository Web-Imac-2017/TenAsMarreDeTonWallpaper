<?php
	require('controllers/db_connect.php');
	
	/* GESTION DES QUESTIONS */
	
	// Renvoie les informations de toutes les questions
	function getQuestions() {
		$bdd = getBdd();
		$sql = 'SELECT id AS q_id, q_courte AS q_courte, q_longue AS q_longue, importance AS importance, nb_apparition AS nb_apparition FROM question';
		$questions = $bdd->query($sql);
		return $questions;
	}
	
	// Renvoie les informations sur une seule question
	function getQuestion($questionID) {
		$bdd = getBdd();
		$sql = 'SELECT id AS q_id, q_courte AS q_courte, q_longue AS q_longue, importance AS importance, nb_apparition AS nb_apparition FROM question WHERE id=?';
		$question = $bdd->prepare($sql);
		$question->execute(array($questionID));
		if ($question->rowCount() == 1)
			return $question->fetch();  // Accès à la première ligne de résultat
		else
			throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
	}
	
	// Rajoute une question
	function addQuestion($q_courte, $q_longue, $importance, $nb_apparition, $categories) {
		$bdd = getBdd();
		$sql = 'INSERT INTO question(q_courte, q_longue, importance, nb_apparition) VALUES(:q_courte, :q_longue, :importance, :nb_apparition)';
		$req = $bdd->prepare($sql);
		$req->bindParam(':q_courte', $q_courte);
		$req->bindParam(':q_longue', $q_longue);
		$req->bindParam(':importance', $importance);
		$req->bindParam(':nb_apparition', $nb_apparition);
		$req->execute();
		$id_nouveau = $bdd->lastInsertId();
		addQuestionCategorie($id_nouveau, $categories);
	}
	
	// Associe une catégorie à une question
	function addQuestionCategorie($questionID, $categoriesID)
	{
		foreach ($categoriesID as $cat) {
			$bdd = getBdd();
			$sql = 'INSERT INTO categorie_question VALUES(?, ?)';
			$req = $bdd->prepare($sql);
			$req->execute(array($questionID, $cat));
		}
	}
	
	function setImportance($questionID)
	{
		$bdd = getBdd();
		$sql = 'SELECT wallpaper_id, COUNT( * ) AS nb_wpp FROM categorie_wallpaper AS c_w INNER JOIN c_w.categorie_question ON categorie_id = categorie_question.categorie_id WHERE question_id=? GROUP BY wallpaper_id';
		$importance = $bdd->prepare($sql);
		$importance->execute(array($questionID));

		return $importance;
	}
	
	// Supprime une question
	function deleteQuestion($questionID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM question WHERE id = '.$questionID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Supprime une question de la table question_categorie
	function deleteQuestionCategorie($questionID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM categorie_question WHERE question_id = '.$questionID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Modifie une question
	function changeQuestion($questionID, $q_courte, $q_longue, $importance, $nb_apparition) {
		$bdd = getBdd();
		$sql = 'UPDATE question SET q_courte=:q_courte, q_longue=:q_longue, importance=:importance, nb_apparition=:nb_apparition WHERE id = :questionID';
		$req = $bdd->prepare($sql);
		$req->bindParam(':q_courte', $q_courte);
		$req->bindParam(':q_longue', $q_longue);
		$req->bindParam(':importance', $importance);
		$req->bindParam(':nb_apparition', $nb_apparition);
		$req->bindParam(':questionID', $questionID);
		$req->execute();
		//changeQuestionCategorie($questionID, $categorieID);
	}
	
	// Modifie la catégorie d'une question
	function changeQuestionCategorie($questionID, $categoriesID) {
		foreach ($categoriesID as $categoriesID):
			$bdd = getBdd();
			$sql = 'UPDATE categorie_question SET categorie_id=:categorieID WHERE question_id=:questionID';
			$req = $bdd->prepare($sql);
			$req->bindParam(':categorieID', $categoriesID);
			$req->bindParam(':questionID', $questionID);
			$req->execute();
		endforeach;
	}
	
	// Renvoie les catégories liées à la question
	function getQuestionCategories($questionID) {
		$i = 0;
		$bdd = getBdd();
		$sql = 'SELECT categorie_id AS cat_id FROM categorie_question WHERE question_id =?';
		$req = $bdd->prepare($sql);
		if($req->execute(array($questionID))) {
			while ($cat_id = $req->fetch()) {
				$categories[$i] = getCategorie($cat_id['cat_id']);
				$i++;
			}
		return $categories;
		}
		else
			throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
	}
	
	// Renvoie le nombre d'occurences d'une question dans la table question_categorie
	function getQuestionOccurences($questionID) {
		$bdd = getBdd();
		$sql = 'SELECT question_id AS id, COUNT( question_id ) AS nb_cat FROM categorie_question WHERE question_id =?';
		$req = $bdd->prepare($sql);
		$req->execute(array($questionID));
		if ($req->rowCount() >= 1)
		{
			$occurences = $req->fetch(); // Accès à la première ligne première colone de résultat (id)
			return $occurences['nb_cat'];
		}
		else
			throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
	}
	
	// -------------------------------------------------------------------------------------------------- //
	
	/* GESTION DES CATEGORIES */
	
	// Renvoie toutes les catégories
	function getCategories() {
		$bdd = getBdd();
		$sql = 'SELECT id AS id, nom AS nom FROM categorie';
		$req = $bdd->prepare($sql);
		$req->execute();
		$categories = $req->fetchAll();
		return $categories;
	}
	
	// Renvoie la catégorie demandée
	function getCategorie($categorieID) {
		$bdd = getBdd();
		$sql = 'SELECT id AS id, nom AS nom FROM categorie WHERE id =?';
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
		$sql = 'INSERT INTO categorie(nom) VALUES(:nom)';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->execute();
	}
	
	// Supprime une catégorie
	function deleteCategorie($categorieID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM categorie WHERE id = '.$categorieID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Modifie une catégorie
	function changeCategorie($categorieID, $nom) {
		$bdd = getBdd();
		$sql = 'UPDATE categorie SET nom=:nom WHERE id = :categorieID';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nom', $nom);
		$req->bindParam(':categorieID', $categorieID);
		$req->execute();
	}
	
	// Renvoie le nombre d'occurences d'une catégorie dans la table question_categorie
	function getCategorieOccurences($categorieID) {
		$bdd = getBdd();
		$sql = 'SELECT categorie_id AS id, COUNT( categorie_id ) AS nb_cat FROM categorie_question WHERE categorie_id =?';
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