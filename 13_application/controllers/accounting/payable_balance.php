<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payable_balance extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_payable_list');
		$this->load->model('md_project');
		
	}

	public function index(){

		$this->lib_auth->title = "Payable Balance";
		$this->lib_auth->build = "accounting/payable_list/payable_balance";
				
		$data['project'] = $this->md_project->get_profit_center();
		$data['supplier'] = $this->md_payable_list->get_supplier();		
		$data['bank_setup'] = $this->md_payable_list->get_bank_setup();
		$this->lib_auth->render($data);		
		
	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();

		$result = $this->md_payable_list->supplier_balance($arg);		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed table-hover">');
		$this->table->set_template($tmpl);

			$show = array(
					'Supplier',
					'Payables Balance',
					'',
			 		);
				$total = 0;
				foreach($result as $key => $value){
					$row_content = array();
					$row_content[] = array('data'=>$value['Supplier'],'class'=>'supplier');
					$row_content[] = array('data'=>$this->extra->number_format($value['payables']),'class'=>'payables');
					$row_content[] = array('data'=>'<span class="btn-link view_info" data-supID="'.$value['supplierID'].'">view</span>','class'=>'view');
					$this->table->add_row($row_content);
					$total = $total + $value['payables'];
				}
				$row_content = array();
				$row_content[] = array('data'=>'<strong>Total</strong>','class'=>'');
				$row_content[] = array('data'=>$this->extra->number_format($total),'class'=>'total');
				$row_content[] = array('data'=>'','class'=>'');

				$this->table->add_row($row_content);

		$this->table->set_heading($show);
		echo $this->table->generate();		
	}

	public function view_info(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg    = $this->input->post();
		$data['result'] = $this->md_payable_list->view_info($arg);
		$projects = array();
		foreach($data['result'] as $row){
			$projects[$row['project_requestor']][] = $row;
		}
		$data['projects'] = $projects;
		$this->load->view('accounting/payable_list/view_info',$data);
	}
}

