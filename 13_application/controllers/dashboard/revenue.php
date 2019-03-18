<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Revenue extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		
		$this->load->model('dashboard/md_revenue_update');		
	}

	public function index(){

		$this->lib_auth->title = "Revenue Update";		
		$this->lib_auth->build = "dashboard/revenue/index";
		$this->lib_auth->render();
		
	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->md_revenue_update->display();


	}



}