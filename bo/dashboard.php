<?php
$page['title'] = "Tableau de bord";
include('header.php');
?>

<div class="row">
    <div class="col-md-12">
        <h2 class="sub-header">Wallpapers (10 derniers)</h2>
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
</div>

<div class="row">
    <div class="col-md-4 col-sm-12">
        <h2 class="sub-header">Questions</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Q_courte</th>
                    </tr>
                </thead>
                <tbody id="req2">
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-md-4 col-sm-12">
        <h2 class="sub-header">Catégories</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                    </tr>
                </thead>
                <tbody id="req3">
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-4 col-sm-12">
        <h2 class="sub-header">Membres</h2>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Pseudo</th>
                        <th>Mail</th>
                    </tr>
                </thead>
                <tbody id="req4">
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
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
                $("#req1").append(chaine);
            }
        });

        $.ajax({
            url: "/TenAsMarreDeTonWallpaper/api/question/getAll",
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                var chaine = "";
                var res = JSON.parse(data);
                for(var i=0; i<res.data.length; i++) {
                    chaine += '<tr><td>' + res.data[i].id + '</td>';
                    chaine += '<td>' + res.data[i].q_courte + '</td></tr>';
                }
                $("#req2").html(chaine);
            }
        });

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
                $("#req3").html(chaine);
            }
        });
        
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
                $("#req4").html(chaine);
            }
        });


    });

</script>


<?php
include('footer.php');
?>