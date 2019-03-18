<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_ledger extends CI_Model {

		function __construct(){
		parent::__construct();
		
	}	
	

	function get_projects($id = null){
		$sql = "SELECT * FROM project_title";
		$result=$this->db->query($sql);
		if ($result)
			return $result->result_array();
		else 
			return false;
	}
	
	function get_profit_center($id = null){
		$sql = "SELECT title_id,project_id,project,CONCAT(project,' - ',project_name,' - ',project_location) AS 'project_full' FROM setup_project";
		if(!empty($id))
			$sql .=" WHERE title_id = '".$id."'";
		$result=$this->db->query($sql);
		if ($result)
			return $result->result_array();
		else 
			return false;
	}

	
	function ledger_display($cmbAccountName='%%',$arg=""){
		$sql="CALL Ledger_display_all_accounts_1('".$cmbAccountName."','{$arg['from']}','{$arg['to']}','{$arg['location']}','{$arg['identifier']}')";
		$sql2="CALL Ledger_display_account_details_1('".$cmbAccountName."','{$arg['from']}','{$arg['to']}','{$arg['location']}','{$arg['identifier']}')";
		/*$array=array(
						$arg['from'],
						$arg['to'],
						$arg['location'],
						$arg['identifier']);*/
		$result=$this->db->query($sql);		
		$this->db->close();
		$result2=$this->db->query($sql2);		
		$this->db->close();

		//
		if($arg['per_ledger']=='true'){
			$sql3  = $arg['str'];
			$array3=array(
							$arg['from'],
							$arg['to'],
							$arg['location'],
							$arg['identifier'],
							($arg['supplier']==0)? "%" : $arg['supplier'],
							$arg['param5']);
										
			if($sql3 == ""){
				$result3=$result2->result_array();
			}else{
				$result3=$this->db->query($sql3,$array3);			
				$this->db->close();
				$result3=($result3->num_rows > 0)? $result3->result_array():false;
			}
			
		}else{
			$sql3="CALL Ledger_display_account_details_1('{$arg['selectedAccount']}','{$arg['from']}','{$arg['to']}','{$arg['location']}','{$arg['identifier']}')";
			$array3=array(
						$arg['selectedAccount'],
						$arg['from'],
						$arg['to'],
						$arg['location'],
						$arg['identifier']);

			$result3=$this->db->query($sql3);			
			$this->db->close();
			$result3=($result3->num_rows > 0)? $result3->result_array():false;

		}



	
			
		//
		
		if($result->num_rows > 0 || $result2->num_rows > 0){
			$return=array('0'=>$result->result_array(),
							'1'=>$result2->result_array(),
							'2'=>$result3);
			return $return;
			}
		else
			return false;
	}
	
	function account_setup(){
		//$sql="SELECT DISTINCT account_setup.account_id, account_setup.account_code, account_setup.account_description FROM account_setup";
		//$sql .=" INNER JOIN journal_detail ON(account_setup.account_id = journal_detail.account_id) ORDER BY account_setup.account_description asc";\
		// NEW SQL from update Aug.20
		$sql="SELECT DISTINCT
			  account_setup.account_id,
			  account_setup.account_code,
			  account_setup.account_description,
			  bank,
			  sub_class_code
			FROM account_setup
			  INNER JOIN journal_detail
			    ON (account_setup.account_id = journal_detail.account_id)
			ORDER BY account_setup.account_description asc";

		$result=$this->db->query($sql);		
		if($result->num_rows > 0)
			return $result->result_array();
		else
			return false;

	}
	
	function display_supplier_byAccountID(){
		if($this->input->post('supplier')=='Person')
			$sql="CALL display_supplier_person_byAccountID(?,?,?)";
		else
			$sql="CALL display_supplier_business_byAccountID(?,?,?)";
		
		
		$array=array($this->input->post('txtAccountNo_Tag'),
					$this->input->post('location'),
					$this->input->post('identifier'));
		$result=$this->db->query($sql,$array);
		$this->db->close();
		return ($result->num_rows > 0)? $result->result_array() : false;
	}
	
	function display_bank_ledger(){
		$sql = "CALL display_bank_ledger(?,?)";
		$array=array($this->input->post('param1'),
					$this->input->post('identifier'));
		$result=$this->db->query($sql,$array);
		$this->db->close();
		return ($result->num_rows > 0)? $result->result_array() : false;
	}
	
	function display_list_employee_profile2(){
		$sql = "CALL display_list_employee_profile2(?,?)";
		$array=array($this->input->post('param1'),
					$this->input->post('identifier'));
		$result=$this->db->query($sql,$array);
		$this->db->close();
		return ($result->num_rows > 0)? $result->result_array() : false;
	}
	
	function fund_transfer(){
		$sql  = "SELECT DISTINCT ";
		$sql .= "bankID AS 'supplier ID', ";
		$sql .= "_get_BankName(bankID) 'Supplier' ";
		$sql .= "FROM ";
		$sql .= "account_entry ";
		$sql .= "INNER JOIN account_entry_dtl ";
		$sql .= "ON(account_entry.accountEntry_ID = account_entry_dtl.accountEntry_ID) ";
		$sql .= "WHERE ";
		$sql .= "account_entry.memo = 'DIRECT TRANSFER ENTRY' AND ";
		$sql .= "account_entry_dtl.account_id=? AND ";
		$sql .= ($this->input->post('identifier') == 1)? "account_entry.projectID = ?" : "account_entry.titleID = ?";
		$array=array($this->input->post('param1'),
					$this->input->post('identifier'));
		$result=$this->db->query($sql,$array);
		$this->db->close();
		return ($result->num_rows > 0)? $result->result_array() : false;
	}
	

	public function display_ledger($project_id){

		$sql = "Call display_Ledger_bank('{$project_id}');";
		$result  = $this->db->query($sql);
		$this->db->close();
		
		return $result->result_array();

	}

	public function business(){
		$sql = "SELECT DISTINCT
				  business_number    'Supplier ID',
				  business_name      'Supplier Name',
				  trade_name,
				  address,
				  contact_no,
				  term_type,
				  term_days
				FROM business_list
				  INNER JOIN journal_main
				    ON business_list.business_number = journal_main.name_id
				WHERE business_list.status = 'ACTIVE'
				    AND journal_main.name_type = 'BUSINESS';
				";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function person(){

		$sql = "CALL purchase_order_supplier_person_journal";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();


	}

}