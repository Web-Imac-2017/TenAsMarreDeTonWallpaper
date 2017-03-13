<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'algo.php';
require_once MODEL_DIR . 'wallpaper.php';

class AlgoController extends Controller {

	public function __construct(){
		parent::__construct();
	}
	
	/* 	Les fonctions renvoient :										*
	*	"returnCode", 1 (success) sinon echec 							*
	* 	'data', qui contient la question actuelle et le nombre			*
	*	 de wpp restants pour numero_question > 2						*
	*	OU le nombre de wpp_restants et les infos de ces wpp si			*
	*	returnCode == 1 et continue == false							*
	*	"continue" qui indique si on peut continuer(true) ou pas(false)	*
	*	"returnMessage" qui dit ce qu'il s'est passé					*/
	
	// Renvoie la data sous forme de : $_SESSION['question']['q_longue', 'reponses', 'values', 'numero']
	public function getFirstQuestion($restart = NULL)
	{
		$algo = new Algo();

		if ($_SESSION['lock'][0]==false)
		{
			$_SESSION['lock'][0] = true;
			$_SESSION['firstQuestion'] = $algo->firstQuestion();
			$_SESSION['question'][0] = $_SESSION['firstQuestion'];
		}

		if ($restart == 1)
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "continue" => true, "returnMessage" => "Partie recommencée"];
		}
		else if ($restart == 2)
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "continue" => true, "returnMessage" => "On retourne à la question 1"];
		}
		else
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][0], "continue" => true, "returnMessage" => "On affiche la question 1"];
		}

		echo json_encode($data);
	}
	
	
	// Renvoie la data sous forme de : $_SESSION['question']['question', 'reponses', 'values', 'numero']
	// Le 2eme 'question' est un array qui contient ['id', 'nb_a', 'q_longue']
	// $reponse = $_POST['reponse']
	public function getNextQuestion($reponse) {
		$algo = new Algo();

		// Si on vient de répondre à la première question
		if($_SESSION['num_question'] == 1)
		{
			// Si on a choisi une catégorie
			if(isset($reponse)) // val : 0, 1 2 3 ou 4
			{	
				// On stocke les catégories choisies
				$_SESSION['categories'] = $_SESSION['question'][0]['values'][$reponse];
				
				// On incrémente le numero de la question
				$_SESSION['num_question']++;
				
				if ($_SESSION['lock'][1]==false)
				{
					$_SESSION['lock'][1] = true;
					// On appelle la 2eme question (les autres questions seront appellées au-dessus après vérifications)
					$next = $algo->nextQuestion();
					$tmp = array('q_longue'=>$next['question']['question']['q_longue'], 'reponses'=>$next['question']['reponses'],'numero'=>$next['question']['numero'], 'id'=>$next['question']['question']['id'], 'nb_a'=>$next['question']['question']['nb_a']);
					$_SESSION['question'][1] = $tmp;
				}
				// On peut continuer
				$_SESSION['continue'] = true;
				
				$data = ["returnCode" => 1, 'data' => $_SESSION['question'][1], "continue" => true, "returnMessage" => "On affiche la question 2"];
			}
			// Sinon ça veut dire qu'on a pas répondu à la 1ère question
			else
			{
				$_SESSION['continue'] = false;
				$data = ["returnCode" => 0, 'data' => $_SESSION['question'][0], "continue" => false, "returnMessage" => "Vous n'avez pas répondu à la 1ère question"];
			}
			
			echo json_encode($data);
		}
		else if($_SESSION['num_question'] > 1) {
			if (isset($reponse)) {
				// On envoie la réponse choisie et on test si on peut continuer ou pas
				$_SESSION['resultat'] = $algo->checkContinue($reponse);
				// Si on peut continuer
				if($_SESSION['resultat']['returnCode'] == 1)
				{
					$data = ["returnCode" => 1, 'data' => $_SESSION['question'][$_SESSION['num_question']-1], "continue" => true, "returnMessage" => "Vous pouvez continuer"];
				}
				// Si on a fini la partie
				else
				{
					$data = ["returnCode" => 1, 'data' => $_SESSION['resultat'], "continue" => false, "returnMessage" => "Vous avez trouvé des wallpapers"];
				}
			}
			// renvoyer les questions
			else {
				$data = ["returnCode" => 0, 'data' => $_SESSION['question'][$_SESSION['num_question']-1], "continue" => false, "returnMessage" => "Vous n'avez pas répondu à la question".$_SESSION['num_question']];
			}
			
			echo json_encode($data);
		}
	}
	
	public function currentQuestion()
	{
		// Si on est arrivé à la fin, on recommence une partie
		if ($_SESSION['continue'] == false && $_SESSION['num_question'] > 1)
		{
			$this->restart();
		}
		// Si c'est la 1ère question, on appelle getFirstQuestion qui va renvoyer la 1ère question (et la générer si pas fait)
		if($_SESSION['num_question'] == 1)
		{
			$this->getFirstQuestion();
		}
		// Sinon on retourne la question actuelle
		else
		{
			if (isset($_SESSION['question'][$_SESSION['num_question']-1]))
			{
				$data = ["returnCode" => 1, 'data' => $_SESSION['question'][$_SESSION['num_question']-1], "continue" => true, "returnMessage" => "Question ".$_SESSION['num_question']." envoyee"];
				echo json_encode($data);
			}
			else
			{
				$this->getNextQuestion(2);
			}
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
		
		$this->getFirstQuestion(1);
	}
	
	public function undo()
	{
		$algo = new Algo();
		
		// Si on a voulu corriger la question précédente, on revient à la question précédente
		if ($_SESSION['continue'] == true && $_SESSION['num_question']>1)
		{
			$_SESSION['num_question']--;
		}
		$_SESSION['continue'] = true;
		if ($_SESSION['num_question'] > 1)
		{
			$algo->updateImportance(1);
		}
		// si question on était à la question 2 au moment de l'appel
		if($_SESSION['num_question'] == 1)
		{
			$this->getFirstQuestion(2);
		}
		// sinon
		else
		{
			$data = ["returnCode" => 1, 'data' => $_SESSION['question'][$_SESSION['num_question']-1], "continue" => true, "returnMessage" => "On revient à la question ".$_SESSION['num_question']];
			echo json_encode($data);
		}
	}
	
	public function updateDL($wallpaper_id)
	{
		$wallpaper = new Wallpaper();
		if(isset($wallpaper_id))
		{
			$wallpaper->incrementer_nb_telechargement($wallpaper_id);
			$data = ["returnCode" => 1, 'data' => '', "continue" => false, "returnMessage" => "Le wallpaper a bien été téléchargé"];
		}
		else
		{
			$data = ["returnCode" => 0, 'data' => '', "continue" => false, "returnMessage" => "Aucun id de wallpaper sélectionné"];
		}
		echo json_encode($data);
	}
}