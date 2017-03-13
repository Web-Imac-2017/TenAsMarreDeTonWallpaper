<?php
require('header.php');
?>
<body>
    <div class="container">

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
    </div>
</body>
</html>
