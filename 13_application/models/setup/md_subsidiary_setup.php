<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_subsidiary_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function account_ledger(){

		$sql = "
			SELECT * FROM account_ledger;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function tbl_business_list($arg){

		$insert = array(
			'business_name'=>$arg['business_name'],
			'trade_name'=>$arg['trade_name'],
			'type'=>$arg['type'],
			);

		$this->db->insert('business_list',$insert);

		return true;

	}



	public function tbl_bank_setup($arg){

		$insert = array(
			'bank_name'=>$arg['bank_name'],
			'bank_address'=>$arg['bank_address'],			
			);
		$this->db->insert('bank_setup',$insert);

		return true;

	}

	public function tbl_employees($arg){

		$insert = array(
				'Name_Empl'=>$arg['LastName_Empl'].', '.$arg['FirstName_Empl'].' '.$arg['MiddleName_Empl'],
				'FirstName_Empl'=>$arg['FirstName_Empl'],
				'MiddleName_Empl'=>$arg['MiddleName_Empl'],
				'LastName_Empl'=>$arg['LastName_Empl'],
				'Position_Empl'=>$arg['Position_Empl'],
				'gender'=>$arg['gender'],
			);
		$this->db->insert('employees',$insert);		
		return true;		
	}



	public function save(){

		if($this->input->post('id')==""){

			$data = array(
				'business_name'=>$this->input->post('title'),
				'trade_name'=>$this->input->post('title'),
				'address'=>$this->input->post('address'),
				'tin_number'=>$this->input->post('ref'),
				'type'=>$this->input->post('subsidiary_type'),
			);
			$this->db->insert('business_list',$data); 
			return true;
			
		}else{
			
			$data = array(
				'business_name'=>$this->input->post('title'),
				'trade_name'=>$this->input->post('title'),
				'address'=>$this->input->post('address'),
				'tin_number'=>$this->input->post('ref'),
			);
			$this->db->where('business_number',$this->input->post('id'));
			$this->db->update('business_list',$data); 
			return true;

		}

	}


	public function get_data($type){

		$sql = "SELECT * FROM business_list WHERE TYPE LIKE '{$type}%';";
		$result = $this->db->query($sql);
		return $result->result_array();
				
	}


	public function get_customer(){
		$sql = "SELECT * FROM business_list WHERE `type` = 'CUSTOMER'";
		$result = $this->db->query($sql);
		return $result->result_array();
	}





}