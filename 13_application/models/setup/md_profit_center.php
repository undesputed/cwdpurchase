<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_profit_center extends CI_Model {

	public function __construct(){
		parent :: __construct();
	}







	function get_cumulative(){

		/*$sql = "CALL display_division_setup('".$this->session->userdata('Proj_Main')."')";*/


		/*$sql = "SELECT * FROM setup_project;";*/

		$sql = "
		SELECT * FROM setup_project sp
		INNER JOIN project_title pt
		ON (sp.title_id = pt.title_id);
		";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

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
				);
			*/
			
			/*$insert = array(
				'title_name' =>$this->input->post('project_name'),
				'title_desc1' =>$this->input->post('project_address'),
				'title_desc2' =>$this->input->post('contact_no'),
				);
			*/

			if($this->input->post('date_completed') == ''){
				$date_completed = '';
			}else{
				$date_completed = $this->input->post('date_completed');
			}

			if($this->input->post('project_incharged') == ''){
				$project_incharged = 'NOT SET';
			}else{
				$project_incharged = $this->input->post('project_inchareged');
			}

			if($this->input->post('project_managed') == ''){
				$project_managed = 'NOT SET';
			}else{
				$project_managed = $this->input->post('project_managed');
			}

			$insert = array(
				'project'=>$this->input->post('project'),
				'project_name'=>$this->input->post('project_name'),
				'project_location'=>$this->input->post('project_location'),
				'title_id'=>$this->input->post('title_id'),
				'project_no'=>$this->input->post('project_code'),
				'project_duration'=>$this->input->post('project_duration'),
				'date_started'=>$this->input->post('date_started'),
				'date_projected'=>$this->input->post('date_projected'),
				'date_completed'=>$date_completed,
				'project_incharged'=>$this->input->post('project_incharged'),
				'project_managed'=>$this->input->post('project_managed')
				);

			$this->db->insert('setup_project',$insert);

		}else{
			if($this->input->post('date_completed') == ''){
				$date_completed = '';
			}else{
				$date_completed = $this->input->post('date_completed');
			}

			if($this->input->post('project_incharged') == ''){
				$project_incharged = 'NOT SET';
			}else{
				$project_incharged = $this->input->post('project_inchareged');
			}

			if($this->input->post('project_managed') == ''){
				$project_managed = 'NOT SET';
			}else{
				$project_managed = $this->input->post('project_managed');
			}

			$insert = array(
				'project'=>$this->input->post('project'),
				'project_name'=>$this->input->post('project_name'),
				'project_location'=>$this->input->post('project_location'),
				'title_id'=>$this->input->post('title_id'),
				'project_no'=>$this->input->post('project_code'),
				'project_duration'=>$this->input->post('project_duration'),
				'date_started'=>$this->input->post('date_started'),
				'date_projected'=>$this->input->post('date_projected'),
				'date_completed'=>$date_completed,
				'project_incharged'=>$this->input->post('project_incharged'),
				'project_managed'=>$this->input->post('project_managed')
				);

			$this->db->where('project_id',$this->input->post('id'));
			$this->db->update('setup_project',$insert);
			
		}
			
		return true;

	}

	function update_division(){

		$sql = "CALL update_division_setup('{0}','{1}','{2}','{3}','{4}','{5}')";
		$this->db->query();

		return true;

	}
	



}