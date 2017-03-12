<?php

class Controller {

	public function __construct() {
		
	}

	public function error() {
		$returnMessage = utf8_encode("La route est inexistante ou invalide, vérifiez les paramètres");
		$data = ["returnCode" => 1, "returnMessage" => $returnMessage, "data" => ''];
		echo json_encode($data);
	}
}