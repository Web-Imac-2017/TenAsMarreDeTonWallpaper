<?php
	require('db_connect.php');
	
	/* GESTION DES QUESTIONS */
	
	// Renvoie les informations de toutes les questions
	function getQuestions() {
		$bdd = getBdd();
		$sql = 'SELECT id AS q_id, question_courte AS q_courte, question_longue AS q_longue, importance AS importance, popularite AS popularite FROM questions';
		$questions = $bdd->query($sql);
		return $questions;
	}
	
	// Renvoie les informations sur une seule question
	function getQuestion($questionID) {
		$bdd = getBdd();
		$sql = 'SELECT id AS q_id, question_courte AS q_courte, question_longue AS q_longue, importance AS importance, popularite AS popularite FROM questions WHERE id=?';
		$question = $bdd->prepare($sql);
		$question->execute(array($questionID));
		if ($question->rowCount() == 1)
			return $question->fetch();  // Accès à la première ligne de résultat
		else
			throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
	}
	
	// Rajoute une question
	function addQuestion($q_courte, $q_longue, $importance, $popularite, $categories) {
		$bdd = getBdd();
		$sql = 'INSERT INTO questions(question_courte, question_longue, importance, popularite) VALUES(:q_courte, :q_longue, :importance, :popularite)';
		$req = $bdd->prepare($sql);
		$req->bindParam(':q_courte', $q_courte);
		$req->bindParam(':q_longue', $q_longue);
		$req->bindParam(':importance', $importance);
		$req->bindParam(':popularite', $popularite);
		$req->execute();
		$id_nouveau = $bdd->lastInsertId();
		addQuestionCategorie($id_nouveau, $categories);
	}
	
	// Associe une catégorie à une question
	function addQuestionCategorie($questionID, $categoriesID)
	{
		foreach ($categoriesID as $cat) {
			$bdd = getBdd();
			$sql = 'INSERT INTO questions_categories VALUES(?, ?)';
			$req = $bdd->prepare($sql);
			$req->execute(array($questionID, $cat));
		}
	}
	
	// Supprime une question
	function deleteQuestion($questionID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM questions WHERE id = '.$questionID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Supprime une question de la table question_categorie
	function deleteQuestionCategorie($questionID) {
		$bdd = getBdd();
		$sql = 'DELETE FROM questions_categories WHERE question_id = '.$questionID.'';
		$req = $bdd->prepare($sql);
		$req->execute();
	}
	
	// Modifie une question
	function changeQuestion($questionID, $q_courte, $q_longue, $importance, $popularite) {
		$bdd = getBdd();
		$sql = 'UPDATE questions SET question_courte=:q_courte, question_longue=:q_longue, importance=:importance, popularite=:popularite WHERE id = :questionID';
		$req = $bdd->prepare($sql);
		$req->bindParam(':q_courte', $q_courte);
		$req->bindParam(':q_longue', $q_longue);
		$req->bindParam(':importance', $importance);
		$req->bindParam(':popularite', $popularite);
		$req->bindParam(':questionID', $questionID);
		$req->execute();
		//changeQuestionCategorie($questionID, $categorieID);
	}
	
	// Modifie la catégorie d'une question
	function changeQuestionCategorie($questionID, $categoriesID) {
		foreach ($categoriesID as $categoriesID):
			$bdd = getBdd();
			$sql = 'UPDATE questions_categories SET categorie_id=:categorieID WHERE question_id=:questionID';
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
		$sql = 'SELECT categorie_id AS cat_id FROM questions_categories WHERE question_id =?';
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
		$sql = 'SELECT question_id AS id, COUNT( question_id ) AS nb_cat FROM questions_categories WHERE question_id =?';
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
	function getQuestionsby($categorie,$orderby,$sens,$limit){
        $selector= 'SELECT ID,question_courte,question_longue,importance,nb_apparition
        FROM question, categorie_question 
        WHERE ID=question_id AND categorie_id=? ORDER BY ? ? LIMIT ?';
        $reponsebdd = $bddpdo->prepare($selector);
        $reponsebdd->execute(array($categorie,$orderby,$sens,$limit));
        return $reponsebdd;

    }
?>