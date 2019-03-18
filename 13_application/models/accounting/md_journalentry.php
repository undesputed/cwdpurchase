<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_journalEntry extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_payCenter(){
		$sql = "SELECT id, paycenter FROM pay_center ORDER BY id DESC;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}



	public function get_payItem(){

		$sql = "SELECT * FROM project_category";
		/*$sql = "SELECT id, CONCAT(itemno,'-',itemdescription) as 'itemdescription' FROM pay_item where title_id = '".$this->session->userdata('Proj_Code')."' order by itemdescription asc";*/
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}



	public function get_accountDescription(){

		$sql = "
			SELECT
			 account_setup.account_id,
			 account_setup.account_code,
			 account_setup.account_description,
			 account_setup.t_account,
			 account_setup.bank,
			 account_setup.ledger
			FROM account_main
			  INNER JOIN classification_setup
			    ON (account_main.name = classification_setup.short_description)
			  INNER JOIN sub_classification_setup
			    ON (classification_setup.id = sub_classification_setup.class_id)
			  INNER JOIN account_setup
			    ON (sub_classification_setup.sub_classification_id = account_setup.sub_class_code)
			  INNER JOIN setup_title_accounts
			    ON (account_setup.account_id = setup_title_accounts.account_id)
			WHERE setup_title_accounts.title_id LIKE '{$this->session->userdata('Proj_Main')}'
			AND setup_title_accounts.status = 'ACTIVE'
			GROUP BY account_setup.account_id
			ORDER BY account_main.code,classification_setup.code,sub_classification_setup.sub_classification_code,account_setup.account_code;
		";

		/*$sql = "CALL accounting_display_accounts_title('".$this->session->userdata('Proj_Main')."');";*/

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_accountType($account_id){

		$sql = "SELECT ledger FROM account_setup WHERE account_id = '".$account_id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	public function get_payTo(){

		if($this->input->post('type')=="person"){
			$sql = "CALL purchase_order_supplier_person1()";
		}else{
			$sql = "SELECT business_number 'Supplier ID',business_name 'Supplier Name',trade_name,address,contact_no,term_type,term_days FROM business_list WHERE `status` = 'ACTIVE'";
		}

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_bankAccount($location){

		$sql = "SELECT DISTINCT
				  bank_id,
				  _get_BankName(bank_id)    'bank name'
				FROM setup_asset_details
				  INNER JOIN setup_asset
				    ON (setup_asset_details.asset_id = setup_asset.asset_id)
				WHERE setup_asset.proj_profit_center = '".$location."'
				    AND `setup_asset`.`status` <> 'CANCELLED'
				    AND dtl_status <> 'CANCELLED'
			   ";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_bankAccountNo($bank_id,$location){

		$sql = "SELECT
				  dtl_id,
				  account_no,
				  account_name
				FROM setup_asset_details
				  INNER JOIN setup_asset
				    ON (setup_asset_details.asset_id = setup_asset.asset_id)
				WHERE setup_asset.status <> 'CANCELLED'
				    AND bank_id = '".$bank_id."'
				    AND proj_profit_center = '".$location."'";
		$result = $this->db->query($sql);		
		$this->db->close();

		return $result->result_array();		

	}

	public function get_cv_no(){		
		$sql = "SELECT
		  cvdtl_id,
		  cv_no
		FROM setup_cv_dtl
		  INNER JOIN setup_cv
		    ON (setup_cv_dtl.cv_id = setup_cv.cv_id)
		WHERE title_id = '2' AND cv_status='UNUSED'";
		$result = $this->db->query($sql);		
		$this->db->close();

		return $result->result_array();	

	}

	public function get_checkNo($bank_acctNo){

		$sql = "CALL display_checkNoList_by_ID('".$this->session->userdata('Proj_Main')."','".$bank_acctNo."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_journalMain($journal_id){

		$sql    = "SELECT * FROM journal_main1 WHERE journal_id = '".$journal_id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	public function get_journalDetail($journal_id){

		$sql = "SELECT
				account_setup.`account_id`,
				account_setup.`account_code` 'Account Code',
				account_setup.`account_description` 'Account',
				`journal_detail1`.`amount` 'Amount',
				`journal_detail1`.`discount_tax` 'Discount/Tax',
				`journal_detail1`.`dr_cr` 'CR/DR',
				`journal_detail1`.subsidiary_type,
				`journal_detail1`.subsidiary,
				`journal_detail1`.bank_account_name,
				`journal_detail1`.cv_no,
				`journal_detail1`.check_date
			FROM 
				`journal_detail1`
				INNER JOIN account_setup
					ON(account_setup.account_id = journal_detail1.account_id)
			WHERE
				`journal_id` = '".$journal_id."';";
		
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();

	}


	public function if_bank($location,$account_id){

		$sql    = "SELECT DISTINCT bank_id,_get_BankName(bank_id) 'bank name' FROM setup_asset_details INNER JOIN setup_asset ON(setup_asset_details.asset_id = setup_asset.asset_id) WHERE setup_asset.proj_profit_center = '".$location."' AND setup_asset.account_code='".$account_id."' AND `setup_asset`.`status` <> 'CANCELLED'  AND dtl_status <> 'CANCELLED'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}



	public function get_bank(){
		$sql = "SELECT * FROM bank_setup;";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
	
	
	public function if_supplier(){

		$sql = "SELECT business_number,business_name FROM business_list WHERE type = 'BUSINESS' AND status = 'ACTIVE' ORDER BY business_name asc";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function if_employee(){

		$sql = "SELECT GET_EmployeeName_by_ID(emp_number) AS 'emp_name',emp_number FROM hr_employee";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function if_ppe(){

		$sql = "SELECT description,group_detail_id FROM setup_group_detail WHERE group_id = 2 OR group_id <> 4";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function if_materials(){
		
		$sql = "SELECT description,group_detail_id FROM setup_group_detail WHERE (group_id <> 2 OR group_id <> 4)";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
		
	}

	public function if_affiliates(){

		$sql = "SELECT project_id,project_name FROM setup_project";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	
	public function if_other_office(){

		$sql = "SELECT business_number,business_name FROM business_list WHERE `type`='OTHER OFFICE';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_subsidiary($type=""){

		$sql = "SELECT business_number,business_name FROM business_list WHERE `type`='{$type}' AND status = 'ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function save_journal(){

		$this->db->trans_begin();		
		$insert = array(
			'reference_no'=>$this->input->post('ref_no'),
			'trans_date'=>$this->input->post('date'),
			'type'=>$this->input->post('transaction_type'),
			'memo'=>$this->input->post('memo'),
			'trans_type'=>$this->input->post('trans_type'),
			'status'=>$this->input->post('status'),
			'location'=>$this->input->post('location'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'userid'=>$this->session->userdata('user'),
			'username'=>$this->session->userdata('username'),
			'division'=>'0',
			'transaction_type'=>'0',
			'expense_type'=>'0',
			'cost_type'=>'0',
			'fund_location'=>'0',
			'po_id'=>'0',
			'account_type'=>'0',
			'pay_center'=>$this->input->post('paycenter'),
			'pay_item'=>$this->input->post('payitem'),
			'_due_date'=>$this->input->post('dueday'),
			'_balance'=>$this->input->post('balance'),
			'_amount'=>$this->input->post('balance'),
			'name_id'=>$this->input->post('typesup_id'),
			'name_type'=>$this->input->post('typesup'),
			'particulars'=>$this->input->post('particulars'),
			'voucher_id'=>$this->input->post('voucher_id'),
			);
				
		$this->db->insert('journal_main1',$insert);

		$details = $this->input->post('details');
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

			$insert_details = array(
				'journal_id'=>$journal_id,
				'account_id'=>$row['account_id'],
				'amount'=>str_replace(',','',$amount),
				'discount_tax'=>'',
				'dr_cr'=>$dr_cr,
				'supplier_id'=>$this->input->post('cussup'),
				'type'=>$row['supplierType'],
				'bank_account_no'=>$row['bankAccountNo'],
				'bank_account_name'=>$row['bankAccount'],
				'cv_no'=>$row['cvNo'],
				'check_id'=>$row['checkNo'],
				'check_date'=>$row['checkDate'],
				'balance'=>'',
				'from_location'=>'0',
				'subsidiary'=>$row['subAccount'],
				'subsidiary_type'=>$row['accountType'],				
				'cv_id'=>'0',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'memorandum'=>$row['memo'],
				'check_no'=>$row['account_no'],
			);
		
		$this->db->insert('journal_detail1',$insert_details);

		}

		$dv_id = $this->input->post('dv_id');

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

	function update_journal(){

		$this->db->trans_begin();

		$insert = array(
			'reference_no'=>$this->input->post('ref_no'),
			'trans_date'=>$this->input->post('date'),
			'type'=>$this->input->post('transaction_type'),
			'memo'=>$this->input->post('memo'),
			'trans_type'=>$this->input->post('trans_type'),
			'status'=>$this->input->post('status'),
			'location'=>$this->input->post('location'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			'userid'=>$this->session->userdata('user'),
			'username'=>$this->session->userdata('username'),
			'division'=>'0',
			'transaction_type'=>'0',
			'expense_type'=>'0',
			'cost_type'=>'0',
			'fund_location'=>'0',
			'po_id'=>'0',
			'account_type'=>'0',
			'pay_center'=>$this->input->post('paycenter'),
			'pay_item'=>$this->input->post('payitem'),
			'_due_date'=>$this->input->post('dueday'),
			'_balance'=>$this->input->post('balance'),
			'_amount'=>$this->input->post('balance'),
			'name_id'=>$this->input->post('typesup_id'),
			'name_type'=>$this->input->post('typesup'),
			'particulars'=>$this->input->post('particulars'),
			);
		
		$this->db->where('journal_id',$this->input->post('journal_id'));
		$this->db->update('journal_main1',$insert);

		$details = $this->input->post('details');
		$journal_id = $this->input->post('journal_id');
		
		$this->db->query("DELETE FROM journal_detail1 WHERE journal_id = '".$this->input->post('journal_id')."'");

		foreach($details as $row){

			$dr_cr = null;
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
				'supplier_id'=>$this->input->post('cussup'),
				'type'=>$row['supplierType'],
				'bank_account_no'=>$row['bankAccountNo'],
				'bank_account_name'=>$row['bankAccount'],
				'cv_no'=>$row['cvNo'],
				'check_id'=>$row['checkNo'],
				'check_date'=>$row['checkDate'],
				'balance'=>'',
				'from_location'=>'0',
				'subsidiary'=>$row['subAccount'],
				'subsidiary_type'=>$row['accountType'],				
				'cv_id'=>'0',
				'dtl_id'=>'0',
				'chkdtl_id'=>'0',
				'memorandum'=>$row['memo']
			);
		
		$this->db->insert('journal_detail1',$insert_details);		

		}
		
		if($this->db->trans_status() === true){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
		}



	}


	

	function get_cumulative(){

		$sql = "CALL display_journal_main1_range('".$this->input->post('transaction_type')."','".$this->input->post('location')."','".$this->input->post('from')."','".$this->input->post('to')."','True');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function update_status($journal_id){		
		$sql = "UPDATE journal_main SET `status` = 'CANCELLED' WHERE journal_id = '".$journal_id."'";
		$this->db->query($sql);
		return true;
	}

	function get_journal_detail($journal_id = ""){

		$sql = "
		SELECT
			account_setup.`account_id`,
			account_setup.`account_code` 'Account Code',
			account_setup.`account_description` 'Account',
			`journal_detail1`.`amount` 'Amount',
			`journal_detail1`.`discount_tax` 'Discount/Tax',
			`journal_detail1`.`subsidiary` 'subsidiary',
			`journal_detail1`.`dr_cr` 'CR/DR'
		FROM 
			`journal_detail1`
			INNER JOIN account_setup
				ON(account_setup.account_id = journal_detail1.account_id)
		WHERE
			`journal_id` = '{$journal_id}'
		";
		
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}
		

	function get_cumulative_range($from,$to){

		$sql = "
		SELECT
		`journal_id`,
		 status,
		`reference_no` 'Reference No',
		`trans_date` 'Trans Date',
		`type` 'Type', 
		`memo` 'Memo',
		`status` 'Status',
		`location` 'Project ID',
		(SELECT project_name FROM setup_project WHERE project_id = journal_main1.location) 'Project Name',
		journal_main1.trans_type 'Trans Type',
		journal_main1.pay_center,
		(SELECT project_name FROM project_category WHERE id = journal_main1.pay_item) 'pay_item',
		journal_main1.particulars,
		journal_main1.username,
		`_amount`
		FROM
			journal_main1
		WHERE
			`trans_date` BETWEEN '{$from}' AND '{$to}';
		";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	function delete_journal($journal_id){
		$update = array(
			'status'=>'CANCELLED'
			);
		$this->db->where('journal_id',$journal_id);
		$this->db->update('journal_main1',$update);
		return $this->db->affected_rows();
	}

	function posting($journal_id){
		$update = array(
			'status'=>'POSTED'
			);
		$this->db->where('journal_id',$journal_id);
		$this->db->update('journal_main1',$update);
		return $this->db->affected_rows();
	}


	public function get_customer(){

		$sql = "SELECT * FROM business_list where type = 'CUSTOMER' AND status = 'ACTIVE'";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_account_setup(){

		$sql = "
			SELECT
			* 
			FROM `account_setup` a
			LEFT JOIN `account_setup` b
			ON (a.group = b.`account_id`)		
		";
		$result = $this->db->query($sql);

	}



	
}