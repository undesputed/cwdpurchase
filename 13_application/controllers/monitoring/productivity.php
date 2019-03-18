<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Productivity extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('monitoring/md_productivity');
	}


		public function index(){
		$this->lib_auth->title = "Equipment Productivity ";		
		$this->lib_auth->build = "monitoring/productivity/index";		
		$this->lib_auth->render();
	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		$this->md_productivity->get_display();

		
		
	}



}

