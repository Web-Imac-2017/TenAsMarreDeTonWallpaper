<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>test</title>
    </head>
    <body>
 
            <?php 

            if(isset($_POST['submit'])) {
                if(isset($_POST['pseudo']) && isset($_POST['mail']) && isset($_POST['mdp'])) {
                    echo "Bienvenue ".$_POST['pseudo']."</br>";
                    echo "Mot de passe : ".$_POST['mdp']."</br>";
                    echo "Adresse mail : ".$_POST['mail']."</br>";
                }
                else{
                    echo "non existing var";
				}
            }
            else {
                
            ?>
            <form action="" method="post">
				<label for="pseudo">Pseudo :</label>
				<input type="text"name="pseudo" />
                        
				<label for="mail">Email :</label>
				<input name="mail" /></br>
            
				<label for="mdp">Mot de passe :</label>
				<input type="password" name="mdp" />
                    
				<input type="submit" value="Sign in" name="submit" /></br>
				
            </form>
            <?php
            }

            ?>

    </body>
</html>
