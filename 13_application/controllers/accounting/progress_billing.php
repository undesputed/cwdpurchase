<?php defined('BASEPATH') OR exit('No direct script access allowed');

class progress_billing extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
				
		$this->load->model('md_project');
		$this->load->model('accounting/md_progress_billing');
		
	}


	public function index(){

		$this->lib_auth->title = "Progress Billing Entry";		
		$this->lib_auth->build = "accounting/progress_billing/entry";


		$data['project'] = $this->md_project->get_profit_center();
		$data['project_category'] = $this->md_progress_billing->get_project_category();

		$this->lib_auth->render($data);

	}





}