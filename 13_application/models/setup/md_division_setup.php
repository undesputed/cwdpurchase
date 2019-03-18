<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_division_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();
	}


	function get_cumulative(){

		$sql = "CALL display_division_setup('".$this->session->userdata('Proj_Main')."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function save_division(){

		if($this->input->post('id')==""){

			$sql = "SELECT IFNULL ((SELECT MAX(division_id)+1 FROM division_setup),1) as 'max';";
			$result = $this->db->query($sql);
			$row = $result->row_array();

			$insert = array(
				'division_code'=>$row['max'],
				'division_name'=>$this->input->post('department_name'),
				'title_id'=>$this->session->userdata('Proj_Code'),
				'division_code1'=>$this->input->post('department_Code'),
				'userid'=>$this->session->userdata('user'),
				);
			$this->db->insert('division_setup',$insert);

		}else{

			$insert = array(
				'division_code1'=>$this->input->post('department_Code'),
				'division_name'=>$this->input->post('department_name'),
				'title_id'=>$this->session->userdata('Proj_Code'),				
				'userid'=>$this->session->userdata('user'),
				);
			$this->db->where('division_id',$this->input->post('id'));
			$this->db->update('division_setup',$insert);
			
		}
			
		return true;

	}

	function update_division(){

		$sql = "CALL update_division_setup('{0}','{1}','{2}','{3}','{4}','{5}')";
		$this->db->query();

		return true;

	}
	



}