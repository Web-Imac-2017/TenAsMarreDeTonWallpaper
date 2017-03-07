<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <title>BO | Formulaire inscription</title>

    </head>
    <body>
        <div class="container">

            <h1>Formulaire inscription</h1>
            <hr>

            <form action="../api/membre/add" enctype="multipart/form-data" method="post">
                <table>
                    <tr>
                        <td>Pseudo<span style="color:red;">*</span> :</td>
                        <td><input type="text" name="pseudo" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Mot de passe<span style="color:red;">*</span> :</td>
                        <td><input type="password" name="password" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Confirmer mot de passe<span style="color:red;">*</span> :</td>
                        <td><input type="password" name="password2" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td>Adresse email<span style="color:red;">*</span> :</td>
                        <td><input type="email" name="mailAdress" class="form-control" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input type="submit" value="S'inscrire" name="submit" class="btn btn-primary"/></td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>