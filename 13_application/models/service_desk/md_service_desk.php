<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_service_desk extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}
		

	function get_ref_no(){
		$date = $this->input->post('date');
		$date = explode('-',$date);
		$sql  = "SELECT SUBSTRING(MAX(`sr_no`),7,3) AS MAX FROM pm_service_request WHERE SUBSTRING(`sr_no`,4,2) = '".$date[1]."' AND SUBSTRING(`sr_no`,11,4) = '".$date[0]."' AND main_project = '".$this->session->userdata('Proj_Main')."'";	
		$result = $this->db->query($sql);

		$output = $result->row_array();

		if($output['MAX']==""){			
			$counter = str_pad('1','3','0',STR_PAD_LEFT);
		}else{
			$counter = str_pad($output['MAX'],'3','0',STR_PAD_LEFT);
		}

		return "SR-".$date[1]."-".$counter."-".$date[0]."";

	}	



	function get_service(){
		$sql = "SELECT * FROM pm_service_request order by id desc";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();
	}
	

}