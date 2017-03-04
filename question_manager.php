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

        <title>QUESTION MANAGER</title>

    </head>
    <body>
	
        <fieldset>
            <legend>DISPLAY QUESTIONS</legend>
			<?php
				require('models/categorie_question.php');
			?>
			<form action="" method="post">
                <table>
					<tr>
                        <td> <label>Question courte :</label></td>
						<select name="question_id">
						<?php
						$questions = getQuestions();
						foreach ($questions as $questions):
						?>
						<option value="<?= $questions['q_id'] ?>"><?php echo $questions['q_courte'] ?></option>
						<?php
							endforeach;
						?>
						</select>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Voir la question" name="search" /></td>
                    </tr>
                </table>
            </form>
			
			<?php
				if(isset($_POST['search']))
				{
					$question = getQuestion($_POST['question_id']);
					$q_categories = getQuestionCategories($_POST['question_id']);
					$occurences = sizeof($q_categories);
					$_SESSION['q_id'] = $question['q_id'];
			?>
			<hr />
			<form action="" method="post">
                <table>
                    <tr>
                        <td> <label for="q_courte">Question courte :</label></td>
                        <td><input type="text" id="q_courte" name="q_courte" value="<?= $question['q_courte'] ?>"/></td>
                    </tr>
					<tr>
                        <td> <label for="q_longue">Question longue :</label></td>
                        <td><input type="text" id="q_longue" name="q_longue"  value="<?= $question['q_longue'] ?>"/></td>
                    </tr>
					<tr>
                        <td> <label for="importance">Importance :</label></td>
                        <td><input type="number" id="importance" name="importance" min="0" max="50" value="<?= $question['importance'] ?>"></td>
                    </tr>
					<tr>
                        <td> <label for="popularite">Popularite :</label></td>
                        <td><input type="number" id="nb_apparition" name="nb_apparition" min="0" max="100" value="<?= $question['nb_apparition'] ?>"></td>
                    </tr>
					<tr>
                        <td> <label for="categorie">Catégories :</label></td>
						<td>						
						<?php
							$allCategories = getCategories();
							$i = 0;
							foreach ($allCategories as $allCategories):

						?>
						<input type="checkbox" name="q_categories[]" value="<?= $allCategories['id'] ?>"
						<?php 
							// Vérifie pour chaque categorie si elle appartient à la question
							for ($i = 0; $i < $occurences; $i++)
							{
								if($q_categories[$i]['nom'] == $allCategories['nom'])
								{
									echo 'checked';
								}
							}
						?>/>
						<?php echo $allCategories['nom']; ?>
						<?php
						endforeach;
						?>
                    </tr>
					<p>ID : <?= $question['q_id'] ?></p>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Modifier la question" name="change" /></td>
						<td><input type="submit" value="Supprimer la question" name="delete" /></td>
                    </tr>
                </table>
            </form>
		<?php
			}
			if(isset($_POST['change']))
			{
                if(isset($_POST['q_courte']) && isset($_POST['q_longue']) && !empty($_POST['q_courte']) && !empty($_POST['q_longue']))
				{
					if(empty($_POST['importance']))
					{
						$_POST['importance'] = $question['importance'];
					}
					if(empty($_POST['nb_apparition']))
					{
						$_POST['nb_apparition'] = $question['nb_apparition'];
					}
					
					$_SESSION['q_courte'] = $_POST['q_courte'];
					$_SESSION['q_longue'] = $_POST['q_longue'];
					$_SESSION['importance'] = $_POST['importance'];
					$_SESSION['nb_apparition'] = $_POST['nb_apparition'];
					$_SESSION['q_categories'] = $_POST['q_categories'];
					print_r($_SESSION['q_categories']);
					changeQuestion($_SESSION['q_id'], $_SESSION['q_courte'], $_SESSION['q_longue'], $_SESSION['importance'], $_SESSION['nb_apparition']);
					deleteQuestionCategorie($_SESSION['q_id']);
					addQuestionCategorie($_SESSION['q_id'], $_SESSION['q_categories']);
					
					echo "<h3>Bravo ta question a bien été modifiée !</h3>";
				}
				else
				{
                    echo "T'as dû faire une erreur !";
				}
			}
			else if(isset($_POST['delete']))
			{
				deleteQuestion($_SESSION['q_id']);
				echo "<h3>Bravo ta question a bien été supprimée !</h3>";
			}
		?>
		
        </fieldset>
		
		<fieldset>
            <legend>ADD QUESTION</legend>
			<form action="" method="post">
                <table>
                    <tr>
                        <td><input type="submit" value="Ajouter une question" name="show_add_q" /></td>
                    </tr>
                </table>
            </form>
			<?php
			if(isset($_POST['show_add_q']))
			{
				?>
			<hr />
			<form action="" method="post">
                <table>
                    <tr>
                        <td> <label for="add_q_courte">Question courte :</label></td>
                        <td><input type="text" id="add_q_courte" name="add_q_courte" value=""/></td>
                    </tr>
					<tr>
                        <td> <label for="add_q_longue">Question longue :</label></td>
                        <td><input type="text" id="add_q_longue" name="add_q_longue"  value=""/></td>
                    </tr>
					<tr>
                        <td> <label for="add_importance">Importance :</label></td>
                        <td><input type="number" id="add_importance" name="add_importance" min="0" max="50" value=""></td>
                    </tr>
					<tr>
                        <td> <label for="categorie">Catégories :</label></td>
						<td>						
						<?php
							$categories = getCategories();
							foreach ($categories as $categories):

						?>
						<input type="checkbox" name="add_categories[]" value="<?= $categories['id'] ?>"/>
						<?php echo $categories['nom']; ?>
						<?php
						endforeach;
						?>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Ajouter la question" name="addQ" /></td>
                    </tr>
                </table>
            </form>
			<?php
			}
			if(isset($_POST['addQ']))
			{
				if(isset($_POST['add_q_courte']) && isset($_POST['add_q_longue']) && !empty($_POST['add_q_courte']) && !empty($_POST['add_q_longue']))
				{
					if(empty($_POST['add_importance']))
					{
						$_POST['add_importance'] = 25;
					}
					
					$_SESSION['add_q_courte'] = $_POST['add_q_courte'];
					$_SESSION['add_q_longue'] = $_POST['add_q_longue'];
					$_SESSION['add_importance'] = $_POST['add_importance'];
					$_SESSION['add_categories'] = $_POST['add_categories'];
					
					addQuestion($_SESSION['add_q_courte'], $_SESSION['add_q_longue'], $_SESSION['add_importance'], 0, $_SESSION['add_categories']);
					echo "<h3>Bravo ta question a bien été enregistrée !</h3>";
				}
				else
				{
					echo "T'as dû faire une erreur !";
				}
			}
			?>
        </fieldset>
		
		<fieldset>
            <legend>ADD CATEGORIE</legend>
			<form action="" method="post">
                <table>
                    <tr>
                        <td><input type="submit" value="Ajouter une catégorie" name="show_add_cat" /></td>
                    </tr>
                </table>
            </form>
			<?php if(isset($_POST['show_add_cat']))
			{
				?>
			<hr />
			<form action="" method="post">
                <table>
                    <tr>
                        <td> <label for="new_cat">Nouvelle Catégorie :</label></td>
                        <td><input type="text" id="new_cat" name="new_cat" value=""/></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Ajouter la catégorie" name="addC" /></td>
                    </tr>
                </table>
            </form>
			<?php
			}
			if(isset($_POST['addC']))
			{
				if(isset($_POST['new_cat']) && !empty($_POST['new_cat']))
				{
					$_SESSION['new_cat'] = $_POST['new_cat'];
					
					addCategorie($_SESSION['new_cat']);
					echo "<h3>Bravo ta catégorie a bien été rajoutée !</h3>";
				}
				else
				{
					echo "T'as dû faire une erreur !";
				}
			}
			?>
        </fieldset>
		
		<fieldset>
            <legend>DELETE CATEGORIE</legend>
			<form action="" method="post">
                <table>
				    <tr>
                        <td> <label for="new_cat">Nom de la catégorie :</label></td>
						<select name="categorie_id">
							<?php
							$cat = getCategories();
							foreach ($cat as $cat):
							?>
							<option value="<?= $cat['id'] ?>"><?php echo $cat['nom'] ?></option>
							<?php
								endforeach;
							?>
						</select>
					</tr>
                    <tr>
                        <td><input type="submit" value="Supprimer la catégorie" name="deleteCat" /></td>
                    </tr>
                </table>
            </form>
			<?php
				if(isset($_POST['deleteCat']))
				{
					deleteCategorie($cat['id']);
					echo "<h3>Bravo ta catégorie a bien été supprimée !</h3>";
				}
			?>
        </fieldset>
		
    </body>
</html>