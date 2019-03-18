<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('monitoring/md_schedule');			
	}


	public function index(){

		$this->lib_auth->title = "Schedule Service";		
		$this->lib_auth->build = "monitoring/schedule/index";
		
		$data['unit'] = $this->md_schedule->get_unit();
		$this->lib_auth->render($data);


	}

	
	
	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_schedule->get_display();

	}

}