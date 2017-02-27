<?php // Ici c'est le VRAI index.php
// METTEZ PLEIN DE TRUCS


var_dump($_SERVER['REQUEST_URI']);

require_once "./kernel/kernel.php";

//var_dump('Ceci est api.php');
var_dump($_SERVER);


Kernel::run();