<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_schedule extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_unit(){
		$sql    = "SELECT equipment_id,equipment_description,equipment_model,equipment_chassisno,equipment_smr,equipment_brand FROM db_equipmentlist";
		$result = $this->db->query($sql);	
		return $result->result_array();

	}



	public function get_display(){

		if($this->input->post('type')=="month"){
			$sql = "CALL display_equipment_pm_history_monthly('".$this->input->post('unit')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."')";
		}else{
			$sql = "CALL display_equipment_pm_history('".$this->input->post('unit')."')";
		}


		$result = $this->db->query($sql);
		$this->db->close();
		
		$options = array(
			'result'=>$result,
			'hide'=>array('service_id'),
			);
		return $this->extra->generate_table($options);




	}


}