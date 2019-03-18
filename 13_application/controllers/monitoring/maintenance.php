<?php defined('BASEPATH') OR exit('No direct script access allowed');

class maintenance extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('monitoring/md_maintenance');
	}

	
	public function index(){
		$this->lib_auth->title = "Equipment Maintenance";		
		$this->lib_auth->build = "monitoring/maintenance/index";
		$this->lib_auth->render();
		
	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->md_maintenance->display();


	}



}