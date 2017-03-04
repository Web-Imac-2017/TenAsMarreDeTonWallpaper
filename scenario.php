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
		$_SESSION['num_question'] = 1;
		$_SESSION['importance'] = 50;
		$_SESSION['requete'] = "";
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
		if(isset($_POST['sub'.$_SESSION['num_question']]))
		{
			$_SESSION['categories'] = $_SESSION['firstQuestion']['values'][$_POST['categorie']];
			$_SESSION['num_question']++;
			$_SESSION['nextQuestion'] = nextQuestion($_SESSION['categories'], $_SESSION['importance']);
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
		//if(isset($_POST['sub'.$_SESSION['num_question']]))
		if(isset($_POST['sub2']))
		{
			// On récupére la valeur de la réponse
			$_SESSION['reponse'] = $_POST['reponse'];
			
			// On compte combien de wpp on trouve et on met à jour la requete
			$wppLeft = answerQuestion($_SESSION['nextQuestion']['id'][0],$_SESSION['reponse'],$_SESSION['requete']);
			
			// Si on trouve moins de 10 wpp, on arrête
			if($wppLeft['nb_wpp_left']<10)
			{
				$wallpapers = stopGame($wppLeft['id']);
				echo "<a href='".$wallpapers[0]['url']."' download='".$wallpapers[0]['nom']."'>Télécharger l'image</a>";
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
				
				// Si on peut pas on arrête
				if($checkQuestion['continue']==false)
				{
					$wallpapers = stopGame($wppLeft['id']);
					echo "<a href='".$wallpapers[0]['url']."' download='".$wallpapers[0]['nom']."'>Télécharger l'image</a>";
				}
			}
		}
	?>
    </body>
</html>