<?php
  /*veiller à sauvegarder dans la session l'id du visiteur*/
    function permission(){
        $bdd = getBdd();
        $sql = 'SELECT est_admin,est_modo FROM Membre WHERE id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($_SESSION['id']));
    
        if($req){
            $res=$req->fetch();
            if ($res['est_admin']) {
                return 2;
            }elseif ($res['est_modo']) {
                return 1;
            }else{
                echo 'no permission'
                return 0;
            }
        }else{
            return -1;
        }
    }
    function checkemail($email){
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 1;
        }
        return 0;
    }
?>
