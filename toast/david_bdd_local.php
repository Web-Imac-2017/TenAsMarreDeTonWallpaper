<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">

        <title>David</title>

    </head>
    <body>
	
        <h1>david.php</h1>
        <fieldset>
            <legend>Inscription</legend>
			
            <?php 

            if(isset($_POST['submit'])) { // the form has been submitted
                if(isset($_POST['pseudo']) && isset($_POST['email']) && isset($_POST['mdp'])) {
					try
					{
						$bdd = new PDO('mysql:host=localhost;dbname=tenamarredetonwallpaper;charset=utf8', 'root', '');
					}
					catch(Exception $e)
					{
							die('Erreur : '.$e->getMessage());
					}

					// On ajoute une entrée dans la table jeux_video
					$bdd->exec('INSERT INTO toast(pseudo, email, mdp) VALUES('.$_POST['pseudo'].', '.$_POST['email'].', '.sha1($_POST['mdp']).')');

					//$sql = "INSERT INTO "toast" ("pseudo", "email", "mdp")VALUES ".$_POST['pseudo'].", ".$_POST['email'].", ".sha1($_POST['mdp'])."";
					
                    echo "<h3>Bien joué ".$_POST['pseudo'].", t'es inscrit !</h3>";
                    echo "<ul><li>Mot de passe : ".$_POST['mdp']."</li>";
                    echo "<li>Adresse mail : ".$_POST['email']."</li></ul>";
                }
                else
                    echo "T'as dû faire une erreur !";
            }
            else {
                
            ?>
            <form action="" method="post">
                <table>
                    <tr>
                        <td> <label for="pseudo">Pseudo :</label></td>
                        <td><input type="text" id="pseudo" name="pseudo" /></td>
                    </tr>
                    <tr>
                        <td><label for="mail">Email :</label></td>
                        <td><input type="mail" id="email" name="email" /></td>
                    </tr>
                    <tr>
                        <td><label for="mdp">Mot de passe :</label></td>
                        <td><input type="password" id="mdp" name="mdp" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="S'inscrire" name="submit" /></td>
                    </tr>
                </table>
            </form>
            <?php
            }

            ?>
        </fieldset>
    </body>
</html>
