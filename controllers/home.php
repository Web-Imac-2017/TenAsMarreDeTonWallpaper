<?php

require_once "./kernel/controller.php";

class Home extends Controller {

	public function __construct(){
		
	}

	public function index() {
		var_dump("home/index");
	}
}