<?php defined('BASEPATH') OR exit('No direct script access allowed');

class journal_entry extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";
		$this->load->model('accounting/md_journalEntry');			
		$this->load->model('md_project');
		$this->load->model('accounting/md_voucher');		
	}

	public function index(){

		if(isset($_GET['edit'])){

			$id = $_GET['edit'];

			$data['main']   = $this->md_journalEntry->get_journalMain($id);
			$data['detail'] = $this->md_journalEntry->get_journalDetail($id);
			
			$this->lib_auth->title = "Journal Entry Edit";
			$this->lib_auth->build = "accounting/journal_entry/edit";
			$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();

			$item_content = array();
			$item_value   = array();
			$cnt          = 0;
			
			foreach($data['accountDescription'] as $row){
				$item_value["_".$row['account_id']]   = $row['account_description'];
				$item_content["_".$row['account_id']] = array(
							'account_description'=>$row['account_description'],
							'account_id'=>$row['account_id'],
							'account_code'=>$row['account_code'],
							);
			}
			
			$data['item_value']   = json_encode($item_value);
			$data['item_content'] = json_encode($item_content);			
			$data['pay_item'] = $this->md_project->get_project_category();
			
			$this->lib_auth->render($data);
			
		}else
		{

			$this->lib_auth->title = "Journal Entry";
			$this->lib_auth->build = "accounting/journal_entry/index";		
			$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();

			$item_content = array();
			$item_value   = array();
			$cnt          = 0;
			
			foreach($data['accountDescription'] as $row){
				$item_value["_".$row['account_id']]   = $row['account_description'];
				$item_content["_".$row['account_id']] = array(
							'account_description'=>$row['account_description'],
							'account_id'=>$row['account_id'],
							'account_code'=>$row['account_code'],
							);
			}
			
			$data['item_value']   = json_encode($item_value);
			$data['item_content'] = json_encode($item_content);

			$data['pay_item'] = $this->md_project->get_project_category();
			$this->lib_auth->render($data);

		}


		
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

			case "BANK ACCOUNT":

				$result = $this->md_journalEntry->get_bank();

				$data = array();
				$content = array();
				foreach($result as $row){
					$data[$row['bank_id']] = $row['bank_name'];
					$content[$row['bank_id']] = array(
							'bank_name'=>$row['bank_name']
						);
				}

				 $sub_account['data'] = $data;

				/*				
				$output['bank_account']  = $this->md_journalEntry->get_bankAccount($this->input->post('location'));
				$output['bank_accountColumn'] = array('text'=>'bank name','value'=>'bank_id');
				
				$output['column'] = array('text'=>'bank name','value'=>'bank_id');
				$output['bank_accountNo'] = $this->md_journalEntry->get_bankAccountNo('',$this->input->post('location'));
				$output['bank_column'] = array('text'=>'account_no','value'=>'dtl_id');
				
				$output['cv_no'] = $this->md_journalEntry->get_cv_no();
				$output['cv_no_column'] = array('text'=>'cv_no','value'=>'cvdtl_id');
				*/
				  
			break;
			
			case "CUSTOMER":	

				$result = $this->md_journalEntry->get_customer();

				$data = array();
				$content = array();
				foreach($result as $row){
					$data[$row['business_number']] = $row['business_name'];
					$content[$row['business_number']] = array(
							'business_name'=>''
						);
				}
				$sub_account['data'] = $data;

			break;
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

			case "OTHER OFFICE":
				$sub_account = $this->md_journalEntry->if_other_office();
				$output['column'] = array('text'=>'business_name','value'=>'business_number');
			break;

			default :
				$sub_account = "";
			break;

		}
		
		$output['sub_account'] = $sub_account;
				
		echo json_encode($output);

	}



	public function editable_get_accountType(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$account_id = $this->input->post('account_id');
		if(!ctype_digit($account_id)){
			exit(0);
		}

		$result = $this->md_journalEntry->get_accountType($account_id);

		$output = array();
			
		switch($result['ledger']){

			case "BANK ACCOUNT":

				$result = $this->md_journalEntry->get_bank();

				$data = array();
				$content = array();
				foreach($result as $row){
					$data[$row['bank_id']] = array('value'=>$row['bank_name'],'attr'=>array('data-account-no'=>$row['account_no'],'data-bank_id'=>$row['bank_id']));

						/*
						$content[$row['bank_id']] = array(
							'bank_name'=>$row['bank_name']
						);
						*/
						
				}
				$output = $data;		  
			break;
			
			case "CUSTOMER":	

				$result = $this->md_journalEntry->get_customer();

				$data = array();
				$content = array();
				foreach($result as $row){
					$data[$row['business_number']] = array('value'=>$row['business_name']);
					$content[$row['business_number']] = array(
							'business_name'=>''
						);
				}

				$output = $data;

			break;
			case "SUPPLIER":
				$result = $this->md_journalEntry->if_supplier();

				$data = array();
				$content = array();
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}

				$output = $data;
				
			break;

			case "ASSET":
				$result = $this->md_journalEntry->get_subsidiary('ASSET');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}

				$output = $data;
			break;
				
			case "EMPLOYEE":
				$result = $this->md_journalEntry->get_subsidiary('EMPLOYEE');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}

				$output = $data;
			break;

			case "EQUIPMENT":
				$result = $this->md_journalEntry->get_subsidiary('EQUIPMENT');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}

				$output = $data;
			break;

			case "ITEM":
				$result = $this->md_journalEntry->get_subsidiary('ITEM');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			case "LOCATION":
				$result = $this->md_journalEntry->get_subsidiary('LOCATION');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			case "LOT":
				$result = $this->md_journalEntry->get_subsidiary('LOT');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			case "OFFICE":
				$result = $this->md_journalEntry->get_subsidiary('OFFICE');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			case "PAYEE":
				$result = $this->md_journalEntry->get_subsidiary('PAYEE');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			case "VEHICLE":
				$result = $this->md_journalEntry->get_subsidiary('VEHICLE');
				$data = array();				
				foreach($result as $row){
					$data["_".$row['business_number']] = array('value'=>$row['business_name']);					
				}
				$output = $data;
			break;

			default :
				$sub_account = "";
			break;

		}		
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
		$from = $this->input->post('from');
		$to   = $this->input->post('to');
		$data['result'] = $this->md_journalEntry->get_cumulative_range($from,$to);
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

	public function posting(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_journalEntry->posting($this->input->post('journal_id'));

		if($result == 0 ){
			echo '0';
		}else{
			echo 1;
		}

	}

	public function get_voucher_approved(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['data'] = $this->md_voucher->get_approved_voucher();	
		$cnt = count($data['data']);	
		
		if($cnt > 0){
			$count  = "<span class='badge'>".$cnt."</span>";
		}

		$output = array(
			'count'=>$count,
			'content'=> $this->load->view('accounting/journal_entry/voucher_list',$data,TRUE),
			);		
		echo json_encode($output);

	}


	public function get_voucher_info(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$cash_voucher_id = $this->input->post('id');
		$data['main']    = $this->md_voucher->get_cash_voucher_info($cash_voucher_id);
		$data['details'] = $this->md_voucher->get_cash_voucher_details($cash_voucher_id);
		$data['bank']    = $this->md_voucher->get_bank();
				
		$output = array(
			'content'=>$this->load->view('accounting/journal_entry/disbursement_view',$data,TRUE),
			'main'=>$data['main'],			
		);
		
		echo json_encode($output);

	}




}