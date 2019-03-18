<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('dashboard/md_maintenance_update');
	}

	public function index(){

		$this->lib_auth->title = "Maintenance Update";		
		$this->lib_auth->build = "dashboard/maintenance/index";
		
		$this->lib_auth->render();
		
	}

	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->md_maintenance_update->display();


	}



}