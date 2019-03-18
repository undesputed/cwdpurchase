<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_maintenance extends CI_Model {

	var $downtime;
	var $mech_avail;
	public function __construct(){
		parent :: __construct();	
		$this->load->library('table');	
	}


	public function display(){
		$tmpl = array ( 'table_open'  => '<table class="table table-condensed">');
		$this->table->set_template($tmpl); 

		$output = null;
		$sql = "CALL display_maintenance_daily_report('".$this->input->post('location')."','".$this->input->post('date')."%');";
		$result = $this->db->query($sql);
		$this->db->close();
		$row = $result->row_array();

		if(isset($row['RESULT'])){

			$result = $this->db->query($row['RESULT']);		

			$this->db->close();
			$output = $result->result_array();

		}

		$this->populate_array->hide = array(
			'scope_id',
			'scope_equip_id',
			'location',
		);

		$this->populate_array->overide = array(
				'body_no'=>'Unit No',
				'program_hrs'=>'PROGHRS',
				'TOTAL Downtime_unplanned'=>'Total Downtime',
				);



		$this->populate_array->column  = array('body_no'=>'TOTAL');
				
		$output = $this->populate_array->_row($output,'type_desc');

		$thead = $this->populate_array->_header();
		$this->table->add_row($thead);

		foreach ($output as $key => $value) {
				$header = $this->populate_array->sub_header(array('data'=>$key));
				$this->table->add_row($header);
				$total = array();
				foreach ($value as $key1 => $value1){
						$row_data = array();
						$margin = "";
										
							foreach ($value1 as $key2 => $value2){
								if(in_array($key2, $this->populate_array->hide)){
									$style = "display:none";
								}else{
									$style = "";											
									$margin = (array_key_exists($key2, $this->populate_array->column))? "<span style='margin-left:3em;'></span>" : "";																													
								}
								$row_data[] = array('data'=>$margin.$value2,'style'=>$style);
								$total[$key2] = (isset($total[$key2]))? $total[$key2] + $value2 : $value2;								
							}
						$this->table->add_row($row_data);						
				}

				$row_total = $this->populate_array->generate_row($total);
				$this->table->add_row($row_total);
				
								
		}



		echo $this->table->generate();

	}

	
	function get_downtime($downtime,$pms){			
		$this->downtime = (($downtime + $pms) >0) ? $downtime + $pms : "-";
		return $this->downtime;
	}


	function get_mech_avail($prog_hrs){		
		$this->mech_avail = (($prog_hrs - $this->downtime) / $prog_hrs) * 100 ; 
		return $this->mech_avail;
	}

	
	function get_equip_reli(){
		return $this->mech_avail - (100 - $this->mech_avail);
	}


}