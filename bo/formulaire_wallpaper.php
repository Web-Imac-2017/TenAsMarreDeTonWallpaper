<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <title>BO | Formulaire wallpaper</title>

    </head>
    <body>
        <div class="container">

            <h1>Formulaire wallpaper</h1>
            <h4>Ajouter un nouveau wallpaper</h4>
            <hr>

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
                $.ajax({
                    url: "/Tenasmarredetonwallpaper/api/categorie/getAll",
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
                    url: "/Tenasmarredetonwallpaper/api/question/getAll",
                    type: "POST",
                    success: function(data, textStatus, jqXHR) {
                        var chaine = "";
                        var res = JSON.parse(data);
                        for(var i=0; i<res.data.length; i++) {
                            chaine += "<tr><td>" + res.data[i].q_longue + "</td><td><input type='text' name='rep[" + res.data[i].id + "][0]' value='0' /></td><td><input type='text' name='rep[" + res.data[i].id + "][1]' value='49' /></td></tr>";
                        }
                        $("#rep").append(chaine);

                    }
                });
            });

        </script>
    </body>
</html>