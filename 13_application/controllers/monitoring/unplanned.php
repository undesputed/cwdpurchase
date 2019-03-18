<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Unplanned extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('monitoring/Md_unplanned');
	}


	public function index(){

		$this->lib_auth->title = "Unplanned Work";		
		$this->lib_auth->build = "monitoring/unplanned/index";

		$data['unit'] = $this->Md_unplanned->get_unit();

		$this->lib_auth->render($data);
		

	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->Md_unplanned->get_display();

	}



	public function get_model_no(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo json_encode($this->Md_unplanned->get_model($this->input->post('model_id')));

	}
}