<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_payroll_entry extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	

	public function save($arg){

		$arg['payroll_amount'] = str_replace(',', '', $arg['payroll_amount']);

		if(!empty($arg['id'])){

			$insert = array(
				'project_id'=>$arg['project_id'],
				'project_name'=>$arg['project_name'],
				'payroll_date'=>$arg['payroll_date'],
				'payroll_amount'=>$arg['payroll_amount'],
				'project_type_id'=>$arg['project_type_id'],
				'project_type'=>$arg['project_type'],
			);
			$this->db->where('id',$arg['id']);
			$this->db->update('payroll_entry',$insert);	

		}else{

			$insert = array(
				'project_id'=>$arg['project_id'],
				'project_name'=>$arg['project_name'],
				'payroll_date'=>$arg['payroll_date'],
				'payroll_amount'=>$arg['payroll_amount'],
				'project_type_id'=>$arg['project_type_id'],
				'project_type'=>$arg['project_type'],
			);
			$this->db->insert('payroll_entry',$insert);	
		}

		

		return true;
	}	

	public function delete($arg){

		$this->db->where('id',$arg['id']);
		$this->db->delete('payroll_entry');	
		return true;
	}


	public function get_data(){

		$sql = "
			SELECT * FROM payroll_entry;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


}