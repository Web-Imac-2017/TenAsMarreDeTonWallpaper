<?php
$page['title'] = "Catégorie";
include('header.php');
?>

<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                </tr>
            </thead>
            <tbody id="req1">
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-6 col-sm-12">
    <h2 class="sub-header">Ajouter une catégorie</h2>
    <form id="add" class="form-inline">
        <div class="form-group">
            <label>Nom<span style="color:red;">*</span> :</label>
            <input type="text" class="form-control nom" />
        </div>
        <input type="submit" value="Ajouter" name="submit" class="btn btn-success" />
    </form>
</div>

<div class="col-md-6 col-sm-12">
    <h2 class="sub-header">Modifier une catégorie</h2>
    <form id="change" class="form-inline">
        <div class="form-group">
            <select class="select form-control"></select>
            <label>Nouveau nom<span style="color:red;">*</span> :</label>
            <input type="text" class="form-control nom" />
        </div>
        <input type="submit" value="Modifier" name="submit" class="btn btn-info" />
    </form>
</div>

<div class="col-md-6 col-sm-12">
    <h2 class="sub-header">Supprimer une catégorie</h2>
    <form id="delete" class="form-inline">
        <select class="select form-control id"></select>
        <input type="submit" value="Supprimer" name="submit" class="btn btn-danger" />
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        reload();
        
        function reload() {
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/categorie/getAll",
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    var chaine = "";
                    var res = JSON.parse(data);
                    for(var i=0; i<res.data.length; i++) {
                        chaine += '<tr><td>' + res.data[i].id + '</td>';
                        chaine += '<td>' + res.data[i].nom + '</td></tr>';
                    }
                    $("#req1").html(chaine);

                    chaine = "";

                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<option value='" + res.data[i].id + "'>" + res.data[i].nom + "</option>";
                    }

                    $(".select").html(chaine);
                }
            });
        }

        $("body #add").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/categorie/add",
                type: "POST",
                data: {
                    nom: $("#add .nom").val()
                },
                success: function(data) {
                    reload();
                }
            });
        });

        $("body #change").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/categorie/change",
                type: "POST",
                data: {
                    id: $("#change .select").val(),
                    nom: $("#change .nom").val()
                },
                success: function(data) {
                    reload();
                }
            });
        });

        $("body #delete").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/categorie/delete",
                type: "POST",
                data: {
                    id: $("#delete .select").val()
                },
                success: function(data) {
                    reload();
                }
            });
        });
    });

</script>

<?php
include('footer.php');
?>
