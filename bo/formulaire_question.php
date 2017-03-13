<?php

    session_start();
    require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
    require_once KERNEL . 'kernel.php';
    require_once MODEL_DIR . 'question.php';
    require_once MODEL_DIR . 'categorie.php';

?>
<?php
$page['title'] = "Question";
include('header.php');
?>

<body>
    <fieldset>
            <legend>DISPLAY QUESTIONS</legend>
            <form action="" method="post">
                <table>
                    <tr>
                        <td> <label>Question courte :</label></td>
                        <select name="question_id">
                        <?php
                        $q=new Question();
                        $questions = $q->getAll()['data'];
                        foreach ($questions as $questions):
                        ?>
                        <option value="<?= $questions['id'] ?>"><?php echo $questions['q_courte'] ?></option>
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
                    $question = $q->get($_POST['question_id']);
                    $q_categories = getQuestionCategories($_POST['question_id']);
                    $occurences = sizeof($q_categories);
                    $_SESSION['q_id'] = $question['q_id'];
                    $_SESSION['importance'] = $question['importance'];
                    $_SESSION['nb_apparition'] = $question['nb_apparition'];
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
                            $c=new Categorie();
                            $allCategories = $c->getAll();
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
                                    echo 'checked="checked"';
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
                        $_POST['importance'] = $_SESSION['importance'];
                    }
                    if(empty($_POST['nb_apparition']))
                    {
                        $_POST['nb_apparition'] = $_SESSION['nb_apparition'];
                    }
                    
                    $_SESSION['q_courte'] = $_POST['q_courte'];
                    $_SESSION['q_longue'] = $_POST['q_longue'];
                    $_SESSION['importance'] = $_POST['importance'];
                    $_SESSION['nb_apparition'] = $_POST['nb_apparition'];
                    $_SESSION['q_categories'] = $_POST['q_categories'];
                    $q->changeQuestion($_SESSION['q_id'], $_SESSION['q_courte'], $_SESSION['q_longue'], $_SESSION['importance'], $_SESSION['nb_apparition']);
                    $q->deleteQuestionCategorie($_SESSION['q_id']);
                    $q->addQuestionCategorie($_SESSION['q_id'], $_SESSION['q_categories']);
                    
                    echo "<h3>Bravo ta question a bien été modifiée !</h3>";
                }
                else
                {
                    echo "T'as dû faire une erreur !";
                }
            }
            else if(isset($_POST['delete']))
            {
                $q->deleteQuestion($_SESSION['q_id']);
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
                            $allCategories = $c->getAll();
                            $i = 0;
                            foreach ($allCategories as $allCategories):

                        ?>
                        <input type="checkbox" name="add_categories[]" value="<?= $allCategories['id'] ?>"/>
                        <?php echo $allCategories['nom']; ?>
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
                    
                    $q->add($_SESSION['add_q_courte'], $_SESSION['add_q_longue'], $_SESSION['add_importance'], 0, $_SESSION['add_categories']);
                    echo "<h3>Bravo ta question a bien été enregistrée !</h3>";
                }
                else
                {
                    echo "T'as dû faire une erreur !";
                }
            }
            ?>
        </fieldset>
</body>
</html>

<?php
include('footer.php');
?>

