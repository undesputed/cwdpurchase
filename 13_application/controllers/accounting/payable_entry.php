<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payable_entry extends CI_Controller {

	public function __construct(){
		parent :: __construct();	

		$this->lib_auth->default = "default-accounting";	
		$this->load->model('md_project');
	}

	public function index(){

		$this->lib_auth->title = "Payable List";
		$this->lib_auth->build = "accounting/payable_entry/payable_entry";
		
		$data['project'] = $this->md_project->get_profit_center();

		$data['supplier']['Affiliate'] = $this->md_project->get_supplier_affiliate();
		$data['supplier']['Business']  = $this->md_project->get_supplier_business();

		$this->lib_auth->render($data);

	}

}