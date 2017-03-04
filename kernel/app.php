<?php

class App {

   protected $controller = "";

   protected $method = "";

   protected $params = [];

   protected $router;

   public function __construct() {
      $this->launch();
   }

   public function launch() {


      $this->router = new Router();

      // Verification et parsage de l'url, et récupération des controllers/actions a exécuter
      $urlInfo = Router::parseUrl(isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : "/");

      $codeSucces = $urlInfo['codeSucces'];
      var_dump($urlInfo);

      // Si la route existe
      if ($codeSucces) {

         // On décompose l'url en un array, avec chaque variables qui étaient séparées par des "/"
         $url = $urlInfo['url'];
         $urlR  = $urlInfo['controller']['url'];

         // Notre url de base, en forme d'array pour avoir les actions/controllers/valeurs des parametres
         $url = explode("/", filter_var(trim($url, "/"), FILTER_SANITIZE_URL));
         // Notre url de base, en forme d'array pour avoir les actions/controllers/valeurs des parametres (l'url l'url n'ont pas forcément les mêmes noms au niveau des controller/action)
         $urlR = explode("/", filter_var(trim($urlR, "/"), FILTER_SANITIZE_URL));

         // On vérifie que le fichier de controller existe
         if(file_exists(ROOT . CONTROLLER_DIR . $urlR[0] . 'Controller.php')) {
            $this->controller = $urlR[0];
            unset($url[0]);
         }
         else {
            $this->controller = new Controller();
            $this->method = "error";
            $this->params = [];
            call_user_func_array([$this->controller, $this->method], $this->params);
         }

         // On récupère le controller correspondant
         require_once ROOT . CONTROLLER_DIR . $this->controller . '.php';
      
         // Instanciation
         $this->controller = new $this->controller;

         // On vérifie que l'action est définie dans la classe
         if (isset($url[1]) && isset($urlR[1])) {
            if(method_exists($this->controller, $urlR[1])) {
               $this->method = $urlR[1];
               unset($url[1]);
            }
            else {
               $this->controller = new Controller();
               $this->method = "error";
               $this->params = [];
               call_user_func_array([$this->controller, $this->method], $this->params);
            }     
         }

         // On récupère les paramètres
         $this->params = $url ? array_values($url) : [];

         // On appelle l'action correspondante, pour le bon controller avec les paramètres donnés
         call_user_func_array([$this->controller, $this->method], $this->params);
      }

      else {
         $this->controller = new Controller();
         $this->method = "error";
         $this->params = [];
         call_user_func_array([$this->controller, $this->method], $this->params);
      }
   }

}