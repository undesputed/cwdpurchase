<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_unplanned extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}
	
	function get_unit(){
		$sql    = "SELECT equipment_id,equipment_description,equipment_model,equipment_chassisno,equipment_smr,equipment_brand FROM db_equipmentlist";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	function get_model($model_id){
		$sql = "SELECT CODE FROM pm_model_setup WHERE pm_model_id = '".$model_id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}


	function get_display(){

		$type = ($this->input->post('pending')==true)?  "%" :  "COMPLETE" ;

		if($this->input->post('type')=='month'){
			$sql = "CALL display_equipment_history_monthly('".$this->input->post('unit')."','".$this->input->post('date_from')."','".$type."','".$this->input->post('date_to')."');";
		}else{
			$sql = "CALL display_equipment_history('".$this->input->post('unit')."','".$type."')";
		}

		$result = $this->db->query($sql);
			
		$this->db->close();
		
		$options = array(
			'result'=>$result
			);
		return $this->extra->generate_table($options);


	}

}