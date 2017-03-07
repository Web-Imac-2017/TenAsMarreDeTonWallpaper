<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <title>Formulaire wallpaper</title>

    </head>
    <body>
        <div class="container">

            <h1>Formulaire wallpaper</h1>
            <h4>Ajouter un nouveau wallpaper</h4>
            <hr>

            <form action="api/wallpaper/add" enctype="multipart/form-data" method="post">
                <table>
                    <tr>
                        <td>Nom <span style="color:red;">*</span>:</td>
                        <td><input type="text" name="nom" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Auteur :</td>
                        <td><input type="text" name="auteur" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Upload <span style="color:red;">*</span> :</td>
                        <td><input type="file" name="image" required /></td>
                    </tr>
                    <tr>
                        <td>Réponses <span style="color:red;">*</span> :</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="Ajouter" name="submit" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
        </div>
        
        <script type="text/javascript">

        // requête ajax vers /question/getAll
            
        </script>
    </body>
</html>