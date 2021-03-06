<?php
$page['title'] = "Membre";
include('header.php');
?>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pseudo</th>
                        <th>Mail</th>
                    </tr>
                </thead>
                <tbody id="req1">
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
        <h2 class="sub-header">Ajouter un membre</h2>
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
                    <td><input type="email" name="mailAdress" class="form-control mailAdress" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="S'inscrire" name="submit" class="btn btn-success"/></td>
                </tr>
            </table>
        </form>
    </div>
    
    <div class="col-md-6 col-sm-12">
        <h2 class="sub-header">Supprimer un membre</h2>
        <form id="delete" class="form-inline">
            <select class="form-control id"></select>
            <input type="submit" value="Supprimer" name="submit" class="btn btn-danger" />
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        function hideErrors() {
            $(".alert").remove();
        };

        reload();
        function reload() {
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/membre/getAll",
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    var chaine = "";
                    var res = JSON.parse(data);
                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<tr>";
                        chaine += "<td>" + res.data[i].id + "</td>";
                        chaine += "<td>" + res.data[i].pseudo + "</td>";
                        chaine += "<td>" + res.data[i].mail + "</td>";
                        chaine += "</tr>";
                    }
                    $("#req1").html(chaine);

                    chaine = "";

                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<option value='" + res.data[i].id + "'>" + res.data[i].pseudo + "</option>";
                    }

                    $(".id").html(chaine);
                }
            });
        }

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
                        reload();
                    }
                }
            });
        });

        $("body #delete").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/membre/delete",
                type: "POST",
                data: {
                    id: $("#delete .id").val()
                },
                success: function(data) {
                    hideErrors();
                    var res = JSON.parse(data);
                    if(res.returnCode != 1) {
                        $("#delete").parent().append('<div class="alert alert-danger" role="alert"><strong>' + res.returnMessage + '</strong></div>');
                    }
                    else {
                        reload();
                    }
                }
            });
        });
    });

</script>

<?php
include('footer.php');
?>
