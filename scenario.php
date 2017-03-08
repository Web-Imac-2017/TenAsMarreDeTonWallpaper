<?php
session_start();

// Si on a redémarrer la session (il faut actualiser la page pour que ça se mette à jour)
if(isset($_POST['restart']))
{
	// remove all session variables
	session_unset();

	// destroy the session
	session_destroy();
	
	// Restart a session
	session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">

        <title>DISCUSSION AVEC MR.WALLMATCH</title>

    </head>
    <body>
	<?php
		require('models/categorie_question.php');
		require('models/scenario_functions.php');
		
		// Declarations des variables
		
		// Pour gérer les boucles for
		$i;
		// Pour stocker le résultat
		$resultat = array('nb_wpp_left'=>0, 'wallpapers'=>array());
		// Si on trouve moins de wpp que ce nombre, on arrête
		if (! isset($_SESSION['minWPP'])) $_SESSION['minWPP'] = 3;
		// Si on atteint ce nombre de questions, on arrête
		if (! isset($_SESSION['maxQuestion'])) $_SESSION['maxQuestion'] = 5;
		// Le numéro de la question actuelle
		if (! isset($_SESSION['num_question'])) $_SESSION['num_question'] = 1;
		// L'importance qui sera de plus en plus petite
		if (! isset($_SESSION['importance'])) $_SESSION['importance'] = 50;
		// Un string qui contient les différents SELECT après chaque question
		if (! isset($_SESSION['requete'])) $_SESSION['requete'] = array("");
		// Si ce booléan est 'false', on s'arrête
		if (! isset($_SESSION['continue'])) $_SESSION['continue'] = false;
		// Stock les questions qui sont passées
		if (! isset($_SESSION['question'])) $_SESSION['question'] = array();
		// Empêche les fonctions d'être appelée à nouveau (pour éviter d'avoir une nouvelle question après un UNDO)
		if (! isset($_SESSION['lock'])) 
		{
			for($i = 0; $i < $_SESSION['maxQuestion']; $i++)
			{
				$_SESSION['lock'][$i] = false;
			}
		}
		
		// Si on a voulu corriger la question précédente, on revient à la question précédente
		if(isset($_POST['undo']))
		{
			undoQuestion();
		}

		// Affichage 1ère question
		if(!(isset($_POST['sub1'])) && $_SESSION['num_question']==1)
		{
			// Ici on utilise 'undo' pour empêcher de resélectionner des catégories si on décide de corriger
			if ($_SESSION['lock'][0]==false)
			{
				$_SESSION['lock'][0] = true;
				$_SESSION['firstQuestion'] = firstQuestion();
				$_SESSION['question'][0] = $_SESSION['firstQuestion'];
			}
	?>
        <fieldset>
            <legend>QUESTION 1</legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $_SESSION['question'][0]['question']; ?></label></td>
						<td>
						  <input type="radio" name="categorie" value="0"> <?php echo $_SESSION['question'][0]['reponses'][0]; ?>
						  <input type="radio" name="categorie" value="1"> <?php echo $_SESSION['question'][0]['reponses'][1]; ?>
						  <input type="radio" name="categorie" value="2"> <?php echo $_SESSION['question'][0]['reponses'][2]; ?>
						  <input type="radio" name="categorie" value="3"> <?php echo $_SESSION['question'][0]['reponses'][3]; ?>
						  <input type="radio" name="categorie" value="4"> <?php echo $_SESSION['question'][0]['reponses'][4]; ?>
						</td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Valider" name="sub1" /></td>
                    </tr>
                </table>
            </form>
		</fieldset>
	<?php
		}

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
					$nextQuestion = nextQuestion();
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
				echo "Merci de choisir une réponse";
			}
		}

		// Si on a répondu à une question autre que la première, on vérifie les conditions d'arrêts
		if(isset($_POST['sub'.$_SESSION['num_question']]) && $_SESSION['num_question']>1)
		{
			// On envoie la réponse choisie et on test si on peut continuer ou pas
			$resultat = checkContinue($_POST['reponse']);
		}

		// Si on peut continuer et qu'on a trouvé une question
		if($_SESSION['continue'] == true && isset($_SESSION['question'][$_SESSION['num_question']-1]) && $_SESSION['num_question'] > 1)
		{ 
	?>
	    <fieldset>
            <legend>QUESTION <?php echo $_SESSION['num_question']; ?></legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $_SESSION['question'][$_SESSION['num_question']-1]['question']['q_longue']; ?></label></td>
						<td>
						  <input type="radio" name="reponse" value="0"> Non
						  <input type="radio" name="reponse" value="25"> Probablement pas
						  <input type="radio" name="reponse" value="50"> Peut-être
						  <input type="radio" name="reponse" value="75"> Probablement oui
						  <input type="radio" name="reponse" value="100"> Oui
						</td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Valider" name="sub<?php echo $_SESSION['num_question']; ?>" /></td>
                    </tr>
                </table>
            </form>
		</fieldset>
	<?php
		}
		// Sinon, si on ne peut pas continuer, on affiche les résultats
		else if($_SESSION['continue'] == false)
		{
			$wallpapers = $resultat['wallpapers'];
			// Pour chaque wallpaper on affiche les infos
			for ($i = 0; $i < $resultat['nb_wpp_left']; $i++)
			{
				echo "<br/> <a href='".$wallpapers[$i]['url']."' download='".$wallpapers[$i]['nom']."'> <img src='".$wallpapers[$i]['url_thumb']."' width='300' height ='180'> </a>";	
			}
		}
		// On peut corriger uniquement après avoir répondu à la question 2
		if ($_SESSION['num_question'] > 1)
		{
	?>
		<br/>
		<fieldset>
            <legend>CORRIGER LA QUESTION</legend>
			<form action="" method="post">
                <input type="submit" value="Corriger" name="undo" />
            </form>
		</fieldset>
	<?php
		}
	?>
		<br/>
		<fieldset>
            <legend>REDEMARRER LA SESSION</legend>
			<form action="" method="post">
                <input type="submit" value="Restart" name="restart" />
            </form>
		</fieldset>
    </body>
</html>