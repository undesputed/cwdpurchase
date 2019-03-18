<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_transfer extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_cumulative($location){
		$sql = "CALL display_equipment_materials_request_main1('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_details($equipment_request_id){
		$sql = "SELECT `equipment_request_details`.`item_no`, `setup_group_detail`.`description`, `equipment_request_details`.`quantity` FROM `equipment_request_details` INNER JOIN `setup_group_detail`  ON (`equipment_request_details`.`item_no` = `setup_group_detail`.`group_detail_id`) WHERE request_main = '".$equipment_request_id."'";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result;		
	}
	
	function get_items($location){
		$sql = "CALL display_equipmentrequest_materials_available_F1('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function save_stockTransfer(){
		
		$insert = array(
				'equipment_request_no'=>$this->input->post('equipment_request_no'),
				'to_location'=>$this->input->post('to_location'),
				'to_receiver'=>$this->input->post('to_receiver'),
				'approver_id'=>$this->input->post('approver_id'),
				'equipment_status_id'=>$this->input->post('equipment_status_id'),
				'requested_by'=>$this->input->post('requested_by'),
				'date_requested'=>$this->input->post('date_requested'),
				'from_location'=>$this->input->post('from_location'),
				'remarks'=>$this->input->post('remarks'),
				'MR_ID'=>$this->input->post('MR_ID'),
				'title_id'=>$this->input->post('title_id'),
			);
		
		$this->db->insert('equipment_request_main',$insert);
		

		foreach($this->input->post('details') as $key => $value){
		
			$insert = array(
				'request_main'=>$this->db->insert_id(),
				'equipment_id'=>'0',
				'date_created'=>$this->input->post('date_requested'),
				'remarks'=>$this->input->post('remarks'),
				'project_id'=>$this->input->post('title_id'),
				'item_no'=>$value['item_no'],
				'division'=>$this->input->post('division'),
				'account'=>$this->input->post('account'),
				'quantity'=>$value['withdrawn_qty'],
				'inventory_id'=>$value['inventory_id'],
				'title_id'=>$this->input->post('title_id'),
				);

			$this->db->insert('equipment_request_details',$insert);
		}			

		return true;
	}


	public function get_mainTbl($id){
		$sql = "SELECT * FROM equipment_request_main where equipment_request_id = '".$id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
		/*SELECT * FROM equipment_request_details;*/
	}

	public function get_detailsTbl($id){	

		$sql = "
			SELECT
		  `equipment_request_details`.`item_no`,
		  `setup_group_detail`.`description` AS 'item_description',
		  `equipment_request_details`.`quantity` as 'withdrawn_qty',
		   equipment_request_details.division,
		   equipment_request_details.account,
		   equipment_request_details.inventory_id,
		   equipment_request_details.item_No AS 'item_no'
		FROM `equipment_request_details`
		 INNER JOIN `setup_group_detail`
		    ON (`equipment_request_details`.`item_no` = `setup_group_detail`.`group_detail_id`)
		WHERE request_main = '".$id."';
		";
		
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function update_stockTransfer(){

		$insert = array(
				'equipment_request_no'=>$this->input->post('equipment_request_no'),
				'to_location'=>$this->input->post('to_location'),
				'to_receiver'=>$this->input->post('to_receiver'),
				'approver_id'=>$this->input->post('approver_id'),
				'equipment_status_id'=>$this->input->post('equipment_status_id'),
				'requested_by'=>$this->input->post('requested_by'),
				'date_requested'=>$this->input->post('date_requested'),
				'from_location'=>$this->input->post('from_location'),
				'remarks'=>$this->input->post('remarks'),
				'MR_ID'=>$this->input->post('MR_ID'),
				'title_id'=>$this->input->post('title_id'),
			);
		$this->db->where('equipment_request_id',$this->input->post('equipment_request_id'));
		$this->db->update('equipment_request_main',$insert);
		
		$this->db->where('request_main',$this->input->post('equipment_request_id'));
		$this->db->delete('equipment_request_details');

		foreach($this->input->post('details') as $key => $value){
			$insert = array(
				'request_main'=>$this->input->post('equipment_request_id'),
				'equipment_id'=>'0',
				'date_created'=>$this->input->post('date_requested'),
				'remarks'=>$this->input->post('remarks'),
				'project_id'=>$this->input->post('title_id'),
				'item_no'=>$value['item_no'],
				'division'=>$this->input->post('division'),
				'account'=>$this->input->post('account'),
				'quantity'=>$value['withdrawn_qty'],
				'inventory_id'=>$value['inventory_id'],
				'title_id'=>$this->input->post('title_id'),
				);

			$this->db->insert('equipment_request_details',$insert);
		}			

		return true;

	}

}