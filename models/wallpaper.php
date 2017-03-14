<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/TenAsMarreDeTonWallpaper/config.php';
require_once KERNEL . 'kernel.php';

class Wallpaper extends Model {

    public function __construct(){
        parent::__construct();
    }

    // Renvoie les informations d'un seul wallpaper
    public function get($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$id]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }


    public function getMines($membre_id, $nb) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper INNER JOIN mise_en_ligne on mise_en_ligne_id=mise_en_ligne.id WHERE membre_id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$membre_id]);
                $selection = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($nb <= 1) {
                    $wallpapers = $selection[array_rand($selection, 1)];
                }
                else {
                    if ($nb > count($selection)) {
                        $nb = count($selection);
                    }
                    $random=array_rand($selection, $nb);
                    for ($i=0; $i<$nb; $i++) {
                        $wallpapers[$i] = $selection[$random[$i]];
                    }
                }

                $data = $wallpapers;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Ajoute un nouveau wallpaper
    public function add($url, $url_thumb, $mel_id, $nom, $auteur, $width, $height, $format) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'INSERT INTO wallpaper VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$url, $url_thumb, $mel_id, $nom, $auteur, $width, $height, $format, date("Y-m-d"), 0, 0]);
                $lastInsertId = $bdd->lastInsertId();

                $sqlQuery = "SELECT * FROM wallpaper WHERE id=?";
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$lastInsertId]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult[0];

                return array("returnCode" => 1, "returnMessage" => "Wallpaper ajouté",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }

    }

    // Supprime un wallpaper, sa mise en ligne, ses réponses et ses catégories
    public function delete($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT mise_en_ligne_id FROM wallpaper WHERE id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $stmt->execute([$id]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $mel_id = $bddResult[0]['mise_en_ligne_id'];

                try {
                    $sqlQuery = 'DELETE FROM mise_en_ligne WHERE id=?';

                    try {
                        $stmt = $bdd->prepare($sqlQuery);
                        $stmt->execute([$mel_id]);

                        return array("returnCode" => 1, "returnMessage" => "Wallpaper supprimé",  "data" => $data);
                    }

                    catch (PDOException $e) {
                        return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
                    }
                }
                catch (PDOException $e) {
                    return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
                }
            }
            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Associe des catégories à un wallpaper
    public function setCategories($id, $categories) {
        foreach ($categories as $cat) {
            $bdd = Database::get();
            $sql = 'INSERT INTO categorie_wallpaper VALUES(?,?)';
            $req = $bdd->prepare($sql);
            $req->execute([$cat, $id]);
        }
    }

    // Modifie un wallpaper
    public function changewallpaper($wallpaperID, $url, $estapparent, $categories) {
        $bdd = Database::get();
        $sql = 'UPDATE Wallpaper SET url=?, estapparent=? WHERE id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($url, $estapparent, $wallpaperID));

        deleteWallpaperCategories($wallpaperID);
        setWallpaperCategories($wallpaperID, $categories);
    }

    // Supprimer toutes les catégories d'un wallpaper
    public function deleteWallpaperCategories($wallpaperID) {
        $bdd = Database::get();
        $sql = 'DELETE FROM WallpaperCategories WHERE wallpaper_id=?';
        $req = $bdd->prepare($sql);
        $req->execute(array($wallpaperID));
    }	

    // Renvoie les wallpapers appartenant à une catégorie
    public function getByCategorie($id) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper INNER JOIN categorie_wallpaper ON wallpaper.id=wallpaper_id WHERE categorie_id=?';

            try {
                $stmt = $bdd->prepare($sqlQuery);
                $success = $stmt->execute([$id]);
                $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $data = $bddResult;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    // Incrémente la colonne nb_apparition
    public function incrementer_nb_apparition($id) {
        $bdd = Database::get();
        $sqlQuery = "UPDATE wallpaper SET nb_apparition=nb_apparition+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);		
    }

    // Incrémente la colonne nb_telechargement
    public function incrementer_nb_telechargement($id) {
        $bdd = Database::get();
        $sqlQuery = "UPDATE wallpaper SET nb_telechargement=nb_telechargement+1 WHERE id=?";
        $stmt = $bdd->prepare($sqlQuery);
        $stmt->execute([$id]);		
    }

    public function random($nb) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = "SELECT * FROM wallpaper INNER JOIN mise_en_ligne ON wallpaper.id=mise_en_ligne.id WHERE statut='Validé'";

            try {
                $req = $bdd->prepare($sqlQuery);
                $req->execute();
                $selection = $req->fetchAll(PDO::FETCH_ASSOC);

                if ($nb <= 1) {
                    $wallpapers = $selection[array_rand($selection, 1)];
                }
                else {
                    if ($nb > count($selection)) {
                        $nb = count($selection);
                    }
                    $random=array_rand($selection, $nb);
                    for ($i=0; $i<$nb; $i++) {
                        $wallpapers[$i] = $selection[$random[$i]];
                    }
                }

                $data = $wallpapers;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    public function getMostDL($nb) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper ORDER BY nb_telechargement DESC';

            try {
                $req = $bdd->prepare($sqlQuery);
                $req->execute();
                $selection = $req->fetchAll(PDO::FETCH_ASSOC);

                if ($nb <= 1) {
                    $nb = 1;
                }
                else if ($nb > count($selection)) {
                    $nb = count($selection);
                }

                for ($i=0; $i<$nb; $i++){
                    $wallpapers[$i] = $selection[$i];
                }

                $data = $wallpapers;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    public function getMostAP($nb) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper ORDER BY nb_apparition DESC';

            try {
                $req = $bdd->prepare($sqlQuery);
                $req->execute();
                $selection = $req->fetchAll(PDO::FETCH_ASSOC);

                if ($nb <= 1) {
                    $nb = 1;
                }
                else if ($nb > count($selection)) {
                    $nb = count($selection);
                }

                for ($i=0; $i<$nb; $i++){
                    $wallpapers[$i] = $selection[$i];
                }

                $data = $wallpapers;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    public function latest($nb) {
        $bdd = Database::get();
        $data = "";

        try {
            $sqlQuery = 'SELECT * FROM wallpaper ORDER BY id DESC';

            try {
                $req = $bdd->prepare($sqlQuery);
                $req->execute();
                $selection = $req->fetchAll(PDO::FETCH_ASSOC);

                if ($nb <= 1) {
                    $nb = 1;
                }
                else if ($nb > count($selection)) {
                    $nb = count($selection);
                }

                for ($i=0; $i<$nb; $i++){
                    $wallpapers[$i] = $selection[$i];
                }

                $data = $wallpapers;

                return array("returnCode" => 1, "returnMessage" => "Requête réussie",  "data" => $data);
            }

            catch (PDOException $e) {
                return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
            }
        }
        catch (PDOException $e) {
            return array("returnCode" => -1, "returnMessage" => $e->getMessage(),  "data" => $data);
        }
    }

    public function getUrl($id) {
        $bdd = Database::get();
        $result = ["returnCode" => "", "returnMessage" => "",  "data" =>  ""];

        $sqlQuery = 'SELECT url FROM wallpaper WHERE id = ?';

        try {
            $stmt = $bdd->prepare($sqlQuery);
            $stmt->execute([$id]);
            $bddResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result['data'] = $bddResult[0]['url'];
            $result['returnMessage'] = "Récupération de l'url OK";
            $result['returnCode'] = 1;
        }
        catch (PDOException $e) {
            $result['returnMessage'] = $e->getMessage();
            $result['returnCode'] = 0;
        }


        return $result;
    }

}