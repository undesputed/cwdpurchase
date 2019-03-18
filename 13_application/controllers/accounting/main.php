<?php defined('BASEPATH') OR exit('No direct script access allowed');

class main extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->lib_auth->default = "default-accounting";

		$this->load->model('accounting/md_voucher');
		$this->load->model('md_project');
		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_received_purchase');
		$this->load->model('procurement/md_received_purchase');
		$this->load->model('inventory/md_stock_withdrawal');
		$this->load->model('accounting/md_payable_list');
		$this->load->model('accounting/md_journalEntry');

	}

	public function index(){	

		$this->lib_auth->title = "Accounting";
		$this->lib_auth->build = "accounting/main/index";
		$this->lib_auth->render();

	}

	public function voucher(){
		
		$data['view'] =	$this->load->view('accounting/disbursement_voucher/voucher_list','',true);
		$this->lib_auth->title = "Disbursement Voucher";
		$this->lib_auth->build = "procurement/transaction_list/sidebar";
		$this->lib_auth->render($data);

	}

	public function voucher_summary(){
		
		$this->lib_auth->title = "Voucher Summary";
		$this->lib_auth->build = "accounting/disbursement_voucher/voucher_summary";
		
		$data['project']  = $this->md_project->get_profit_center();
		$data['supplier'] = $this->md_voucher->get_payto_voucher();

		$this->lib_auth->render($data);
		
	}

	public function get_voucher_summary(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);
				
		$arg = $this->input->post();
		$result = $this->md_voucher->get_cash_voucher_summary($arg);
		
		$show = array(
				'Date',
				'Voucher No',
				'TIN',
				'Supplier Name',
				'Particulars',
				'Bank & Check No',
				'Reference Doc',
				'Net of VAT',
				'Input VAT',
				'Gross Amount',
		 		);

		$this->table->set_heading($show);

		$grand_total['total'] = 0;

		foreach($result as $key => $row){

			$row_content   = array();
			$row_content[] = array('data'=>$row['voucher_date']);
			$row_content[] = array('data'=>$row['voucher_no']);
			$row_content[] = array('data'=>$row['tin_number']);
			$row_content[] = array('data'=>$row['pay_to']);
			$row_content[] = array('data'=>$row['type']." ".$row['short_desc']);
			$row_content[] = array('data'=>$row['bank']." ".$row['check_no']);
			$row_content[] = array('data'=>$row['ref_doc']);

			$gross = ($row['total_amount'] / 1.12);
			$input_vat = ($row['total_amount'] - $gross);

			$grand_total['total'] = $grand_total['total'] + $row['total_amount'];

			$row_content[] = array('data'=>number_format($gross,2));
			$row_content[] = array('data'=>number_format($input_vat,2));
			$row_content[] = array('data'=>number_format($row['total_amount'],2));
			$this->table->add_row($row_content);

		}

		$row_content = array();

		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
		$row_content[] = array('data'=>$this->extra->number_format($grand_total['total']),'style'=>'font-weight:bold;');

		$this->table->add_row($row_content);

		echo $this->table->generate();

	}


	public function _voucher($po_id = ''){

		$data['bank']  = $this->md_voucher->get_bank();
		$data['dv_no'] = $this->md_voucher->get_dv_no();
		$this->lib_auth->title = "Disbursement Voucher";

		if(!empty($po_id)){
			if(is_numeric($po_id)){
				$data['po_main'] = $this->md_purchase_order->getBypo_id($po_id);
				$data['rr_main'] = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
				$data['item_list'] = $this->md_voucher->get_voucher_item($po_id);

				if($data['rr_main'][0]['paymentTerm'] == 'COD'){
					$data['type'] = 'ENTER PAYMENT';
				}else{
					$data['type'] = 'ENTER PAYABLE';
				}
				
				$this->lib_auth->build = "accounting/disbursement_voucher/index";
			}else{
				if($po_id =='cumulative'){

					$data['cumulative'] =  $this->md_voucher->get_voucher_cumulative();
					$this->lib_auth->build = "accounting/disbursement_voucher/cumulative";
				}else{
					$this->lib_auth->build = "accounting/disbursement_voucher/index_2";	
				}
				
			}

		}else{
			$this->lib_auth->build = "accounting/disbursement_voucher/index_2";	
		}
			
			
		$this->lib_auth->render($data);

	}

	public function get_po_list(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['result'] = $this->md_voucher->get_po_list();
		$this->load->view('accounting/disbursement_voucher/po_list',$data);
				
	}


	public function save_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_voucher->save_voucher($arg);
				
	}

	public function update_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_voucher->update_voucher($arg);
				
	}

	


	public function create_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$po_id = $this->input->post('po_id');
		$data['bank']      = $this->md_voucher->get_bank();
		$data['dv_no']     = $this->md_voucher->voucher_no();


		$data['po_main']   = $this->md_purchase_order->getBypo_id($po_id);
		$data['rr_main']   = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
		$data['item_list'] = $this->md_voucher->get_voucher_item($po_id);
				
		if($data['rr_main'][0]['paymentTerm'] == 'COD'){
			$data['type'] = 'ENTER PAYMENT';
		}else{
			$data['type'] = 'ENTER PAYABLE';
		}
		$data['project_category'] = $this->md_project->get_project_category();
		$this->load->view("accounting/disbursement_voucher/index",$data);
		
	}


	public function get_invoice_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$receipt_id = $this->input->get('receipt_id');
		$po_id      = $this->input->get('po_id');
		$data['item_list'] = $this->md_voucher->get_invoice_item($receipt_id,$po_id);
		$this->load->view('accounting/disbursement_voucher/invoice_item',$data);
		
	}


	public function cash_voucher(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$data['type'] = array('REQUEST BUDGET FOR','PAYMENT FOR','REIMBURSEMENT','CASH ADVANCE','PAYROLL');
		/*$item_list = $this->md_voucher->get_items();*/
		
		/*
		$data['item_list'] = $this->md_voucher->get_items();		
		$item_content = array();
		$item_value   = array();
		$cnt = 0;
		foreach($item_list as $row){
			$item_value["_".$row['item_no']]   = $row['description'];
			$item_content["_".$row['item_no']] = array(
						'item_name'=>$row['description'],
						'unit_measure'=>$row['unit_measure'],
						'account_id'=>$row['account_id'],
						);
		}		
		$data['item_value']   = json_encode($item_value);		
		$data['item_content'] = json_encode($item_content);		
		*/

		$data['dv_no']   = $this->md_voucher->voucher_no();
		$data['po_list'] = $this->md_voucher->get_received_po();

		$arg = array(
			'prepared_by'=>'web_signatory',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);

		$data['signatory']      = $this->extra->signatory4($arg);
		$data['projects']         = $this->md_project->get_profit_center();		
		$data['project_category'] = $this->md_project->get_project_category();
		$data['bank_setup']    = $this->md_payable_list->get_bank_setup();
		$this->load->view('accounting/cash_voucher/cash_voucher',$data);
		
	}

	public function save_cash_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();
		echo $this->md_voucher->save_cash_voucher($arg);
		
	}


	public function update_cash_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		echo $this->md_voucher->edit_cash_voucher();
		/*echo $this->md_voucher->update_cash_voucher($arg);*/
		
	}

	public function edit_1(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$data['cash_main_id']  = $this->input->post('id');
		$po_id                 = $this->input->post('po_id');		

		$data['cash_main'] = $this->md_voucher->get_cash_voucher_main($data['cash_main_id']);
		$data['cash_details'] = $this->md_voucher->get_cash_voucher_details($data['cash_main_id']);

		$data['bank']      = $this->md_voucher->get_bank();
		$data['dv_no']     = $this->md_voucher->voucher_no();

		$data['po_main']   = $this->md_purchase_order->getBypo_id($po_id);
		$data['rr_main']   = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
		$data['item_list'] = $this->md_voucher->get_voucher_item($po_id);
		
		if($data['rr_main'][0]['paymentTerm'] == 'COD'){
			$data['type'] = 'ENTER PAYMENT';
		}else{
			$data['type'] = 'ENTER PAYABLE';
		}
		
		$this->load->view("accounting/disbursement_voucher/index_edit",$data);
	}


	public function edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$id = $this->input->post('id');

		$data['type']    = array('REQUEST BUDGET FOR','PAYMENT FOR','REIMBURSEMENT','CASH ADVANCE','PAYROLL');
		
		$data['main']    = $this->md_voucher->get_cash_voucher_main($id);
		$data['details'] = $this->md_voucher->get_cash_voucher_details($id);
		$data['journal'] = $this->md_voucher->voucher_journal($id);



		$data['dv_no']   = $this->md_voucher->get_dv_no();
		$data['po_list'] = $this->md_voucher->get_received_po($data['main']['rr_id']);
			
		$arg = array(
			'prepared_by'=>'1',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);
		
		$data['projects']  = $this->md_project->get_profit_center();
		$data['signatory'] = $this->extra->signatory4($arg);		
		$data['project_category'] = $this->md_project->get_project_category();	
		$data['bank_setup']    = $this->md_payable_list->get_bank_setup();

		$this->load->view('accounting/cash_voucher/cash_voucher_edit2',$data);		
	}

	public function journal_voucher_edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$id = $this->input->post('id');

		$data['type']    = array('REQUEST BUDGET FOR','PAYMENT FOR','REIMBURSEMENT','CASH ADVANCE','PAYROLL');
		
		$data['main']    = $this->md_voucher->get_cash_voucher_main($id);
		$data['details'] = $this->md_voucher->get_cash_voucher_details($id);
		$data['journal'] = $this->md_voucher->voucher_journal($id);



		/*$data['dv_no']   = $this->md_voucher->get_dv_no();
		$data['po_list'] = $this->md_voucher->get_received_po($data['main']['rr_id']);*/
			
		$arg = array(
			'prepared_by'=>'1',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);
		
		$data['projects']  = $this->md_project->get_profit_center();
		$data['signatory'] = $this->extra->signatory4($arg);		
		$data['project_category'] = $this->md_project->get_project_category();	
		$data['bank_setup']    = $this->md_payable_list->get_bank_setup();

		$this->load->view('accounting/cash_voucher/journal_voucher_edit',$data);
		
	}



	public function save_changes(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_voucher->save_changes($arg);
	}	


	public function is_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		echo $this->md_voucher->is_print($arg);
		
	}

	public function cancel(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		$this->md_voucher->cancel($arg);

	}

	public function approved_voucher(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$arg = $this->input->post();
		echo $this->md_voucher->approved_voucher($arg);

	}

	public function get_supplier(){	
		
		$result = $this->md_project->get_supplier();
		$data = array();
		$output = array();
		foreach($result as $row){
			$output['text'][] = $row['business_name'];
			$output['address'][$row['business_name']] = $row['address'];
			$output['tin'][$row['business_name']] = $row['tin_number'];
		}
		
		echo json_encode($output);		
	}

	public function get_rr_details(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
	}


	public function tag_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		

		$arg = $this->input->post();
		$result = $this->md_voucher->tag_details($arg);		
		echo json_encode($result);

	}


	public function get_account_title(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['accountDescription'] = $this->md_journalEntry->get_accountDescription();

			$item_content = array();
			$item_value   = array();
			$cnt          = 0;
				
			foreach($data['accountDescription'] as $row){
				$item_value["_".$row['account_id']] = $row['account_description'];
				$item_content["_".$row['account_id']] = array(
							'account_description'=>$row['account_description'],
							'attr'=>array(
								'data-account_id'=>$row['account_id'],
								'data-ledger'=>$row['ledger'],
								),
							);
			}

		echo json_encode($item_content);

		/*
		$data['item_value']   = json_encode($item_value);
		$data['item_content'] = json_encode($item_content);
		*/

	}


	public function tag(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['voucher_id'] = $this->input->post('voucher_id');
		$data['po_list'] = $this->md_voucher->get_received_po();		
		$this->load->view('accounting/cash_voucher/tag',$data);

	}

	public function do_tag(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg = $this->input->post();
		return $this->md_voucher->do_tag($arg);

	}

	public function journal_voucher(){

		$data['type'] = array('REQUEST BUDGET FOR','PAYMENT FOR','REIMBURSEMENT','CASH ADVANCE','PAYROLL');
		$data['dv_no']   = $this->md_voucher->voucher_no();
		/*$data['po_list'] = $this->md_voucher->get_received_po();*/

		$arg = array(
			'prepared_by'=>'1',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);

		$data['signatory']        = $this->extra->signatory4($arg);
		$data['projects']         = $this->md_project->get_profit_center();
		$data['project_category'] = $this->md_project->get_project_category();
		$data['bank_setup']       = $this->md_payable_list->get_bank_setup();
		$this->load->view('accounting/cash_voucher/journal_voucher',$data);

	}



}