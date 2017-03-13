<?php
$page['title'] = "Tableau de bord";
include('header.php');
?>

<div class="row placeholders" id="req1">

</div>

<div class="row">
    <div class="col-md-6 col-sm-12">
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
    
    <div class="col-md-6 col-sm-12">
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
</div>


<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: "/TenAsMarreDeTonWallpaper/api/wallpaper/random/4",
            type: "POST",
            success: function(data, textStatus, jqXHR) {
                var chaine = "";
                var res = JSON.parse(data);
                for(var i=0; i<res.data.length; i++) {
                    chaine += '<div class="col-xs-6 col-sm-3 placeholder">';
                    chaine += '<img src="../' + res.data[i].url + '" width="200" height="200" class="img-responsive" />';
                    chaine += '<h4>' + res.data[i].nom + '</h4>';
                    chaine += '<span class="text-muted">' + res.data[i].auteur + '</span></div>';
                }
                $("#req1").html(chaine);
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
    });

</script>


<?php
include('footer.php');
?>