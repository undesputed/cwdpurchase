<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_account_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}
		
	public function get_account(){

		$sql = "CALL display_account_setup_with_contra_accounts();";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	public function get_classification($short_desc){
		$sql = "SELECT id, full_description FROM classification_setup WHERE short_description = '".$short_desc."' AND `status` = 'ACTIVE' order by `code` asc";
		$result = $this->db->query($sql);
		$this->db->close();		
		return $result->result_array();
	}

	public function get_sub_classification($class_id){
		$sql = "SELECT * FROM sub_classification_setup WHERE class_id = '".$class_id."' AND sub_classification_code <> '999' ORDER BY sub_classification_code ASC";
		$result = $this->db->query($sql);
		$this->db->close();		
		return $result->result_array();
	}

	
	public function get_ledger(){
		$sql = "Select ID, TYPE from account_ledger";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
	
	public function get_account_type(){
		$sql = "Select ID, TYPE from account_type";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
	

	public function get_account_setup(){
		$sql = "Select account_id,account_code,account_description from account_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	public function save_account_setup(){
		$this->db->trans_begin();

		if($this->check_exist($this->input->post('txtAccountCode'),$this->input->post('cmb_subClassification'))){
			return "exist";
		}

		$bank = ($this->input->post('chkBank')=='false') ? 'FALSE': 'TRUE';


		$sql = "INSERT INTO account_setup
		(
		  `account_code`,
		  `account_description`,
		  `sub_class_code`,
		  `classification_code`,
		  `account_type`,
		  `t_account`,
		  `contra_account`,
		   `bank`,
		   `ledger`
		)VALUES
		(
				'".$this->input->post('txtAccountCode')."',
				'".$this->input->post('txtDescription')."',
				'".$this->input->post('cmb_subClassification')."',
				'".$this->input->post('cmbClassification')."',
				'".$this->input->post('cbxaccounttype')."',
				'".$this->input->post('txtShortDesc')."',
				'".$this->input->post('contra_account')."',
				'".$bank."',
				'".$this->input->post('cbxledger')."'				
		)";
		
		$this->db->query($sql);

		$sql = "INSERT INTO setup_title_accounts (account_id,title_id) VALUES((SELECT MAX(account_id) FROM account_setup WHERE account_description = '".$this->input->post('txtDescription')."'),'".$this->session->userdata('Proj_Main'). "')";
		$this->db->query($sql);

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		}		
		return true;

	}

	public function check_exist($account_code,$sub_class){
		$sql = "SELECT * FROM account_setup WHERE account_code = '".$account_code."' and sub_class_code = '".$sub_class."'";
		$result = $this->db->query($sql);
		$this->db->close();
		if($result->num_rows() > 0){
			return true;
		}
			return false;

	}

	public function get_account_setup_single($id){

		$sql = "SELECT
	    `account_setup`.`account_id`
	    , `account_setup`.`account_code` 'Account Code'
	    , `account_setup`.`account_description` 'Account Description'
	    , `sub_classification_setup`.`sub_classification_name` 'Sub Classification'
	    , `account_setup`.`classification_code` 'Classification Code'
	     , `classification_setup`.`full_description` 'Classification'
	    , `account_setup`.`sub_class_code`
	     , `account_setup`.`account_type` 'Account Type'
	    , `account_setup`.`t_account` 'Account Classification'
	    , `account_setup`.`contra_account` 
	    , `account_setup_1`.`account_code` 'Contra Account Code'
	    , `account_setup_1`.`account_description` 'Contra Account Description'
	    , classification_setup.short_description 'Short Desc'
	    , IF(account_setup.bank = 'True','Yes','No') 'Bank'
	    , `account_setup`.`ledger` 'Ledger'
		FROM
	    `account_setup`
	    INNER JOIN `classification_setup` 
	        ON (`account_setup`.`classification_code` = `classification_setup`.`id`)
	    INNER JOIN `sub_classification_setup` 
	        ON (`account_setup`.`sub_class_code` = `sub_classification_setup`.`sub_classification_id`)
	    LEFT JOIN `account_setup` AS `account_setup_1`
	        ON (`account_setup`.`contra_account` = `account_setup_1`.`account_id`)
	        WHERE account_setup.status = 'ACTIVE'
	        AND account_setup.account_id = '".$id."'
	        ORDER BY classification_setup.`id`,sub_classification_setup.sub_classification_code,account_setup.account_code ASC;        
        ";
        $result = $this->db->query($sql);
        $this->db->close();        
        return $result->row_array();

	}

	public function update_account_setup(){

		if($this->check_exist_2($this->input->post('txtAccountCode'),$this->input->post('cmb_subClassification'),$this->input->post('txtAccountID'))){
			return "exist";
		}
		
		$bank = ($this->input->post('chkBank')=='false') ? 'FALSE': 'TRUE';

		$sql  = "CALL update_account_setup('".$this->input->post('txtAccountID')."','".$this->input->post('txtAccountCode')."','".$this->input->post('txtDescription')."','".$this->input->post('cmb_subClassification')."','".$this->input->post('cmbClassification')."','".$this->input->post('cbxaccounttype')."','".$this->input->post('txtShortDesc')."','".$this->input->post('contra_account')."','".$bank."','".$this->input->post('cbxledger')."')";
		$result = $this->db->query($sql);

		return true;

	}



	public function check_exist_2($account_code,$sub_class,$account_id){
		
		$sql = "SELECT * FROM account_setup WHERE account_code = '".$account_code."' and sub_class_code = '".$sub_class."'  AND account_id <> '".$account_id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		if($result->num_rows() > 0){
			return true;
		}
			return false;

	}



	/******************************EXTRA******************************/


	/***CLASSIFICATION****/

	public function get_classification_setup(){

		$sql = "CALL display_classification_setup()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}


	public function save_classification_setup(){

		if($this->input->post('id')!=""){
			$sql = "CALL update_classification_setup('".$this->input->post('id')."','".$this->input->post('txtClassCode')."','".$this->input->post('txtShortDesc')."','".$this->input->post('txtClassName')."','".$this->input->post('group')."','".$this->input->post('cashflow')."')";
		}else{
			$sql = "CALL insert_classification_setup('".$this->input->post('txtClassCode')."','".$this->input->post('txtShortDesc')."','".$this->input->post('txtClassName')."','".$this->input->post('group')."','".$this->input->post('cashflow')."')";	
		}

		$result = $this->db->query($sql);		
		return true;
	}
	

	/***SUB CLASSIFICATION***/

	public function get_sub_classification_setup(){
		$sql = "call display_sub_classification_name";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function save_sub_classification_setup(){

		if($this->input->post('id')!=""){

			$sql = "UPDATE sub_classification_setup SET sub_classification_name = '".$this->input->post('txt_SubClassName')."',class_id = '".$this->input->post('cmbClassification')."',sub_classification_code = '".$this->input->post('txt_SubClassName1')."' WHERE sub_classification_id = '".$this->input->post('id')."'";
			$this->db->query($sql);

		}else{
			$sql = "INSERT INTO sub_classification_setup(sub_classification_name,class_id,sub_classification_code) VALUES('".$this->input->post('txt_SubClassName')."','".$this->input->post('cmbClassification')."','".$this->input->post('txt_SubClassName1')."')";
			$result = $this->db->query($sql);

			if($this->input->post('checked')==""){				
				$sql = "UPDATE sub_classification_setup SET sub_subclass_id = '".$this->db->insert_id()."' WHERE sub_classification_id = '".$this->db->insert_id()."'";			
				$this->db->query($sql);
			}
		}
		
		
		return true;

	}



	/****LEDGER*****/

	public function get_ledger_setup(){

		$sql = "Select * from account_ledger";
		$result = $this->db->query($sql);

		return $result;		
	}

	public function save_ledger(){

		if($this->input->post('id')!=""){
			$insert = array(
				'type'=>$this->input->post('ledger_name'),
			);

			$this->db->where('id',$this->input->post('id'));
			$this->db->update('account_ledger',$insert);

		}else{
			$insert = array(
				'type'=>$this->input->post('ledger_name'),
			);
			$this->db->insert('account_ledger',$insert);
		}
		
	}


	/***ACCOUNT***/

	public function get_account_type_setup(){

		$sql = "Select * from account_type";
		$result = $this->db->query($sql);

		return $result;		
	}


	public function save_account_type_setup(){

		if($this->input->post('id')!=""){
			$insert = array(
				'TYPE'=>$this->input->post('ledger_name'),
			);

			$this->db->where('id',$this->input->post('id'));
			$this->db->update('account_type',$insert);

		}else{
			$insert = array(
				'TYPE'=>$this->input->post('ledger_name'),
			);
			$this->db->insert('account_type',$insert);
		}
		
	}


	


		


		
	

}