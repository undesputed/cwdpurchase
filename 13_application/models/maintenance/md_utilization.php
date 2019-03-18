<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_utilization extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_itemName(){

		$sql    = "SELECT group_detail_id,description FROM setup_group_detail WHERE group_id1 = 2;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_checklist(){
		$sql = "CALL display_checklists()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;		
	}


	public function checked_equipmentName($item_id){
		$sql  = "SELECT
				id AS 'id',
				 IF((SELECT checked FROM fvc_utilization_setup WHERE checklist_id = a.id AND item_id = '".$item_id."')IS NULL,'FALSE','TRUE') AS 'INCLUDE?',
				`type`
				FROM fvc_checklist_setup a";

		$result = $this->db->query($sql);
		return $result;

	}


	public function save_utilization(){
		
		$this->db->trans_begin();
		
		$sql = "DELETE FROM fvc_utilization_setup WHERE item_id = '".$this->input->post('equipment_name')."'";
		$result = $this->db->query($sql);

		foreach($this->input->post('checklist') as $value){	

				$insert = array(
						'item_id'=>$this->input->post('equipment_name'),
						'checklist_id'=>$value,
						'checked'=>'TRUE',
						'location'=>$this->session->userdata('Proj_Code'),
						'title_id'=>$this->session->userdata('Proj_Main'),
						'userid'=>$this->session->userdata('user'),
					);
				$this->db->insert('fvc_utilization_setup',$insert);

		}

		$sql = "DELETE FROM fvc_utilization_checklist WHERE item_id ='".$this->input->post('equipment_name')."' ";
		$result = $this->db->query($sql);

		if($this->db->trans_status()===FALSE){
			 $this->db->trans_rollback();
			return false;
		}else{
			$this->db->trans_commit();
			return true;
		}

		return true;

	}
	






		

}