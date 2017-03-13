<?php
$page['title'] = "Catégorie";
include('header.php');
?>
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
include('footer.php');
?>
