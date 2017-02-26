<?php

/**
 * @brief Stocke la requête parsée de l'utilisateur.
 */
class Router
{
    
    public $url;
    public $controller;
    public $action;
    public $params = array();
    
    function __construct()
    {
        
    }
      
    public static function getClientUrl() {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    public function getRequest() {

    }

}