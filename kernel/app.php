<?php

class App {

   protected $controller = "";

   protected $method = "";

   protected $params = [];

   protected $router;

   public function __construct() {
      $this->launch();
	  $this->initialize();
   }

    public function initialize() {
		// Si on trouve moins de wpp que ce nombre, on arrête
		if (! isset($_SESSION['minWPP'])) $_SESSION['minWPP'] = 3;
		// Si on atteint mbre de quce noestions, on arrête
		if (! isset($_SESSION['maxQuestion'])) $_SESSION['maxQuestion'] = 5;

		// Pour stocker le résultat
		if (! isset($_SESSION['$resultat'])) $_SESSION['$resultat'] = array('nb_wpp_left'=>0, 'wallpapers'=>array());
		// Le numéro de la question actuelle
		if (! isset($_SESSION['num_question'])) $_SESSION['num_question'] = 1;
		// L'importance qui sera de plus en plus petite
		if (! isset($_SESSION['importance'])) $_SESSION['importance'] = 50;
		// Un string qui contient les différents SELECT après chaque question
		if (! isset($_SESSION['requete'])) $_SESSION['requete'] = array("");
		// Si ce booléan est 'false', on s'arrête
		if (! isset($_SESSION['continue'])) $_SESSION['continue'] = false;
		// Stock les questions qui sont passées
		if (! isset($_SESSION['question'])) $_SESSION['question'] = array();
		// Empêche les fonctions d'être appelée à nouveau (pour éviter d'avoir une nouvelle question après un UNDO)
		if (! isset($_SESSION['lock'])) 
		{
			for($i = 0; $i < $_SESSION['maxQuestion']; $i++)
			{
				$_SESSION['lock'][$i] = false;
			}
		}
    }
   
   public function launch() {

      $this->router = new Router();

      // Verification et parsage de l'url, et récupération des controllers/actions a exécuter
      $urlInfo = Router::parseUrl(isset($_GET['url']) && !empty($_GET['url']) ? $_GET['url'] : "/");

      $codeSucces = $urlInfo['codeSucces'];

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
         if(file_exists(CONTROLLER_DIR . $urlR[0] . 'Controller.php')) {
            $this->controller = $urlR[0] . 'Controller';
            unset($url[0]);
         }
         else {
            $this->controller = new Controller();
            $this->method = "error";
            $this->params = [];
            call_user_func_array([$this->controller, $this->method], $this->params);
            return;
         }

         // On récupère le controller correspondant
         require_once CONTROLLER_DIR . $this->controller . '.php';
         // Instanciation
         $this->controller = new $this->controller;

         // On vérifie que l'action est définie dans la classe
         if (isset($urlR[1])) {
            if(method_exists($this->controller, $urlR[1])) {
               $this->method = $urlR[1];
               unset($url[1]);
            }
            else {
               $this->controller = new Controller();
               $this->method = "error";
               $this->params = [];
               call_user_func_array([$this->controller, $this->method], $this->params);
               return;
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
         return;
      }
   }

}