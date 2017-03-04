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
		if($_SESSION['num_question']==1 && !(isset($_POST['sub'])))
		{
			$_SESSION['firstQuestion'] = firstQuestion();
	?>
        <fieldset>
            <legend>QUESTION 1</legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $_SESSION['firstQuestion'][0]; ?></label></td>
						<td>
						  <input type="radio" name="categorie" value="0"> <?php echo $_SESSION['firstQuestion'][1][0]; ?>
						  <input type="radio" name="categorie" value="1"> <?php echo $_SESSION['firstQuestion'][1][1]; ?>
						  <input type="radio" name="categorie" value="2"> <?php echo $_SESSION['firstQuestion'][1][2]; ?>
						  <input type="radio" name="categorie" value="3"> <?php echo $_SESSION['firstQuestion'][1][3]; ?>
						  <input type="radio" name="categorie" value="4"> <?php echo $_SESSION['firstQuestion'][1][4]; ?>
						</td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Valider" name="sub" /></td>
                    </tr>
                </table>
            </form>
		</fieldset>	
	<?php
		}
		if(isset($_POST['sub']))
		{
			$_SESSION['categories'] = $_SESSION['firstQuestion'][2][$_POST['categorie']];
			$_SESSION['importance'] = 50;
			$_SESSION['num_question']++;
			$nextQuestion = nextQuestion($_SESSION['categories'], $_SESSION['importance']);
	?>
	    <fieldset>
            <legend>QUESTION <?php echo $_SESSION['num_question']; ?></legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label><?php echo $nextQuestion[0][0]; ?></label></td>
						<td>
						  <input type="radio" name="categorie" value="0"> Non
						  <input type="radio" name="categorie" value="25"> Probablement pas
						  <input type="radio" name="categorie" value="50"> Peut-être
						  <input type="radio" name="categorie" value="75"> Probablement oui
						  <input type="radio" name="categorie" value="100"> Oui
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
	?>
    </body>
</html>