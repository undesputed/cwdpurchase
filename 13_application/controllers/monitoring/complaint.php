<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complaint extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('monitoring/md_complaint');		
	}



	public function index(){

		$this->lib_auth->title = "Checklist Complaint";		
		$this->lib_auth->build = "monitoring/complaint/index";
					
		$data['unit'] = $this->md_complaint->get_unit();

		$this->lib_auth->render($data);

	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_complaint->get_display();

	}

	public function get_checklist(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo json_encode($this->md_complaint->checklist($this->input->post('unit')));	
	}



}