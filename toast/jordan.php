<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Jordan - Test PHP</title>
    </head>
    <body>
        <h1>Page test - Formulaire</h1>
        <form action="./jordan.php" method="post">
            <input type="textarea" placeholder="Entrez votre texte" name="myText"/>
            <input type="submit" value="Valider" name="submit"/>
        </form>
        <div>
            <?php
                if (isset($_POST['myText'])) {
                    $content = $_POST['myText'];
                    echo "<h1>Texte récupéré : </h1>";
                    echo "<p>" . $content . "</p>";
                }
            ?>
        </div>

    </body>
</html>
