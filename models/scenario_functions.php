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
		$firstQuestion = array("question"=>$question, "reponses"=>$reponses, "values"=>$values);
		return $firstQuestion;
	}
	
	/* GESTION DES QUESTIONS SUIVANTES */
	
	// Renvoie 2 questions en fonction de la categorie et de l'importance
	function nextQuestion($categories, $importance)
	{
		// S'il y a plusieurs catégories, on en sélectionne 1 au hasard
		if (gettype($categories) == "array")
		{
			$categorie = $categories[array_rand($categories,1)];
		}
		else
		{
			$categorie = $categories;
		}
		$importanceUp = $importance+5;
		$bdd = getBdd();
		// On select les questions qui ont les categories choisies et l'importance demandée
		$sql = "SELECT DISTINCT id, nb_apparition AS nb_a, q_longue FROM question AS q 
				INNER JOIN categorie_question AS c_q 
				ON q.id = c_q.question_id
				WHERE (q.importance >= :importance AND q.importance < ".$importanceUp.")
				AND (c_q.categorie_id = :categorie)";
		$req = $bdd->prepare($sql);
		$req->bindParam(':importance', $importance);
		$req->bindParam(':categorie', $categorie);
		$req->execute();
		$selection = $req->fetchAll();
		$nb_q = count($selection);
		// Si on trouve au moins une question on stock les infos dans des tableaux
		if ($nb_q > 0)
		{
			$selected = array();
			$questions = array();
			$id = array();
			$nb_a = array();
			// S'il y a plus de 3 questions selectionnées, on en tire 3 au hasard
			if ($nb_q > 3)
			{
				$random_question=array_rand($selection,3);
				$nb = 3;
			}
			else {
				$random_question=array(0,1,2);
				$nb = $nb_q;
			}
			for ($i = 0; $i < $nb; $i++)
			{
				array_push($selected, $selection[$random_question[$i]]);
				array_push($questions, $selection[$i]['q_longue']);
				array_push($id, $selection[$i]['id']);
				array_push($nb_a, $selection[$i]['nb_a']);
			}
			$nextQuestion = array('nb_q'=>$nb_q, 'questions'=>$questions, 'id'=>$id, 'nb_a'=>$nb_a);
		}
		// S'il n'y a aucune question qui correspond à la requête
		else
		{
			$nextQuestion = array('nb_q'=>0,'questions'=>array("Aucune question"),'id'=>array(0),'nb_a'=>array(0));
		}

		return $nextQuestion;
	}
	
	// Met à jour le nombre d'apparition d'une question
	function updateNb_aQ($question_id, $nb_a)
	{
		$nb_a++;
		$bdd = getBdd();
		$sql = 'UPDATE question SET nb_apparition=:nb_a WHERE id=:question_id';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nb_a', $nb_a);
		$req->bindParam(':question_id', $question_id);
		$req->execute();
	}
	
	/* GESTION DES REPONSES */
	
	// Renvoie le nombre de wpp correspondant à la requete actuelle
	function answerQuestion($question_id, $reponse, $requete)
	{
		$bdd = getBdd();
		// On select les wpp qui correspondent aux reponses choisies de la question actuelle
		$sql = "SELECT DISTINCT wallpaper_id FROM reponse AS r 
				WHERE question_id =".$question_id."
				AND (val_min <=".$reponse." AND val_max>=".$reponse.")";
		// On utilise l'opérateur IN avec les anciens select s'il y en a déjà eu
		if ($requete)
		{
			$sql .= " AND wallpaper_id IN ( ".$requete." )";
		}
		$req = $bdd->prepare($sql);
		$req->execute();
		$selection = $req->fetchAll(PDO::FETCH_ASSOC);		
		$nb_wpp_left = count($selection);
		$id = array();
		foreach ($selection as $selection)
		{
			array_push($id, $selection['wallpaper_id']);
		}
		$wppLeft = array("nb_wpp_left"=>$nb_wpp_left, "id"=>$id, "requete"=>$sql);
		
		return $wppLeft;
	}
	
	/* GESTION DES CONDITIONS D'ARRET */
	
	// Renvoie le nombre de wpp correspondant à la requete actuelle
	function stopGame($wpp_id)
	{		
		$i = 0;
		$selection = array();
		$bdd = getBdd();
		foreach ($wpp_id as $id)
		{
			$sql = 'SELECT * FROM wallpaper WHERE id=:id';
			$req = $bdd->prepare($sql);
			$req->bindParam(':id', $id);
			$req->execute();
			$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
			array_push($selection, $resultat[0]);
			
			$nb_apparition = $selection[$i]['nb_apparition']+1;
			$sql2 = 'UPDATE wallpaper SET nb_apparition=:nb_apparition WHERE id=:id';
			$req2 = $bdd->prepare($sql2);
			$req2->bindParam(':nb_apparition', $nb_apparition);
			$req2->bindParam(':id', $id);
			$req2->execute();
			
			$i++;
		}
		
		return $selection;
	}
	
	// Check si la question peut fournir des wpp
	function checkQuestion($question_id, $requete)
	{		
		$wpp_left = array();
		$reponse;
		$nb_min = 1;
		// On recupere le nombre de wpp qu'on peut trouver pour chaque reponse fournie
		for ($reponse = 0; $reponse <= 100; $reponse+=25)
		{
			$wpp = answerQuestion($question_id, $reponse, $requete);
			array_push($wpp_left, $wpp['nb_wpp_left']);
		}
		print_r($wpp_left);
		// Si il y a suffisamment de wpp pour chaque reponse possible, on continue
		if ($wpp_left[0] >= $nb_min && $wpp_left[1] >= $nb_min && $wpp_left[2] >= $nb_min && $wpp_left[3] >= $nb_min && $wpp_left[4] >= $nb_min)
		{
			$continue = true;
		}
		// Sinon on arrete
		else
		{
			$continue = false;
		}
		$checkQuestion = array('wpp_left'=>$wpp_left, 'continue'=>$continue);
		return $checkQuestion;
	}
	
	// Incremente l'importance de -5
	function updateImportance($importance)
	{
		return $importance-5;
	}
?>