<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		
	}

	public function index(){

		if($this->session->userdata('type_user')=='dispatcher'){
			redirect(base_url().index_page()."/dispatch");
		}
		
		$this->lib_auth->title = "Welcome";
		$this->lib_auth->build = "main/design1";		
		$this->lib_auth->render();
	}
	

}


