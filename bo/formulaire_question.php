<?php
    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
    require_once KERNEL . 'kernel.php';
    require_once MODEL_DIR . 'question.php';
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

        <title>BO | Formulaire question</title>

    </head>
    <body>
        <?php
            try{
                $bdd = Database::get();
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
        <div class="container">

            <h1>Formulaire question</h1>
            <h4>Ajouter une nouvelle question</h4>
            <hr>

            <form action="../api/question/add" method="post">
                <table>
                    <tr>
                        <td>Question courte <span style="color:red;">*</span>:</td>
                        <td><input type="text" name="q_courte" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Question longue <span style="color:red;">*</span>:</td>
                        <td><input type="text" name="q_longue" class="form-control" /></td>
                    </tr>
                    <tr>
                        <?php
                        foreach($entries as $entry):
                        ?>
                            <td>Categorie <span style="color:red;">*</span>:</td>
                            <td><input type="checkbox" name="case[]"></td>
                            <?php echo $entry['nom'];?>
                        <?php
                          endforeach;
                        ?>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Ajouter" name="submit" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
        </div>
        <?php
            if(isset($_POST['submit'])) {
                if(isset($_POST['q_longue']) && isset($_POST['q_courte']) && isset($_POST['case']) && isset($_SESSION['id'])) {
                    try{
                        $question=new Question();
                        $question->add($_POST['qcourt'], $_POST['qlong'], 0, $_SESSION['id'], $_POST['case']);

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