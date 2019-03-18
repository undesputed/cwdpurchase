<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_tire extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}




	function get_unit(){
		$sql = "SELECT equipment_id,equipment_description,equipment_model,equipment_chassisno,equipment_fulltank,equipment_brand,equipment_platepropertyno FROM db_equipmentlist WHERE equipment_status = 'AVAILABLE' AND equipment_typecode = '1'";		
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}


	function get_display(){		
		if($this->input->post('month')){
			$sql = "CALL display_tire_monitoring('".$this->input->post('unit')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."','".$this->input->post('location')."')";				
		}else{
			$sql = "CALL display_tire_monitoring1('".$this->input->post('unit')."','".$this->input->post('location')."')";
		}
		
		$result = $this->db->query($sql);	
		$this->db->close();

		$options = array(
			'result'=>$result
			);
	return $this->extra->generate_table($options);
	}




}