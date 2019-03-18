<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_operation extends CI_Model {

	public function __construct(){
		parent :: __construct();		
		$this->load->library('table');

	}



	function get_display(){

		$tmpl = array ( 'table_open'  => '<table class="table">' );
		$this->table->set_template($tmpl); 


		$sql = "SELECT
				  fvc_operation_setup.operation_type,
				  fvc_area_setup.area_description,
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
				WHERE fvc_equipment_utilization_setup.location = '".$this->input->post('location')."'
				    AND fvc_equipment_utilization_setup.trans_date BETWEEN '".$this->input->post('date')."'
				    AND '".$this->input->post('date')."'
				GROUP BY fvc_operation_setup.operation_id,fvc_area_setup.area_id,fvc_assign_setup.assign_id
				ORDER BY fvc_operation_setup.operation_id,fvc_area_setup.area_id,fvc_assign_setup.assign_id";

		$result = $this->db->query($sql);		
		$this->db->close();

		$temp_column = $result->result_array();
		

		$sql = "CALL project_operationSQL_report('".$this->input->post('location')."','".$this->input->post('date')."','".$this->input->post('date')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		$output = $result->row_array();

		$result = $this->db->query($output['RESULT']);

		$data = $result->result_array();
		
		$temp_desc   = array();
		$hide_column = array('type_desc'=>'');
		$heading     = array(
				array('data'=>'Unit No.','class'=>'heading'),
				array('data'=>'ProgHrs','class'=>'heading'),
				array('data'=>'SMR Beg','class'=>'heading'),
				array('data'=>'SMR End','class'=>'heading'),			
			);

		$total = array();
		$data_holder = array();
		$operation   = array();
	
		$header_top  = array();

		foreach($data as $key=>$row){
			if(empty($temp_desc)){
				$temp_desc[] = $row['type_desc'];				
				$dt = array('data'=>$row['type_desc']);				
			}else{
				if(in_array($row['type_desc'],$temp_desc)){
														
				}else{
					$temp_desc[] = $row['type_desc'];					
				}
			}

			$cnt = 0;
			$row_content = array();
			$count_key = 0;
			foreach($row as $k=>$sub_row){

				$style = "";
				$style_status = false;
				

				if(is_numeric($k)){
					
					 if(!array_key_exists($temp_column[$k]['operation_type'], $operation)){
					 	$operation[$key][$temp_column[$k]['operation_type']] = array();					 	
					 }
					 	
					 if(!in_array($temp_column[$k]['area_description'], $operation[$key][$temp_column[$k]['operation_type']])){
					 	$operation[$key][$temp_column[$k]['operation_type']][] = $temp_column[$k]['area_description'];
					 }
					
					$heading[]   = array('data'=>$temp_column[$k]['assign_description'],'class'=>'grand_total heading');
					$count_key++;


				}else{
					$header_top[$key][$k] = array('data'=>'');
				}
				if(array_key_exists($k,$hide_column)){
					$style = "display:none";
					$style_status = true;
				}

				$total_row = ($cnt==1)? '<span style="margin-left:3em;"></span>TOTAL': $sub_row;
				$sub_row = ($cnt==1)? '<span style="margin-left:1em;"></span>'.$sub_row : $sub_row;
				$total[$cnt] = (isset($total[$cnt]))? $total[$cnt]['data'] + $total_row : array('data'=>$total_row,'style'=>$style);
				
				$row_content[] = array('data'=>$sub_row,'style'=>$style);
				$cnt++;

			} /*END INNER LOOP*/

			$data_holder[] = $row_content;
			
		} /*END OUTER LOOP*/


		$heading[] = array('data'=>'Total','class'=>'heading');
		$heading[] = array('data'=>'Remarks','class'=>'heading');
		$row_header = array();
		$row_header1 = array();
		$prev = "";
		$prev1= "";


		foreach($operation as $row){
			 foreach($row as $k=>$inner){
			 	$row_header = array();;
			 	foreach($inner as $value){			 		
			 			for ($i=0; $i < count($heading); $i++){
						    $str = strpos($heading[$i]['class'],'grand_total');			 
							if($str===0){
								if($prev!=$value){
								 	$row_header[] = array('data'=>$value,'colspan'=>$count_key,'class'=>'align-center');
								 	$row_header1[] = array('data'=>$k,'colspan'=>$count_key,'class'=>'align-center');
								}
								$prev = $value;
							}else{
								 $row_header[] = "";
								 $row_header1[] = "";
							}
						}
						$this->table->add_row($row_header1);
					$this->table->add_row($row_header);	

			 	}

			}

			//$this->table->add_row($row_header);
		}
		
					
	
		
		$prev = "";
		$this->table->add_row($heading);		
		for ($i=0; $i < count($data_holder); $i++) {				
				if(in_array($data_holder[$i][0]['data'],$temp_desc)){
					if($prev != $data_holder[$i][0]['data']){						
						$this->table->add_row(array('data'=>$data_holder[$i][0]['data'],'colspan'=>count($data_holder[$i])));							
						$prev = $data_holder[$i][0]['data'];				
					}					
				}
				$this->table->add_row($data_holder[$i]);
		}	
		
		$grand_total = array();

		if(count($data) > 0){				
				for ($i=0; $i < count($heading) ; $i++) { 
					$grand_total[$i] = ($i==0)? array('data'=>'GRAND TOTAL','class'=>'grand_total') : "";
				}
				$this->table->add_row($total);
		}else{
			    $grand_total = array('data'=>'Empty Result','colspan'=>count($heading));
		}
				
		$this->table->add_row($grand_total);
		echo $this->table->generate();

	}

}