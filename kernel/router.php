<?php

/**
 * @brief Stocke la requête parsée de l'utilisateur.
 */
class Router {
    
    public $url;
    public $request;
    public $controller;
    public $action;
    public $params = array();
    
    function __construct()
    {
        
    }
      
    public static function getClientUrl() {
        $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public static function analyze( $query ) {
        $result = array(
            "controller" => "Error",
            "action" => "error404",
            "params" => array()
        );
        if( $query === "" || $query === "/" ) {
            $result["controller"] = "Index";
            $result["action"] = "display";
        } 
        else {
            $parts = explode("/", $query);
            if($parts[0] == "item" && count($parts) == 2) {
                $result["controller"] = "Item";
                $result["action"] = "display";
                $result["params"]["slug"] = $parts[1];            
            }
        }
        return $result;
    }

    public function parse($url) {

    }

}

$router = new Router();
$query = $router->getClientUrl();
echo $query;
echo "<br>";
print_r($router->analyze($query));
//echo ROOT;