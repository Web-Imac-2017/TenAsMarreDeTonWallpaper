<?php
session_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';
require_once MODELS . 'question.php';
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <title>MEL QUESTION</title>

    </head>
    <body>

    <?php
      try{
        $bdd = $bdd = Database::get();
        $query='SELECT * FROM Categorie ';
        $res=$bdd->prepare($query);
        $res->execute();
      }catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
      }
      $entries=$res->fetchAll(PDO::FETCH_ASSOC);
      $nb=$res->rowCount();
      $res->closeCursor();
    ?>

    <form action="" method="post">
          MISE EN LIGNE - QUESTION
			<label for="qlong">Intitulé (long) :</label>
			<input type="text"name="qlong" />
                   
			<label for="qcourt">Intitulé (court) :</label>
			<input type="text" name="qcourt" /></br>
    <?php
      foreach($entries as $entry):
    ?>
    
      <input type="checkbox" name="case[]">
      <?php echo $entry['nom'];?>
    
    <?php
      endforeach;
    ?>
			
               
			<input type="submit" value="VALIDER" name="submit" /></br>
			
        </form>
    <?php
    if(isset($_POST['submit'])) {
      if(isset($_POST['qlong']) && isset($_POST['qcourt']) && isset($_POST['case']) && isset($_POST['id'])) {
        try{
          $question=new Question();
          $question->add($_POST['qcourt'], $_POST['qlong'], 0, $_POST['id'], $_POST['case']);
  

          echo "question long :".$_POST['qlong']."</br>";
          echo "question court :".$_POST['qcourt']."</br>";
          foreach ($_POST['case'] as $nom):
            echo "cases : ".$nom."</br>";
          endforeach;

        }catch (Exception $e){
          die('Erreur : ' . $e->getMessage());
        } 
      }
    }

    ?>
    </body>
</html>