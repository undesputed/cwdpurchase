<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Print_controller extends CI_Controller {


	var $head;

	public function __construct(){
		parent :: __construct();


		$this->load->model('procurement/md_project');
		$this->load->model('procurement/md_transaction');
		$this->load->model('procurement/md_transaction_history');
		$this->load->model('procurement/md_project');

		$this->load->model('procurement/md_canvass_sheet');
		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_stock_availability');
		$this->load->model('procurement/md_purchase_request');
		$this->load->model('procurement/md_item_issuance');
		$this->load->model('procurement/md_item_transfer');

		$this->load->model('inventory/md_stock_withdrawal');	
		$this->load->model('inventory/md_stock_inventory');	
		$this->load->model('procurement/md_received_purchase');

		$this->load->model('accounting/md_project_expense');

		$this->load->model('accounting/md_voucher');

		$this->load->model('accounting/md_payable_list');
		$this->load->model('accounting/md_trial_balance');
		$this->load->model('accounting/md_balancesheet');
		$this->load->model('accounting/md_income_statement');

		$this->load->model('accounting/md_change_in_equity');
		
		$result = $this->md_project->get_print();
		
		$header = array();

		foreach($result as $row){
			$header['title']     = $row['title'];
		 	$header['sub_title'] = $row['sub_title'];
		 	$header['address']   = $row['address'];
		 	$header['contact']   = $row['contact_no'];
		 	$header['fax_no']    = $row['fax_no'];
		 	$header['website']   = $row['website'];
		 	$header['email']     = $row['email'];	
		
		 	$header['full_name'] = $row['full_name'];
		}
		
		$this->head = $header;

	}

	public function index(){

		redirect(base_url(),'refresh');
	}
	public function pr($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type);
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Purchase Request";
		$this->lib_auth->build = "procurement/print/pr_print";
		$this->lib_auth->render($data);

	}

	public function returns($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_purchase_request->get_return_main($type);
		$data['details_data'] = $this->md_purchase_request->get_return_details($data['main_data']['receipt_id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Return";
		$this->lib_auth->build = "procurement/print/return_print";
		$this->lib_auth->render($data);

	}

	public function pr_quotation($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type);
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Request for Quotation";
		$this->lib_auth->build = "procurement/print/pr_print_quotation";
		$this->lib_auth->render($data);

	}

	public function jo($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_purchase_request->get_jo_main($type);
		$data['details_data'] = $this->md_purchase_request->get_jo_details($data['main_data']['id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Job Order Slip";
		$this->lib_auth->build = "procurement/print/jo_print";
		$this->lib_auth->render($data);

	}

	public function canvass($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_canvass_sheet->get_canvassMain_no($type);
		$data['pr_main']   = $this->md_purchase_request->get_purchaseNo($data['main_data']['purchaseNo']);
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);
		$data['canvass_details'] = $this->md_canvass_sheet->get_canvassDetails_2($data['main_data']['can_id']);
		$data['approvedsupplier'] = $this->md_canvass_sheet->get_approved_suppliers($data['main_data']['can_id']);
		$data['approveditems'] = $this->md_canvass_sheet->get_approved_items($data['main_data']['can_id']);

		$data['supplier_list']['Affiliate'] = $this->md_project->get_supplier_affiliate();
		$data['supplier_list']['Business']  = $this->md_project->get_supplier_business();

		$form = "cv";
		$data['chairperson'] = $this->md_canvass_sheet->get_chairperson($form);
		$data['vice_chairperson'] = $this->md_canvass_sheet->get_vice_chairperson($form);
		$data['member'] = $this->md_canvass_sheet->get_member($form);
		$data['end_user'] = $this->md_canvass_sheet->get_end_user($form);
		$data['general_manager'] = $this->md_canvass_sheet->get_general_manager($form);

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Canvass";
		$this->lib_auth->build = "procurement/print/canvass_print";
		$this->lib_auth->render($data);
	}


	public function bcanvass($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_purchase_request->get_purchaseNo($type);
		$data['details_data'] = $this->md_purchase_request->get_pr_details($data['main_data']['pr_id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Canvass";
		$this->lib_auth->build = "procurement/print/bcanvass_print";
		$this->lib_auth->render($data);
	}



	public function po($type) 
	{

		$data['header'] = $this->head;

		$data['main_data']    = $this->md_purchase_order->get_po_main($type);
		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
		$data['details_data'] = $this->md_purchase_order->get_po_details($data['main_data']['po_id']);
		$data['amountinwords'] = $this->convert_number_to_words($data['main_data']['totalunitcost']);

		$data['pr_main'] = $this->md_purchase_request->get_pr_main($data['main_data']['pr_id']);
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title   = "Print : PO";
		$this->lib_auth->build   = "procurement/print/po_print";
		$this->lib_auth->render($data);
		
	}


	public function rr($type){

		$rr_main  = $this->md_received_purchase->get_rr_main_no($type);		

		$data['header'] = $this->head;

		$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_main['receipt_id']);
		$result = $this->md_purchase_order->get_po_main_po_id($data['rr_details'][0]['po_id']);	
		$data['rr_main']  = $this->md_received_purchase->get_po_received_single($result['po_number'],$rr_main['receipt_id']);	
		$data['main_data']  = $this->md_purchase_order->get_po_main($result['po_number']);
		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);
		

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : RR";
		$this->lib_auth->build = "procurement/print/rr_print";
		$this->lib_auth->render($data);


	}
	public function withdrawal($type){
		$data['header'] = $this->head;

		$data['main_data'] = $this->md_stock_withdrawal->get_withdraw_main_no($type);
		$data['details'] = $this->md_stock_withdrawal->get_withdraw_details($data['main_data']['withdraw_id']);
		$data['request_main'] = $this->md_stock_withdrawal->get_request_main($data['main_data']['equipment_id']);
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Material Withdrawal";
		$this->lib_auth->build = "procurement/print/withdrawal_print";
		$this->lib_auth->render($data);

	}

	public function withdrawal_gate_pass($type){
		$data['header'] = $this->head;

		$data['main_data'] = $this->md_stock_withdrawal->get_withdraw_main_no($type);

		$data['details'] = $this->md_stock_withdrawal->get_withdraw_details($data['main_data']['withdraw_id']);
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Gate Pass";
		$this->lib_auth->build = "procurement/print/withdrawal_gate_pass_print";
		$this->lib_auth->render($data);

	}

	public function inventory_withdrawal(){
		$data['header'] = $this->head;

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Withdrawal Inventory";
		$this->lib_auth->build = "procurement/print/inv_withdrawal_print";
		$this->lib_auth->render();

	}
	public function issuance($type){
		$data['header'] = $this->head;

		$data['main_data'] = $this->md_item_issuance->get_issuance_main($type);
		$data['details'] = $this->md_item_issuance->get_issuance_details($data['main_data']['issuance_id']);

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Material Issuance";
		$this->lib_auth->build = "procurement/print/issuance_print";
		$this->lib_auth->render($data);
	}
	public function inventory_issuance(){
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Issuance Inventory";
		$this->lib_auth->build = "procurement/print/inv_issuance_print";
		$this->lib_auth->render();
	}
	public function inventory($arg = ""){

		$data['_active'] = '';
		
		$data['header'] = $this->head;
		
		$data['project']   = $this->md_project->get_setup_project($arg);
		$data['item_list'] = $this->md_stock_inventory->get_inventory($arg);
		$data['item_category'] = json_encode($this->md_stock_inventory->get_inventory_power($arg));

		$array = array();

		foreach($data['item_list'] as $row){

			/*if($row['setup_group_head'] == "")
			{
				$index = "OTHERS MONITORING";
			}else{
				$index = $row['setup_group_head'];
			}*/
			
			$array[$row['group_description']][] = $row;
			
		}
		
		ksort($array);

		$data['item'] = $array;
		$data['view'] = $this->load->view("procurement/transaction_list/inventory",$data,true);

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Inventory";
		$this->lib_auth->build = "procurement/print/inventory_print";
		$this->lib_auth->render($data);

	}

	public function inventory_stock_card($arg = ""){

		$data['_active'] = '';
		
		$data['header'] = $this->head;

		$arg = array(
				'item_id' => $this->uri->segment(4),
				'location_id' => $this->uri->segment(3)
			);
		
		$data['item']   = $this->md_stock_inventory->get_item($this->uri->segment(4));
		$data['item_list'] = $this->md_stock_inventory->get_inventory_stock_card($arg);

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Inventory Stock Card";
		$this->lib_auth->build = "procurement/print/inventory_stock_card_print";
		$this->lib_auth->render($data);

	}

	public function item_transfer_report($type){
		$data['header'] = $this->head;

		$data['profit_center'] = $this->md_project->get_project_site_not_me();
		$data['main_data'] = $this->md_item_transfer->get_main($type);
		$data['details'] = $this->md_item_transfer->get_details($data['main_data']['id']);
		$data['type'] = $this->uri->segment(4);

		$this->lib_auth->default = 'print';
		if(!empty($data['type'])){
			$this->lib_auth->title = "Print : Transfer Sheet";
			$this->lib_auth->build = "procurement/print/item_transfer_sheet";
		}else{
			$this->lib_auth->title = "Print : Material Transmittal Sheet";
			$this->lib_auth->build = "procurement/print/item_transfer_report";
		}
		$this->lib_auth->render($data);
	}

	public function item_transfer_gate_pass($type){
		$data['header'] = $this->head;

		$data['profit_center'] = $this->md_project->get_project_site_not_me();
		$data['main_data'] = $this->md_item_transfer->get_main($type);
		$data['details'] = $this->md_item_transfer->get_details($data['main_data']['id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Gate Pass";
		$this->lib_auth->build = "procurement/print/item_transfer_gate_pass";
		$this->lib_auth->render($data);
	}

	public function item_request_report($type){
		$data['header'] = $this->head;

		$data['profit_center'] = $this->md_project->get_project_site_not_me();
		$data['main_data'] = $this->md_item_request->get_main($type);
		$data['details'] = $this->md_item_request->get_details($data['main_data']['id']);	

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Item Request";
		$this->lib_auth->build = "procurement/print/item_request_report";
		$this->lib_auth->render($data);
	}

	public function item_receive_report($type){

		$data['header'] = $this->head;

		$data['main_data'] = $this->md_item_transfer->get_main($type);

		$data['details'] = $this->md_item_transfer->get_details($data['main_data']['id']);

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Item Receiving";
		$this->lib_auth->build = "procurement/print/item_receive_report";
		$this->lib_auth->render($data);

	}
	public function disbursement_voucher($po_id){

		$data['header']    = $this->head;		
		$data['po_main']   = $this->md_purchase_order->getBypo_id($po_id);
		$data['rr_main']   = $this->md_received_purchase->get_po_received($data['po_main']['po_number']);
		$data['item_list'] = $this->md_voucher->get_voucher_item($po_id);
		$data['voucher_info'] = $this->md_voucher->get_voucher_cumulative_po_id($po_id);

		$data['journal_main'] = $this->md_voucher->get_journal_main($po_id);

		$data['supplier']  = $this->md_project->get_supplier_business($data['po_main']['supplierID']);
		$data['journal_details'] = $this->md_voucher->journal_details($data['journal_main']['journal_id']);

		$data['journal_credit'] = $this->md_voucher->journal_details_credit($data['journal_main']['journal_id']);


		$arg = array(
			'prepared_by'=>'1',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);
		
		$data['signatory'] = $this->extra->signatory4($arg);
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title   = "Print : Disbursement Voucher";
		$this->lib_auth->build   = "accounting/print/disbursement_voucher";
		$this->lib_auth->render($data);

	}

	public function payables(){

		$data['header'] = $this->head;

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Payable List";
		$this->lib_auth->build = "accounting/print/payable";

		$arg['location']  = $_GET['project'];
		$arg['view_type'] = 'date_range';
		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];
		$arg['supplier_id'] = $_GET['supplier'];

		$data['from'] = date('F j, Y',strtotime($arg['date_from']));
		$data['to']   = date('F j, Y',strtotime($arg['date_to']));

		$result = $this->md_payable_list->get_payable_list($arg);	

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">');
		$this->table->set_template($tmpl);
			
			$show = array(
					'PO #',
					'Date',
					'Amount',
					'Project',
					'Supplier',
					array('data'=>'SI #','style'=>'width:30px'),
					'SI Date',
					'Check#',
					'SI Amount',
					'Due Date',
					'Status',
			 		);

				$total_debit  = 0;
				$total_credit = 0;

				$po_list = array();
				$total_cost = 0;
				$total_balance = 0;
				foreach($result as $key => $value){			

					$row_content = array();

					$project_requestor = isset($value['project_requestor'])? $value['project_requestor'] : '' ;

					if(!isset($po_list[$value['reference_no']])){
						$po_list[$value['reference_no']] = '';
							$row_content[] = array('data'=>$value['reference_no'],'class'=>'po_no');
							$row_content[] = array('data'=>$value['po_date'],'class'=>'po_date');
							$row_content[] = array('data'=>$this->extra->number_format($value['total_cost']),'class'=>'total_amount');
							$row_content[] = array('data'=>$project_requestor);
							$row_content[] = array('data'=>$value['Supplier']);
							$balance = $value['total_cost'];
							$total_cost = $total_cost + $value['total_cost'];
					}else{							
							$row_content[] = array('data'=>'<span style="display:none">'.$value['reference_no'].'</span>','class'=>'po_no');
							$row_content[] = array('data'=>'<span style="display:none">'.$value['po_date'].'</span>','class'=>'po_date');
							$row_content[] = array('data'=>'<span style="display:none">'.$this->extra->number_format($value['total_cost']).'</span>','class'=>'total_amount');
							$row_content[] = array('data'=>'');
							$row_content[] = array('data'=>'');
					}

					$balance = $balance - $value['si_amount'];
					
					$total_balance = $total_balance + $value['paid_amount'];
					
					$value['supplier_invoice'] = str_replace(',',', ', $value['supplier_invoice']);
					$value['supplier_invoice'] = wordwrap($value['supplier_invoice'], 20, "<br />\n");
					$row_content[] = array('data'=>(isset($value['supplier_invoice'])? $value['supplier_invoice'] : '' ),'class'=>'word-break');

					$row_content[] = array('data'=>(isset($value['invoice_date'])? $value['invoice_date'] : ''));
					$row_content[] = array('data'=>(isset($value['bank'])? $value['bank']." ".$value['_check_no'] : ''));
					$row_content[] = array('data'=>$this->extra->number_format($value['si_amount']));
					$row_content[] = array('data'=>(isset($value['check_date'])? $value['check_date'] : ''));
					/*$row_content[] = array('data'=>(isset($value['rr_date'])? $value['rr_date'] : ''));*/
					/*$row_content[] = array('data'=>$this->extra->number_format($balance));*/	
					if($value['paid_status'] == 'paid')
					{

						$row_content[] = array('data'=>'<label class="label label-success" data-value="paid" data-id="'.$value['po_id'].'">paid</label>');
						/*$row_content[] = array('data'=>'<label class="label label-success update" data-value="paid" data-id="'.$value['po_id'].'">paid</label>');*/
						/*
						$row_content[] = array('data'=>'
								<div class="btn-group font-10">
								  <button class="btn btn-default btn-xs btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								    Paid <span class="caret"></span>
								  </button>
								  <ul class="dropdown-menu pull-right">
								  	<li><a href="javascript:void(0)" class="update" data-id="'.$value['po_id'].'">Add Invoice</a></li>
								  	<li class="divider"></li>
								    <li><a href="javascript:void(0)" class="edit" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Edit</a></li>
								    <li><a href="javascript:void(0)" class="delete" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Delete</a></li>
								  </ul>
								</div>
							','style'=>'width:90px');					

							*/
					}else{
						$row_content[] = array('data'=>'<label class="label label-danger" data-value="paid" data-id="'.$value['po_id'].'">Unpaid</label>');
						/*
						$row_content[] = array('data'=>'
									<div class="btn-group font-10">																		
									  <button class="btn btn-default btn-xs btn-warning dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									    Unpaid <span class="caret"></span>
									  </button>									
									  <ul class="dropdown-menu pull-right">									  										   
										<li><a href="javascript:void(0)" class="update" data-id="'.$value['po_id'].'" data-value="unpaid" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Pay Payable</a></li>
										<li class="divider"></li>
									    <li><a href="javascript:void(0)" class="delete" data-id="'.$value['po_id'].'" data-receipt_id="'.$value['receipt_id'].'" data-journal_id="'.$value['journal_id'].'">Delete</a></li>									    
									  </ul>
									</div>
							');

							*/
					}
					$this->table->add_row($row_content);
				}

				$row_content = array();
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
				$row_content[] = array('data'=>$this->extra->number_format($total_cost),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');		
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');
				$row_content[] = array('data'=>'');

				$this->table->add_row($row_content);		

		$this->table->set_heading($show);				
		$data['table'] = $this->table->generate();

		$this->lib_auth->render($data);

	}

	public function project(){

		$data['header'] = $this->head;

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Expenses";
		$this->lib_auth->build = "accounting/print/project";
		$this->lib_auth->render($data);
	}



	public function cash_voucher($type){

		$rr_main  = $this->md_received_purchase->get_rr_main_no($type);	
			
		$data['rr_details'] = $this->md_received_purchase->get_rr_details($rr_main['receipt_id']);
		$result = $this->md_purchase_order->get_po_main_po_id($data['rr_details'][0]['po_id']);	
		$data['rr_main']  = $this->md_received_purchase->get_po_received_single($result['po_number'],$rr_main['receipt_id']);	
		$data['main_data']  = $this->md_purchase_order->get_po_main($result['po_number']);
		$data['supplier']     = $this->md_purchase_order->get_supplier_po($data['main_data']['supplierType'],$data['main_data']['supplierID']);

		$data['po_data'] = $this->md_purchase_order->getBypo_id($data['main_data']['po_id']);
		$data['credit'] = $this->md_voucher->get_cv_no($data['rr_main']['receipt_id'],$data['rr_main']['po_id']);

		$total = 0;
		foreach($data['rr_details'] as $row)
		{
			$cost = $row['item_quantity_actual'] * $row['item_cost_ordered'];
			$total =  $total + $cost;
		}

		$data['total'] = $this->extra->number_format($total);
		$data['date'] = date('Y-m-d');

		$arg = array(
			'prepared_by'=>'1',
			'type'=>'dv',
			'approved_by'=>'1',
			'noted_by'=>'1',
			'checked_by'=>'1',
			);
		
		$data['signatory'] = $this->extra->signatory4($arg);

		$cash_or_check = $this->md_voucher->cash_or_check($data['rr_main']['receipt_id']);
				
		if($cash_or_check['check_no']==''){
			$data['type'] ="CASH VOUCHER";
		}else{
			$data['type'] ="CHECK VOUCHER";
		}
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Cash Voucher";
		$this->lib_auth->build = "accounting/print/cash_voucher";
		$this->lib_auth->render($data);
		
	}


	public function cash_vouchering($id){

		$data['main']    = $this->md_voucher->get_cash_voucher_info($id);
		$data['details'] = $this->md_voucher->get_cash_voucher_details($id);
		$data['journal'] = $this->md_voucher->voucher_journal($id);
		
		$this->lib_auth->build = "accounting/print/cash_vouchering_journal";		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Cash Voucher";		
		$this->lib_auth->render($data);		
	}

	public function journal_voucher($id){

		$data['main']    = $this->md_voucher->get_cash_voucher_info($id);
		$data['details'] = $this->md_voucher->get_cash_voucher_details($id);
		$data['journal'] = $this->md_voucher->voucher_journal($id);
		
		$this->lib_auth->build = "accounting/print/journal_voucher";		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Journal Voucher";		
		$this->lib_auth->render($data);

	}


	public function voucher_summary(){

		$data['header'] = $this->head;

		$arg = array(
				'location' => $_GET['project'],
				'date_from' => $_GET['from'],
				'date_to' => $_GET['to'],
				'view_type' => $_GET['view_type'],
				'supplier' => $_GET['supplier'],
				'cash_advance' => $_GET['ca'],
				'year' => $_GET['year'],
				'month' => $_GET['month'],
				'payment_type' => $_GET['payment']
			);

		$data['project_name'] = $_GET['name'];
		$data['payment'] = $_GET['payment'];
		$data['from'] = date('F d Y',strtotime($_GET['from']));
		$data['to']   = date('F d Y',strtotime($_GET['to']));
		
		/*$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);
		
		$result = $this->md_voucher->get_cash_voucher_summary($arg);
		
		$show = array(
				'Date',
				'Voucher No',
				'Particulars',
				'Amount',
		 		);
		
		$this->table->set_heading($show);
		foreach($result as $key => $row){

			$row_content   = array();
			$row_content[] = array('data'=>$row['voucher_date']);
			$row_content[] = array('data'=>$row['voucher_no']);
			$row_content[] = array('data'=>$row['type']." ".$row['pay_to']);
			$row_content[] = array('data'=>number_format($row['total_amount'],2));
			$this->table->add_row($row_content);

		}

		$data['table'] = $this->table->generate();*/

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_voucher->get_cash_voucher_summary($arg);
		
		$show = array(
				'Date',
				'Voucher No',
				/*'TIN',*/
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
			/*$row_content[] = array('data'=>$row['tin_number']);*/
			$row_content[] = array('data'=>$row['pay_to']);
			$row_content[] = array('data'=>$row['type']." ".$row['short_desc']);
			$row_content[] = array('data'=>$row['bank']." ".$row['check_no']);
			$row_content[] = array('data'=>$row['ref_doc']);

			$gross = ($row['total_amount'] / 1.12);
			$input_vat = ($row['total_amount'] - $gross);

			$grand_total['total'] = $grand_total['total'] + $row['total_amount'];

			$row_content[] = array('data'=>number_format($gross,2),'style'=>'text-align:right;');
			$row_content[] = array('data'=>number_format($input_vat,2),'style'=>'text-align:right;');
			$row_content[] = array('data'=>number_format($row['total_amount'],2),'style'=>'text-align:right;');
			$this->table->add_row($row_content);

		}

		$row_content = array();

		/*$row_content[] = array('data'=>'','style'=>'font-weight:bold');*/
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'','style'=>'font-weight:bold');
		$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
		$row_content[] = array('data'=>$this->extra->number_format($grand_total['total']),'style'=>'font-weight:bold;text-align:right;');

		$this->table->add_row($row_content);

		$data['table'] = $this->table->generate();

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Voucher Summary";
		$this->lib_auth->build = "accounting/print/voucher_summary";
		$this->lib_auth->render($data);

	}





	public function project_expenses(){

		$data['header'] = $this->head;

		$arg['location']  = $_GET['project'];
		$arg['view_type'] = 'date_range';
		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];


		$data['from'] = date('F d Y',strtotime($arg['date_from']));
		$data['to']   = date('F d Y',strtotime($arg['date_to']));

		$result = $this->md_project_expense->get_expense2($arg);		
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-condensed">' );
		$this->table->set_template($tmpl);

			$show = array(
					array('data'=>'Type','style'=>'width:60px'),
					'Project',
					'Others',
					'BOQ',
					'PAYROLL',
					'Total',
			 		);

				$total_debit  = 0;
				$total_credit = 0;

				$po_list = array();
				$total_cost = 0;

				$grand_total['others'] = 0;
				$grand_total['boq'] = 0;
				$grand_total['payroll'] = 0;
				$grand_total['total'] = 0;

				foreach($result as $key => $value){			
					$total = 0;
					$row_content = array();
					$row_content[] = array('data'=>(isset($value['project_type'])? $this->extra->project_type_label2($value['project_type']) : '' ));
					$row_content[] = array('data'=>(isset($value['project_name'])? $value['project_name'] : '' ));
					$row_content[] = array('data'=>(isset($value['OTHERS'])? $this->extra->number_format($value['OTHERS']) : ''));					
					$row_content[] = array('data'=>(isset($value['BOQ'])? $this->extra->number_format($value['BOQ']) : ''));
					$row_content[] = array('data'=>(isset($value['PAYROLL'])? $this->extra->number_format($value['PAYROLL']) : ''));
					$total = $value['OTHERS'] +  $value['BOQ'] + $value['PAYROLL'];

					$grand_total['others'] = $grand_total['others'] + $value['OTHERS'];
					$grand_total['boq'] = $grand_total['boq'] + $value['BOQ'];
					$grand_total['payroll'] = $grand_total['payroll'] + $value['PAYROLL'];
					$grand_total['total'] = $grand_total['total'] + $total;

					$row_content[] = array('data'=>(isset($total)? $this->extra->number_format($total) : ''));					
					$this->table->add_row($row_content);					
				}

					$row_content = array();				
				$row_content[] = array('data'=>'Total','style'=>'font-weight:bold');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['others']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['boq']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['payroll']),'style'=>'font-weight:bold;');
				$row_content[] = array('data'=>$this->extra->number_format($grand_total['total']),'style'=>'font-weight:bold;');				

		$this->table->add_row($row_content);

		$this->table->set_heading($show);
		$data['table'] = $this->table->generate();
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Project Expenses";
		$this->lib_auth->build = "accounting/print/project_expenses";
		$this->lib_auth->render($data);

	}


	public function trial_balance(){

		$data['header'] = $this->head;
		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];
		$arg['location']  = $_GET['location'];
						
		$data['from'] = date('F d,Y',strtotime($arg['date_from']));
		$data['to']   = date('F d,Y',strtotime($arg['date_to']));

		$result = $this->md_trial_balance->get_cumulative($arg);

		$data['data'] = $result->result_array();
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Trial Balance";
		$this->lib_auth->build = "accounting/print/trial_balance";
		$this->lib_auth->render($data);

	}


	public function income_statement(){

		$data['header'] = $this->head;

		/*
		
		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];
		$arg['location']  = $_GET['location'];
		
		$data['from'] = date('F d,Y',strtotime($arg['date_from']));
		$data['to']   = date('F d,Y',strtotime($arg['date_to']));
		
		$result = $this->md_trial_balance->get_cumulative($arg);
		
		$data['data'] = $result->result_array();
		
		*/

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);
			
		$data['from'] = $_GET['from'];
		$data['to']   = $_GET['to'];
		
		$data['pay_item'] = '';
		$data['project']  = $_GET['location'];
		$data['check']    = $this->session->userdata('Proj_Main');
		$data['year']     = '';
		
		$result = $this->md_income_statement->get_cumulative($data['from'],$data['to'],$data['project'],$data['check'],$data['pay_item']);		
		
		$data['from'] = date('F d,Y',strtotime($data['from']));
		$data['to']   = date('F d,Y',strtotime($data['to']));
		
		$table  = null;
		$prev_total_income = 0;
		$current_total_income = 0;
		$prev_total_expenses = 0;
		$current_total_expenses = 0;
		$prev_total_revenue = 0;
		$current_total_revenue = 0;
		$include_income = false;
		$include_expense = false;		



		$counter = 0;
		$cnt     = 0 ;
		$dup_td  = "";

		if($result){
			$table .="<table class='myTable' style='width:100%'>";
			$table .= "<thead>
						<tr>
							<td>Income / Expense</td>";

					foreach($result[5] as $row){
						$table.="<td>{$row['DATE']}</td>";
						$counter ++;
						$dup_td .="<td></td>";
					}

			$table .="
						</tr>
						</thead>
				<tbody>";
				$table .= "<tr><td><strong style='color:red;'>INCOME</strong></td>{$dup_td}</tr>";
				foreach($result[0] as $resZero){
					if($resZero['short_description'] == "INCOME"){
						$include_income = false;
						foreach($result[2] as $resTwo){
							if($resZero['id'] == $resTwo['id']){

								if($include_income == false){
									$table .= "<tr><td style='padding-left:7em;'>".$resZero['full_description']."</td>{$dup_td}</tr>";
									$include_income = true;
								}

								$table .="<tr>";
								$cnt = 0;								
								foreach($resTwo as $key=>$row){
									if (ctype_digit($key)){
										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array[$cnt] =@ $array[$cnt] + $row;										
										$cnt++;
									}else{
										if($key == 'account_description_com'){
												$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
									}
								}								
								$table .="</tr>";
							}
						}
					}
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Income</strong></td> ";
				for ($i=0; $i < $counter ; $i++){ 
					$total = (isset($array[$i]))? $array[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
				}														
				$table.="</tr>";
				$table.="<tr></tr>";
				
				$table .= "<tr><td></td>{$dup_td}</tr>";
				$table .= "<tr><td>COST OF REVENUES</td>{$dup_td}</tr>";

				foreach($result[4] as $resFour){
					$cnt = 0;
					foreach($resFour as $key=>$row){
								if (ctype_digit($key)){

										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array1[$cnt] =@ $array1[$cnt] + $row;
										$cnt++;

									}else{
										if($key == 'account_description_com'){
												$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
								}
					}							
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Cost of Revenues</strong></td> ";
				for ($i=0; $i < $counter ; $i++) { 
					$total = (isset($array1[$i]))? $array1[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
				}														
				$table.="</tr>";
				$table.="<tr></tr>";

				$table .= "<tr><td><strong style='color:red;'>EXPENSES</strong></td>{$dup_td}</tr>";
				foreach($result[1] as $resOne){
					if($resOne['short_description'] == "EXPENSES"){
						foreach($result[3] as $resThree){
							if($resOne['id'] == $resThree['id']){
								if($include_expense == false){
									$table .= "<tr><td style='padding-left:7em;'>".$resOne['full_description']."</td>{$dup_td}</tr>";
									$include_expense = true;
								}

								$table .="<tr>";
								$cnt    = 0;

								foreach($resThree as $key=>$row){									
									if (ctype_digit($key)){
										$table .= "<td>".number_format($row, 2, '.', ',')."</td>";
										$array2[$cnt] =@ $array2[$cnt] + $row;
										$cnt++;
									}else{
										if($key == 'account_description_com'){
										$table .= "<tr><td style='padding-left:10em;'>".$row."</td>";
										}
									}
								}

								$table .="</tr>";	

							}
						}
					}
				}

				$table .= "<tr><td style='padding-left:5em;'><strong>Total Expense</strong></td>";
							
				for ($i=0; $i < $cnt ; $i++){
					$total  = (isset($array2[$i]))? $array2[$i] : 0;
					$table .="<td><strong>".number_format($total, 2, '.', ',')."</strong></td>";
					$net_income[$i] =@ ($array[$i] - $array1[$i]) - $total;
				}

				$table.="</tr>";
				$table.="<tr></tr>";
				
				$table .="<tr>";
				$table .="<td><strong>Net Profit</strong></td>";
				for ($i=0; $i < $cnt; $i++)
				 {
					$table.="<td><strong>".number_format($net_income[$i], 2, '.', ',')."</strong></td>";
				 }
				$table.="</tr></table>";			
				$table.="</table>";
			 $table;
		}

		$data['table'] = $table;

		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Income Statement";
		$this->lib_auth->build = "accounting/print/income_statement";
		$this->lib_auth->render($data);



	}

	

	public function balance_sheet_single(){

		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];
		$arg['location']  = $_GET['location'];

		$data['header'] = $this->head;
		$result = $this->md_balancesheet->get_balance_sheet($arg);
				
		$a = array();	
		foreach($result as $row){
			$a[$row['short_description']][$row['full_description']][] = $row;
		}
		
		$data['data'] = $a;

		$data['from'] = date('F d,Y',strtotime($arg['date_from']));
		$data['to']   = date('F d,Y',strtotime($arg['date_to']));
		
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Balance Sheet";
		$this->lib_auth->build = "accounting/print/balance_sheet_single";
		$this->lib_auth->render($data);
		
	}



	public function change_in_equity(){

		$arg['date_from'] = $_GET['from'];
		$arg['date_to']   = $_GET['to'];
		$arg['location']  = $_GET['location'];

		$data['header'] = $this->head;
		$result = $this->md_change_in_equity->get_equity($arg);
				
		$data['result'] = $result['data'];
		$data['date']   = $result['date'];
		$data['table'] = $this->load->view('accounting/change_in_equity/tbl_changeequity',$data,TRUE);

		$data['from'] = date('F d,Y',strtotime($arg['date_from']));
		$data['to']   = date('F d,Y',strtotime($arg['date_to']));
			
		$this->lib_auth->default = 'print';
		$this->lib_auth->title = "Print : Statement of Change in Equity";
		$this->lib_auth->build = "accounting/print/change_in_equity";
		$this->lib_auth->render($data);		
	}

	/***EXPORTING***/

	public function voucher_summary_excel(){
				
		$data = array(
					'supplier' => $_GET['supplier'],
					'year' => $_GET['year'],
					'month' => $_GET['month'],
					'location' => $_GET['location'],
					'display_by' => $_GET['display_by'],
					'view_type' => $_GET['view_type'],
					'date_from' => $_GET['date_from'],
					'date_to' => $_GET['date_to'],
					'payment_type' => $_GET['payment_type'],
					'cash_advance' => $_GET['cash_advance']
			);
		$result = $this->md_voucher->get_cash_voucher_summary($data);

		$title = "Voucher Summary - ".date('Y-m-d H:m:s');		
		require_once 'Classes/PHPExcel/IOFactory.php';
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');

		$objPHPExcel = $objReader->load("template/voucher_summary.xlsx");
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setCellValue('A1','Period : '.$data['date_from'].'-'.$data['date_to']);
		
		$baseRow = 3;
		foreach($result as $r=> $dataRow) {			
			$row = $baseRow + $r;			
			/*$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);*/			


			$row_content = array();



			$gross = ($dataRow['total_amount'] / 1.12);
			$input_vat = ($dataRow['total_amount'] - $gross);

			$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $dataRow['voucher_date']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, $dataRow['voucher_no']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$row, $dataRow['tin_number']);
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$row, $dataRow['pay_to']);
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $dataRow['type']." ".$dataRow['short_desc']);
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $dataRow['bank']." ".$dataRow['check_no']);
			$objPHPExcel->getActiveSheet()->setCellValue('G'.$row, $dataRow['ref_doc']);
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$row, number_format($gross,2));
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$row, number_format($input_vat,2));
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$row, number_format($dataRow['total_amount'],2));

						
		}
	
		/*	
		$row = $row +2;
		$objPHPExcel->getActiveSheet()->setCellValue('B'.$row, 'TOTAL');
		$objPHPExcel->getActiveSheet()->setCellValue('E'.$row, $t_debit);
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$row, $t_credit);
		$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
		*/

			
		header('Content-type: application/vnd.ms-excel');		
		header('Content-Disposition: attachment;filename="'.$title.'.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		ob_end_clean();
		$objWriter->save('php://output');
		


	}


	public function convert_number_to_words($number) {

	    $hyphen      = '-';
	    $conjunction = ' ';
	    $separator   = ', ';
	    $negative    = 'negative ';
	    $decimal     = ' and ';
	    $dictionary  = array(
	        0                   => 'zero',
	        1                   => 'one',
	        2                   => 'two',
	        3                   => 'three',
	        4                   => 'four',
	        5                   => 'five',
	        6                   => 'six',
	        7                   => 'seven',
	        8                   => 'eight',
	        9                   => 'nine',
	        10                  => 'ten',
	        11                  => 'eleven',
	        12                  => 'twelve',
	        13                  => 'thirteen',
	        14                  => 'fourteen',
	        15                  => 'fifteen',
	        16                  => 'sixteen',
	        17                  => 'seventeen',
	        18                  => 'eighteen',
	        19                  => 'nineteen',
	        20                  => 'twenty',
	        30                  => 'thirty',
	        40                  => 'fourty',
	        50                  => 'fifty',
	        60                  => 'sixty',
	        70                  => 'seventy',
	        80                  => 'eighty',
	        90                  => 'ninety',
	        100                 => 'hundred',
	        1000                => 'thousand',
	        1000000             => 'million',
	        1000000000          => 'billion',
	        1000000000000       => 'trillion',
	        1000000000000000    => 'quadrillion',
	        1000000000000000000 => 'quintillion'
	    );

	    if (!is_numeric($number)) {
	        return false;
	    }

	    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
	        // overflow
	        trigger_error(
	            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
	            E_USER_WARNING
	        );
	        return false;
	    }

	    if ($number < 0) {
	        return $negative . $this->convert_number_to_words(abs($number));
	    }

	    $string = $fraction = null;

	    if (strpos($number, '.') !== false) {
	        list($number, $fraction) = explode('.', $number);
	    }

	    switch (true) {
	        case $number < 21:
	            $string = $dictionary[$number];
	            break;
	        case $number < 100:
	            $tens   = ((int) ($number / 10)) * 10;
	            $units  = $number % 10;
	            $string = $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
	            break;
	        case $number < 1000:
	            $hundreds  = $number / 100;
	            $remainder = $number % 100;
	            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
	            if ($remainder) {
	                $string .= $conjunction . $this->convert_number_to_words($remainder);
	            }
	            break;
	        default:
	            $baseUnit = pow(1000, floor(log($number, 1000)));
	            $numBaseUnits = (int) ($number / $baseUnit);
	            $remainder = $number % $baseUnit;
	            $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
	            if ($remainder) {
	                $string .= $remainder < 100 ? $conjunction : ' ';
	                $string .= $this->convert_number_to_words($remainder);
	            }
	            break;
	    }

	    if (null !== $fraction && is_numeric($fraction)) {
	        $string .= $decimal; 

	        $fraction = str_pad($fraction,2,'0',STR_PAD_RIGHT);

	       	if($fraction < 10){
	       		$string .= $dictionary[abs($fraction)];
	        }elseif($fraction < 21){
            	$string .= $dictionary[$fraction];
            }elseif($fraction < 100){
            	$tens   = ((int) ($fraction / 10)) * 10;
	            $units  = $fraction % 10;
	            $string .= $dictionary[$tens];
	            if ($units) {
	                $string .= $hyphen . $dictionary[$units];
	            }
            }
			$string .= ' cents';
	        /*$string .= $this->convert_number_to_words(abs($fraction));*/

	        
	    }

	    return $string;
	}


}