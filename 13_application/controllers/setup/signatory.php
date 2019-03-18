<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Signatory extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		
		$this->load->model('md_project');

	}

	public function index(){
				
		$this->lib_auth->title = "Signatory Setup";
		$this->lib_auth->build = "setup/signatory/signatory";

		$data['employee'] = $this->md_project->all_employee();

		$this->lib_auth->render($data);
		
	}

	public function signatory_print(){
				
		$this->lib_auth->title = "Signatory Setup";
		$this->lib_auth->build = "setup/signatory/signatory_print";

		$data['employee'] = $this->md_project->all_employee();

		$this->lib_auth->render($data);
		
	}

	public function addtolist(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['form']        = $this->input->post('form');
		$arg['signatory']   = $this->input->post('signatory');
		$arg['employee_id'] = $this->input->post('employee_id');
		$this->md_project->addtolist_signatory($arg);

		$arg['form'] = $this->input->post('form');
		$arg['signatory'] = $this->input->post('signatory');
		$result = $this->md_project->get_websignatory($arg);

		echo json_encode($result);

	}

	public function addtolist_print(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['form']        = $this->input->post('form');
		$arg['signatory']   = $this->input->post('signatory');
		$arg['designation'] = $this->input->post('designation');
		$arg['employee_id'] = $this->input->post('employee_id');
		$this->md_project->addtolist_signatory_print($arg);

		$arg['form'] = $this->input->post('form');
		$arg['signatory'] = $this->input->post('signatory');
		$result = $this->md_project->get_signatory_print($arg);

		echo json_encode($result);

	}

	public function signatory_list(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$arg['form'] = $this->input->post('form');
		$arg['signatory'] = $this->input->post('signatory');
		$result = $this->md_project->get_websignatory($arg);

		echo json_encode($result);

	}

	public function signatory_list_print(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$arg['form'] = $this->input->post('form');
		$arg['signatory'] = $this->input->post('signatory');
		$result = $this->md_project->get_signatory_print($arg);

		echo json_encode($result);

	}

	public function remove(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['employee_id'] = $this->input->post('employee_id');
		$arg['form']        = $this->input->post('form');
		$arg['signatory']   = $this->input->post('signatory');

		$this->md_project->remove_signatory($arg);

		$result = $this->md_project->get_websignatory($arg);

		echo json_encode($result);
		
	}

	public function remove_print(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg['id'] = $this->input->post('id');
		

		$this->md_project->remove_signatory_print($arg);

		$result = $this->md_project->get_signatory_print($arg);

		echo json_encode($result);
		
	}


}