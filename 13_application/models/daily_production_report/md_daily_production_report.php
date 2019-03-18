<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_daily_production_report extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_material(){
		$sql = "SELECT * FROM project_material_setup WHERE remarks = 'material';";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	public function get_material_static(){
		$sql = "
			select mat_id,mat_code from project_material_setup
			where 
				  mat_code = 'L1A' OR
				  mat_code = 'L1B' OR
				  mat_code = 'L2A' OR
				  mat_code = 'L2B' OR
				  mat_code = 'L3A' OR
				  mat_code = 'L3B' OR
				  mat_code = 'L4' OR
				  mat_code = 'L5' OR
				  mat_code = 'L6' OR
				  mat_code = 'S1' OR
				  mat_code = 'S2' OR
				  mat_code = 'S3' OR
				  mat_code = 'S4' OR
				  mat_code = 'CO1' OR
				  mat_code = 'CO2' OR
				  mat_code = 'CO3' OR
				  mat_code = 'CO4' OR
				  mat_code = 'LFL1' OR
				  mat_code = 'LFL2' OR
				  mat_code = 'LNS' OR
				  mat_code = 'WS';
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function display_mine_operation($date){

		//$sql = "CALL display_mine_operation('2014-06-14','".$arg['material']."','1')";		
		//$sql = "CALL display_mine_operation_ben('$date')";
		$sql = "CALL display_mine_operation_ben2('$date')";
		$result = $this->db->query($sql);
		$this->db->close();
		$row = $result->row_array();
		$result = $this->db->query($row['result']);

		return $result->result_array();
	}

	public function display_transferring($date){

		$sql = "CALL display_transferring_ben('$date')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function display_barging_operation($date){

		$sql = "CALL display_barging_operation_ben('$date')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function display_equipment_inventory($date){
		$sql  = "CALL display_maintenance_report3('$date')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function display_shipment($date){
		$sql = "SELECT vessel_id,vessel_name,voyage_no FROM area_draft_survey WHERE  `status` = 'INCOMPLETE' GROUP BY vessel_id";
		$result = $this->db->query($sql);
		$this->db->close();

		$data = array();
		$cnt = 0;
		foreach($result->result_array() as $row)
		{
			$sql = "CALL display_vessel('".$row['vessel_id']."','".$row['voyage_no']."','".$date."');";
			$result_data = $this->db->query($sql);
			$this->db->close();
			
			$data[$cnt] = $result_data->result_array();
			$cnt ++;
		}

		return $data;		
	}

	public function hourly_weather($arg){
		$sql = "SELECT * FROM pm_hourly_weather WHERE weather_date = '".$arg['date']."';";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
	
	












}