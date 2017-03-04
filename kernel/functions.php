<?php

// Des fonctions utiles un peu partout.

/**
 * @brief Retoune un booléen pour savoir à un endroit T si on est en mode debug.
 * @param in string $str La clé de débogage. la constante DEBUG doit valoir true ou doit contenir $str pour que le debug soit activé à cet endroit.
 * @return Vrai ou faux.
 */
function isdebug($str = null)
{
    if (DEBUG === true) return true;
    if (DEBUG === false) return false;
    if ($str && is_array(DEBUG) && in_array($str, DEBUG))
        return true;
    return false;
}

/**
 * @brief Affiche le débogage d'une variable à l'aide de var_dump().
 * @param in mixed $var La variable à déboguer.
 * @param in string $k La clé de débogage.
 * @param in bool $b Affiche la pile d'appel si vrai.
 * @return Cette fonction ne retourne rien
 */
function debug($var, $k = null, $b = false)
{
    if(isdebug($k))
    {
        var_dump($var);
        if($b) var_dump(debug_backtrace());
    }
}
/**
 * @brief Affiche le débogage d'une variable à l'aide de var_dump() de façon repérable.
 * @param in string $echo $echo sera affiché avant le var_dump de façon lisible.
 * @param in mixed $var La variable à déboguer.
 * @param in string $k La clé de débogage.
 * @param in bool $b Affiche la pile d'appel si vrai.
 * @return Cette fonction ne retourne rien
 */
function debugn($echo, $var, $k = null, $b = false)
{
    if(isdebug($k))
    {
        echo '<div style="
                display : inline-block;
                background : #fdd;
                border : solid 1px red;
                padding : 4px;
                overflow : visible;
                font-size : 1rem;
              ">';
        echo '<div style="
                font-weight : bold;
                background : #fff;
                padding : 4px;
              ">',
            $echo,
            '</div>';
        var_dump($var);
        
        if($b)
        {
            echo '<div style="
                background : #fff0f0;
              ">';
            var_dump(debug_backtrace());
            echo '</div>';
        }
        
        
        echo '</div>';
    }
}



/**
 * @brief Générateur de N entiers alétoires différents.
 */
class uniqueRandom { 
    public $rands = array(); 
    function generate($min, $max) { 
        while ($rand = mt_rand($min, $max) && array_diff(range($min, $max), $this -> rands)) { 
            if (!in_array($rand, $this -> rands)) { 
                $this-> rands [] = $rand; 
                return $rand; 
            } 
        } 
    } 
} 