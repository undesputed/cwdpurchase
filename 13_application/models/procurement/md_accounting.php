<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_accounting extends CI_Model {

	var $DEFAULT;

	public function __construct(){
		parent :: __construct();	

		$this->DEFAULT = $this->config->item('accounting');
			
	}

	public function journal_entry($main,$details,$rr_id = '',$date=''){
		

		/*	
		$db_accounting = $this->load->database('accounting', TRUE); 
		$db_accounting->trans_begin();
		*/

		$total_cost = "";
		if($rr_id !=''){
			$rr_main = $this->receiving_details($rr_id);
			$total_cost = $rr_main['total_rr_cost'];
			$name_id = $rr_main['supplier_id'];
		}else{
			$total_cost = $main['total_cost'];
			$name_id = 0;			
		}
		
		if($main['paymentTerm'] == 'In Days'){
			$type = "PAYABLE";
			$trans_type = "ENTER PAYABLE";
		}else{
			$type = "PAYABLE";
			$trans_type = "ENTER PAYABLE";
		}

		/** GET REQUEST ORIGINATED**/
		$from_project = $this->get_from_pr($main['pr_id']);
		if($date ==''){
			$date = date('Y-m-d');
		}
		
		$insert = array(
			'reference_no'=>$this->extra->get_journal_code($date,$trans_type,$this->session->userdata('Proj_Code'),'JEV'),
			'memo'=>'PO '.$main['reference_no'],
			'trans_date'=>$date,
			'type'=>$type,
			'trans_type'=>$trans_type,
			'status'=>'POSTED',
			'location'=>$from_project['from_projectCode'],
			'title_id'=>$this->session->userdata('Proj_Main'),
			'userid'=>$this->session->userdata('emp_id'),
			'division'=>$this->session->userdata('division'),			
			'po_id'=>$main['po_id_main'],
			'name_id'=>$name_id,
			'name_type'=>'BUSINESS',
			'_Amount'=>$total_cost,
			'_bAlance'=>$total_cost,
			'rr_id'=>$rr_id,
			'pay_item'=>$main['pay_item'],
		);

		$this->db->insert('journal_main1',$insert);
		$id = $this->db->insert_id();

		$dr_cr = array('DEBIT','CREDIT');
		$cnt = 0;
		
		$insert_details = array();
		$item_cost = 0;
		$vat_in    = 0;


		if($main['vat']=='VAT'){
			foreach($details as $row){
				$vatable = round(($row['total_cost'] / 1.12),2);
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$row['account_id'],
					'amount'=>$vatable,
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);			
			}
			
			$vatable = round(($total_cost / 1.12),2);
			$vat     = $total_cost - $vatable;

			$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$this->DEFAULT['vat_input_tax'],
					'amount'=>$vat,
					'dr_cr'=>'DEBIT',
					'supplier_id'=>'',
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);

		}else
		{
			foreach($details as $row){
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$row['account_id'],
					'amount'=>$row['total_cost'],
					'dr_cr'=>'DEBIT',
					'supplier_id'=>$main['supplierID'],
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
				);		
			}	
		}


		
				
		if($main['paymentTerm'] == 'In Days'){
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$this->DEFAULT['accounts_payable'],
					'amount'=>$total_cost,
					'dr_cr'=>'CREDIT',
					'supplier_id'=>$main['supplierID'],
					'type'=>'SUPPLIER',
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
					'subsidiary'=>$main['Supplier'],
					'subsidiary_type'=>'SUPPLIER',
				);

		}else{
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$this->DEFAULT['accounts_payable'],
					'amount'=>$total_cost,
					'type'=>'SUPPLIER',
					'dr_cr'=>'CREDIT',
					'supplier_id'=>$main['supplierID'],
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
					'subsidiary'=>$main['Supplier'],
					'subsidiary_type'=>'SUPPLIER',

				);
		}
		
		foreach($insert_details as $row){
			$this->db->insert('journal_detail1',$row);
		}

		/*$this->db->insert_batch('journal_detail',$insert_details);*/

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


	public function receiving_details($rr_id){

		$sql = "
		SELECT 
			* 
			FROM receiving_main
			INNER JOIN (
				SELECT receipt_id 'rr_id',SUM((item_quantity_actual * item_cost_actual)) 'total_rr_cost' FROM receiving_details GROUP BY receipt_id
				) rr_details
			ON (rr_details.rr_id = receiving_main.receipt_id)
			WHERE receiving_main.receipt_id = '".$rr_id."'
		";
		$result = $this->db->query($sql);
		return $result->row_array();

	}


	public function get_from_pr($pr_id){
		$sql = "
			SELECT 
			* 
			FROM purchaserequest_main
			INNER JOIN (
				SELECT 
				* 
				FROM 
				transaction_history
				WHERE TYPE = 'Purchase Request'
				) transaction_history
			ON (transaction_history.reference_id = purchaserequest_main.pr_id)
			WHERE pr_id = '{$pr_id}'
		";
		$result = $this->db->query($sql);		
		return $result->row_array();

	}


	public function update_journal_entry($arg,$details,$main){
		

		$sql    = "SELECT * FROM journal_main1 where rr_id = '{$arg['rr_id']}'";
		$result = $this->db->query($sql);
		$result = $result->row_array();

		if(empty($result['journal_id'])){
			return false;
		}

		$id = $result['journal_id'];

		$this->db->where('journal_id',$id);
		$this->db->delete('journal_detail');
		

		$total_cost = 0;
		foreach($details as $row){

			$insert_details[] = array(
				'journal_id'=>$id,
				'account_id'=>$row['account_id'],
				'amount'=>$row['total_cost'],
				'dr_cr'=>'DEBIT',
				'supplier_id'=>$main['supplierID'],
				'bank'=>'False',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'cv_id'=>'0',
			);		
			$total_cost += $row['total_cost'];
		}
				
		if($main['paymentTerm'] == 'In Days'){
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$this->DEFAULT['accounts_payable'],
					'amount'=>$total_cost,
					'dr_cr'=>'CREDIT',
					'supplier_id'=>$main['supplierID'],
					'type'=>'SUPPLIER',
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
					'subsidiary'=>$main['Supplier'],
					'subsidiary_type'=>'SUPPLIER',
				);
				
		}else{
				$insert_details[] = array(
					'journal_id'=>$id,
					'account_id'=>$this->DEFAULT['accounts_payable'],
					'amount'=>$total_cost,
					'type'=>'SUPPLIER',
					'dr_cr'=>'CREDIT',
					'supplier_id'=>$main['supplierID'],
					'bank'=>'False',
					'dtl_id'=>'0',
					'chkdtl_id'=>'0',
					'cv_id'=>'0',
					'subsidiary'=>$main['Supplier'],
					'subsidiary_type'=>'SUPPLIER',

				);
		}
		
		foreach($insert_details as $row){
			$this->db->insert('journal_detail1',$row);
		}


		$update = array(
			'_amount'=>$total_cost,
			'_balance'=>$total_cost,
		);

		$this->db->where('journal_id',$id);
		$this->db->update('journal_main1',$update);				
		return true;

	}

}



