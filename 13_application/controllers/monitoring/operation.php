<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Operation extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('monitoring/md_operation');
	}

	

	public function index(){
		$this->lib_auth->title = "Equipment Operation ";		
		$this->lib_auth->build = "monitoring/operation/index";		
		$this->lib_auth->render();
	}

	
	
	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_operation->get_display();
	}
	
	

	
}
