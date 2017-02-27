<?php

//require_once("router.php");

class Kernel {

   protected $controller = "home";

   protected $method = "index";

   protected $params = [];

   public function __construct() {

      $url = $this->parseUrl();

      print_r($url);

      if(file_exists(ROOT . CONTROLLER_DIR . $url[0] . '.php')) {
         $this->controller = $url[0];
         unset($url[0]);
      }

      require_once ROOT . CONTROLLER_DIR . $this->controller . '.php';
   
      $this->controller = new $this->controller;

      if (isset($url[1])) {
         {
            if(method_exists($this->controller, $url[1])) {
               $this->method = $url[1];
               unset($url[1]);
            }
         }
      }

      $this->params = $url ? array_values($url) : [];

      print_r($this->params);

      call_user_func_array([$this->controller, $this->method], $this->params);

   }

   public function parseUrl() {
      if (isset($_GET['url'])) {
         return $url = explode("/", filter_var(trim($_GET['url'], "/"), FILTER_SANITIZE_URL));
      }
   }

}

$kernel = new Kernel();

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