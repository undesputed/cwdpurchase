<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Billing_statement extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_billing_statement');
		$this->load->model('md_project');
		$this->load->model('accounting/md_ledger');	


	}

	public function index(){

		$this->lib_auth->title = "Billing Statement";
		$this->lib_auth->build = "accounting/billing_statement/index";


		$data['business'] = json_encode($this->md_ledger->business());		
		$data['person']   = json_encode($this->md_ledger->person());
		$data['project_category'] = $this->md_project->get_project_category();

		$this->lib_auth->render($data);
		
	} 

	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		echo $this->md_billing_statement->save();

	}

	public function cumulative(){

		$this->lib_auth->title = "Billing Statement Cumulative";
		$this->lib_auth->build = "accounting/billing_statement/cumulative";
				
		$this->lib_auth->render();
	}

	public function view_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$project_id = $this->input->post('project_id');
		$all = $this->input->post('all');
		if($all == 'true'){
			$result = $this->md_billing_statement->get_all_invoice($project_id);		
		}else{
			$from = $this->input->post('from');
			$to   = $this->input->post('to');
			$result = $this->md_billing_statement->get_invoice_date($project_id,$from,$to);
		}
		
		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$header = array(
				array('data'=>'invoice_id','style'=>"display:none"),
				'INVOICE NO.',
				'INVOICE DATE',
				'DUE DATE',
				'CUSTOMER',
				'TYPE',
				'P.O NO.',
				'TERMS',
				'AMOUNT',
				'BALANCE'
			 		);
			foreach($result as $key => $row){
				$row_content = array();

				$row_content[] = array('data'=>$row['INVOICE NO.'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['INVOICE DATE'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['DUE DATE'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['CUSTOMER'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['TYPE'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['P.O NO.'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['TERMS'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['AMOUNT'],'style'=>'','class'=>'');
				$row_content[] = array('data'=>$row['BALANCE'],'style'=>'','class'=>'');
				
				$this->table->add_row($row_content);

			}
			$this->table->set_heading($header);
			echo $this->table->generate();

	}


}