<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_maintenance_update extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}

	var $header;
	
	public function display(){

		$tmpl = array ( 'table_open'  => '<table class="table table-condensed table-striped">');
		$this->table->set_template($tmpl); 


		$sql = "SELECT
				  db_equipment_id AS 'equipment_id',
				  (SELECT
				     equipment_brand
				   FROM db_equipmentlist
				   WHERE equipment_id = db_equipment_id) AS 'equipment_brand'
				FROM fvc_equipment_utilization_setup
				GROUP BY db_equipment_id";
		$result  = $this->db->query($sql);
		$this->db->close();
		$output = $result->result_array();		

		$date_from = $this->input->post('date_from');
		$date_to   = $this->input->post('date_to');


		$this->header = array(
				'Description',
				'Target',
				'Actual',
			);

		$this->table->set_heading($this->header);
		foreach ($output as $key => $value) {

			$header = $this->_header($value['equipment_brand'],count($this->header));		
			$this->table->add_row($header);

			$sql = "CALL display_mechanical_availability('".$date_from."','".$date_to."','2014-02-09','".$value['equipment_id']."','".$this->input->post('location')."');";
			$result = $this->db->query($sql);
			$this->db->close();
			$data = $result->result_array();
			foreach ($data as $key1 => $value1){
				$row_content = array();
				$cnt = 0;
				foreach ($value1 as $key2 => $value2) {
					if($cnt==0){

					}else{
						$margin = ($cnt==1)? "<span style='margin-left:3em;'></span>": "" ;
						$row_content[] = array('data'=>$margin.$value2);
					}
					$cnt++;
				}

				$this->table->add_row($row_content);
			}				
		}


		$json['equipment'] = $this->table->generate();
		$json['summary'] = $this->_summary();

		echo json_encode($json);


	}



	function _header($value,$count){
		$header = array();
		for ($i=0; $i < $count; $i++) { 
			switch ($i) {
				case 0:
					$header[] = $value;
					break;
				
				default:
					$header[] = "";
					break;
			}
		}
		return $header;
	}


	function _summary(){

		$yesterday = date('Y-m-d',strtotime($this->input->post('date_to')." -1 day"));
		$this->table->clear();

		$output = array();


		$result = $this->db->query("CALL display_total_mechanical_availability('".$this->input->post('date_from')."','".$this->input->post('date_to')."','".$yesterday."','".$this->input->post('location')."')");
		$output['I. OVERALL MECHNANICAL AVAILABILITY'] =  $result->result_array();
		$this->db->close();
		$result = $this->db->query("CALL display_mechanical_reliability('".$this->input->post('date_from')."','".$this->input->post('date_to')."','".$yesterday."','".$this->input->post('location')."')");
		$output['II. OVERALL EQUIPMENNT RELIABILITY']  =  $result->result_array();
		$this->db->close();
		$result = $this->db->query("CALL display_equipment_utilization('".$this->input->post('date_from')."','".$this->input->post('date_to')."','".$yesterday."','".$this->input->post('location')."')");
		$output['III. OVERALL EQUIPMENT UTILIZATION']  =  $result->result_array();
		$this->db->close();

		$this->table->set_heading($this->header);


		foreach($output as $key=>$row){

			$header = $this->_header($key,3);

			$this->table->add_row($header);

			foreach ($row as $key => $value){
				$row_content = array();
				$cnt = 0;
				foreach ($value as $key1 => $value1){
					$margin = ($cnt==0)? "<span style='margin-left:3em;'></span>" : "";
					$row_content[] = $margin.$value1;
				$cnt++;
				}
				$this->table->add_row($row_content);
			}

		}		
			

		return $this->table->generate();

	}



}