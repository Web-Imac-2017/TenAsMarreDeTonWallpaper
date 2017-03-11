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
            $result['returnMessage'] = "Echec de la connexion : " . $e->getMessage();   // Changer pour le message de PDO   
        }

        return $result;
    }

    // Ajoute une question
    function add($q_courte, $q_longue, $importance, $idUser, $categories) {
        $bdd = Database::get();

        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sqlQuery = 'SELECT COUNT(*) FROM membre WHERE id = ? and (moderateur = 1 or admin = 1)';
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$idUser]);
            $validationAuto = $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['COUNT(*)'];
            if ($validationAuto)
                $statut = "Validé";
            else
                $statut = "En attente";

            try {

                // Création de la mise en ligne
                $sqlQuery = 'INSERT INTO mise_en_ligne(statut, membre_id, moderateur_id) VALUES(?,?, 1)';
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$statut,$idUser]);
                $miseEnLigneId = $bdd->lastInsertId();

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

                    $this->addQuestionCategorie($id_nouveau, $categories);
                }

                catch (PDOException $e) {
                    $result['returnCode'] = -1;
                    $result['returnMessage'] = "Echec de la mise en ligne : " . $e->getMessage(); 
                }
            }

            catch (PDOException $e) {
                $result['returnCode'] = -1;
                $result['returnMessage'] = "Echec de la mise en ligne : " . $e->getMessage(); 
            } 
        }
        catch (PDOException $e) {
                $result['returnCode'] = -1;
                $result['returnMessage'] = "Echec de la mise en ligne : " . $e->getMessage();
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
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Insertion des catégories pour la question réussie';
        }
        catch (PDOException $e) {
                $result['returnCode'] = -1;
                $result['returnMessage'] = "Echec de l'ajout de la catégorie à la question' : " . $e->getMessage(); 
        }
        return $result;
    }
    
    function setImportance($questionID) {
        $bdd = getBdd();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sql = 'SELECT wallpaper_id, COUNT(*) AS nb_wpp FROM categorie_wallpaper AS c_w INNER JOIN c_w.categorie_question ON categorie_id = categorie_question.categorie_id WHERE question_id=? GROUP BY wallpaper_id';
            $importance = $bdd->prepare($sql);
            $importance->execute(array($questionID));
            $result['data'] = $importance->fetchAll(PDO::FETCH_ASSOC)[0]['nb_wpp'];
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Mise à jour de l\'importance OK';
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la mise à jour";
        }

        return $result;
    }

    // Supprime une question
    function deleteQuestion($questionID) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];
        try {
            $sql = 'DELETE FROM question WHERE id = ?';
            $req = $bdd->prepare($sql);
            $req->execute([$questionID]);
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Suppression de la question OK';
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la suppression";
        }
        return $result;
    }

    // Supprime une question de la table question_categorie
    function deleteQuestionCategorie($questionID) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sql = 'DELETE FROM categorie_question WHERE question_id = ?';
            $req = $bdd->prepare($sql);
            $req->execute([$questionID]);
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Suppression de la categorie / question OK';
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la suppression";
        }
    }

    // Modifie une question
    function changeQuestion($questionID, $q_courte, $q_longue, $importance, $nb_apparition) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {

            $sql = 'UPDATE question SET q_courte=:q_courte, q_longue=:q_longue, importance=:importance, nb_apparition=:nb_apparition WHERE id = :questionID';
            $req = $bdd->prepare($sql);
            $req->bindParam(':q_courte', $q_courte);
            $req->bindParam(':q_longue', $q_longue);
            $req->bindParam(':importance', $importance);
            $req->bindParam(':nb_apparition', $nb_apparition);
            $req->bindParam(':questionID', $questionID);
            $req->execute();
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Mise à jour de la question OK';
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la mise à jour";
        }
        return $result;
    }

    // Modifie la catégorie d'une question
    function changeQuestionCategorie($questionID, $categoriesID) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $this->deleteQuestionCategorie($questionID);
            $this->addQuestionCategorie($questionID, $categoriesID);
            $result['returnCode'] = 1;
            $result['returnMessage'] = 'Mise à jour de la catégorie d\'une question OK';
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la mise à jour";
        }


    }

    // Renvoie les catégories liées à la question
    function getQuestionCategories($questionID) {
        $i = 0;
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sql = 'SELECT categorie_id AS cat_id FROM categorie_question WHERE question_id =?';
            $req = $bdd->prepare($sql);
            $req->execute(array($questionID));
            $categories = [];
            while ($cat_id = $req->fetch()) {
                $categories[$i] = getCategorie($cat_id['cat_id']);
                $i++;
            }

            $result['data'] = $categories;

            if (empty($categories)) {
                $result['returnCode'] = 0;
                $result['returnMessage'] = "Aucune catégorie ne correspond à la question à l'identifiant '$questionID'";
            }
            else {
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Récupération des catégories pour une question OK';
            }
        }
        catch (PDOException $e){
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la récupération des catégories";
        }
        return $result;
    }

    // Renvoie le nombre d'occurences d'une question dans la table question_categorie
    function getQuestionOccurences($questionID) {
        $bdd = Database::get();
        $result = ['returnCode' => '', 'returnMessage' => '', 'data' => ''];

        try {
            $sql = 'SELECT question_id AS id, COUNT( question_id ) AS nb_cat FROM categorie_question WHERE question_id =?';
            $req = $bdd->prepare($sql);
            $req->execute(array($questionID));

            if ($req->rowCount() >= 1) {
                $occurences = $req->fetch(); // Accès à la première ligne première colone de résultat (id)
                $result['data'] = $occurences['nb_cat'];
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'Récupération du nombre d\'apparition d\'une question OK';
            }
            else {
                $result['returnCode'] = 1;
                $result['returnMessage'] = 'La question n\'apparaît aucune fois';
            }
        }
        catch (PDOException $e) {
            $result['returnCode'] = -1;
            $result['returnMessage'] = "Echec de la récupération des catégories";

        }
        return $result;

    }

}

$question = new Question();
//var_dump($question->add("Ro", "Roberto", "10", "1", array()));
var_dump($question->getAll());
//var_dump($question->get(4));
//var_dump($question->deleteQuestion(4));
var_dump($question->getQuestionCategories(3));