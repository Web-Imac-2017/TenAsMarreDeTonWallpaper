<?php
session_start();

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
		$i;
		$minWPP = 3;
		$maxQuestion = 5;
		if (! isset($_SESSION['num_question'])) $_SESSION['num_question'] = 1;
		if (! isset($_SESSION['importance'])) $_SESSION['importance'] = 50;
		if (! isset($_SESSION['requete'])) $_SESSION['requete'] = "";
		if (! isset($_SESSION['continue'])) $_SESSION['continue'] = true;
		if($_SESSION['num_question']==1 && !(isset($_POST['sub1'])))
		{
			$_SESSION['firstQuestion'] = firstQuestion();
	?>
        <fieldset>
            <legend>QUESTION 1</legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $_SESSION['firstQuestion']['question']; ?></label></td>
						<td>
						  <input type="radio" name="categorie" value="0"> <?php echo $_SESSION['firstQuestion']['reponses'][0]; ?>
						  <input type="radio" name="categorie" value="1"> <?php echo $_SESSION['firstQuestion']['reponses'][1]; ?>
						  <input type="radio" name="categorie" value="2"> <?php echo $_SESSION['firstQuestion']['reponses'][2]; ?>
						  <input type="radio" name="categorie" value="3"> <?php echo $_SESSION['firstQuestion']['reponses'][3]; ?>
						  <input type="radio" name="categorie" value="4"> <?php echo $_SESSION['firstQuestion']['reponses'][4]; ?>
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
		
		// Si on a répondu à une question autre que la première
		if(isset($_POST['sub'.$_SESSION['num_question']]) && $_SESSION['num_question']>1)
		{
			// On récupére la valeur de la réponse
			$_SESSION['reponse'] = $_POST['reponse'];
			
			// On compte combien de wpp on trouve et on met à jour la requete
			$wppLeft = answerQuestion($_SESSION['nextQuestion']['id'][0],$_SESSION['reponse'],$_SESSION['requete']);
			//print_r($wppLeft);
			// Si on trouve moins de $minWPP wpp, on arrête
			if($wppLeft['nb_wpp_left']<$minWPP || $_SESSION['num_question'] == $maxQuestion)
			{
				$_SESSION['continue'] = false;
				echo "Il n'y a plus que ".$wppLeft['nb_wpp_left']." wallpapers qui correspondent à vos critères";
				$wallpapers = stopGame($wppLeft['id']);
				// On affiche les wallpapers trouvés
				for ($i = 0; $i < $wppLeft['nb_wpp_left']; $i++)
				{
					echo "<br/> <a href='".$wallpapers[$i]['url']."' download='".$wallpapers[$i]['nom']."'> <img src='".$wallpapers[$i]['url_thumb']."' width='300' height ='180'> </a>";	
				}
			}
			// Sinon on passe à la question suivante
			else {
				// On met à jour l'importance
				$_SESSION['importance'] = updateImportance($_SESSION['importance']);
				
				// On prépare la prochaine question
				$_SESSION['nextQuestion'] = nextQuestion($_SESSION['categories'], $_SESSION['importance']);
				
				// On met à jour la requete
				$_SESSION['requete'] = $wppLeft['requete'];
				
				// On check si on peut trouver des wpp avec la prochaine question
				$checkQuestion = checkQuestion($_SESSION['nextQuestion']['id'][0], $_SESSION['requete']);
				print_r($checkQuestion);
				// Si on peut pas on arrête
				if($checkQuestion['continue']==false)
				{
					$_SESSION['continue'] = false;
					echo "La question suivante ne permet pas de trouver assez de wallpapers";
					$wallpapers = stopGame($wppLeft['id']);
					// On affiche les wallpapers trouvés
					for ($i = 0; $i < $wppLeft['nb_wpp_left']; $i++)
					{
						echo "<br/> <a href='".$wallpapers[$i]['url']."' download='".$wallpapers[$i]['nom']."'> <img src='".$wallpapers[$i]['url_thumb']."' width='300' height ='180'> </a>";	
					}
				}
			}
		}

		// Si on a répondu au formulaire précédent et qu'on peut continuer
		if(isset($_POST['sub'.$_SESSION['num_question']]) && $_SESSION['continue'] == true)
		{ 
			// Si on a choisi une catégorie
			if(isset($_POST['categorie']) || isset($_SESSION['categories']))
			{	
				// Après avoir répondu à la premiere question, on stock les catégories choisies
				if ($_SESSION['num_question'] == 1)
				{
					$_SESSION['categories'] = $_SESSION['firstQuestion']['values'][$_POST['categorie']];
				}
				// On incrémente le numero de la question
				$_SESSION['num_question']++;
				// On selectionne la question suivante
				if ($_SESSION['num_question'] == 2)
					$_SESSION['nextQuestion'] = nextQuestion($_SESSION['categories'], $_SESSION['importance']);
				// On met à jour le nombre d'apparition de la question
				updateNb_aQ($_SESSION['nextQuestion']['id'][0],$_SESSION['nextQuestion']['nb_a'][0]);
	?>
	    <fieldset>
            <legend>QUESTION <?php echo $_SESSION['num_question']; ?></legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $_SESSION['nextQuestion']['questions'][0]; ?></label></td>
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
			// Sinon ça veut dire qu'on a pas répondu à la 1ère question
			else
			{
				echo "Merci de choisir une réponse";
			}
		}
	?>
		<br/>
		<fieldset>
            <legend>REDEMARRER LA SESSION</legend>
			<form action="" method="post">
                <input type="submit" value="Restart" name="restart" />
            </form>
		</fieldset>
	<?php
		if(isset($_POST['restart']))
		{
			// remove all session variables
			session_unset();

			// destroy the session
			session_destroy();
		}
	?>
    </body>
</html>