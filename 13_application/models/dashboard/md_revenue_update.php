<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_revenue_update extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	var $total = array();
	var $total_title = array();

	function display(){
		$tmpl = array ( 'table_open'  => '<table class="table table-condensed table-striped">');
		$this->table->set_template($tmpl); 

		$display = array();


		$sql = "CALL display_revenue_update('".$this->input->post('location')."','".$this->input->post('date_from')."','".$this->input->post('date_to')."');";
		$result = $this->db->query($sql);
		
		$this->db->close();

		$output = $result->result_array();


		$start_operation = date('m/d/Y',strtotime($this->input->post('project_effectivity')));
		$for_the = (string)date("F,Y",strtotime($this->input->post('date_to')));

		$margin = "<span style='margin-left:3em'></span>";
		$column1 =  array(
						array($margin.'For yesterday <'.date('m/d/Y',strtotime($this->input->post('date_to').'- 1 day')).'>:','','','',''),
						array($margin.'For the < '.$for_the.' >:','','','',''),
						array($margin.'For the <'.date('Y',strtotime($this->input->post('date_to'))).'>:','','','',''),
						array($margin.'From Start of operation <'.$start_operation.':','','','',''),
				);
			

		
		$display['equipment'] = $this->equipment($output,$column1);
		$display['summary']   = $this->summary($output,$column1);

		echo json_encode($display);

	}


	function equipment($output,$column){

		$data_column = array('Yest_Actual','Month_Actual','Year_Actual','Date_Actual');

		$output = $this->populate_array->_row($output,'body_no');
		$this->populate_array->heading = array(
				'DESCRIPTION',
				'TARGET',
				'ACTUAL',
				'VARIANCE(MT)',
				'ATTAINMENT(%)',
		);

		
		$this->populate_array->hide = array('area_description');
		$header = $this->populate_array->_header();
		$this->table->set_heading($header);
		$total = array();
		
		foreach($output as $key=>$row){
			$header = $this->populate_array->sub_header(array('data'=>$key),5);
			$this->table->add_row($header);
			
			foreach ($row as $key1 => $value1){
				$row_data = array();
					

						foreach($data_column as $data_key=>$data_row){

							$column[$data_key][1] = $value1[$data_row];
							$column[$data_key][2] = $value1[$data_row];
							$column[$data_key][3] = $column[$data_key][1] - $column[$data_key][2];
							$attainment = ($column[$data_key][1] == 0 && $column[$data_key][2]==0)?  0 :  (($column[$data_key][1] / $column[$data_key][2]) * 100);
							$column[$data_key][4] = $attainment."%";
							$sub_row_column = $column[$data_key];
							$this->table->add_row($sub_row_column);

							$total[$data_row][1] = (isset($total[$data_row][1]))? $total[$data_row][1] + $this->comma($column[$data_key][1]) : $this->comma($column[$data_key][1]);
							$total[$data_row][2] = (isset($total[$data_row][2]))? $total[$data_row][2] + $this->comma($column[$data_key][2]) : $this->comma($column[$data_key][2]);
							$total[$data_row][3] = (isset($total[$data_row][3]))? $total[$data_row][3] + $this->comma($column[$data_key][3]) : $this->comma($column[$data_key][3]);

						}
									

			

				array_push($this->total_title, $value1['code']);
				
			}
		}
			
		$this->total = $total;
		return  $this->table->generate();


	}


	function summary($output,$column){

		$data_column = array('Yest_Actual','Month_Actual','Year_Actual','Date_Actual');

		$roman = array('I','II','III','IV','V','VI','VII','VIII','IX','X');
		
		$push['Total Production ('.implode($this->total_title,' and ').')'] = array(						
						array(
							'body_no' => 'BH03',
		                    'Yest_Actual' =>  (isset($this->total['Yest_Actual'][1]))?  $this->number($this->total['Yest_Actual'][1]) : '0',
		                    'Month_Actual'=>  (isset($this->total['Month_Actual'][1]))? $this->number($this->total['Month_Actual'][1]) : '0',
		                    'Year_Actual' =>  (isset($this->total['Year_Actual'][1]))?  $this->number($this->total['Year_Actual'][1]) : '0',
		                    'Date_Actual' =>  (isset($this->total['Date_Actual'][1]))?  $this->number($this->total['Date_Actual'][1]) : '0',
		                    'code' => '-',
		                    'type_code' => '-',
							),
					);
		
		$output = $this->populate_array->_row($output,'area_description',$push);

		$this->populate_array->heading = array(
				'DESCRIPTION',
				'TARGET',
				'ACTUAL',
				'VARIANCE(MT)',
				'ATTAINMENT(%)',
		);

		$header = $this->populate_array->_header();
		$this->table->set_heading($header);
		$cnt = 0;
		foreach($output as $key=>$row){
			$header = $this->populate_array->sub_header(array('data'=>$roman[$cnt].". ".$key),5);
			$this->table->add_row($header);

			foreach ($row as $key1 => $value1){
				$row_data = array();

							
						foreach($data_column as $data_key=>$data_row){

							$column[$data_key][1] = $value1[$data_row];
							$column[$data_key][2] = $value1[$data_row];
							$column[$data_key][3] = $column[$data_key][1] - $column[$data_key][2];
							$attainment = ($column[$data_key][1] == 0 && $column[$data_key][2]==0)?  0 :  (($column[$data_key][1] / $column[$data_key][2]) * 100);
							$column[$data_key][4] = $attainment."%";
							$sub_row_column = $column[$data_key];							
							$this->table->add_row($sub_row_column);
						
						}
					
				
			}
						
			$cnt++;
		}


		return $this->table->generate();
	}

	function comma($value){
		return str_replace(',','', $value);
	}
	function number($value){
		return number_format($value,2,'.',',');
	}

}