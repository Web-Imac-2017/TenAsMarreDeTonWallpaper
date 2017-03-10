<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Question extends Model {

    public function __construct(){
        parent::__construct();
    }

    // Renvoie les informations de toutes les questions
    public function getAll() {
        $bdd = Database::get();
        $data = "";
        
        $sqlQuery = 'SELECT * FROM question';

        try {

            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute();
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $data = $bddResult;

            return array("returnCode" => 1, "returnMessage" => "Questions récupérées",  "data" => $data);
        }

        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Renvoie les informations d'une seule question
    public function get($id) {       
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];
        $sqlQuery = 'SELECT * FROM question WHERE id=?';

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $success = $stmt->execute([$id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(!empty($bddResult)) {
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Question récupérée';
            }
            else {
                $result['returnCode'] = 0;
                $result['returnMessage'] = 'Echec de la requête : aucune question trouvée ayant pour id : '.$id;
            }
        }

        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();	// Changer pour le message de PDO	
        }

        return $result;
    }

    // Ajoute une question
    function add($q_courte, $q_longue, $importance, $idUser, $categories) {
        $bdd = Database::get();

        try {

        // Création de la mise en ligne
        $sqlQuery = 'INSERT INTO mise_en_ligne(statut  membre_id   moderateur_id) VALUES("En attente", 1, 1)';
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute();
        $miseEnLigneId = $bdd->lastInsertId();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

            try {
                $sqlQuery = 'INSERT INTO question(q_courte, q_longue, mise_en_ligne_id, importance, nb_apparition ) VALUES(?, ?, ?, ?, 0)';
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$q_courte, $q_longue, $miseEnLigneId, $importance]);

                $id_nouveau = $bdd->lastInsertId();

                $stmt = $bdd->prepare('SELECT * FROM question WHERE id = ?');
                $stmt->execute([$id_nouveau]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $result['data'] = $bddResult[0];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Insertion de la question réussie';

                addQuestionCategorie($id_nouveau, $categories);
            }

            catch (PDOException $e) {
                $result['returnCode'] = -1;
                $result['returnMessage'] = "Echec de la mise en ligne : " . $e->getMessage();	// Changer pour le message de PDO	
            } 
        }
        catch (PDOException $e) {
                $result['returnCode'] = -1;
                $result['returnMessage'] = "Echec de la mise en ligne : " . $e->getMessage();   // Changer pour le message de PDO
        }

        return $result;
    }

    // Associe une catégorie à une question
    function addQuestionCategorie($questionID, $categoriesID) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            foreach ($categoriesID as $cat) {
                $sql = 'INSERT INTO categorie_question VALUES(?, ?)';
                $req = $bdd->prepare($sql);
                $req->execute(array($questionID, $cat));
            }  
        }
        catch (PDOException $e) {

        }
    }
    
    function setImportance($questionID) {
		$bdd = getBdd();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
    		$sql = 'SELECT wallpaper_id, COUNT( * ) AS nb_wpp FROM categorie_wallpaper AS c_w INNER JOIN c_w.categorie_question ON categorie_id = categorie_question.categorie_id WHERE question_id=? GROUP BY wallpaper_id';
    		$importance = $bdd->prepare($sql);
    		$importance->execute(array($questionID));
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la mise à jour";
        }

		return $importance;
	}

    // Supprime une question
    function deleteQuestion($questionID) {
        $bdd = Database::get();
        $sql = 'DELETE FROM question WHERE id = ?';
        $req = $bdd->prepare($sql);
        $req->execute([$questionID]);
    }

    // Supprime une question de la table question_categorie
    function deleteQuestionCategorie($questionID) {
        $bdd = Database::get();
        $sql = 'DELETE FROM categorie_question WHERE question_id = ?';
        $req = $bdd->prepare($sql);
        $req->execute([$questionID]);
    }

    // Modifie une question
    function changeQuestion($questionID, $q_courte, $q_longue, $importance, $nb_apparition) {
        $bdd = Database::get();
        $sql = 'UPDATE question SET q_courte=:q_courte, q_longue=:q_longue, importance=:importance, nb_apparition=:nb_apparition WHERE id = :questionID';
        $req = $bdd->prepare($sql);
        $req->bindParam(':q_courte', $q_courte);
        $req->bindParam(':q_longue', $q_longue);
        $req->bindParam(':importance', $importance);
        $req->bindParam(':nb_apparition', $nb_apparition);
        $req->bindParam(':questionID', $questionID);
        $req->execute();
        //changeQuestionCategorie($questionID, $categorieID);
    }

    // Modifie la catégorie d'une question
    function changeQuestionCategorie($questionID, $categoriesID) {
        foreach ($categoriesID as $categoriesID):
        $bdd = Database::get();
        $sql = 'UPDATE categorie_question SET categorie_id=:categorieID WHERE question_id=:questionID';
        $req = $bdd->prepare($sql);
        $req->bindParam(':categorieID', $categoriesID);
        $req->bindParam(':questionID', $questionID);
        $req->execute();
        endforeach;
    }

    // Renvoie les catégories liées à la question
    function getQuestionCategories($questionID) {
        $i = 0;
        $bdd = Database::get();
        $sql = 'SELECT categorie_id AS cat_id FROM categorie_question WHERE question_id =?';
        $req = $bdd->prepare($sql);
        if($req->execute(array($questionID))) {
            while ($cat_id = $req->fetch()) {
                $categories[$i] = getCategorie($cat_id['cat_id']);
                $i++;
            }
            return $categories;
        }
        else
            throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
    }

    // Renvoie le nombre d'occurences d'une question dans la table question_categorie
    function getQuestionOccurences($questionID) {
        $bdd = Database::get();
        $sql = 'SELECT question_id AS id, COUNT( question_id ) AS nb_cat FROM categorie_question WHERE question_id =?';
        $req = $bdd->prepare($sql);
        $req->execute(array($questionID));
        if ($req->rowCount() >= 1)
        {
            $occurences = $req->fetch(); // Accès à la première ligne première colone de résultat (id)
            return $occurences['nb_cat'];
        }
        else
            throw new Exception("Aucune question ne correspond à l'identifiant '$questionID'");
    }

}

$question = new Question();
var_dump($question->getAll());