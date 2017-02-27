<?php

// All necessary includes & requires

require_once KERNEL . "model.php";
require_once KERNEL . "router.php";
require_once KERNEL . "app.php";

require_once ROOT . "routes.php";

// Start session

session_start();

$app = new App();

/*c lass Kernel {

   public static function autoload($class) {

      if(file_exists("../kernel/$class.php")) {
         require_once("../kernel/$class.php"); echo "ok"; }
      else if(file_exists("../controller/$class.php")) {
         require_once("../controller/$class.php");}
      else if(file_exists("../model/$class.php")) {
         require_once("../model/$class.php");}
   }

   public static function run() {
      // Autoload
      spl_autoload_register(array("Kernel", "autoload"));
      // Etape 1 : Récupérer la requete
      $query = isset($_SERVER["PATH_INFO"]) ? trim($_SERVER['PATH_INFO'], "/") : "";
      var_dump($query);
      // Etape 2 : Apeller le bon controlleur
      $route = Router::analyze( $query );
      // Instancier le controleur et
      // executer l'action
      $class = $route["controller"]."Controller";
      if(class_exists($class)) {
         $controller = new $class ($route);
         $method = array($controller, $route["action"]);
         if( is_callable( $method ))
            call_user_func($method);
      }
      // Gestion des erreurs
   }
   
}


$kernel = new Kernel();
$kernel->autoload('model');
$kernel->run();*/