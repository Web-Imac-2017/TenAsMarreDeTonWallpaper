<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODEL_DIR . 'algo.php';

class AlgoController extends Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function answerFirstQuestion() {
		$algo = new Algo();
		
		// Si on vient de répondre à la première question
		if(isset($_POST['sub1']) && $_SESSION['num_question'] == 1)
		{
			// Si on a choisi une catégorie
			if(isset($_POST['categorie']) || isset($_SESSION['categories']))
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
				// On arrête
				$_SESSION['continue'] = false;
				//echo "Merci de choisir une réponse";
			}
		}
	}
	
	public function checkContinue() {
		$algo = new Algo();
		
		// Si on a répondu à une question autre que la première, on vérifie les conditions d'arrêts
		if(isset($_POST['sub'.$_SESSION['num_question']]) && $_SESSION['num_question']>1)
		{
			// On envoie la réponse choisie et on test si on peut continuer ou pas
			$resultat = $algo->checkContinue($_POST['reponse']);
		}
		echo json_encode($resultat);
	}
}