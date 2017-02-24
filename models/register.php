<?php require('functions.php');

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>Register</title>
    </head>
    <body>
        <h1>Page test - Formulaire</h1>
        <form action="./register.php" method="post">
            <input type="text" placeholder="login" name="login"/>
            <input type="password" placeholder="password" name="password"/>
            <input type="mail" placeholder="mail address" name="mailAddress"/>
            <input type="submit" value="Valider" name="submit"/>
        </form>
        <div>
            <?php
                if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['password']) && !empty($_POST['password']) && isset($_POST['mailAddress']) && !empty($_POST['mailAddress']) && filter_var($_POST['mailAddress'], FILTER_VALIDATE_EMAIL) ) {
                    $login = $_POST['login'];
                    $password = $_POST['password'];

                    $mailAddress = $_POST['mailAddress'];
                    registerMember($login, $password, $mailAddress);
                    echo 'Success';
                }
                else {
                    echo 'Formulaire non envoyé / Echec.';
                }
            ?>
        </div>

    </body>
</html>
