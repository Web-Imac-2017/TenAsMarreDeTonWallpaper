<?php

// Des fonctions utiles un peu partout.

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