<?php
$page['title'] = "Inscription";
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
            <td>Confirmer mot de passe<span style="color:red;">*</span> :</td>
            <td><input type="password" class="form-control password2" /></td>
        </tr>
        <tr>
            <td>Adresse email<span style="color:red;">*</span> :</td>
            <td><input type="email" class="form-control mailAdress" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="S'inscrire" name="submit" class="btn btn-success"/></td>
        </tr>
    </table>
</form>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {

        function hideErrors() {
            $(".alert").remove();
        };

        $("body #add").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/membre/add",
                type: "POST",
                data: {
                    pseudo: $("#add .pseudo").val(),
                    password: $("#add .password").val(),
                    password2: $("#add .password2").val(),
                    mailAdress: $("#add .mailAdress").val(),
                },
                success: function(data) {
                    hideErrors();
                    var res = JSON.parse(data);
                    if(res.returnCode != 1) {
                        $("#add").parent().append('<div class="alert alert-danger" role="alert"><strong>' + res.returnMessage + '</strong></div>');
                    }
                    else {
                        document.location.href='/TenAsMarreDeTonWallpaper/bo/';
                    }
                }
            });
        });
    });

</script>

<?php
include('footer.php');
?>
