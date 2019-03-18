<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Material_transfer extends CI_Controller {
	parent :: __construct();		
		$this->load->model('setup/md_material_transfer');

	}


	public function index(){

		$this->lib_auth->title = "Material Transfer";		
		$this->lib_auth->build = "procurement/material_transfer/index";
		
		$this->lib_auth->render();

	}

}
