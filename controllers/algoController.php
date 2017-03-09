<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'algo.php';

class AlgoController extends Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function getFirstQuestion($restart = NULL)
	{
		$algo = new Algo();
		
		if ($_SESSION['lock'][0]==false)
		{
			$_SESSION['lock'][0] = true;
			$_SESSION['firstQuestion'] = firstQuestion();
			$_SESSION['question'][0] = $_SESSION['firstQuestion'];
		}
		
		if ($restart == 1)
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "returnMessage" => "Partie recommencée"];
		}
		else if ($restart == 2)
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "returnMessage" => "On retourne à la question 1"];
		}
		else
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "returnMessage" => "On affiche la question 1"];
		}
		echo json_encode($data);
	}
	
	public function getNextQuestion() {
		$algo = new Algo();
		
		// Si on vient de répondre à la première question
		if($_SESSION['num_question'] == 1)
		{
			// Si on a choisi une catégorie
			if(isset($_POST['categorie'])) // val : 0, 1 2 3 ou 4
			{	
				// On stocke les catégories choisies
				$_SESSION['categories'] = $_SESSION['question'][0]['values'][$_POST['categorie']];
				
				// On incrémente le numero de la question
				$_SESSION['num_question']++;
				
				if ($_SESSION['lock'][1]==false)
				{
					$_SESSION['lock'][1] = true;
					// On appelle la 2eme question (les autres questions seront appellées au-dessus après vérifications)
					$nextQuestion = $algo->nextQuestion();
					$_SESSION['question'][1] = $nextQuestion['question'];
				}
				// On peut continuer
				$_SESSION['continue'] = true;
				
			}
			// Sinon ça veut dire qu'on a pas répondu à la 1ère question
			else
			{
				// METTRE MESSAGE D'ERREUR
				// On arrête
				$_SESSION['continue'] = false;
				//echo "Merci de choisir une réponse";
			}
			
			// echo json_encode($data);
		}
		else if($_SESSION['num_question'] > 1) {
			if (isset($_POST['response']) && !empty($_POST['response'])) {
			
				// On envoie la réponse choisie et on test si on peut continuer ou pas
				$_SESSION['$resultat'] = $algo->checkContinue($_POST['reponse']);
			}
			// renvoyer les questions
			else {
				// MESSAGES D'ERREUR
			}
			
			echo json_encode($data);
		}
	}
	
	
	
	public function restart()
	{		
		// Déclarations des variables
		
		$_SESSION['$resultat'] = array('nb_wpp_left'=>0, 'wallpapers'=>array());
		// Le numéro de la question actuelle
		$_SESSION['num_question'] = 1;
		// L'importance qui sera de plus en plus petite
		$_SESSION['importance'] = 50;
		// Un string qui contient les différents SELECT après chaque question
		$_SESSION['requete'] = array("");
		// Si ce booléan est 'false', on s'arrête
		$_SESSION['continue'] = false;
		// Stock les questions qui sont passées
		$_SESSION['question'] = array();
		// Empêche les fonctions d'être appelée à nouveau (pour éviter d'avoir une nouvelle question après un UNDO)
		for($i = 0; $i < $_SESSION['maxQuestion']; $i++)
		{
			$_SESSION['lock'][$i] = false;
		}
		
		getFirstQuestion(1);
	}
	
	public function undo()
	{
		$algo = new Algo();
		
		// Si on a voulu corriger la question précédente, on revient à la question précédente
		if ($_SESSION['continue'] == true)
		{
			$_SESSION['num_question']--;
		}
		$_SESSION['continue'] = true;
		if ($_SESSION['num_question'] > 1)
		{
			$algo->updateImportance(1);
		}
		// si question 1
		if($_SESSION['num_question'] == 2)
		{
			getFirstQuestion(2);
		}
		// sinon
		else
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][$_SESSION['num_question']-1], "returnMessage" => "On revient à la question ".$_SESSION['num_question']];
			echo json_encode($data);
		}
	}
}