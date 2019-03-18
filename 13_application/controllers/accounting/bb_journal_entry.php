<?php defined('BASEPATH') OR exit('No direct script access allowed');

class journal_entry extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_journalEntry');			
	}


	public function index(){
		
		$this->lib_auth->title = "Journal Entry";
		$this->lib_auth->build = "accounting/journal_entry/index";
		$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();
		$data['pay_item'] = $this->md_project->get_project_category();
		$this->lib_auth->render($data);
		
	}


	public function get_payTo(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$result = $this->md_journalEntry->get_payTo();	
		echo json_encode($result);
		

	}

	public function get_accountType(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_journalEntry->get_accountType($this->input->post('account_id'));

		$output = array();
		$output['ledger']         = $result['ledger'];
		$output['column']         = array();
		$output['bank_column']    = array();
		$output['bank_accountNo'] = array();
		$output['cv_no']          = array();
		$output['cv_no_column']   = array();
		$output['bank_accountColumn'] = array();

		switch($result['ledger']){

			case "BANK":

				$sub_account = $this->md_journalEntry->if_bank($this->input->post('location'),$this->input->post('account_id'));

				$output['bank_account']  = $this->md_journalEntry->get_bankAccount($this->input->post('location'));
				$output['bank_accountColumn'] = array('text'=>'bank name','value'=>'bank_id');

				$output['column'] = array('text'=>'bank name','value'=>'bank_id');
				$output['bank_accountNo'] = $this->md_journalEntry->get_bankAccountNo('',$this->input->post('location'));
				$output['bank_column'] = array('text'=>'account_no','value'=>'dtl_id');

				$output['cv_no'] = $this->md_journalEntry->get_cv_no();
				$output['cv_no_column'] = array('text'=>'cv_no','value'=>'cvdtl_id');

				    
			break;
			
			case "CUSTOMER":
			case "SUBCONTRACTOR":
			case "SUPPLIER":
				$sub_account = $this->md_journalEntry->if_supplier();
				$output['column'] = array('text'=>'business_name','value'=>'business_number');
			break;
			
			case "EMPLOYEES":
				$sub_account = $this->md_journalEntry->if_employee();
				$output['column'] = array('text'=>'emp_name','value'=>'emp_number');
			break;
			
			case "AFFILIATES":
				$sub_account = $this->md_journalEntry->if_affiliates();
				$output['column'] = array('text'=>'project_name','value'=>'project_id');
			break;

			case "MATERIALS":
				$sub_account = $this->md_journalEntry->if_materials();
				$output['column'] = array('text'=>'description','value'=>'group_detail_id');
			break;

			case "PPE":
				$sub_account = $this->md_journalEntry->if_ppe();
				$output['column'] = array('text'=>'description','value'=>'group_detail_id');
			break;
			default :
				$sub_account = "";
			break;

		}
		
		$output['sub_account'] = $sub_account;


		echo json_encode($output);

	}

	public function get_checkNo(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_journalEntry->get_checkNo($this->input->post('bank_id'));
		echo json_encode(array('check_no'=>$result));

	}

	public function save_journal(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_journalEntry->save_journal();
	}

	public function update(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_journalEntry->update_journal();
	}


	public function cumulative(){

		$this->lib_auth->title = "Journal Entry | Cumulative";
		$this->lib_auth->build = "accounting/journal_entry/cumulative";

		$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();

		$this->lib_auth->render($data);
	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['result'] = $this->md_journalEntry->get_cumulative_monthly();
		$this->load->view('accounting/journal_entry/tbl_cumulative',$data);
		
	}


	public function get_journal_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['result'] = $this->md_journalEntry->get_journal_detail($this->input->post('journal_id'))->result_array();
		$this->load->view('accounting/journal_entry/journal_details',$data);

	}

	public function cancel(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$result = $this->md_journalEntry->delete_journal($this->input->post('journal_id'));
		if($result == 0 ){
			echo '0';
		}else{
			echo 1;
		}
		
	}




}