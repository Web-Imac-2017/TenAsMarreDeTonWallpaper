<?php
$page['title'] = "Question";
include('header.php');
?>
<div class="row">
    <div class="col-md-12">
        <p>Dernières questions ajoutées</p>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Q_courte</th>
                        <th>Q_longue</th>
                        <th>Mise_en_ligne_id</th>
                        <th>Importance</th>
                        <th>Nb_apparition</th>
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
        <h2 class="sub-header">Ajouter une question</h2>
        <form id="add">
            <table>
                <tr>
                    <td>Question courte<span style="color:red;">*</span> : </td>
                    <td><input type="text" class="form-control q_courte" /></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Question longue<span style="color:red;">*</span> : </td>
                    <td><input type="text" class="form-control q_longue" /></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Catégories<span style="color:red;">*</span> : </td>
                    <td id="cat"></td>
                    <td></td>
                </tr>
                <tr>
                    <td></td>
                    <td><input type="submit" value="Ajouter" name="submit" class="btn btn-success"/></td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>

    <div class="col-md-6 col-sm-12">
        <h2 class="sub-header">Supprimer une question</h2>
        <form id="delete" class="form-inline">
            <select class="select form-control id"></select>
            <input type="submit" value="Supprimer" name="submit" class="btn btn-danger" />
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $.ajax({
            url: "/TenAsMarreDeTonWallpaper/api/categorie/getAll",
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                var chaine = "";
                var res = JSON.parse(data);
                for(var i=0; i<res.data.length; i++) {
                    chaine += "<input type='checkbox' value='" + res.data[i].id + "' name='categories[]' /> " + res.data[i].nom + "<br />";
                }
                $("#cat").html(chaine);

            }
        });
        
        function hideErrors() {
            $(".alert").remove();
        };

        function reload() {
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/question/latest/10",
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    var chaine = "";
                    var res = JSON.parse(data);
                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<tr>";
                        chaine += "<td>" + res.data[i].id + "</td>";
                        chaine += "<td>" + res.data[i].q_courte + "</td>";
                        chaine += "<td>" + res.data[i].q_longue + "</td>";
                        chaine += "<td>" + res.data[i].mise_en_ligne_id + "</td>";
                        chaine += "<td>" + res.data[i].importance + "</td>";
                        chaine += "<td>" + res.data[i].nb_apparition + "</td>";
                        chaine += "</tr>";
                    }
                    $("#req1").html(chaine);

                    chaine = "";

                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<option value='" + res.data[i].id + "'>" + res.data[i].q_courte + "</option>";
                    }

                    $(".select").html(chaine);
                }
            });
        };

        reload();

        $("body #add").submit(function(event) {
            event.preventDefault();
            var categories = [];
            $('input[name="categories[]"]:checked').each(function() {
                categories.push(parseInt(this.value));
            });
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/question/add",
                type: "POST",
                data: {
                    q_courte: $("#add .q_courte").val(),
                    q_longue: $("#add .q_longue").val(),
                    categories: categories
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
                url: "/TenAsMarreDeTonWallpaper/api/question/delete",
                type: "POST",
                data: {
                    id: $("#delete .select").val()
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
