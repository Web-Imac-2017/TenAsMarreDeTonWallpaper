<?php
$page['title'] = "Connexion";
include('header.php');
?>

<form id="add">
    <table>
        <tr>
            <td>Pseudo<span style="color:red;">*</span> :</td>
            <td><input type="text" class="form-control pseudo" /></td>
        </tr>
        <tr>
            <td>Mot de passe<span style="color:red;">*</span> :</td>
            <td><input type="password" class="form-control password" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Se connecter" name="submit" class="btn btn-primary"/></td>
        </tr>
    </table>
</form>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("body #add").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/membre/login",
                type: "POST",
                data: {
                    pseudo: $("#add .pseudo").val(),
                    password: $("#add .password").val()
                },
                success: function(data) {
                    document.location.href='/TenAsMarreDeTonWallpaper/bo/dashboard.php';
                }
            });
        });
    });

</script>


<?php
include('footer.php');
?>
