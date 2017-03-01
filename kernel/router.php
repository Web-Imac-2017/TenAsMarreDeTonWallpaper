<?php

/**
 * @brief Stocke la requête parsée de l'utilisateur.
 */
class Router {
    
    public static $routes = array(); //*< Tableau des routes par pattern
    
    function __construct() {
        
    }


    public static function parseUrl($url) {

        // On retourne l'url en un array, avec chaque variables qui étaient séparées par des "/"
        return $url = explode("/", filter_var(trim($_GET['url'], "/"), FILTER_SANITIZE_URL));
    }

    /**
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
        self::$routes[$pat] = $r;
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
                        return '(?P<' . $matches[1] . '>' . $elems[$matches[1]] . ')';
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
    public static function url($url, $relative = false)
    {
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