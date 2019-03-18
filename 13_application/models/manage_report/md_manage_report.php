<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_manage_report extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function save(){

		$insert = array(
			'type'=>$this->input->post('type'),
			'amount'=>$this->input->post('amount'),
			'date'=>$this->input->post('date'),
			);
		$this->db->insert('tbl_mining_operation',$insert);

	}

	public function get_all(){
		
		$sql = "SELECT * FROM tbl_mining_operation";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_plot(){

		$sql = "SELECT type,amount,date from tbl_mining_operation";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_production_dates($from,$to){

		$sql = "SELECT DISTINCT trans_date FROM area_delivery WHERE trans_date BETWEEN '".$from."' AND '".$to."'";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function get_production($from,$to){

		//$sql = "SELECT date ,adt_unit_day 'ADT-Day Shift',adt_unit_night 'ADT-Night Shift',dt_unit_day 'DT-Day Shift',dt_unit_night 'DT-Night Shift'  FROM tbl_production;";		
		$sql = "CALL display_production_ben('".$from."','".$to."');";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();
		
	}

	public function get_subcon_inhouse($from,$to,$type){

		//$sql = "CALL display_production2('".$date."','".$type."');";
		$sql = "CALL display_production2_ben('".$from."','".$to."','".$type."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function shipment_subcon_inhouse($from,$to,$type){

		/*$sql = "CALL display_barging2('".$date."','".$type."');";*/
		$sql = "CALL display_barging2_ben('".$from."','".$to."','".$type."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_barging($from,$to){
		/*$sql = "CALL display_barging('$date');";*/
		$sql = "CALL display_barging_ben('".$from."','".$to."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_draft_survey(){
		$sql = "SELECT vessel_name,draft_date,truck_load,truck_factor,running_balance,STATUS FROM area_draft_survey WHERE STATUS = 'incomplete' ORDER BY vessel_id,draft_date";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_stockpile($prev_month,$from,$to){

		$sql = "CALL display_net_stockpile('".$prev_month."','".$from."','".$to."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function save_etc($type,$description,$value){
		
		$insert = array(
			'type'=>$type,
			'description'=>$description,
			'value'=>$value,
		);		
		$this->db->insert('tbl_etc',$insert);

	}

	public function delete_etc($type){
		$this->db->where('type',$type);
		$this->db->delete('tbl_etc');
	}


	public function get_type($type){
		
		$sql = "SELECT * FROM tbl_etc WHERE `type` = '$type'";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}


	public function save_total(){
		foreach($this->input->post() as $key=>$row){
			$insert = array(
			'description'=>$key,
			'value'=>$row,
			'date'=>$this->input->post('date'),
			);
			$this->db->insert('tbl_total',$insert);
		}

	
	}

	public function get_total(){

		$sql ="SELECT * FROM 
				(SELECT MAX(id) AS id FROM tbl_total GROUP BY description) AS t1				
				INNER JOIN tbl_total
				 ON (t1.id = tbl_total.id)";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}



	public function get_report(){
		$sql = "SELECT * FROM tbl_report ORDER BY id DESC LIMIT 3";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function insert_production($value){

		foreach($value as $row){
			if($this->check_dup_date($row[0])){
				$sql = "DELETE FROM tbl_production where date = '$row[0]'";
				$this->db->query($sql);
			}

			$insert = array(
						'date'=>$row[0],
						'adt_unit_day'=>$row[1],
						'adt_trip_day'=>$row[2],
						'adt_wmt_day'=>$row[3],
						'dt_unit_day'=>$row[4],
						'dt_trip_day'=>$row[5],
						'dt_wmt_day'=>$row[6],
						'total_wmt_day'=>$row[7],
						'adt_unit_night'=>$row[8],
						'adt_trip_night'=>$row[9],
						'adt_wmt_night'=>$row[10],
						'dt_unit_night'=>$row[11],
						'dt_trip_night'=>$row[12],
						'dt_wmt_night'=>$row[13],
						'total_wmt_night'=>$row[14],
						'to_date_wmtd'=>$row[15],
			);
			$this->db->insert('tbl_production',$insert);

		}

	}


	public function insert_shipment($value){

		foreach($value as $row){
			if($this->check_dup_date($row[0])){
				$sql = "DELETE FROM tbl_shipment where date = '$row[0]'";
				$this->db->query($sql);
			}

			$insert = array(
						'date'=>$row[0],
						'dt_unit_day'=>$row[1],
						'dt_trip_day'=>$row[2],
						'dt_wmt_day'=>$row[3],
						'dt_unit_night'=>$row[4],
						'dt_trip_night'=>$row[5],
						'dt_wmt_night'=>$row[6],
						'to_date_wmt'=>$row[7],
			);
			$this->db->insert('tbl_shipment',$insert);

		}

	}


	public function check_dup_date($date){

		$sql = "select id from tbl_production where date = '".$date."'";
		$result = $this->db->query($sql);
		if($result->num_rows()> 0){
			return true;
		}else{
			return false;
		}

	}


	public function avg_trips($date= '',$operation = '',$equipment_type= '',$owner = ''){
		
		switch($equipment_type){
			case"DT":
				$condition = " <> 'ADT'";
			break;
			case"ADT":
				$condition = " = 'ADT'";
			break;
		}

		$sql = "SELECT COUNT(equipment_brand) 'no_trucks' ,SUM(trips) 'total_trips' FROM (SELECT equipment_brand,COUNT(*)'trips' FROM view_area_delivery WHERE trans_date = '".$date."' AND equipment_description ".$condition." AND production = '".$operation."' ".$owner." GROUP BY equipment_brand) AS a;";
		$result = $this->db->query($sql);		
		return $result->result_array();
		
	}


	public function get_date($operation = '',$equipment_type= '',$owner = ''){

		$sql = "SELECT max(trans_date) 'date' FROM view_area_delivery WHERE trans_date = '".$date."' AND equipment_description ".$condition." AND production = '".$operation."' ".$owner." GROUP BY equipment_brand";
		
		$result = $this->db->query($sql);

		return $result->row_array();

	}

	
	public function get_utilization($arg){

		$sql = "CALL display_utilization_ben('".$arg['date']."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
		
	}


	public function progress_target(){
		
		$target =  3500000;
		$sql = "SELECT 
				SUM(truck_factor) 'load' 
				FROM area_delivery
				WHERE (production = 'PRODUCTION' || production = 'DIRECT')
	    ";

		$result = $this->db->query($sql);
		$this->db->close();

		$row = $result->row_array();


		$percentage = (($row['load']/$target) * 100);

		$output = array(
			'load'=>$row['load'],
			'percentage' =>$percentage,
			);
		return $output;
	}


	public function get_barge_out(){

		$sql = "
		SELECT
		  FORMAT(SUM(RUNNING_TOTAL),0) 'barge_out',  
		  (SELECT
			COUNT(*)
			FROM
			(SELECT
			  COUNT(vessel_id)
			FROM area_draft_survey
			WHERE STATUS = 'COMPLETE'
			GROUP BY vessel_id,voyage_no
			)AS a) AS 'total_vessel_complete'
		FROM (SELECT
		        RUNNING_TOTAL
		      FROM (SELECT *
		            FROM area_draft_survey
		            ORDER BY DRAFT_ID DESC) A
		      GROUP BY VESSEL_ID,VOYAGE_NO) B;
	    ";
	    $result = $this->db->query($sql);
	    $this->db->close();

	    $row = $result->row_array();
	    return $row;

	}








		

}