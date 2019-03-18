<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class md_signatory_setup extends CI_Model{

	function __construct(){
		parent::__construct();
		
	}

	function btnSave($data=""){
		$setup_signatory_arr	=	array(
									'form_id'		=> $data['cmbForm'] ,
									'setupType_id'	=> $data['cmbSignatory'] ,
									'employee_id'	=> $data['cmbEmployeeName'] ,
									'priority_no'	=> $data['cmbPriority'] ,
									'location'		=> $data['cmbprojectlocation'] ,
									'title_id'		=> $data['cmbMainProject_cumulative']
		);
		/*print_r($setup_signatory_arr);*/
		return ($this->db->insert('setup_signatory',$setup_signatory_arr)) ? true : false;
	}

	function cmbEmployeeName(){
		return $this->call_query("CALL display_list_employee_profile1()");
	}

	function cmbSignatory(){
		return $this->call_query("SELECT * FROM setup_signatorytype ORDER BY `type` ASC");
	}

	function cmbForm(){
		return $this->call_query("SELECT * FROM setup_formtype ORDER BY `type` ASC;");
	}

	function cmbprojectlocation($data){
		return $this->call_query("CALL display_signatory_setup_list('".$data."')");
	}

	function DataObject($sql=""){
		$result=$this->call_query($sql);
		$result = ($result->num_rows > 0) ? array_values($result->row_array()): false;
		if($result!= false)
			return $result[0];
		else
			return false;
	}


	function call_query($sql=""){
		$result=$this->db->query($sql);
		$this->db->close();
		return $result;
	}
	
}