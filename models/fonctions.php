<?php
    function getQuestionsby($categorie,$orderby,$sens,$limit){
        $selector= 'SELECT ID,q_courte,q_longue,importance,nb_apparition
        FROM question, categorie_question 
        WHERE ID=question_id AND categorie_id=? ORDER BY ? ? LIMIT ?';
        $reponsebdd = $bddpdo->prepare($selector);
        $reponsebdd->execute(array($categorie,$orderby,$sens,$limit));
        return $reponsebdd;

    }
    /*$urls est un tableau de url, $tabcategories est une matrice de categories
    dont chaque ligne renseigne les catégories à associer à l'url correspondante*/
    function addWallpapers($urls, $estapparent, $tabcategories){
        $i=0;
        for($i;i<count($urls);i++) {
            $url=$urls[i];
            $categories=$tabcategories[i];
            $bdd = getBdd();
            $sql = 'INSERT INTO wallpaper VALUES(NULL, ?, ?)';
            $req = $bdd->prepare($sql);
            $req->execute(array($url, $estapparent));
    
            $id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
            setWallpaperCategories($id, $categories); // on associe le wallpaper aux différentes catégories
        }
    }
    /*veiller à sauvegarder dans la session l'id du visiteur*/
    function permission(){
        $bdd = getBdd();
        $sql = 'SELECT admin,moderateur FROM membre WHERE id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($_SESSION['id']));
    
        if($req){
            $res=$req->fetch();
            if ($res['admin']) {
                return 2;
            }elseif ($res['moderateur']) {
                return 1;
            }else{
                echo 'no permission'
                return 0;
            }
        }else{
            return -1;
        }
    }
    function addWallpaper($url, $estapparent, $categories) {
        $bdd = getBdd();
        $sql = 'INSERT INTO wallpaper VALUES(NULL, ?, ?)';
        $req = $bdd->prepare($sql);
        $req->execute(array($url, $estapparent));

        $id = getIdLastWallpaper(); // on récupère l'id du nouvel wallpaper
        setWallpaperCategories($id, $categories); // on associe le wallpaper aux différentes catégories
    }
    function setWallpaperCategories($wallpaperID, $categories) {
        foreach ($categories as $cat) {
            $bdd = getBdd();
            $sql = 'INSERT INTO categorie_wallpaper VALUES(?,?)';
            $req = $bdd->prepare($sql);
            $req->execute(array($wallpaperID, $cat));
        }
    }
?>