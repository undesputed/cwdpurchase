<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_complaint extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	function get_unit(){
		$sql = "SELECT equipment_id,equipment_description,equipment_model,equipment_chassisno,equipment_smr,equipment_brand FROM db_equipmentlist";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	function checklist($unit){
		$sql    = "SELECT (SELECT id FROM fvc_checklist_setup WHERE id = checklist_id) AS 'id', (SELECT `type` FROM fvc_checklist_setup WHERE id = checklist_id) AS 'type' FROM fvc_utilization_checklist WHERE db_equipment_id = '".$unit."' GROUP BY `type`";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

		
	function get_display(){

		if($this->input->post('type')=='month'){
			if($this->input->post('all')=='true'){
				$sql = "CALL display_complaint_history_monthly('".$this->input->post('unit')."','".$this->input->post('all')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."')";
			}else{
				$sql = "CALL display_complaint_history_by_type_monthly('".$this->input->post('unit')."','".$this->input->post('all')."','".$this->input->post('date_from')."','".$this->input->post('checklist')."','".$this->input->post('date_to')."')";
			}

		}else{

			if($this->input->post('all')=='true'){
				$sql = "CALL display_complaint_history('".$this->input->post('unit')."','".$this->input->post('all')."')";
			}else{
				$sql = "CALL display_complaint_history_by_type('".$this->input->post('unit')."','".$this->input->post('all')."','".$this->input->post('checklist')."')";
			}
		}

		$result = $this->db->query($sql);		
		$this->db->close();
		
		$options = array(
			'result'=>$result
			);
		return $this->extra->generate_table($options);


	}

}