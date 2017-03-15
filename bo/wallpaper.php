<?php
$page['title'] = "Wallpaper";
include('header.php');
?>

<div class="col-md-12">
    <p>Derniers wallpapers ajoutés</p>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Auteur</th>
                    <th>Url</th>
                    <th>Largeur</th>
                    <th>Hauteur</th>
                    <th>Format</th>
                    <th>Date</th>
                    <th>Nb d'apparition</th>
                    <th>Nb de téléchargement(s)</th>
                </tr>
            </thead>
            <tbody id="req1">
            </tbody>
        </table>
    </div>
</div>

<div class="col-md-6">
    <h2 class="sub-header">Ajouter un wallpaper</h2>
    <form action="../api/wallpaper/add" enctype="multipart/form-data" method="post">
        <table>
            <tr>
                <td>Nom <span style="color:red;">*</span>:</td>
                <td><input type="text" name="nom" class="form-control" /></td>
                <td></td>
            </tr>
            <tr>
                <td>Auteur :</td>
                <td><input type="text" name="auteur" class="form-control" /></td>
                <td></td>
            </tr>
            <tr>
                <td>Upload <span style="color:red;">*</span> :</td>
                <td><input type="file" name="image" required /></td>
                <td></td>
            </tr>
            <tr>
                <td>Catégories <span style="color:red;">*</span> :</td>
                <td id="cat"></td>
                <td></td>
            </tr>
            <tr>
                <td>Questions :</td>
                <td id="rep"></td>
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
    <h2 class="sub-header">Supprimer un wallpaper</h2>
    <form id="delete" class="form-inline">
        <div class="form-group">
            <label>Id<span style="color:red;">*</span> :</label>
            <input type="text" class="form-control id" />
        </div>
        <input type="submit" value="Supprimer" name="submit" class="btn btn-danger" />
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var rep =
            [
                {
                    value: 100,
                    text: 'Oui'
                },
                {
                    value: 75,
                    text: 'Eventuellement'
                },
                {
                    value: 50,
                    text: 'Peu importe'
                },
                {
                    value: 25,
                    text: 'Pas vraiment'
                },
                {
                    value: 0,
                    text: 'Non'
                }
            ];

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

        $.ajax({
            url: "/TenAsMarreDeTonWallpaper/api/question/getAll",
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                var chaine = "<tr><td></td><td>Min</td><td>Max</td></tr>";
                var res = JSON.parse(data);
                for(var i=0; i<res.data.length; i++) {
                    chaine += "<tr><td>" + res.data[i].q_courte + "</td>";
                    chaine += "<td><select name='rep[" + res.data[i].id + "][0]'>";
                    for(var j=0; j<rep.length; j++) {
                        selected = (j==4 ? "selected" : "");
                        chaine += "<option " + selected + " value='" + rep[j].value + "'>" + rep[j].text + "</option>";
                    }
                    chaine += "</select></td><td><select name='rep[" + res.data[i].id + "][1]'>";
                    for(var j=0; j<rep.length; j++) {
                        selected = (j==2 ? "selected" : "");
                        chaine += "<option " + selected + " value='" + rep[j].value + "'>" + rep[j].text + "</option>";
                    }
                    chaine += "</select></td></tr>";
                }
                $("#rep").html(chaine);
            }
        });

        function reload() {
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/wallpaper/latest/10",
                type: "POST",
                success: function(data, textStatus, jqXHR) {
                    var chaine = "";
                    var res = JSON.parse(data);
                    for(var i=0; i<res.data.length; i++) {
                        chaine += "<tr>";
                        chaine += "<td>" + res.data[i].id + "</td>";
                        chaine += "<td>" + res.data[i].nom + "</td>";
                        chaine += "<td>" + res.data[i].auteur + "</td>";
                        chaine += "<td>" + res.data[i].url + "</td>";
                        chaine += "<td>" + res.data[i].largeur + "</td>";
                        chaine += "<td>" + res.data[i].hauteur + "</td>";
                        chaine += "<td>" + res.data[i].format + "</td>";
                        chaine += "<td>" + res.data[i].date + "</td>";
                        chaine += "<td>" + res.data[i].nb_apparition + "</td>";
                        chaine += "<td>" + res.data[i].nb_telechargement + "</td>";
                        chaine += "</tr>";
                    }
                    $("#req1").html(chaine);
                }
            });
        };
        
        reload();

        $("body #delete").submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "/TenAsMarreDeTonWallpaper/api/wallpaper/delete",
                type: "POST",
                data: {
                    id: $("#delete .id").val()
                },
                success: function(data) {
                    var res = JSON.parse(data);
                    if(res.returnCode != 1) {
                        alert(data);
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
