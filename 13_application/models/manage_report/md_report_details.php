<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_report_details extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_area_delivery($date,$shift,$equipment_type){

		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;

		}

		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."' AND shift ='".$shift."' AND (production = 'PRODUCTION' OR production = 'DIRECT') AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);	

		return $result->result_array();
	}

	public function get_area_delivery_subcon($date,$shift,$equipment_type){

		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;
		}

		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."' AND haul_owner <> '1' AND shift ='".$shift."' AND (production = 'PRODUCTION' OR production = 'DIRECT') AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);				
		return $result->result_array();

	}


	public function get_area_delivery_inhouse($date,$shift,$equipment_type){
		
		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;
		}
		
		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."' AND haul_owner = '1' AND shift ='".$shift."' AND (production = 'PRODUCTION' OR production = 'DIRECT') AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);				
		return $result->result_array();
		
	}


	public function get_area_delivery_shipment($date,$shift,$equipment_type){
		
		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;
		}
		
		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."'  AND shift ='".$shift."' AND production = 'BARGING' AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);			
		return $result->result_array();
		
	}


	public function get_area_delivery_shipment_subcon($date,$shift,$equipment_type){
		
		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;
		}
		
		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."'  AND shift ='".$shift."' AND haul_owner <> '1' AND (production = 'BARGING' OR production = 'DIRECT') AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);	

		return $result->result_array();
		
	}

	public function get_area_delivery_shipment_inhouse($date,$shift,$equipment_type){
		
		switch($equipment_type){
			case"DT":
				$condition = " = 'ADT'";
			break;
			case"ADT":
				$condition = " <> 'ADT'";
			break;
		}
		
		$sql = "SELECT *,COUNT(haul_equip)'trips' FROM view_area_delivery  WHERE trans_date = '".$date."'  AND shift ='".$shift."' AND haul_owner = '1' AND (production = 'BARGING' OR production = 'DIRECT') AND equipment_description ".$condition." GROUP BY haul_equip ORDER BY trips desc";
		$result = $this->db->query($sql);			
		return $result->result_array();
		
	}





	public function truck_history($truck){

		$sql    = "SELECT trans_date,production,shift,COUNT(*)'trips' FROM view_area_delivery WHERE equipment_brand = '".$truck."'  GROUP BY trans_date,shift ORDER BY trans_date ASC";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function max_min_mod($truck){

		$result = $this->truck_history($truck);

		$trip = array();
		foreach($result as $row)
		{
			$numbers = array_map(function($result) {
				  return $result['trips'];
				}, $result);
		}
				
		$data['avg'] = round((array_sum($numbers)/count($numbers)));
		$data['min'] = min($numbers);
		$data['max'] = max($numbers);		
		return $data;

	}

	public function get_driver_perDT($dt){
		$sql = "SELECT pp_fullname,trans_date,COUNT(pp_fullname)'trip',shift FROM view_area_delivery WHERE equipment_brand = '".$dt."' GROUP BY pp_fullname,shift;";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function adt_inhouse(){
		$sql = "SELECT trans_date,shift,equipment_brand,production,COUNT(haul_equip)'trips' FROM view_area_delivery WHERE equipment_description = 'ADT' AND haul_owner = '1' AND (production = 'BARGING' OR production = 'PRODUCTION') GROUP BY haul_equip,trans_date,shift,production;";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
		
	public function get_equipment_monitoring($date){
		$sql = "CALL _display_equipment_monitoring_ben('".$date."','".$date."','%','%','PRODUCTION');";
		$result = $this->db->query($sql);
		$this->db->close();
		$row = $result->row_array();
		$result = $this->db->query($row['RESULT']);
		$this->db->close();
		return $result->result_array();
	}









}