<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_equip_checklist extends CI_model {

	public function __construct(){
		parent :: __construct();
	}


	function get_checklist(){
		$sql = "SELECT id,TYPE FROM fvc_checklist_setup WHERE STATUS = 'ACTIVE' order by id desc;";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;

	}

	function insert_checklist(){
		$insert = array(
				'type'=>$this->input->post('type'),
				'userid'=>$this->session->userdata('user'),
				'location'=>$this->session->userdata('Proj_Code'),
				'title'=>$this->session->userdata('Proj_Main'),
			);
		$this->db->insert('fvc_checklist_setup',$insert);
		return true;
	}

	function update_checklist(){

			$update = array(
				'type'=>$this->input->post('type'),
				'userid'=>$this->session->userdata('user'),
				'location'=>$this->session->userdata('Proj_Code'),
				'title'=>$this->session->userdata('Proj_Main'),
			);

			$this->db->where('id',$this->input->post('id'));
			$this->db->update('fvc_checklist_setup',$update);

		return true;
	}




}