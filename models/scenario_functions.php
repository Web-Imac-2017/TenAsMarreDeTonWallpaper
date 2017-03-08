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
			"Surprenez-moi !"
		);
		// On conserve uniquement les id des catégories dans un array
		$categories_id = array();
		foreach($categories as $categories)
		{
			array_push($categories_id, $categories['id']);
		}
		// On stocke toutes les catégories sauf les 2 tirées au hasard
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
		shuffle($other);
		$firstQuestion = array("question"=>$question, "reponses"=>$reponses, "values"=>$values);
		return $firstQuestion;
	}
	
	/* GESTION DES QUESTIONS SUIVANTES */
	
	// Renvoie 2 questions en fonction de la categorie et de l'importance
	function nextQuestion()
	{		
		// S'il y a plusieurs catégories, on en sélectionne 1 au hasard
		if (gettype($_SESSION['categories']) == "array")
		{
			$categorie = $_SESSION['categories'][array_rand($_SESSION['categories'],1)];
		}
		else
		{
			$categorie = $_SESSION['categories'];
		}
		// On fixe une marge pour l'importance
		$importanceUp = $_SESSION['importance']+5;
		
		// On effectue la requete SQL qui récupére la question
		$bdd = getBdd();
		// On select les questions qui ont les categories choisies et l'importance demandée
		$sql = "SELECT DISTINCT id, nb_apparition AS nb_a, q_longue FROM question AS q 
				INNER JOIN categorie_question AS c_q 
				ON q.id = c_q.question_id
				WHERE (q.importance >= :importance AND q.importance < ".$importanceUp.")
				AND (c_q.categorie_id = :categorie)";
		$req = $bdd->prepare($sql);
		$req->bindParam(':importance', $_SESSION['importance']);
		$req->bindParam(':categorie', $categorie);
		$req->execute();
		// On récupére le résultat dans un tableau $selection
		$selection = $req->fetchAll(PDO::FETCH_ASSOC);
		// On compte combien de question on a trouvé
		$nb_q = count($selection);
		
		// Si on trouve au moins une question on en tire une au hasard et on la renvoie
		if ($nb_q > 0)
		{
			// Si on en trouve seulement une
			if ($nb_q == 1)
			{
				$random_question=array_rand($selection,1);
				$selected = $selection[$random_question];
			}
			// Si on en trouve plus d'une
			else
			{
				// On en tire 2 au hasard
				$random_question=array_rand($selection,2);
				// On selectionne la question qui est apparue le moins de fois
				if ($selection[$random_question[0]]['nb_a'] <= $selection[$random_question[1]]['nb_a'])
				{
					$selected = $selection[$random_question[0]];
				}
				else
				{
					$selected = $selection[$random_question[1]];	
				}
			}
			$nextQuestion = array('nb_q'=>$nb_q, 'question'=>$selected);
			// Si c'est la 2ème question, on update le nombre d'apparition ici, sinon ça se fera dans une autre fonction
			if ($_SESSION['num_question'] == 2)
			{
				updateNb_aQ($selected['id'],$selected['nb_a']);
			}
		}
		// S'il n'y a aucune question qui correspond à la requête
		else
		{
			$nextQuestion = array('nb_q'=>0,'question'=>NULL);
			$_SESSION['continue'] = false;
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
			// Ou alors faire une série de AND ... AND ...  (plus performant)
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
	
	// Renvoie les informations des wpp trouvés
	function stopGame($wpp_id)
	{		
		$i = 0;
		$selection = array();
		$bdd = getBdd();
		foreach ($wpp_id as $id)
		{
			// On récupére les informations
			$sql = 'SELECT * FROM wallpaper WHERE id=:id';
			$req = $bdd->prepare($sql);
			$req->bindParam(':id', $id);
			$req->execute();
			$resultat = $req->fetchAll(PDO::FETCH_ASSOC);
			array_push($selection, $resultat[0]);
			
			// On met à jour le nombre d'apparition des wpp
			$nb_apparition = $selection[$i]['nb_apparition']+1;
			$sql2 = 'UPDATE wallpaper SET nb_apparition=:nb_apparition WHERE id=:id';
			$req2 = $bdd->prepare($sql2);
			$req2->bindParam(':nb_apparition', $nb_apparition);
			$req2->bindParam(':id', $i);
			$req2->execute();
			
			$i++;
		}
		// On rend la selection aléatoire
		shuffle($selection);
		// On renvoie les wpp sélectionnés
		return $selection;
	}
	
	// Check si la question peut fournir des wpp (appelle la fonction nextQuestion et les fonctions de test)
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
	
	// Incremente l'importance (mettre -1 pour -5 | 1 pour 5)
	function updateImportance($sens)
	{
		$_SESSION['importance']=$_SESSION['importance']+(5*$sens);
	}
	
	// Retourne la question actuelle
	function getCurrentQuestion()
	{
		return $_SESSION['question'][$_SESSION['num_question']];
	}
	
	// Check les conditions d'arrêts, dis si on peut continuer ou pas
	// Retourne la nouvelle question et met à jour les variables (num_question, importance, requete)
	function checkContinue($reponse)
	{
		// Variables retournées avec initialisation si jamais elles ne sont pas modifiées
		$nb_wpp_left = 0;
		$wallpapers = array();
		
		// On récupére la valeur de la réponse
		$_SESSION['reponse'] = $reponse;

		// On compte combien de wpp on trouve et on met à jour la requete
		$wppLeft = answerQuestion($_SESSION['question'][$_SESSION['num_question']-1]['question']['id'],$_SESSION['reponse'],$_SESSION['requete'][$_SESSION['num_question']-2]);
		
		// Si on trouve moins de $minWPP wpp, on arrête
		if($wppLeft['nb_wpp_left']<=$_SESSION['minWPP'] || $_SESSION['num_question'] == $_SESSION['maxQuestion'])
		{
			$_SESSION['continue'] = false;
			//echo "Il n'y a plus que ".$wppLeft['nb_wpp_left']." wallpapers qui correspondent à vos critères";
			// On récupére les infos de tous les wpp qu'il reste
			$wallpapers = stopGame($wppLeft['id']);
			$nb_wpp_left = $wppLeft['nb_wpp_left'];
		}
		
		// Sinon on passe à la question suivante
		else
		{
			// On met à jour l'importance
			updateImportance(-1);
						
			// Si on n'est pas dans le cas "undo", on cherche une nouvelle question (sinon elle a déjà été trouvée)
			if ($_SESSION['lock'][$_SESSION['num_question']]==false)
			{	
				$_SESSION['lock'][$_SESSION['num_question']] = true;
				// On prépare la prochaine question
				$_SESSION['question'][$_SESSION['num_question']] = nextQuestion();
			}
				
			// On met à jour la requete
			$_SESSION['requete'][$_SESSION['num_question']-1] = $wppLeft['requete'];
				
			// On check si on peut trouver des wpp avec la prochaine question
			$checkQuestion = checkQuestion($_SESSION['question'][$_SESSION['num_question']]['question']['id'], $_SESSION['requete'][$_SESSION['num_question']-1]);

			// Si on ne peut pas, on arrête
			if($checkQuestion['continue']==false)
			{
				$_SESSION['continue'] = false;
				//echo "La question suivante ne permet pas de trouver assez de wallpapers";
				$wallpapers = stopGame($wppLeft['id']);
				// S'il y en a moins que le minimum, la limite = le nombre de wpp qu'il reste
				if($wppLeft['nb_wpp_left']<$_SESSION['minWPP'])
				{
					$nb_wpp_left = $wppLeft['nb_wpp_left'];
				}
				// S'il y en a plus que le minimum, on affiche uniquement le minimum de wpp
				else
				{
					$nb_wpp_left = $_SESSION['minWPP'];
				}
			}
			// Sinon on peut continuer, on incremente le nb_apparition de la question
			else 
			{
				// On incrémente le numero de la question
				$_SESSION['num_question']++;
				$_SESSION['continue'] = true;
				updateNb_aQ($_SESSION['question'][$_SESSION['num_question']-1]['question']['id'],$_SESSION['question'][$_SESSION['num_question']-1]['question']['nb_a']);
			}
		}
		$resultat = array('nb_wpp_left' => $nb_wpp_left, 'wallpapers' => $wallpapers);
		return $resultat;
	}
	
	// Corrige la question précédente
	function undoQuestion()
	{
		if ($_SESSION['continue'] == true)
		{
			$_SESSION['num_question']--;
		}
		$_SESSION['continue'] = true;
		if ($_SESSION['num_question'] > 1)
		{
			updateImportance(1);
		}
		return true;
	}
	
	// RENVOYER UN RETURN CODE
	
	// Met à jour le nombre de téléchargements du wallpaper
	function updateDL($wallpaper_id, $nb_telechargement)
	{
		$nb_telechargement++;
		$bdd = getBdd();
		$sql = 'UPDATE wallpaper SET nb_telechargement=:nb_telechargement WHERE id=:wallpaper_id';
		$req = $bdd->prepare($sql);
		$req->bindParam(':nb_telechargement', $nb_telechargement);
		$req->bindParam(':wallpaper_id', $wallpaper_id);
		$req->execute();
	}
?>