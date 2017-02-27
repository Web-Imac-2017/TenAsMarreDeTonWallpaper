<?php

/**
 * @brief Stocke la requête parsée de l'utilisateur.
 */
class Router {
    
    public static $routes = array(); //*< Tableau des routes par pattern
    
    function __construct()
    {
        
    }

    /**
     * @brief Crée une route.
     * @param in string $pat La route au format user-friendly.
     * @param in string $url L'url directement compréhensible par le dispatcher.
     * @param in array $elems Les élémens de l'url en clés et leur format PCRE en valeur
     * @return Description of returned value.
     */
    public static function connect($pat, $url, $elems)
    {
        $r = array();
        // Récupération du pattern
        $r['pattern'] = $pat;
        $r['patternR'] = self::regexize($pat, $elems);
        //debug($r['patternR']);
        
        // Récupération de la destination
        $r['url'] = $url;
        $r['urlR'] = self::regexize($url, $elems);
        //debug($r['urlR']);
        
        // Récupération des éléments
        $r['elems'] = $elems;
        
        // Insertion de la route (On utilise $pat comme index pour éviter les multis)
        self::$routes[$pat] = $r;

        var_dump($r);
    }
}