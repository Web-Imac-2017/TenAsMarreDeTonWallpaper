<?php
$page['title'] = "Connexion";
include('header.php');
?>

<form action="../api/membre/login" method="post">
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
            <td></td>
            <td><input type="submit" value="Se connecter" name="submit" class="btn btn-primary"/></td>
        </tr>
    </table>
</form>
</div>
</body>
</html>
