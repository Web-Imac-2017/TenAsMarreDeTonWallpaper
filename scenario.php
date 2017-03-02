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
		$values = firstQuestion();
	?>
        <fieldset>
            <legend>QUESTION 1</legend>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label>Avant de commencer, quelle type de wallpaper préféres-tu ?</label></td>
						<td>
						  <input type="radio" name="categorie" value="1"> <?php echo $values[4][$values[0]]['nom']; ?>
						  <input type="radio" name="categorie" value="2"> <?php echo $values[4][$values[1]]['nom']; ?>
						  <input type="radio" name="categorie" value="3"> <?php echo $values[4][$values[2]]['nom']; ?>
						  <input type="radio" name="categorie" value="4"> Rien de tout ça
						  <input type="radio" name="categorie" value="5"> Surprend moi !
						</td>
                    </tr>
                    <tr>
                        <td><input type="submit" value="Valider" name="sub" /></td>
                    </tr>
                </table>
            </form>
		</fieldset>	
    </body>
</html>