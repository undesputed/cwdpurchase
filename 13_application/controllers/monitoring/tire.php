<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tire extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('monitoring/md_tire');

	}


	public function index(){

		$this->lib_auth->title = "Tire Monitoring";		
		$this->lib_auth->build = "monitoring/tire/index";

		$data['unit'] = $this->md_tire->get_unit();

		$this->lib_auth->render($data);
		
	}



	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_tire->get_display();

	}


}