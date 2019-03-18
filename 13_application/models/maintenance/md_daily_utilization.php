<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_daily_utilization extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}


	function equip_util_no(){
		$data;

		$date =  $this->input->post('date');
		$date =  explode('-',$date);
		
		$month = $date[1];
		$year  = $date[0];
		$month_year = $year.'-'.$month;
		$sql = "SELECT SUBSTRING(IFNULL(MAX(equip_util_no),'EUR-".$month."-00-".$year."'),8,2) AS equip_no FROM fvc_equipment_utilization_setup WHERE trans_date LIKE '".$month_year."%'";

		$result = $this->db->query($sql);
		$this->db->close();
		$data =  $result->row_array();
		$num = str_pad($data['equip_no']+1,2,"0",STR_PAD_LEFT);
		
		return "EUR-".$month."-".$num."-".$year."";

				
	}


	function scope(){
		$sql = "SELECT
				  project_scope_type.id,
				  project_scope_type.type
				FROM project_scope_type
				  INNER JOIN project_scope_setup
				    ON project_scope_type.id = project_scope_setup.scope_id
				WHERE project_scope_setup.location = '".$this->input->post('location')."';";

		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}

	function unit_no(){
		$sql = "SELECT
				  project_scope_equipment.scope_equip_id,
				  project_scope_equipment.scope_id,
				  project_scope_equipment.db_equipment_id,
				  db_equipmentlist.*
				FROM project_scope_equipment
				  INNER JOIN db_equipmentlist
				    ON db_equipmentlist.equipment_id = project_scope_equipment.db_equipment_id
				WHERE project_scope_equipment.scope_id = '".$this->input->post('scope_id')."'
				    AND db_equipmentlist.equipment_status = 'AVAILABLE'";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}




}