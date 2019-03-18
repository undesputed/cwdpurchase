<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Md_material_setup extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function display_group_detail_setup($txtSItemDescr=""){

		return $this->call_query("CALL display_group_detail_setup('%".$txtSItemDescr."%')");
		
	}
	
	function insert_group_detail_setup($form=""){
		
		$sql ="CALL insert_group_detail_setup_1('".$form['cmbGroupName']."','".$form['txtDescription']."','".$form['Quantity']."','".$form['txtUnitCost']."','".$form['txtUnitMeasure']."','".$form['cmbClassification']."','".$form['txtIncomeAccountCode']."','".$form['GroupPanel1']."','".$form['_title_id']."','".$form['cmbIncomeAccountDescription']."','".$form['txtItemCode']."','".$form['base1']."','".$form['base2']."','".$form['base3']."','".$form['base4']."')";
		return ($this->call_query($sql)) ? true : false;

	}
	
	function cmbGroupName($id=""){
		return $this->call_query("SELECT group_id, group_description FROM setup_group ORDER BY group_description asc");
	}
	
	function cmbIncomeAccountDescription($id=""){
		return $this->call_query("SELECT account_code, account_description,account_id FROM account_setup");
	}
	
	function cmbClassification($id=""){
		return $this->call_query("SELECT id, full_description FROM classification_setup WHERE `status` = 'ACTIVE'");
	}
	
	function call_query($sql=""){
		$result=$this->db->query($sql);
		$this->db->close();
		return $result;
	}
	
}
