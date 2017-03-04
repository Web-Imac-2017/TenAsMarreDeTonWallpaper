<?php
session_start();

?>
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

                    $_SESSION['pseudo'] = $_POST['pseudo'];

                    $_SESSION['mail'] = $_POST['mail'];

                    $_SESSION['mdp'] = $_POST['mdp'];

                    try{
                        $bdd = new PDO('mysql:host=https://franc.myds.me/phpMyAdmin/index.php;dbname=LE_NOM_DE_LA_DATABASE;charset=utf8', 'pierre', 'bide0nd0a64');
                    }catch (Exception $e){
                        die('Erreur : ' . $e->getMessage());
                    }
                    $json=array();
                    $reponse = $bdd->query('SELECT * FROM Question ORDER BY RAND() LIMIT 20');
                    if($exist=$reponse->rowCount()){
                        while ($donnees = $reponse->fetch()){
                            $json[]=$donnees;
                        }
                        echo json_encode($json);
                    }


                    $reponse->closeCursor();
                }else{
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
