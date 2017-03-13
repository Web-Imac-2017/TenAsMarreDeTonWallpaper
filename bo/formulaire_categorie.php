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
    <form action="../api/categorie/add" method="POST" class="form-inline">
        <div class="form-group">
            <label for="nom">Nom<span style="color:red;">*</span> :</label>
            <input type="text" class="form-control" name="nom" id="nom" />
        </div>
        <input type="submit" value="Ajouter" name="submit" class="btn btn-success" />
    </form>
</div>

<div class="col-md-6 col-sm-12">
    <h2 class="sub-header">Modifier une catégorie</h2>
    <form action="../api/categorie/change" method="POST" class="form-inline">
        <div class="form-group">
           <select name="id" class="select form-control"></select>
            <label for="nom">Nouveau nom<span style="color:red;">*</span> :</label>
            <input type="text" class="form-control" name="nom" id="nom" />
        </div>
        <input type="submit" value="Modifier" name="submit" class="btn btn-info" />
    </form>
</div>

<div class="col-md-6 col-sm-12">
    <h2 class="sub-header">Supprimer une catégorie</h2>
    <form action="../api/categorie/delete" method="POST" class="form-inline">
        <select name="id" class="select form-control"></select>
        <input type="submit" value="Supprimer" name="submit" class="btn btn-danger" />
    </form>
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
    });

</script>

<?php
include('footer.php');
?>
