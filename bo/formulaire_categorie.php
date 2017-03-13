<?php
$page['title'] = "Catégorie";
include('header.php');
?>

        <!--<h1>Formulaire catégorie</h1>-->
        <h4>Ajouter une nouvelle catégorie</h4>
        <hr>

        <form action="../api/categorie/add" method="post">
            <table>
                <tr>
                    <td>Nom<span style="color:red;">*</span> :</td>
                    <td><input type="text" name="nom" class="form-control" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Ajouter" name="submit" class="btn btn-primary"/></td>
                </tr>
            </table>
        </form>
        <?php
            }
            if(isset($_POST['submit']))
            {
                if(isset($_POST['nom']) && !empty($_POST['nom']))
                {
                    $_SESSION['nom'] = $_POST['nom'];
                    
                    $c->add($_SESSION['nom']);
                    echo "<h3>Bravo ta catégorie a bien été rajoutée !</h3>";
                }
                else
                {
                    echo "T'as dû faire une erreur !";
                }
            }
            ?>
    </div>
    <fieldset>
            <legend>DELETE CATEGORIE</legend>
            <form action="../api/categorie/delete" method="post">
                <table>
                    <tr>
                        <td> <label for="new_cat">Nom de la catégorie :</label></td>
                        <select name="categorie_id">
                            <?php
                            $allCategories = $c->getAll();
                            
                            foreach ($allCategories as $cat):
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
                    $c->delete($cat['id']);
                    echo "<h3>Bravo ta catégorie a bien été supprimée !</h3>";
                }
            ?>
        </fieldset>
</body>
</html>

<?php
include('footer.php');
?>

