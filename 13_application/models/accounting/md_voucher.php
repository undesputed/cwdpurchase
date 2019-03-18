<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_voucher extends CI_MODEL {

	public function __construct(){
		parent :: __construct();
		$this->load->model('md_project');
		$this->load->model('procurement/md_event_logs');		
	}


	public function voucher_no(){
		$date = date('Y-m-d');
		$date = explode('-',$date);

		$sql = "
		SELECT CAST(SUBSTRING(voucher_no,LOCATE('-',voucher_no,'10')+1,5) AS DECIMAL(25)) + 1 AS no 
		FROM voucher_no 		
		WHERE MONTH(save_date) = '{$date[1]}' AND YEAR(save_date) = '{$date[0]}' 
		ORDER BY id DESC LIMIT 1;
		";
		$result = $this->db->query($sql);
				
		$year = $date[0];
		$month = $date[1];
		if($result->num_rows() > 0){
			$row = $result->row_array();
			return "DV-".$year."-".$month."-".$this->str_pad2($row['no']);
		}else{
			return "DV-".$year."-".$month."-".$this->str_pad2(1);
		}
		
	}

	private function str_pad2($num){
		 return str_pad($num, 4, '0', STR_PAD_LEFT);
	}

	public function get_po_list($date = ''){
		if($date == ''){
			$date = date('Y-m-d');
		}

		$sql = "
			SELECT 
				receiving_details.po_id,
				CONCAT('PO ',purchase_order_main.reference_no) 'PO NUMBER',
				purchase_order_main.po_date 'PO DATE',
				(SELECT business_name FROM business_list WHERE business_number = receiving_main.supplier_id) 'SUPPLIER',
				(SELECT FORMAT(SUM(total_unitcost),2) FROM purchase_order_details WHERE po_id = receiving_details.po_id) 'PO AMOUNT',
				purchase_order_main.paymentTerm 'TERM',
				purchase_order_main.status 'STATUS',
				purchase_order_main.bank_id,
				purchase_order_main.supplierID,
				receiving_main.supplier_invoice,
				purchase_order_main.status 
			      FROM receiving_details
			INNER JOIN receiving_main
				ON receiving_main.receipt_id = receiving_details.receipt_id
			INNER JOIN purchase_order_main
				ON purchase_order_main.po_id = receiving_details.po_id
			     WHERE purchase_order_main.project_id LIKE '%%'			     
			     AND (purchase_order_main.status = 'COMPLETE' OR purchase_order_main.status = 'PARTIAL')
			     AND (SELECT cv_id 
					FROM journal_detail 
					INNER JOIN journal_main 
					ON(journal_detail.journal_id = journal_main.journal_id) 
					WHERE journal_main.rr_id = receiving_details.receipt_id 
					AND journal_main.po_id = receiving_details.po_id 
					AND journal_detail.dr_cr = 'CREDIT' LIMIT 1) = '0'
			     GROUP BY receiving_details.po_id
			     ORDER BY receiving_details.po_id;
		";

		/*	$sql = "CALL llard_polist('%%','".$date."','2');";*/
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_voucher_item($po_id,$journal_id = ""){


		$where = "";
		if($journal_id !="")
		{
			/*$where = " AND journal_main.journal_id = '".$journal_id."'";*/
		}

		$sql = "
		SELECT 
		purchase_order_main.po_id,
		purchase_order_main.reference_no 'PO N0.',
		purchase_order_details.item_name 'PO ITEMS',
		(SELECT account_code FROM account_setup WHERE account_id = setup_group_detail.account_id) 'ACCOUNT CODE',
		(SELECT account_description FROM account_setup WHERE account_id = setup_group_detail.account_id) 'ACCOUNT DESCRIPTION',
		FORMAT(purchase_order_details.total_unitcost,2) 'AMOUNT',			
		purchase_order_main.bank_id	
		FROM purchase_order_main
		INNER JOIN purchase_order_details
		ON purchase_order_main.po_id = purchase_order_details.po_id
		INNER JOIN purchaserequest_main
		ON purchaserequest_main.pr_id = purchase_order_main.pr_id
		INNER JOIN setup_group_detail 
		ON (setup_group_detail.group_detail_id = purchase_order_details.itemNo)
		WHERE purchase_order_main.po_id = '{$po_id}'
		ORDER BY purchase_order_main.po_id;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_bank(){				
		$sql    = "SELECT bank_id,bank_name FROM bank_setup";
		$result = $this->db->query($sql);		
		return $result->result_array();	

	}


	public function get_dv_no(){		
		$sql = "SELECT setup_cv_dtl.cvdtl_id,setup_cv_dtl.cv_no,setup_cv_dtl.cv_id FROM setup_cv INNER JOIN setup_cv_dtl ON(setup_cv_dtl.cv_id = setup_cv.cv_id) WHERE setup_cv_dtl.cv_status = 'UNUSED' AND setup_cv.title_id = '".$this->session->userdata('Proj_Main')."'";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}

	

	public function save_voucher($arg){
		$this->db->trans_begin();

		if($this->check_vouche_no($arg['dv_no'])){
			$v_no = $this->voucher_no();
			$voucher_no = array(
				'voucher_no'=>$v_no,
				'save_date'=>date("Y-m-d H:i:s"),
				);
			$this->db->insert('voucher_no',$voucher_no);
		}else{
			$v_no = $arg['dv_no'];
			$voucher_no = array(
				'voucher_no'=>$arg['dv_no'],
				'save_date'=>date("Y-m-d H:i:s"),
				);
			$this->db->insert('voucher_no',$voucher_no);
		}

		$transaction_type = (!isset($arg['transaction_type']))? '' : $arg['transaction_type'];

		
		$main_insert = array(
			'pay_to'=>$arg['Supplier'],
			'address'=>'',
			'voucher_id'=>'',
			'voucher_no'=>$v_no,
			'voucher_date'=>date('Y-m-d'),
			'total_amount'=>$arg['amount'],
			'project_id'=>$arg['from_projectCode'],
			'title_id'=>$arg['from_projectMain'],
			'preparedby'=>$this->session->userdata('user'),
			'checkedby'=>'',
			'approvedby'=>'',
			'remarks'=>$arg['remarks'],
			'bank_id'=>$arg['bank_id'],
			'bank'=>$arg['bank_name'],
			'check_no'=>$arg['check_no'],
			'check_date'=>$arg['due_date'],
			'journal_id'=>'',
			'po_id'=>$arg['po_id'],
			'po_no'=>$arg['po_number'],
			'rr_id'=>$arg['rr_id'],
			'invoice_no'=>$arg['si_no'],
			'type'=>'PAYMENT FOR',
			'payment_type'=>'CHECK',
			'project_type'=>$arg['project_type'],
			'transaction_type'=>$transaction_type,
		);
		
		$this->db->insert('cash_voucher_main',$main_insert);
		$id = $this->db->insert_id();
		$main_insert['cash_voucher_id'] = $id;
		$result = $this->get_invoice_item($arg['rr_id'],$arg['po_id']);

		$group_account_id = array();	
		foreach($result as $row){
					
			$details = array(
				'cash_voucher_main_id'=>$id,
				'item_no'=>$row['item_id'],
				'item_description'=>$row['PO ITEMS'],
				'account_code'=>$row['ACCOUNT CODE'],
				'amount'=>$row['AMOUNT'],				
			);

			$group_account_id[$row['account_id']][] =  $row;
			$this->db->insert('cash_voucher_detail',$details);

		}

		$accounts = array();		
	/*	if(!empty($group_account_id)){				
			$cnt = 0;
			foreach($group_account_id as $key=>$row){
				$accounts[$cnt] = array('account_id'=>$key);
				$total_amount = 0;
				foreach($row as $row1){
					$total_amount = $total_amount + $row1['AMOUNT'];
				}

				$accounts[$cnt]['amount'] = $total_amount;
				$cnt++;
			}
		}*/

		$accounts = $arg['journal_details'];

		$journal_id = $this->save_voucher_journal2($main_insert,$accounts);

		$update = array(
			'journal_id'=>$journal_id
			);

		$this->db->where('cash_voucher_id',$id);
		$this->db->update('cash_voucher_main',$update);

		$event['type']    = 'CASH VOUCHER';
		$event['transaction_no'] = $v_no;
		$event['transaction_id'] = $id;
		$event['remarks'] = '';
		$event['action']  = 'ADD';

		$this->md_event_logs->create($event);


		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
	}

	public function save_voucher_journal2($main,$details){
					
		$this->db->trans_begin();

		$insert = array(
			'reference_no'=>$this->extra->get_journal_code($main['voucher_date'],'',$main['project_id'],'JEV'),
			'trans_date'=>$main['voucher_date'],
			'type'=>'JOURNAL ENTRY',
			'memo'=>'',
			'trans_type'=>'CASH VOUCHER',
			'status'=>'ACTIVE',
			'location'=>$main['project_id'],
			'title_id'=>$main['title_id'],
			'userid'=>$this->session->userdata('user'),
			'username'=>$this->session->userdata('user'),
			'division'=>'0',
			'transaction_type'=>'0',
			'expense_type'=>'0',
			'cost_type'=>'0',
			'fund_location'=>'0',
			'po_id'=>$main['po_id'],
			'account_type'=>'0',
			'pay_center'=>'',
			'pay_item'=>'',
			'_due_date'=>$main['check_date'],
			'_balance'=>$main['total_amount'],
			'_amount'=>$main['total_amount'],
			'name_id'=>'',
			'name_type'=>'',
			'particulars'=>'',
			'voucher_id'=>$main['cash_voucher_id'],
			);
		
		$this->db->insert('journal_main1',$insert);		
		$journal_id = $this->db->insert_id();

		foreach($details as $row){

			$dr_cr  = null;
			$amount = null;
			
			if(!empty($row['debit']) && $row['debit']!="-"){
				$dr_cr = "DEBIT";
				$amount = $row['debit']; 
			}else{
				$dr_cr = "CREDIT";
				$amount = $row['credit']; 
			}


			$subsidiary = $row['bank']." ".$row['check_no'];
			if($subsidiary == ""){
				$subsidiary = $row['remarks'];
			}

			$insert_details = array(
				'journal_id'=>$journal_id,
				'account_id'=>$row['account_id'],
				'amount'=>str_replace(',','',$amount),
				'discount_tax'=>'',
				'dr_cr'=>$dr_cr,
				'supplier_id'=>'',
				'type'=>'',
				'bank'=>$row['bank'],
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$row['check_date'],
				'balance'=>'',
				'from_location'=>'0',
				'subsidiary'=>$subsidiary,
				'subsidiary_type'=>'',
				'cv_id'=>'0',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'memorandum'=>$row['remarks'],
				'check_no'=>$row['check_no'],
			);			
			$this->db->insert('journal_detail1',$insert_details);
		}

		$dv_id = $main['cash_voucher_id'];

		if(!empty($dv_id)){
			$update = array(
				'journal_id'=>$journal_id,
				);
			$this->db->where('cash_voucher_id',$dv_id);
			$this->db->update('cash_voucher_main',$update);		
		}

		if($this->db->trans_status() === true){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
		}

	}

	public function save_voucher_journal($main,$details){
		
		$this->db->trans_begin();
			
			$reference_no = $this->get_journal_max_id($main);
			
			$insert_journal_main = array(
				'reference_no'=>$reference_no,
				'trans_date'=>$main['voucher_date'],
				'type'=>'CASH VOUCHER',
				'memo'=>'CASH VOUCHER',
				'trans_type'=>'ENTER PAYMENT',
				'status'=>'ACTIVE',
				'location'=>$main['project_id'],
				'title_id'=>$this->session->userdata('Proj_Main'),
				'userid'=>$this->session->userdata('user'),
				'division'=>'0',
				'transaction_type'=>'0',
				'expense_type'=>'0',
				'cost_type'=>'0',
				'fund_location'=>$main['project_id'],
				'po_id'=>'0',
				'account_type'=>'0',
				'pay_center'=>'0',
				'pay_item'=>$main['project_type'],
				'_balance'=>$main['total_amount'],
				'_amount'=>$main['total_amount'],
				'po_id'=>$main['po_id'],
				'rr_id'=>$main['rr_id'],
				);
			$this->db->insert('journal_main',$insert_journal_main);
			$journal_id = $this->db->insert_id();
			
			/*
			$sql    = "select journal_id from journal_main where reference_no = '".$reference_no."'";
			$result = $this->db->query($sql);
			$result = $result->row_array();
			$journal_id = $result['journal_id'];
			*/

			foreach($details as $row){

				$insert_journal_detail = array(
					'journal_id'=>$journal_id,
					'account_id'=>$row['account_id'],
					'amount'=>$row['amount'],
					'discount_tax'=>'',
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'posted'=>'TRUE',
					'type'=>'BUSINESS',
					'bank_account_no'=>'',
					'bank_account_name'=>'',
					'cv_no'=>'',
					'check_id'=>'',
					'check_date'=>'',
					'balance'=>'0',
					'from_location'=>'0',
					'subsidiary'=>$main['pay_to'],
					'subsidiary_type'=>'SUPPLIER',
					'bank_account_id'=>'0',
					'bank'=>'FALSE',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);

				$this->db->insert('journal_detail',$insert_journal_detail);
			}
			
			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'4',
				'amount'=>$main['total_amount'],
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>'',
				'posted'=>'FALSE',
				'type'=>'BUSINESS',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>$main['voucher_no'],
				'check_id'=>'',
				'check_date'=>'',
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>'',
				'subsidiary_type'=>'BANK',
				'bank_account_id'=>'',
				'bank'=>'TRUE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>$main['voucher_id'],
				'dv_number'=>$main['voucher_no'],
				);
			$this->db->insert('journal_detail',$insert_journal_detail);
			
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return $journal_id;
		}

	}






	public function update_voucher($arg){

		$this->db->trans_begin();

		$main_insert = array(
			'pay_to'=>$arg['Supplier'],
			'address'=>'',
			'voucher_id'=>'',
			'voucher_no'=>$arg['dv_no'],
			'voucher_date'=>date('Y-m-d'),
			'total_amount'=>$arg['amount'],
			'project_id'=>$arg['from_projectCode'],
			'title_id'=>$arg['from_projectMain'],
			'preparedby'=>$this->session->userdata('user'),
			'checkedby'=>'',
			'approvedby'=>'',
			'remarks'=>$arg['remarks'],
			'bank_id'=>$arg['bank_id'],
			'bank'=>$arg['bank_name'],
			'check_no'=>$arg['check_no'],
			'check_date'=>$arg['due_date'],			
			'po_id'=>$arg['po_id'],
			'rr_id'=>$arg['rr_id'],
			'type'=>'PAYMENT FOR',
			'payment_type'=>'CHECK',
			);

		$this->db->where('cash_voucher_id',$arg['cash_main_id']);		
		$this->db->update('cash_voucher_main',$main_insert);
		$id = $arg['cash_main_id'];	
		$result = $this->get_invoice_item($arg['rr_id'],$arg['po_id']);

		$this->db->where('cash_voucher_main_id',$id);
		$this->db->delete('cash_voucher_detail');
	

		$group_account_id = array();
		foreach($result as $row){
			$details = array(
				'cash_voucher_main_id'=>$id,
				'item_no'=>$row['item_id'],
				'item_description'=>$row['PO ITEMS'],
				'account_code'=>$row['ACCOUNT CODE'],
				'amount'=>$row['AMOUNT'],
			);
			$group_account_id[$row['account_id']][] =  $row;
			$this->db->insert('cash_voucher_detail',$details);
		}
		


		$accounts = array();		
		if(!empty($group_account_id)){				
			$cnt = 0;
			foreach($group_account_id as $key=>$row){
				$accounts[$cnt] = array('account_id'=>$key);
				$total_amount = 0;
				foreach($row as $row1){
					$total_amount = $total_amount + $row1['AMOUNT'];
				}

				$accounts[$cnt]['amount'] = $total_amount;
				$cnt++;
			}
		}

		$main = $this->get_cash_voucher_main($arg['cash_main_id']);

		$this->update_voucher_journal($main,$accounts);

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}

	}


	public function update_voucher_journal($main,$details){
		
		$this->db->trans_begin();
			
			$reference_no = $this->get_journal_max_id($main);
			
			$insert_journal_main = array(
				'reference_no'=>$reference_no,
				'trans_date'=>$main['voucher_date'],
				'type'=>'CASH VOUCHER',
				'memo'=>'CASH VOUCHER',
				'trans_type'=>'ENTER PAYMENT',
				'status'=>'ACTIVE',
				'location'=>$main['project_id'],
				'title_id'=>$this->session->userdata('Proj_Main'),
				'userid'=>$this->session->userdata('user'),
				'division'=>'0',
				'transaction_type'=>'0',
				'expense_type'=>'0',
				'cost_type'=>'0',
				'fund_location'=>$main['project_id'],
				'po_id'=>'0',
				'account_type'=>'0',
				'pay_center'=>'0',
				'pay_item'=>$main['project_type'],
				'_balance'=>$main['total_amount'],
				'_amount'=>$main['total_amount'],
				'po_id'=>$main['po_id'],
				'rr_id'=>$main['rr_id'],
				);

			$this->db->where('journal_id',$main['journal_id']);
			$this->db->update('journal_main',$insert_journal_main);
			$journal_id = $main['journal_id'];
						
			$this->db->where('journal_id',$main['journal_id']);
			$this->db->delete('journal_detail');

			foreach($details as $row){

				$insert_journal_detail = array(
					'journal_id'=>$journal_id,
					'account_id'=>$row['account_id'],
					'amount'=>$row['amount'],
					'discount_tax'=>'',
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'posted'=>'TRUE',
					'type'=>'BUSINESS',
					'bank_account_no'=>'',
					'bank_account_name'=>'',
					'cv_no'=>'',
					'check_id'=>'',
					'check_date'=>'',
					'balance'=>'0',
					'from_location'=>'0',
					'subsidiary'=>$main['pay_to'],
					'subsidiary_type'=>'SUPPLIER',
					'bank_account_id'=>'0',
					'bank'=>'FALSE',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);

				$this->db->insert('journal_detail',$insert_journal_detail);

			}
			
			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'4',
				'amount'=>$main['total_amount'],
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>'',
				'posted'=>'FALSE',
				'type'=>'BUSINESS',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>$main['voucher_no'],
				'check_id'=>'',
				'check_date'=>$main['check_date'],
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$main['bank'],
				'subsidiary_type'=>'BANK',
				'bank_account_id'=>'',
				'bank'=>'TRUE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>$main['voucher_id'],
				'check_no'=>$main['check_no'],
				'dv_number'=>$main['voucher_no'],
				);
			$this->db->insert('journal_detail',$insert_journal_detail);
		
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return $journal_id;
		}

	}



	public function journal_save_voucher($arg){

		$this->db->trans_begin();

		$sql    = "Select journal_id from journal_main where po_id = '".$arg['po_id']."' and rr_id = '{$arg['rr_id']}'";
		$result = $this->db->query($sql);
		$result = $result->row_array();
		$journal_id = $result['journal_id'];
		
		if($arg['paymentTerm'] == 'COD'){

			$update = array(
				'memo'=>$arg['remarks'],			
				);
			$this->db->where('journal_id',$journal_id);
			$this->db->update('journal_main',$update);

			$reference_no = $this->get_journal_max_id($arg);

			$update = array(
				'cv_id'=>$arg['dv_no'],
				'subsidiary'=>$arg['bank_name'],
				'bank_account_id'=>$arg['bank_id'],
				'cv_no'=>$arg['dv_no'],
				'check_no'=>$arg['check_no'],
				'dv_number'=>$arg['dv_no_name'],
				);

			$this->db->where('journal_id',$journal_id);
			$this->db->where('dr_cr','CREDIT');
			$this->db->update('journal_detail',$update);


		}else if($arg['paymentTerm'] == 'In Days'){

			#ref_no
			$update = array(
				'cv_id'=>$arg['dv_no'],
				'subsidiary'=>$arg['bank_name'],
				'bank_account_id'=>$arg['bank_id'],
				'cv_no'=>$arg['dv_no'],
				);
			$this->db->where('journal_id',$journal_id);
			$this->db->where('dr_cr','CREDIT');
			$this->db->update('journal_detail',$update);


			$reference_no = $this->get_journal_max_id($arg);


			$insert_journal_main = array(
				'reference_no'=>$reference_no,
				'trans_date'=>$arg['date'],
				'type'=>'PAYABLE',
				'memo'=>$arg['remarks'],
				'trans_type'=>'PAY PAYABLE',
				'status'=>'ACTIVE',
				'location'=>$this->session->userdata('Proj_Code'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				'userid'=>$this->session->userdata('user'),
				'division'=>'0',
				'transaction_type'=>'0',
				'expense_type'=>'0',
				'cost_type'=>'0',
				'fund_location'=>$this->session->userdata('ProjCode'),
				'po_id'=>$arg['po_id'],
				'account_type'=>'0',
				'pay_center'=>'0',
				'pay_item'=>'0',
				);

			$this->db->insert('journal_main',$insert_journal_main);

			$sql    = "select journal_id from journal_main where reference_no = '".$reference_no."'";
			$result = $this->db->query($sql);
			$result = $result->row_array();
			$journal_id = $result['journal_id'];
			
			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'139',
				'amount'=>$arg['amount'],
				'discount_tax'=>'',
				'dr_cr'=>'DEBIT',
				'supplier_id'=>$arg['supplier_id'],
				'posted'=>'FALSE',
				'type'=>'BUSINESS',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$arg['date'],
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$arg['Supplier'],
				'subsidiary_type'=>'Supplier',
				'bank_account_id'=>'0',
				'bank'=>'FALSE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
				);
			$this->db->insert('journal_detail',$insert_journal_detail);

			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'7',
				'amount'=>$arg['amount'],
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>$arg['supplier_id'],
				'posted'=>'FALSE',
				'type'=>'BUSINESS',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>$arg['dv_no_name'],
				'check_id'=>'',
				'check_date'=>$arg['date'],
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$arg['bank_name'],
				'subsidiary_type'=>'BANK',
				'bank_account_id'=>$arg['bank_id'],
				'bank'=>'TRUE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>$arg['dv_no'],
				);
			$this->db->insert('journal_detail',$insert_journal_detail);

			$update_journalMain = array(
				'_balance'=>$arg['amount'],
				'_journal'=>$journal_id,				
				);			
			$this->db->where('po_id',$arg['po_id']);
			$this->db->update('journal_main',$update_journalMain);

		}

		/*
		$update_cv_dtl = array(
			'cv_status'=>'USED',
			'date_used'=>$arg['date'],
			'employee_id'=>$this->session->userdata('user'),
			);
		$this->db->where('cvdtl_id',$arg['dv_no']);
		$this->db->update('setup_cv_dtl',$update_cv_dtl);
		*/

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return true;
		}		

	}

	public function get_journal_max_id($arg = ''){

			$date = (empty($_POST['date']))? date('Y-m-d') : $_POST['date'];			

			return $this->extra->get_journal_code($date,'VOUCHER',$arg['project_id'],'JRN');

			/*$data  = $this->md_project->get_max_transfer($month,$year);
			$type = "EIV";
			if(empty($data[0]['max'])){
				return  $type."-".$month."-".$this->str_pad(1)."-".$year;
			}else{
				return $type."-".$month."-".$this->str_pad($data[0]['max'] + 1)."-".$year;
			}*/

			
				
	}		

	private function str_pad($num){
		 return str_pad($num, 3, '0', STR_PAD_LEFT);
	}


	public function get_voucher_cumulative($page = "1",$params = ""){

		$branch_type = $this->session->userdata('branch_type');
		$where  = "";
		$search = "";

		/*
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   WHERE (from_projectCode = '".$this->session->userdata('Proj_Code')."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";

			break;			
			default:
				$where = "
				   WHERE (from_projectCode = '".$this->session->userdata('Proj_Code')."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";				
			break;

		}
		*/
		

		$has_where = false;
		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;

				case "pending":
					$where .=" WHERE cvm.status = 'ACTIVE' ";					
					$has_where = true;
				break;

				case "journal":
					$where .=" WHERE cvm.payment_type = 'journal'";
					$has_where = true;
				break;			
			}
		}


		if(isset($params['search']) && $params['search'] != '')
			{

					if($has_where){
						$search .= "
							AND (item_description LIKE '%{$params['search']}%'						
							OR voucher_no like '%{$params['search']}%')			
						";			
					}else{
						$search .= "
							WHERE item_description LIKE '%{$params['search']}%'			
							OR voucher_no like '%{$params['search']}%' 
							OR full_bank like '%{$params['search']}%'									
						";			
					}
					

			}

		
		$sql2 = "

			SELECT
			*
			FROM(				
				SELECT 
				cvm.*,
				cvd.`item_description`,
				concat(cvm.bank,' ',cvm.check_no) as full_bank,
				(SELECT
					CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					`hr_employee`
					INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
					WHERE `hr_employee`.`emp_number` = cvm.preparedby
				) 'preparedBy_name',
				(SELECT project_name FROM setup_project WHERE project_id = cvm.project_id) AS 'project_name',
				(SELECT project_name FROM project_category WHERE id = cvm.project_type) AS 'project_category'
				FROM cash_voucher_main cvm		
				INNER JOIN cash_voucher_detail cvd
	 			ON (cvd.cash_voucher_main_id = cvm.`cash_voucher_id`)
				{$where}	
				GROUP BY cash_voucher_id
				ORDER BY voucher_date DESC,voucher_no DESC 
			)a {$search}
		";
		
		$limit = $this->config->item('limit');		
		$start = ($page * $limit) - $limit;
		$next = '';
		$result = $this->db->query($sql2);			
		if($result->num_rows() > ($page * $limit) ){
			$next = $page + 1;
		}

		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );			
				
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;
		
	}
	

	public function get_voucher_cumulative_po_id($po_id){
		$sql = "				
				SELECT
				purchase_order_main.po_id,
				purchase_order_main.pr_id,
				receiving_details.receipt_id,				
				(SELECT
				 date_used
				FROM journal_detail
				  INNER JOIN journal_main a
				    ON (journal_detail.journal_id = a.journal_id)
				  INNER JOIN setup_cv_dtl
				  ON (journal_detail.cv_id = setup_cv_dtl.cvdtl_id)
				WHERE 					
				     a.po_id = journal_main.po_id
				    AND journal_detail.dr_cr = 'CREDIT') AS 'CV DATE',
				(SELECT
				 setup_cv_dtl.cv_no
				FROM journal_detail
				  INNER JOIN journal_main a
				    ON (journal_detail.journal_id = a.journal_id)
				  INNER JOIN setup_cv_dtl
				  ON (journal_detail.cv_id = setup_cv_dtl.cvdtl_id)
				WHERE 				
				     a.po_id = journal_main.po_id
				    AND journal_detail.dr_cr = 'CREDIT') 'CV NO.',
				purchase_order_main.reference_no 'PO N0.',
				(SELECT supplier_invoice FROM receiving_details INNER JOIN receiving_main ON(receiving_main.receipt_id = receiving_details.receipt_id) WHERE receiving_details.po_id = purchase_order_main.po_id LIMIT 1) 'SI N0.',
				(SELECT business_name FROM business_list WHERE business_number = purchase_order_main.supplierID) 'SUPPLIER',
				purchase_order_main.po_remarks 'REMARKS',
				FORMAT(SUM(receiving_details.item_quantity_ordered * receiving_details.item_cost_ordered),2) 'AMOUNT',
				purchase_order_main.supplierID,
				purchase_order_main.bank_id			
				FROM 		purchase_order_main
				INNER JOIN 	receiving_details
				ON 		receiving_details.po_id = purchase_order_main.po_id
				INNER JOIN 	journal_main
				ON  		journal_main.po_id = purchase_order_main.po_id
				WHERE purchase_order_main.title_id = ".$this->session->userdata('Proj_Main')."
				AND receiving_details.po_id = '{$po_id}'
				GROUP BY receiving_details.receipt_id
				ORDER BY `CV DATE` DESC;				
		";

		$result = $this->db->query($sql);
		return $result->row_array();
				
	}
	
	public function journal_details($journal_id){

		$sql = "CALL display_journal_detail('".$journal_id."');";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}


	public function journal_details_credit($journal_id){

		$sql = "SELECT * FROM journal_detail WHERE journal_id = '".$journal_id."' AND dr_cr = 'CREDIT'";
		$result = $this->db->query($sql);
		return $result->row_array();

	}


	public function get_journal_main($po_id){

		$sql = "SELECT * FROM journal_main WHERE po_id = '".$po_id."' AND trans_type = 'PAY PAYABLE';";
		$result = $this->db->query($sql);
		return $result->row_array();

	}
		
	public function get_po_journal_main($journal_id){
		$sql = "SELECT po_id,journal_id FROM journal_main WHERE journal_id = '".$journal_id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}

	public function get_subsidiary($journal_id){
		$sql = "SELECT * FROM setup_cv_dtl INNER JOIN journal_detail ON (journal_detail.cv_id = setup_cv_dtl.cvdtl_id) WHERE journal_detail.journal_id = '".$journal_id."'";	
		$result = $this->db->query($sql);		
		return $result->row_array();		
	}
	


	public function get_invoice_item($receipt_id,$po_id = ''){
		/*	

			SELECT 
			purchase_order_main.po_id,
			purchase_order_main.reference_no 'PO N0.',
			purchase_order_details.item_name 'PO ITEMS',
			(SELECT account_code FROM account_setup WHERE account_id = purchaserequest_main.account_id) 'ACCOUNT CODE',
			(SELECT account_description FROM account_setup WHERE account_id = purchaserequest_main.account_id) 'ACCOUNT DESCRIPTION',
			FORMAT(rr_main.total_amount,2) 'AMOUNT',			
			purchase_order_main.bank_id		
			FROM purchase_order_main
			INNER JOIN purchase_order_details
			ON purchase_order_main.po_id = purchase_order_details.po_id
			INNER JOIN purchaserequest_main
			ON purchaserequest_main.pr_id = purchase_order_main.pr_id
			INNER JOIN (
				SELECT * FROM receiving_main
				INNER JOIN (
					SELECT receipt_id 'r_id',po_id,(item_quantity_actual * item_cost_actual) 'total_amount'  FROM receiving_details 
					GROUP BY receipt_id
				) details
				ON ( receiving_main.receipt_id  = details.r_id)
			)rr_main
			ON (rr_main.po_id = purchase_order_main.po_id)
			WHERE rr_main.supplier_invoice = '{$invoice_no}'
			ORDER BY purchase_order_main.po_id;

		*/


		$sql = "			
			SELECT 
			(SELECT reference_no FROM purchase_order_main WHERE po_id = details.po_id)'PO N0.',
			details.item_id,
			details.account_id,
			item_name_ordered 'PO ITEMS',
			(SELECT account_code FROM account_setup WHERE account_id = details.account_id) 'ACCOUNT CODE',
			(SELECT account_description FROM account_setup WHERE account_id = details.account_id) 'ACCOUNT DESCRIPTION',
			total_amount 'AMOUNT'
			FROM receiving_main
			INNER JOIN (
				SELECT setup_group_detail.account_id,item_id,item_name_ordered,receipt_id 'r_id',po_id,(item_quantity_actual * item_cost_actual) 'total_amount'  
				FROM receiving_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = receiving_details.item_id)
				WHERE item_quantity_actual <> 0
				GROUP BY receipt_id,item_id
			) details
			ON ( receiving_main.receipt_id  = details.r_id)
			INNER JOIN (
				SELECT 
				purchase_order_main.*,
				purchaserequest_main.account_id
				FROM purchase_order_main
				INNER JOIN purchase_order_details
				ON purchase_order_main.po_id = purchase_order_details.po_id
				INNER JOIN purchaserequest_main
				ON purchaserequest_main.pr_id = purchase_order_main.pr_id	
				WHERE purchase_order_main.po_id = '{$po_id}'	
				ORDER BY purchase_order_main.po_id
			) po 
			ON (details.po_id = po.po_id)
			WHERE receiving_main.receipt_id = '{$receipt_id}'
			GROUP BY item_id
		";
	
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function cash_or_check($rr_id){
		$sql = "
		SELECT 
		a.rr_id,
		a.memo,
		b.*
		FROM journal_main a
		INNER JOIN journal_detail b
		ON (a.journal_id = b.journal_id)
		WHERE b.dr_cr = 'CREDIT'
		AND a.rr_id = '{$rr_id}'
		";
		$result = $this->db->query($sql);
		return $result->row_array();

	}



	public function save_cash_voucher(){
		$this->db->trans_begin();

		if($this->check_vouche_no($this->input->post('voucher_no'))){
			$v_no = $this->voucher_no();
			$voucher_no = array(
				'voucher_no'=>$v_no,
				'save_date'=>date("Y-m-d H:i:s"),
				);
			$this->db->insert('voucher_no',$voucher_no);

		}else{
			$v_no = $this->input->post('voucher_no');
			$voucher_no = array(
				'voucher_no'=>$this->input->post('voucher_no'),
				'save_date'=>date("Y-m-d H:i:s"),
				);
			$this->db->insert('voucher_no',$voucher_no);
		}


		$transaction_type = $this->input->post('transaction_type');
		$transaction_type = (!isset($transaction_type))? '' : $transaction_type;
			
		$insert = array(
			'pay_to'=>$this->input->post('pay_to'),
			'address'=>$this->input->post('address'),
			'voucher_no'=>$v_no,
			'project_id'=>$this->input->post('project_id'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'voucher_date'=>$this->input->post('date'),
			'voucher_id'=>$this->input->post('voucher_id'),
			'total_amount'=>$this->input->post('total_amount'),
			'preparedby'=>$this->input->post('prepared_by'),
			'checkedby'=>$this->input->post('checked_by'),
			'approvedby'=>$this->input->post('approved_by'),
			'remarks'=>$this->input->post('remarks'),
			'type'=>$this->input->post('type'),
			'short_desc'=>$this->input->post('short_desc'),
			'payment_type'=>$this->input->post('payment_type'),
			'project_type'=>$this->input->post('project_category'),
			'po_id'=>$this->input->post('po_id'),
			'rr_id'=>$this->input->post('rr_id'),
			'bank_id'=>$this->input->post('bank_id'),
			'bank'=>$this->input->post('bank'),
			'check_no'=>$this->input->post('check_no'),
			'check_date'=>$this->input->post('due_date'),
			'tin'=>$this->input->post('tin'),
			'transaction_type'=>$transaction_type,
		);
		
		$this->db->insert('cash_voucher_main',$insert);
		$id = $this->db->insert_id();
		$insert['cash_voucher_id'] = $id;

		$details = $this->input->post('details');
			
		$group_account_id = array();
		foreach($details as $row){

			$group_account_id[$row['account_id']][] =  $row;

			$insert_detail = array(
				'cash_voucher_main_id'=>$id,
				'item_no'=>$row['item_no'],
				'item_description'=>$row['name'],
				'account_code'=>$row['account_id'],
				'amount'=>$row['amount'],
				'remarks'=>$row['remarks'],
				);
			$this->db->insert('cash_voucher_detail',$insert_detail);
			
		}
		
		/*
		$accounts = array();
		if(!empty($group_account_id)){				
			$cnt = 0;
			foreach($group_account_id as $key=>$row){
				$accounts[$cnt] = array('account_id'=>$key);
				$total_amount = 0;
				foreach($row as $row1){
					$total_amount = $total_amount + $row1['amount'];
				}
				$accounts[$cnt]['amount'] = $total_amount;
				$cnt++;
			}			
		}
		*/

		$accounts  = json_decode($this->input->post('journal_details'),true);
		$this->save_voucher_journal2($insert,$accounts);
		

		$event['type']    = 'CASH VOUCHER';
		$event['transaction_no'] = $v_no;
		$event['transaction_id'] = $id;
		$event['remarks'] = '';
		$event['action']  = 'ADD';

		$this->md_event_logs->create($event);

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else 
		{
		    $this->db->trans_commit();
		    return true;
		}

	}

	public function edit_cash_voucher(){


		$this->db->trans_begin();		
		$cash_main_id = $this->input->post('cash_main_id');

		$insert = array(
			'pay_to'=>$this->input->post('pay_to'),
			'address'=>$this->input->post('address'),
			'tin'=>$this->input->post('tin'),
			'voucher_no'=>$this->input->post('voucher_no'),
			'project_id'=>$this->input->post('project_id'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'voucher_date'=>$this->input->post('date'),
			'voucher_id'=>$this->input->post('voucher_id'),
			'total_amount'=>$this->input->post('total_amount'),
			'preparedby'=>$this->input->post('prepared_by'),
			'checkedby'=>$this->input->post('checked_by'),
			'approvedby'=>$this->input->post('approved_by'),
			'remarks'=>$this->input->post('remarks'),
			'type'=>$this->input->post('type'),
			'payment_type'=>$this->input->post('payment_type'),
			'project_type'=>$this->input->post('project_category'),
			'short_desc'=>$this->input->post('short_desc'),
			'po_id'=>$this->input->post('po_id'),
			'rr_id'=>$this->input->post('rr_id'),
			'bank_id'=>$this->input->post('bank_id'),
			'bank'=>$this->input->post('bank'),
			'check_no'=>$this->input->post('check_no'),
			'check_date'=>$this->input->post('due_date'),
			);

		$this->db->where('cash_voucher_id',$cash_main_id);
		$this->db->update('cash_voucher_main',$insert);		

		$sql    = "SELECT * FROM cash_voucher_main WHERE cash_voucher_id = '{$cash_main_id}'";
		$result = $this->db->query($sql);
		$main   = $result->row_array();
				
		$details = $this->input->post('details');

		$this->db->where('cash_voucher_main_id',$cash_main_id);
		$this->db->delete('cash_voucher_detail');
		
		$group_account_id = array();
		foreach($details as $row){

			$group_account_id[$row['account_id']][] =  $row;			
			$insert_detail = array(
				'cash_voucher_main_id'=>$cash_main_id,
				'item_no'=>$row['item_no'],
				'item_description'=>$row['name'],
				'account_code'=>$row['account_id'],
				'amount'=>$row['amount'],
				);			
			$this->db->insert('cash_voucher_detail',$insert_detail);
			
		}
		
		$accounts = array();

		/*		
		if(!empty($group_account_id)){
			$cnt = 0;
			foreach($group_account_id as $key=>$row){
				$accounts[$cnt] = array('account_id'=>$key);
				$total_amount = 0;
				foreach($row as $row1){
					$total_amount = $total_amount + $row1['amount'];
				}
				$accounts[$cnt]['amount'] = $total_amount;
				$cnt++;
			}
		}
		*/

		$accounts = json_decode($this->input->post('journal_details'),true);
		$this->update_cash_voucher_journal($insert,$accounts,$main['journal_id']);

		$event['type']    = 'CASH VOUCHER';
		$event['transaction_no'] = $this->input->post('voucher_no');
		$event['transaction_id'] = $cash_main_id;
		$event['remarks'] = '';
		$event['action']  = 'EDIT';
		$this->md_event_logs->create($event);

		if ($this->db->trans_status() === FALSE)
		{

		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return true;
		}	
				
	}

	public function update_cash_voucher_journal($main,$details,$journal_id){

		$this->db->trans_begin();

		$insert = array(			
			'trans_date'=>$main['voucher_date'],
			'type'=>'JOURNAL ENTRY',
			'memo'=>'',
			'trans_type'=>'CASH VOUCHER',			
			'location'=>$main['project_id'],
			'title_id'=>$main['title_id'],
			'userid'=>$this->session->userdata('user'),
			'username'=>$this->session->userdata('user'),
			'division'=>'0',
			'transaction_type'=>'0',
			'expense_type'=>'0',
			'cost_type'=>'0',
			'fund_location'=>'0',
			'po_id'=>$main['po_id'],
			'account_type'=>'0',
			'pay_center'=>'',
			'pay_item'=>'',
			'_due_date'=>$main['check_date'],
			'_balance'=>$main['total_amount'],
			'_amount'=>$main['total_amount'],
			'name_id'=>'',
			'name_type'=>'',
			'particulars'=>'',			
			);
		$this->db->where('journal_id',$journal_id);	
		$this->db->update('journal_main1',$insert);
			
		$this->db->where('journal_id',$journal_id);
		$this->db->delete('journal_detail1');


		foreach($details as $row){

			$dr_cr  = null;
			$amount = null;
			
			if(!empty($row['debit']) && $row['debit']!="-"){
				$dr_cr = "DEBIT";
				$amount = $row['debit']; 
			}else{
				$dr_cr = "CREDIT";
				$amount = $row['credit']; 
			}			
			$insert_details = array(
				'journal_id'=>$journal_id,
				'account_id'=>$row['account_id'],
				'amount'=>str_replace(',','',$amount),
				'discount_tax'=>'',
				'dr_cr'=>$dr_cr,
				'supplier_id'=>'',
				'type'=>'',
				'bank'=>$row['bank'],
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>'',
				'check_id'=>'',
				'check_date'=>$row['check_date'],
				'balance'=>'',
				'from_location'=>'0',
				'subsidiary'=>$row['bank']." ".$row['check_no'],
				'subsidiary_type'=>'',
				'cv_id'=>'0',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'memorandum'=>$row['remarks'],
				'check_no'=>$row['check_no'],
			);			
			$this->db->insert('journal_detail1',$insert_details);
		}

		if($this->db->trans_status() === true){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
		}

	/*	$this->db->trans_begin();
		
			$insert_journal_main = array(
				'trans_date'=>$main['voucher_date'],
				'type'=>'CASH VOUCHER',
				'memo'=>'CASH VOUCHER',
				'trans_type'=>'ENTER PAYMENT',
				'status'=>'ACTIVE',
				'location'=>$main['project_id'],
				'userid'=>$this->session->userdata('user'),
				'fund_location'=>$main['project_id'],
				'_balance'=>$main['total_amount'],
				'_amount'=>$main['total_amount'],
				);

			$this->db->where('journal_id',$journal_id);
			$this->db->update('journal_main',$insert_journal_main);
			
			$this->db->where('journal_id',$journal_id);
			$this->db->delete('journal_detail');

			foreach($details as $row){

				$insert_journal_detail = array(
					'journal_id'=>$journal_id,
					'account_id'=>$row['account_id'],
					'amount'=>$row['amount'],
					'discount_tax'=>'',
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'posted'=>'TRUE',
					'type'=>'SUPPLIER',
					'bank_account_no'=>'',
					'bank_account_name'=>'',
					'cv_no'=>'',
					'check_id'=>'',
					'check_date'=>'',
					'balance'=>'0',
					'from_location'=>'0',
					'subsidiary'=>'',
					'subsidiary_type'=>'',
					'bank_account_id'=>'0',
					'bank'=>'FALSE',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);
				$this->db->insert('journal_detail',$insert_journal_detail);

			}
			
			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'10',
				'amount'=>$main['total_amount'],
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>'',
				'posted'=>'FALSE',
				'type'=>'OTHER OFFICE',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>$main['voucher_no'],
				'check_id'=>'',
				'check_date'=>'',
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>$main['pay_to'],
				'subsidiary_type'=>'OFFICE',
				'bank_account_id'=>'',
				'bank'=>'FALSE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>$main['voucher_id'],
				'dv_number'=>$main['voucher_no'],
				);

			$this->db->insert('journal_detail',$insert_journal_detail);

			if ($this->db->trans_status() === FALSE)
			{
			    $this->db->trans_rollback();
			    return false;
			}
			else
			{
			    $this->db->trans_commit();
			    return true;
			}*/

	}

	public function save_cash_voucher_journal($main,$details){

		$this->db->trans_begin();
			
			$reference_no = $this->get_journal_max_id($main);

			$insert_journal_main = array(
				'reference_no'=>$reference_no,
				'trans_date'=>$main['voucher_date'],
				'type'=>'CASH VOUCHER',
				'memo'=>'CASH VOUCHER',
				'trans_type'=>'ENTER PAYMENT',
				'status'=>'ACTIVE',
				'location'=>$main['project_id'],
				'title_id'=>$this->session->userdata('Proj_Main'),
				'userid'=>$this->session->userdata('user'),
				'division'=>'0',
				'transaction_type'=>'0',
				'expense_type'=>'0',
				'cost_type'=>'0',
				'fund_location'=>$main['project_id'],
				'po_id'=>'0',
				'account_type'=>'0',
				'pay_center'=>'0',
				'pay_item'=>$main['project_type'],
				'_balance'=>$main['total_amount'],
				'_amount'=>$main['total_amount'],
				);
			$this->db->insert('journal_main',$insert_journal_main);
			$journal_id = $this->db->insert_id();

			/*
			$sql    = "select journal_id from journal_main where reference_no = '".$reference_no."'";
			$result = $this->db->query($sql);
			$result = $result->row_array();
			$journal_id = $result['journal_id'];
			*/

			foreach($details as $row){

				$insert_journal_detail = array(
					'journal_id'=>$journal_id,
					'account_id'=>$row['account_id'],
					'amount'=>$row['amount'],
					'discount_tax'=>'',
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'posted'=>'TRUE',
					'type'=>'BUSINESS',
					'bank_account_no'=>'',
					'bank_account_name'=>'',
					'cv_no'=>'',
					'check_id'=>'',
					'check_date'=>'',
					'balance'=>'0',
					'from_location'=>'0',
					'subsidiary'=>'',
					'subsidiary_type'=>'',
					'bank_account_id'=>'0',
					'bank'=>'FALSE',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);
				$this->db->insert('journal_detail',$insert_journal_detail);

			}
			
			$insert_journal_detail = array(
				'journal_id'=>$journal_id,
				'account_id'=>'10',
				'amount'=>$main['total_amount'],
				'discount_tax'=>'',
				'dr_cr'=>'CREDIT',
				'supplier_id'=>'',
				'posted'=>'FALSE',
				'type'=>'BUSINESS',
				'bank_account_no'=>'',
				'bank_account_name'=>'',
				'cv_no'=>$main['voucher_no'],
				'check_id'=>'',
				'check_date'=>'',
				'balance'=>'0',
				'from_location'=>'0',
				'subsidiary'=>'',
				'subsidiary_type'=>'OFFICE',
				'bank_account_id'=>'',
				'bank'=>'TRUE',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>$main['voucher_id'],
				'dv_number'=>$main['voucher_no'],
				);
			$this->db->insert('journal_detail',$insert_journal_detail);
			

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();
		    return $journal_id;
		}

	}


	public function get_cash_voucher(){

		$sql = "
				SELECT 
				*,
				(SELECT
					CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					`hr_employee`
					INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
					WHERE `hr_employee`.`emp_number` = cvm.preparedby
				) 'preparedBy_name',
				(SELECT project_name FROM setup_project WHERE project_id = cvm.project_id) AS 'project_name'
				FROM cash_voucher_main cvm
				WHERE `status`='ACTIVE'
				ORDER BY voucher_date ASC,voucher_no DESC 
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();
	}


	public function get_cash_voucher_info($cash_voucher_id){

		$sql = "
			SELECT 
			*,
			(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = cvm.preparedby
			) 'preparedBy_name',
			(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = cvm.checkedby
			) 'checkedBy_name',
			(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = cvm.approvedby
			) 'approvedBy_name',
			(SELECT project_name FROM setup_project WHERE project_id = cvm.project_id) AS 'project_name',
			(SELECT project_name FROM project_category WHERE id = cvm.project_type) AS 'project_category'
			FROM cash_voucher_main cvm
			WHERE  cash_voucher_id = '{$cash_voucher_id}'			
		";
		$result = $this->db->query($sql);
		return $result->row_array();

	}
	
	public function get_cash_voucher_details($cash_voucher_main_id){
		$sql = "SELECT * FROM cash_voucher_detail WHERE cash_voucher_main_id = '{$cash_voucher_main_id}'";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}	

	public function update_cash_voucher($arg){		
		$this->db->trans_begin();

		$update = array(
			'bank_id'=>$arg['bank_id'],
			'bank'=>$arg['bank_name'],
			'check_no'=>$arg['check_no'],
			'check_date'=>$arg['check_date'],
			);

		$this->db->where('cash_voucher_id',$arg['cash_voucher_id']);
		$this->db->update('cash_voucher_main',$update);

		$sql = "SELECT * FROM cash_voucher_main WHERE cash_voucher_id = '{$arg['cash_voucher_id']}'";
		$result = $this->db->query($sql);
		$row = $result->row_array();

		$update = array(
			'bank_account_id'=>$arg['bank_'],
			'bank'=>'TRUE',
			'subsidiary'=>$arg['bank_name'],
			'subsidiary_type'=>'BANK',
			'check_no'=>$arg['check_no'],			
			);
		$this->db->where('journal_id',$row['journal_id']);
		$this->db->update('journal_detail',$update);
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return true;
		}	

	}




	public function get_cv_no($rr_id,$po_id){

		$sql = "
			SELECT
			  journal_detail.*
			FROM journal_detail
			  INNER JOIN journal_main
			    ON (journal_detail.journal_id = journal_main.journal_id)
			WHERE 
				journal_main.rr_id = '{$rr_id}'
			     AND journal_main.po_id = '{$po_id}'
			    AND journal_detail.dr_cr = 'CREDIT'
		";

		$result = $this->db->query($sql);
		return $result->row_array();

	}


	public function save_changes($arg){
		
		$update = array(
			'subsidiary'=>$arg['bank_name'],
			'subsidiary_type'=>'BANK',
			'bank'=>'TRUE',
			'check_no'=>$arg['check_no'],
			'check_date'=>$arg['check_date'],
		);

		$this->db->where('journal_id',$arg['journal_id']);
		$this->db->where('dr_cr','CREDIT');
		$this->db->update('journal_detail',$update);

		return true;

	}


	public function get_items(){
		
		$sql = "
			SELECT
			  group_detail_id  'item_no',
			  CONCAT((SELECT group_description FROM setup_group WHERE group_id=setup_group_detail.group_id),'-',setup_group_detail.description) 'description',
			  unit_measure,
			  account_id
			FROM setup_group_detail
			WHERE item_status = 'ACTIVE'
			ORDER BY description;
		";

		$result =$this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	public function get_cash_voucher_main($cash_id){
		
		$sql = "
			SELECT *,
			CONCAT(project_name,'-',project_location) 'project_fullname'
			FROM cash_voucher_main 
			INNER JOIN setup_project 
			ON (setup_project.project_id = cash_voucher_main.project_id)
			WHERE cash_voucher_id = '{$cash_id}';
		";
		$result = $this->db->query($sql);		
		return $result->row_array();

	}


	public function check_vouche_no($voucher_no){
		$sql = "SELECT voucher_no FROM voucher_no WHERE voucher_no = '{$voucher_no}'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0 ){
			return true;
		}else{
			return false;
		}
	}


	public function get_cash_voucher_summary($arg){
		$where = "";
		if($arg['location'] != 'all'){
			$where .= " AND project_id = '{$arg['location']}' ";		
		}

		if($arg['supplier'] != 'all'){
			$where .= " AND pay_to LIKE '%{$arg['supplier']}%' ";
		}

		if($arg['cash_advance'] == 'false'){
			$where .= " AND cvm.type <> 'CASH ADVANCE'";
		}
		
		$where .= " AND payment_type LIKE '%{$arg['payment_type']}%' ";	
		


		if($arg['view_type']=="monthly"){
			$date = $arg['year']."-".$arg['month'].'-01';
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));
		}else{
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}


		$sql = "				
				SELECT 
				*,
				IFNULL((SELECT tin_number FROM business_list WHERE business_name = cvm.pay_to limit 1),'') AS 'tin_number',
				(SELECT
					CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					`hr_employee`
					INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
					WHERE `hr_employee`.`emp_number` = cvm.preparedby
				) 'preparedBy_name',
				(SELECT project_name FROM setup_project WHERE project_id = cvm.project_id) AS 'project_name'
				FROM cash_voucher_main cvm 
				INNER JOIN (
					SELECT 
					*,
					GROUP_CONCAT(item_description) 'ref_doc' 
					FROM cash_voucher_detail
					GROUP BY cash_voucher_main_id
				)a
				ON (cvm.`cash_voucher_id` = a.cash_voucher_main_id)
				WHERE `status`='APPROVED' AND voucher_date BETWEEN '{$from}' AND '{$to}'
				{$where}
				ORDER BY voucher_date ASC,voucher_no ASC;

		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function is_print($arg){
		$update = array(
			'is_print'=>$arg['status']
			);		
		$this->db->where('cash_voucher_id',$arg['id']);
		$this->db->update('cash_voucher_main',$update);
		return true;
	}

	public function cancel($arg){

		$update = array(
			'status'=>$arg['status'],
			'remarks'=>$arg['remarks']
			);
		$this->db->where('cash_voucher_id',$arg['id']);
		$this->db->update('cash_voucher_main',$update);

		$event['type']    = 'CASH VOUCHER';
		$event['transaction_no'] = $arg['no'];
		$event['transaction_id'] = $arg['id'];
		$event['remarks'] = $arg['remarks'];
		$event['action']  = $arg['status'];
		$this->md_event_logs->create($event);

	}

	public function approved_voucher($arg){

		$update = array(
			'status'=>'APPROVED',			
			);
		
		$this->db->where('cash_voucher_id',$arg['id']);
		$this->db->update('cash_voucher_main',$update);

		
		$update = array(
				'status'=>'POSTED'
			);
		$this->db->where('voucher_id',$arg['id']);
		$this->db->update('journal_main1',$update);

		return true;

	}

	public function get_approved_voucher(){

		$sql = "
				SELECT 
				*,
				(SELECT
					CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					`hr_employee`
					INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
					WHERE `hr_employee`.`emp_number` = cvm.preparedby
				) 'preparedBy_name',
				(SELECT project_name FROM setup_project WHERE project_id = cvm.project_id) AS 'project_name'
				FROM cash_voucher_main cvm				
				WHERE `status`='APPROVED' AND journal_id = ''				
				ORDER BY voucher_date ASC,voucher_no DESC 
		";

		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function get_payto_voucher(){

		$sql = "SELECT pay_to FROM cash_voucher_main GROUP BY pay_to";
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}


	public function get_received_po($id = ""){
		$where ="";
		if(!empty($id)){
			$where.="  WHERE (d.rr_id IS NULL OR a.receipt_id = '{$id}')";
		}else{
			$where.=" WHERE d.rr_id IS NULL";
		}
		
		$sql = " 
			SELECT
			a.*,
			d.rr_id
			FROM
			(
			SELECT
			CONCAT('PO ',c.`reference_no`,' - DR ',a.`supplier_invoice`) AS 'po',
			a.`receipt_id`,
			c.`reference_no`,
			a.`supplier_invoice`,
			a.`received_status`,
			b.`po_id`

			FROM receiving_main a
			INNER JOIN receiving_details b
			ON (a.receipt_id = b.receipt_id)
			INNER JOIN purchase_order_main c
			ON (c.po_id = b.po_id)
			WHERE a.`status` ='ACTIVE' AND a.`received_status` IN ('COMPLETE','PARTIAL')
			GROUP BY a.receipt_id)a 
			LEFT JOIN cash_voucher_main d
			ON (d.rr_id = a.`receipt_id`)
			{$where}
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function tag_details($arg){

		$sql = " 
			SELECT
			*,
			SUM(item_quantity_actual * item_cost_actual) 'total'
			FROM receiving_main a
			INNER JOIN receiving_details b
			ON (a.receipt_id = b.receipt_id)
			INNER JOIN purchase_order_main c
			ON (c.po_id = b.po_id)
			INNER JOIN business_list d
			ON (d.business_number =  c.`supplierID`)
			WHERE a.receipt_id = '{$arg['rr_id']}' AND c.`po_id` = '{$arg['po_id']}'
			GROUP BY a.receipt_id;
		";
				
		$result = $this->db->query($sql);		
		return $result->row_array();

	}

	public function voucher_journal($voucher_id){

		$sql = "
			SELECT
			*
			FROM 
			journal_main1 a
			INNER JOIN journal_detail1 b
			ON (a.`journal_id` = b.`journal_id`)
			INNER JOIN account_setup c
			ON (c.`account_id` = b.`account_id`)
			WHERE a.`voucher_id` = '{$voucher_id}'
		";		
		$result = $this->db->query($sql);
		return $result->result_array();


	}


	public function do_tag($arg){

		$arg['details'];
		$rr_id = array();
		$po_id = array();
		foreach($arg['details'] as $row){
			$rr_id[] = $row['rr_id'];
			$po_id[] = $row['po_id'];
		}

		$arg['rr_id'] = implode(',', $rr_id);
		$arg['po_id'] = implode(',', $po_id);

		$update = array(
			'po_id'=>$arg['po_id'],
			'rr_id'=>$arg['rr_id'],
			);

		$this->db->where('cash_voucher_id',$arg['voucher_id']);
		$this->db->update('cash_voucher_main',$update);

		return true;

	}



}
