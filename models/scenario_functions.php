<?php	
	/* GESTION DE LA PREMIERE QUESTION */
	
	// Tire au hasard trois catégories.
	// Renvoie l'intitulé de la question 1, les id et nom des 3 catégories, le reste et toutes les catégories
	function firstQuestion() {
		// On récupére toutes les catégories
		$categories = getCategories();
		// On en tire 3 au hasard
		$random_categorie=array_rand($categories,3);
		// Intitulé de la question
		$question = "Avant de commencer, quel type de wallpaper voudrais-tu ?";
		// Reponses possibles (string)
		$reponses = array(
			$categories[$random_categorie[0]]['nom'],
			$categories[$random_categorie[1]]['nom'],
			$categories[$random_categorie[2]]['nom'],
			"Rien de tout ça",
			"Surprend moi !"
		);
		// On conserve uniquement les id des catégories dans un array
		$categories_id = array();
		foreach($categories as $categories)
		{
			array_push($categories_id, $categories['id']);
		}
		// On stocke toutes les catégories sauf les 3 tirées au hasard
		$other = $categories_id;
		for ($i = 2; $i >= 0; $i--)
		{
			array_splice($other, $random_categorie[$i], 1);
		}
		// Values possible (on fait +1 car ça commence à 0 et les id commencent à 1)
		$values = array(
			$random_categorie[0]+1,
			$random_categorie[1]+1,
			$random_categorie[2]+1,
			$other,
			$categories_id
		);
		$firstQuestion = array($question, $reponses, $values);
		return $firstQuestion;
	}

	function answerFirstQuestion($value) {
		return $value;
	}
	
	function updateImportance($importance)
	{
	}
	
	/* GESTION DES QUESTIONS SUIVANTES QUESTION */
	
	// Renvoie 2 questions en fonction de la categorie et de l'importance
	function nextQuestion($categories, $importance)
	{
		$bdd = getBdd();
		// On select les questions qui ont les categories choisies et l'importance demandée
		$sql = "SELECT DISTINCT id, nb_apparition AS nb_a, q_longue FROM question AS q 
				INNER JOIN categorie_question AS c_q 
				ON q.id = c_q.question_id
				WHERE q.importance >= :importance AND ";
		// S'l y a plusieurs catégories
		if (gettype($categories) == "array")
		{
			foreach($categories as $categories) {
				$sql .= "c_q.categorie_id = ".$categories." OR ";
			}
			$sql = substr($sql, 0, -4);	// Pour enlever le dernier " OR "
		}
		else
		{
			$sql .= "c_q.categorie_id = ".$categories."";
		}
		$question = $bdd->prepare($sql);
		$question->bindParam(':importance', $importance);
		$question->execute();
		$selection = $question->fetchAll();
		$nb_q = count($selection);
		// S'il y a plus de 3 questions selectionnées, on en tire 3 au hasard
		if ($nb_q > 3)
		{
			$random_question=array_rand($selection,3);
		}
		else {
			$random_question=array(0,1,2);
		}
		$selected = array();
		$questions = array();
		$id = array();
		for ($i = 0; $i < $nb_q; $i++)
		{
			array_push($selected, $selection[$random_question[$i]]);
			array_push($questions, $selection[$i]['q_longue']);
			array_push($id, $selection[$i]['id']);
		}
		asort($selected);
		print_r($selected);
		$nextQuestion = array($questions, $id);
		return $nextQuestion;
	}
?>