<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_person_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_person(){
		
		// $sql = "SELECT * FROM hr_person_profile;";
		$sql = "SELECT * FROM hr_person_profile WHERE `status` = 'active'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;		
		
	}

	public function get_personId($id){

		$sql = "SELECT * FROM hr_person_profile where pp_person_code = '".$id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}


	public function save_person(){

		$fullname = $this->input->post('last_name')." ,".$this->input->post('first_name').", ".$this->input->post('middle_name');		
		$insert = array(
			'SysPK_PP'=>'0',
			'pp_lastname'=>$this->input->post('last_name'),
			'pp_firstname'=>$this->input->post('first_name'),
			'pp_middlename'=>$this->input->post('middle_name'),			
			'pp_fullname'=>$fullname,
			'pp_dob'=>$this->input->post('date'),
			'pp_birthplace'=>$this->input->post('birthPlace'),
			'pp_sex'=>$this->input->post('gender'),
			'pp_civilstatus'=>$this->input->post('civil_status'),
			'pp_type'=>$this->input->post('pp_type')
		);
		
		
		$this->db->insert('hr_person_profile',$insert);

		return true;

	}

		public function update_person(){

		$fullname = $this->input->post('last_name')." ,".$this->input->post('first_name').", ".$this->input->post('middle_name');

		$insert = array(

			// 'pp_prefix'=>$this->input->post('prefix'),
			'SysPK_PP'=>'0',
			'pp_lastname'=>$this->input->post('last_name'),
			'pp_firstname'=>$this->input->post('first_name'),
			'pp_middlename'=>$this->input->post('middle_name'),
			// 'pp_suffix'=>$this->input->post('suffix'),
			'pp_fullname'=>$fullname,
			'pp_dob'=>$this->input->post('date'),
			'pp_birthplace'=>$this->input->post('birthPlace'),
			'pp_sex'=>$this->input->post('gender'),
			'pp_civilstatus'=>$this->input->post('civil_status'),
		);
		
		$this->db->where('pp_person_code',$this->input->post('id'));
		$this->db->update('hr_person_profile',$insert);
		
		return true;


	}

	public function delete($id){
		$sql = "UPDATE  hr_person_profile SET `status`='CANCELLED' WHERE pp_person_code = '$id'";
		$this->db->query($sql);
		return true;
	}






}
