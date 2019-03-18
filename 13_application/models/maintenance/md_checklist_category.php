<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_checklist_category extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}
	
	function get_category(){
		$sql = "SELECT id,type FROM fvc_category_setup WHERE status = 'ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;

	}

	function insert_category(){
		$insert = array(
				'type'=>$this->input->post('type'),
				'userid'=>$this->session->userdata('user'),
				'location'=>$this->session->userdata('Proj_Code'),
				'title'=>$this->session->userdata('Proj_Main'),
			);

		$this->db->insert('fvc_category_setup',$insert);
		return true;
		
	}

	function update_category(){

			$update = array(
				'type'=>$this->input->post('type'),
				'userid'=>$this->session->userdata('user'),
				'location'=>$this->session->userdata('Proj_Code'),
				'title'=>$this->session->userdata('Proj_Main'),
			);

			$this->db->where('id',$this->input->post('id'));
			$this->db->update('fvc_category_setup',$update);

		return true;
	}



}