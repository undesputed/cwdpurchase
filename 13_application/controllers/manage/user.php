<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
	}

	public function index(){

		$this->lib_auth->title = "Manage Account";		
		$this->lib_auth->build = "user/index";
		

		$this->lib_auth->render();
		
	}



}