<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_revenue_update extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	var $header;
	var $margin;

	
	function display(){
		$tmpl = array ( 'table_open'  => '<table class="table table-condensed table-striped">');
		$this->table->set_template($tmpl); 
		
				
		$sql = "SELECT db_equipment_id, db_equipmentlist.equipment_brand FROM fvc_equipment_utilization_setup INNER JOIN db_equipmentlist ON db_equipmentlist.equipment_id = db_equipment_id WHERE  fvc_equipment_utilization_setup.location = '".$this->input->post('location')."' AND fvc_equipment_utilization_setup.trans_date = '".$this->input->post('date')."' GROUP BY db_equipment_id;";
		$result = $this->db->query($sql);
		$this->db->close();

		$output = $result->result_array();


		$full_date = date('Y-m-d',strtotime($this->input->post('date').'-1 day'));

		$date_chunk = explode('-', $full_date);

		$year_month = $date_chunk['0'].'-'.$date_chunk['1'].'%';
		$year = $date_chunk['0'].'%';
		
		$this->header = array(
				'DESCRIPTION',
				'TARGET',
				'ACTUAL',
				'VARIANCE(MT)',
				'ATTAINMENT(%)',
		);
		$this->table->set_heading($this->header);

		$this->margin = "<span style='margin-left:3em;'></span>";
		foreach ($output as $key => $value){
			
			$sql = "CALL display_fvc_target_and_actual_report('".$full_date."','".$year_month."','".$year."','".$this->input->post('project_effectivity')."','".$value['db_equipment_id']."');";
			$result = $this->db->query($sql);
			$this->db->close();
			$data = $result->result_array();
	
			/**Header**/

			$th = $this->_header($value['equipment_brand'],count($this->header));
			$this->table->add_row($th);
			
			foreach($data as $key1 =>$value1){

				$row_content = array();
					$row_content[] = array('data'=>$this->margin.$value1['For Yesterday']);
					$row_content[] = $this->_number_format($value1['Target']);
					$row_content[] = $this->_number_format($value1['Actual']);
					$row_content[] = $this->_number_format($value1['Variance']);
					$row_content[] = $this->_number_format($value1['Attainment']);
				$this->table->add_row($row_content);

			}
		}

		$json['equipment']=$this->table->generate();
		$this->table->clear();
		$sql = "CALL display_fvc_target_and_actual_report_summary('".$full_date."','".$year_month."%','".$year."%','".$this->input->post('project_effectivity')."','%','".count($output)."');";
		$result = $this->db->query($sql);
		$this->db->close();
		$output = $result->result_array();
		$json['summary'] = $this->_summary($output);

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

	function _number_format($value){
		return number_format($value,2,'.',',');		
	}


	function _summary($output){
		
		$this->table->set_heading($this->header);
		foreach ($output as $key => $value){
				$row_content = array();
					$row_content[] = array('data'=>$this->margin.$value['For Yesterday']);
					$row_content[] = $this->_number_format($value['Target']);
					$row_content[] = $this->_number_format($value['Actual']);
					$row_content[] = $this->_number_format($value['Variance']);
					$row_content[] = $this->_number_format($value['Attainment']);
				$this->table->add_row($row_content);
		}

		return $this->table->generate();


	}

	
}