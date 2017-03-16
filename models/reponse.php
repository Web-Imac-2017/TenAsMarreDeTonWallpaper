<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Reponse extends Model {

    public function __construct(){
        parent::__construct();
    }

    public function add($question_id, $wallpaper_id, $val_min, $val_max) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'INSERT INTO reponse VALUES(NULL, ?, ?, ?, ?)';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$question_id, $wallpaper_id, $val_min, $val_max]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult[0];

                return array("returnCode" => 1, "returnMessage" => "Réponse ajoutée",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }
    
    // Supprime les réponses d'un wallpaper
    public function delete($id) {
        $bdd = Database::get();
        $sql = 'DELETE FROM reponse WHERE wallpaper_id=?';
        $req = $bdd->prepare($sql);
        $req->execute([$id]);
    }

    // Met à jour de l'importance de la question
    public function importance($qid) {
        $bdd = Database::get();

        $query1 = 'SELECT COUNT(DISTINCT wallpaper_id) as nb FROM reponse WHERE question_id = ? AND val_min>=50 AND val_max <=100';
        $query2 = 'SELECT COUNT(DISTINCT wallpaper_id) as nb FROM reponse WHERE question_id = ? AND val_min>=0 AND val_max <=50';
        $query3 = 'SELECT COUNT(*) as nb FROM wallpaper';
        $query4 = 'UPDATE question SET importance = ? WHERE id= ?';    //1st req
        //1st req
        try {
            $req1 = $bdd->prepare($query1);
            $req1->execute(array($qid));
            $res1 = $req1->fetchAll(PDO::FETCH_ASSOC);
            if($res1) {
                $val1=$res1[0]['nb'];
            }
        }
        catch (PDOException $e) {
            //error
        }
        $req1->closeCursor();
        //2nd req
        try {
            $req2 = $bdd->prepare($query2);
            $req2->execute(array($qid));
            $res2 = $req2->fetchAll(PDO::FETCH_ASSOC);
            if($res2) {
                $val2=$res2[0]['nb'];
            }
        }
        catch (PDOException $e) {
            //error
        }
        $req2->closeCursor();
        //3rd req
        try {
            $req3 = $bdd->prepare($query3);
			$req3->execute();
            $res3 = $req3->fetchAll(PDO::FETCH_ASSOC);
            if($res3) {
                $val3=$res3[0]['nb'];
            }
        }
        catch (PDOException $e) {
            //error
        }
        $req3->closeCursor();

        $result=($val1<$val2)?$val1/$val3*100:$val2/$val3*100;

        //4rd req
        try {
            $req4 = $bdd->prepare($query4);
            $req4->execute(array($result,$qid));
        }
        catch (PDOException $e) {
            //error
        }
        $req4->closeCursor();

    }
}