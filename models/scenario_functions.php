<?php	
	/* GESTION DE LA PREMIERE QUESTION */
	
	// Renvoie trois catégories tirées au hasard, le reste, et toutes les catégories
	function firstQuestion() {
		$categories = getCategories();
		$random_categorie=array_rand($categories,3);
		$other = $categories;
		for ($i = 0; $i < 3; $i++)
		{
			array_splice($other, $random_categorie[$i], 1);
		}
		
		$values = array(
			$random_categorie[0],
			$random_categorie[1],
			$random_categorie[2],
			$other,
			$categories
		);
		return $values;
	}
?>