<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_obligation_release extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_cumulative($project,$location){
		
		$sql = "CALL display_mr_main_2('".$project."','".$location."','RO');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
		
	}


	function get_cumulative_2($project,$location){
		
		$sql = "CALL display_mr_main_2('".$project."','".$location."','MR');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
		
	}


	function get_tools($mr_id){
		$sql = "SELECT 
				item_description as 'equipment_description',
				item_cost as 'equipment_cost',
				plate_propertyno as 'equipment_platepropertyno'
		FROM mr_details WHERE mr_id = '".$mr_id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function get_status($equipment_id){		
		$sql = "SELECT equipment_status,equipment_statuscode FROM db_equipmentlist WHERE equipment_id = '".$equipment_id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	

	function save_obligationRelease(){

		$this->db->trans_begin();

		$mr_type = "RO";
	 	$mr_status = "RELEASE";

		$sql = "UPDATE mr_main SET mr_status = ? WHERE equipment_id = ? AND mr_status = 'ACTIVE'";
		 		$data = array(
		 				$mr_status,
		 				$this->input->post('equipment_id'),
		 			);
		$this->db->query($sql,$data);

		$sql  = "UPDATE db_equipmentlist SET equipment_drivercode = ?,equipment_driver = ? WHERE equipment_id = ? ";
		$data = array(
			"1",
			"ADMIN A. ADMIN",
			$this->input->post('equipment_id'),
			);
		$this->db->query($sql,$data);

		$data = array(
	 			'mr_no'=>$this->input->post('txtmrno'),
	 			'person_id'=>$this->input->post('cmbperson'),
	 			'equipment_id'=>$this->input->post('equipment_id'),
	 			'item_no'=>$this->input->post('itemno'),
	 			'project_id'=>$this->input->post('cmbprojectlocation'),
	 			'made_in'=>$this->input->post('txtmadein'),
	 			'date_saved'=>$this->input->post('dtpmrdate'),
	 			'item_cost'=>$this->input->post('txtvalue'),
	 			'to_project_id'=>$this->input->post('cmbtoprojectlocation'),
	 			'requestedby'=>$this->input->post('cmbrequested'),
	 			'issuedby'=>$this->input->post('cmbissued'),
	 			'approvedby'=>$this->input->post('cmbapproved'),
	 			'title_id'=>$this->input->post('from_project'),
	 			'type'=>$mr_type
	 			);
	 			 
	 	$this->db->insert('MR_Main',$data);

	 	$mr_id = $this->db->insert_id();

	 	$loop = $this->input->post('details');

	 	if(!empty($loop) && count($loop)>1){

	 	 	foreach($loop as $row){
	 	 		$data = array(
	 			'mr_id'=>$mr_id,
	 			'equipment_id'=>$this->input->post('equipment_id'),
	 			'item_description'=>$row['equipment_description'],
	 			'item_cost'=>$row['equipment_cost'],
	 			'remarks'=>($this->input->post('equipment_id') == 0)? "Release of OBligation" : "",
	 			'plate_propertyno'=>$this->input->post('plate_property_no'),
	 			'date_saved'=>$this->input->post('dtpmrdate')
	 			);
				$this->db->insert('MR_details',$data);
	 	 	}
		}

	 	$data = array(
	 				'mr_id'=>$mr_id,
	 				'from_location'=>$this->input->post('cmbprojectlocation'),
	 				'to_location'=>$this->input->post('cmbtoprojectlocation'),
	 				'to_assignee'=>$this->input->post('cmbperson'),
	 				'date_transferred'=>$this->input->post('dtpmrdate'),
	 				'remarks'=>$mr_status,
	 				'equipment_id'=>$this->input->post('equipment_id')
			);

	 	$this->db->insert('mr_history',$data);

	 	$data = array(
	 				'request_status'=>'RELEASE'
	 			);
	 	$this->db->where('equipment_request_id',$this->input->post('request_id'));
	 	$this->db->update('equipment_request_main',$data);


	 	if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}

	}




	



}