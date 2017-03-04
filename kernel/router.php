<?php

/**
 * @brief Stocke la requête parsée de l'utilisateur.
 */
class Router {
    
    protected static $routes = array(); //*< Tableau des routes par pattern
    
    function __construct() {
        
    }

    // Récupérer toutes les routes
    public function getRoute() {
        return Self::$routes;
    }

    /**
     * Sert de setter
     * Utilisé par le fichier "routes.php"
     * @brief Crée une route.
     * @param in string $pat La route au format user-friendly.
     * @param in string $url L'url directement compréhensible par le dispatcher.
     * @param in array $elems Les élémens de l'url en clés et leur format PCRE en valeur
     * @return Description of returned value.
     */
    public static function connect($pat, $url, $elems) {
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
        Self::$routes[$pat] = $r;
    }

    // Cette fonction créé à partir de l'url donnée, un array contenant le controlleur utiliser ainsi que l'action
    public static function parseUrl($url) {

        // Si on se rend sur la page d'accueil
        if (preg_match("#^/$#", $url)) {
            $myRoute;
            foreach(Self::$routes as $route) {
                if (preg_match("#^/$#", $route['pattern'])) {
                    $myRoute = $route;
                    break;
                }
            }
            return array("codeSucces" => "1", "url" => $url, "controller" => $myRoute);   
        }
        else {
            // Vérification si la route est dans notre fichier de route & récupération du controller correspondant
            $urlInfo = Self::checkInRoutes($url);
            return $urlInfo;
        }
    }

    // Vérifie que la route existe 
    public static function checkInRoutes($url) {
        foreach (Self::$routes as $route) {
            if (preg_match($route['urlR'], $url))
                return array("codeSucces" => "1", "url" => $url, "controller" => $route);
        }
        return array("codeSucces" => "0", "url" => $url, "controller" => "");
    }

    /**
     * @brief Transforme la route $pat en regex en utilisant les valeurs du tableau $elems
     * @param in string $pat La route à transformer
     * @param in array $elems Les éléments de la route à placer, de la forme element => format PCRE
     * @return retourne la regex générée.
     */
    public static function regexize($pat, $elems) {
        return  '#^' . 
            preg_replace_callback(
                '#:([a-z]+)#', 
                function ($matches) use ($elems)
                {
                    if(isset($elems[$matches[1]]))
                        return '(' . $elems[$matches[1]] . ')';
                    else
                        return $matches[0];
                },
                $pat
            )
            . '$#';
    }

    /**
     * @brief Génère la route à partir du pattern $url
     * @param in string $url L'url à convertir en route.
     * @param in bool $relative Ajoute le http://<racine> devant la route finale, pour pouvoir l'utiliser directement dans un lien.
     * @return La route formattée, prête-à-utiliser.
     */
    public static function url($url, $relative = false) {
        $ret;
        foreach(self::$routes as $r)
        {
            $count = 0;
            $ret = preg_replace_callback(
                $r['urlR'],
                function ($matches) use ($r)
                {
                    // Cette fonction recopie la valeur des éléments de $url dans le pattern correspondant
                    //debugn('matches : ', $matches);
                    
                    // Tous les noms des elems à placer...
                    $searches = array_keys($r['elems']);
                    array_walk($searches,
                        function(&$s, $k)
                        {
                            $s =  ':'.$s;
                        }
                    );
                    //debugn('Searches : ', $searches);
                    
                    // ... et leur valeur.
                    $replaces = $matches;
                    foreach($replaces as $k => $v)
                    {
                        if(is_numeric($k)) unset($replaces[$k]);
                    }
                    //debugn('Replaces : ', $replaces);
                    
                    // Si le callback est exécuté alors on a trouvé une route, donc pas besoin de tester les autres !
                    $found = true;
                    
                    return str_replace($searches, $replaces, $r['pattern']);
                },
                $url,
                -1,
                $count
            );
            //debugn('RET : ', $ret);
            if($count)
            {
                if(!$relative) $ret = BASE_URL . $ret;
                //debugn('RET : ', $ret);
                return $ret;
            }
        }
        
        if(!$relative) $url = BASE_URL . $url;
        //debugn('URL : ', $url);
        return $url;
    }

}