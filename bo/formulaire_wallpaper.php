<?php
$page['title'] = "Wallpaper";
include('header.php');
?>

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
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Ajouter" name="submit" class="btn btn-primary"/></td>
            <td></td>
        </tr>
    </table>
</form>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var min = [0, 25, 50, 75];
        var max = [24, 49, 74, 100];
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
                var chaine = "";
                var res = JSON.parse(data);
                for(var i=0; i<res.data.length; i++) {
                    chaine += "<tr><td>" + res.data[i].q_courte + "</td>";
                    chaine += "<td><select name='rep[" + res.data[i].id + "][0]'>";
                    for(var j=0; j<min.length; j++) {
                        selected = (j==0 ? "selected" : "");
                        chaine += "<option " + selected + " value='" + min[j] + "'>" + min[j] + "</option>";
                    }
                    chaine += "</select></td><td><select name='rep[" + res.data[i].id + "][1]'>";
                    for(var j=0; j<max.length; j++) {
                        selected = (j==1 ? "selected" : "");
                        chaine += "<option " + selected + " value='" + max[j] + "'>" + max[j] + "</option>";
                    }
                    chaine += "</select></td></tr>";
                }
                $("#rep").append(chaine);
            }
        });
    });

</script>
</body>
</html>
