<?php require('functions.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Login</title>
    </head>
    <body>
        <h1>Page test - Formulaire</h1>
        <form action="./login.php" method="post">
            <input type="text" placeholder="login" name="login"/>
            <input type="password" placeholder="password" name="password"/>
            <input type="submit" value="Valider" name="submit"/>
        </form>
        <div>
            <?php
                if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password'])) {
                    $login = $_POST['login'];
                    $password = $_POST['password'];

                    loginMember($login, $password);
                    echo 'Success';
                }
                else {
                    echo 'Echec. / Formulaire non envoyé';
                }
            ?>
        </div>

    </body>
</html>
