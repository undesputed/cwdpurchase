<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_productivity extends CI_Model {

	public function __construct(){
		parent :: __construct();	

		$this->load->library('table');


	}


	function get_display(){
		$tmpl = array ( 'table_open'  => '<table class="table">' );
		$this->table->set_template($tmpl); 


		$sql = "SELECT
			  fvc_assign_setup.assign_description
			FROM fvc_equipment_utilization_setup
			  INNER JOIN fvc_equipment_utilization_dtls AS dtls
			    ON (fvc_equipment_utilization_setup.equip_util_id = dtls.equip_util_id)
			  INNER JOIN fvc_operation_setup
			    ON (dtls.operation_id = fvc_operation_setup.operation_id)
			  INNER JOIN fvc_area_setup
			    ON (dtls.area_id = fvc_area_setup.area_id)
			  INNER JOIN fvc_assign_setup
			    ON (dtls.assign_id = fvc_assign_setup.assign_id)
			  INNER JOIN fvc_assign_activity
			    ON (fvc_assign_setup.assign_id = fvc_assign_activity.assign_id)
			WHERE fvc_equipment_utilization_setup.location = '".$this->input->post('location')."'
			    AND fvc_equipment_utilization_setup.trans_date BETWEEN '".$this->input->post('date')."'
			    AND '".$this->input->post('date')."'
			GROUP BY fvc_operation_setup.operation_id,fvc_area_setup.area_id,fvc_assign_setup.assign_id
			ORDER BY fvc_operation_setup.operation_id,fvc_area_setup.area_id,fvc_assign_setup.assign_id";

		$result = $this->db->query($sql);

		$header_result = $result->row_array();

		$sql = "CALL project_formulaSQL_report('".$this->input->post('location')."','".$this->input->post('date')."','".$this->input->post('date')."');";

		$result = $this->db->query($sql);		
		$this->db->close();

		$output = $result->row_array();
		$result = $this->db->query($output['RESULT']);
		$data = $result->result_array(); /* result sa stored proc */
			

		$total = array();
		$hide  = array('type_desc'=>'');
		$order = array();
		


		$row_title = array();
		
		$grand_total = array();

	

		$header = array();
		if(isset($data[0])){
			foreach($data[0] as $k=>$row){
				$header[] = $k;
			}
		}
		

		$top_header = array(
			array('data'=>'','class'=>'heading'),
			array('data'=>'SBA','class'=>'heading','colspan'=>'2','class'=>'align-center'),
			array('data'=>'DB','class'=>'heading','colspan'=>'2','class'=>'align-center'),
			array('data'=>(isset($header_result['assign_description']))? $header_result['assign_description']: '-' ,'class'=>'align-center','colspan'=>'2'),
			array('data'=>'PRODUCTIVITY','class'=>'align-center','colspan'=>'2'),
		);

		$this->table->add_row($top_header);
				
		$header = array(
			array('data'=>'Unit No','class'=>'heading'),
			array('data'=>'HRS','class'=>'heading'),
			array('data'=>'Remarks','class'=>'heading'),
			array('data'=>'HRS','class'=>'heading'),
			array('data'=>'Remarks','class'=>'heading'),
			array('data'=>'No. of Load','class'=>'heading'),
			array('data'=>'MT','class'=>'heading'),
			array('data'=>'NM','class'=>'heading'),
			array('data'=>'MM','class'=>'heading'),
		);
		$this->table->add_row($header);

		if(empty($data[0])){
			$this->table->add_row(array(
				'data'   =>'Empty Result',
				'colspan'=>count($header)
			));
			echo $this->table->generate();
			exit(0);
		}

		$order = $this->_row($data);

		foreach($order as $k=>$row){
			$row_title = $this->_row_title($k,$row);
			$this->table->add_row($row_title);
			$total = array();
			foreach($row as $inner){
				$row_data = array();
				$cnt = 0;
				foreach($inner as $data){
					$row_data[] = array('data'=>$data);
					switch ($cnt) {
						case 0:
							$total[$cnt] = "<span style='margin-left:2em;'></span>TOTAL";
							break;
						
						default:
							$total[$cnt] = (isset($total[$cnt]))? $total[$cnt] + $data : $data;
							break;
					}
					
					$cnt++;
				}
				$this->table->add_row($row_data);

			}
			$this->table->add_row($total);		
			$grand_total[] = $total;	
		}

		$sum_grandtotal = array();
		foreach ($grand_total as $key => $value){
				foreach ($value as $k => $value){
					switch ($k) {
						case 0:
							$sum_grandtotal[$k] = array('data'=>"<span style='margin-left:4em;'></span>GRAND TOTAL",'class'=>'grand_total');
							break;
						
						default:
							$sum_grandtotal[$k] = (isset($sum_grandtotal[$k]))?$sum_grandtotal[$k] + $value : $value;
							break;
					}
				}
		}

		$this->table->add_row($sum_grandtotal);		
		echo $this->table->generate();

	}



	function _row($data){		
		$type_desc = array();		
		$row_data  = array();
		foreach($data as $row){			
				foreach($row as $k=>$inner){
					if($k=='type_desc')
						continue;
					$row_data[] = $inner;
				}
				$type_desc[$row['type_desc']][] = $row_data;			
		}
		return $type_desc;
	}

	function _row_title($k,$row){
		$row_title = array();
		for ($i=0; $i <count($row[0]) ; $i++){ 
				switch ($i) {
					case 0:
						$row_title[] = $k;
						break;
											
					default:
						$row_title[] = "";
						break;
				}
		}
		return $row_title;
	}

	function sum(){


	}



}