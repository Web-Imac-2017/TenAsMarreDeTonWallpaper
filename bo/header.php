<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="css/style.css">

        <title>BO - <?php echo $page['title'] ?></title>
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Back Office</a>
                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                       <?php if(isset($_SESSION['user'])) { ?>
                        <li><a href="profil.php"><?php echo $_SESSION['user']['pseudo'] ?></a></li>
                        <?php } else { ?>
                        <li><a href="formulaire_connexion.php">Se connecter</a></li>
                        <li><a href="formulaire_inscription.php">S'inscrire</a></li>
                        <?php } ?>
                    </ul>
                    <form class="navbar-form navbar-right">
                        <input type="text" class="form-control" placeholder="Rechercher...">
                    </form>
                </div>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
                       <?php if($page['title'] == "Tableau de bord") { ?>
                        <li class="active"><a href="dashboard.php">Tableau de bord</a></li>
                        <li><a href="formulaire_wallpaper.php">Wallpaper</a></li>
                        <li><a href="formulaire_question.php">Question</a></li>
                        <li><a href="formulaire_categorie.php">Catégorie</a></li>
                        <?php } ?>
                        <?php if($page['title'] == "Wallpaper") { ?>
                        <li><a href="dashboard.php">Tableau de bord</a></li>
                        <li class="active"><a href="formulaire_wallpaper.php">Wallpaper</a></li>
                        <li><a href="formulaire_question.php">Question</a></li>
                        <li><a href="formulaire_categorie.php">Catégorie</a></li>
                        <?php } ?>
                        <?php if($page['title'] == "Question") { ?>
                        <li><a href="dashboard.php">Tableau de bord</a></li>
                        <li><a href="formulaire_wallpaper.php">Wallpaper</a></li>
                        <li class="active"><a href="formulaire_question.php">Question</a></li>
                        <li><a href="formulaire_categorie.php">Catégorie</a></li>
                        <?php } ?>
                        <?php if($page['title'] == "Catégorie") { ?>
                        <li><a href="dashboard.php">Tableau de bord</a></li>
                        <li><a href="formulaire_wallpaper.php">Wallpaper</a></li>
                        <li><a href="formulaire_question.php">Question</a></li>
                        <li class="active"><a href="formulaire_categorie.php">Catégorie</a></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header"><?php echo $page['title'] ?></h1>