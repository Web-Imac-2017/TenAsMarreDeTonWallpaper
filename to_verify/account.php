<?php
    function getaccount($_SESSION,$bddpdo){
        if((isset($_SESSION['pseudo']) || isset($_SESSION['mail'])) && isset($_SESSION['mdp'])) {
            $reponse = $bddpdo->prepare('SELECT * FROM Membre 
            WHERE mdp=? AND 
            (pseudo=? OR mail=?)');
            $reponse->execute(array($SESSION['mdp'],$_SESSION['pseudo'],$_SESSION['mail']));
        }
        if($exist=$reponse->rowCount()){
            $donnee=$reponse->fetch();
            $_SESSION['tab']=$donnee;
        }
        $reponse->closeCursor();

    }
    /*on suppose session actualisé avec les modifs*/
    function modifaccount($_SESSION,$bddpdo){
        $reponse = $bddpdo->prepare('DELETE FROM Membre 
        WHERE id=?');
        $reponse->execute(array($_SESSION['tab']['id']));
        $reponse->closeCursor();
    
        $reponse = $bddpdo->prepare('INSERT INTO Membre(id,mail,pseudo,mdp,est_modo,est_admin,est_ban)
        VALUES(:id,:mail,:pseudo,:mdp,:est_modo,:est_admin,:est_ban)');
    
        $reponse->execute(array(
            'id' => $_SESSION['tab']['id'],
            'mail' => $_SESSION['tab']['mail'],
            'pseudo' => $_SESSION['tab']['pseudo'],
            'mdp' => $_SESSION['tab']['mdp'],
            'est_modo' => $_SESSION['tab']['est_modo'],
            'est_admin' => $_SESSION['tab']['est_admin'],
            'est_ban' => $_SESSION['tab']['est_ban']
        ));
        $reponse->closeCursor();
    }

    function supaccount($id,$bddpdo){
        $reponse = $bddpdo->prepare('DELETE FROM Membre 
        WHERE id=$id');
        $reponse->closeCursor();
    }
?>