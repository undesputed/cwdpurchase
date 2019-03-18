<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_project_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();
	}


	function get_cumulative(){

		/*$sql = "CALL display_division_setup('".$this->session->userdata('Proj_Main')."')";*/


		$sql = "SELECT * FROM project_title";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function get_all(){

		$sql = "SELECT * FROM project_title";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}



	function save(){

		if($this->input->post('id')==""){

			/*$sql = "SELECT IFNULL ((SELECT MAX(division_id)+1 FROM division_setup),1) as 'max';";
			$result = $this->db->query($sql);
			$row = $result->row_array();*/

			/*$insert = array(
				'division_code'=>$row['max'],
				'division_name'=>$this->input->post('department_name'),
				'title_id'=>$this->session->userdata('Proj_Code'),
				'division_code1'=>$this->input->post('department_Code'),
				'userid'=>$this->session->userdata('user'),
				);*/

			$sql = "SELECT * FROM project_title WHERE status = 'ACTIVE'";
			$query = $this->db->query($sql);

			if($query->num_rows() == 0){
				$insert = array(
					'title_name' =>$this->input->post('project_name'),
					'title_desc1' =>$this->input->post('project_address'),
					'title_desc2' =>$this->input->post('contact_no'),
					);

				$this->db->insert('project_title',$insert);
			}

		}else{

			$insert = array(
				'title_name' =>$this->input->post('project_name'),
				'title_desc1' =>$this->input->post('project_address'),
				'title_desc2' =>$this->input->post('contact_no'),
				);
			$this->db->where('title_id',$this->input->post('id'));
			$this->db->update('project_title',$insert);
			
		}
			
		return true;

	}

	function update_division(){

		$sql = "CALL update_division_setup('{0}','{1}','{2}','{3}','{4}','{5}')";
		$this->db->query();

		return true;

	}
	



}