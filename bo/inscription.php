<?php
$page['title'] = "Inscription";
include('header.php');
?>

<form action="../api/membre/add" method="post">
    <table>
        <tr>
            <td>Pseudo<span style="color:red;">*</span> :</td>
            <td><input type="text" name="pseudo" class="form-control" /></td>
        </tr>
        <tr>
            <td>Mot de passe<span style="color:red;">*</span> :</td>
            <td><input type="password" name="password" class="form-control" /></td>
        </tr>
        <tr>
            <td>Confirmer mot de passe<span style="color:red;">*</span> :</td>
            <td><input type="password" name="password2" class="form-control" /></td>
        </tr>
        <tr>
            <td>Adresse email<span style="color:red;">*</span> :</td>
            <td><input type="email" name="mailAdress" class="form-control" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="S'inscrire" name="submit" class="btn btn-primary"/></td>
        </tr>
    </table>
</form>

<?php
include('footer.php');
?>
