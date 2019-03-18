<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_asset_setup');
		$this->lib_auth->default = "default-accounting";

	}


	public function index(){

		$this->lib_auth->title = "Bank Setup";		
		$this->lib_auth->build = "setup/asset_setup/add_bank";
		
		$this->lib_auth->render();

	}




}