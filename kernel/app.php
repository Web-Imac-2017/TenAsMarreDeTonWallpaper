<?php

class App {

   protected $controller = "home";

   protected $method = "index";

   protected $params = [];

   public function __construct() {

      // Verification et parsage de l'url
      $url = Router::parseUrl(isset($_GET['url']) ? $_GET['url'] : "home/index");

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

      call_user_func_array([$this->controller, $this->method], $this->params);

   }

}