<?php

require_once('wallpaper.php');

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">

        <title>Formulaire wallpaper</title>

    </head>
    <body>
        <h1>Formulaire wallpaper</h1>

        <div style="clear: both;"></div>

        <fieldset>
            <legend>Ajouter un nouveau wallpaper</legend>
            <form action="api/wallpaper/add" enctype="multipart/form-data" method="post">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" /><br />
                <label for="auteur">Auteur :</label>
                <input type="text" name="auteur" id="auteur" /><br />
                <label for="image">Upload :</label>
                <input type="file" name="image" id="image" /><br />
                <input type="submit" value="Ajouter" name="submit" />
            </form>
        </fieldset>
    </body>
</html>