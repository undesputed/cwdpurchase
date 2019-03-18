<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_worksheet extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_worksheet(){

		switch($this->input->post('display_by')){
			case"today":
				$dateT = $this->input->post('date');
			break;
			case"month":
				$time = strtotime($this->input->post('date'));
				$final = date("Y-m-d", strtotime("+1 month", $time));
				$dateT = $final;
			break;
			case"year":
				$date =  explode('-',$this->input->post('date'));
				$dateT = $date[0].'-12-31';
			break;

		}

		$sql = "CALL accounting_display_worksheet_new('".$this->input->post('location')."','1900-01-01','".$dateT."','".$this->session->userdata('Proj_Main')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}






}