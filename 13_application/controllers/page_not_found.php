<?php defined('BASEPATH') OR exit('No direct script access allowed');

class page_not_found extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
	}


	public function index(){

		$this->lib_auth->title = "Page Not Found";		
		$this->lib_auth->build = "404/index";
		

		$this->lib_auth->render();
		
	}

}