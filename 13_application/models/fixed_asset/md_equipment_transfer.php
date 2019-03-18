<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_equipment_transfer extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_cumulative($location){
		$sql = "CALL display_equipment_request_main1('".$location."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_items($location){
		$sql = "CALL display_equipmentrequest_available_F('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_mr($id){

		$sql = "SELECT MR_id, MR_no,equipment_id FROM mr_main WHERE equipment_id = '".$id."'";	
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	function get_details($id){

		$sql = "SELECT
		 		`equipment_request_details`.`item_no`,
				`setup_group_detail`.`description`,
				`equipment_request_details`.`quantity`
		  FROM `equipment_request_details`
		  INNER JOIN `setup_group_detail`
		    ON (`equipment_request_details`.`item_no` = `setup_group_detail`.`group_detail_id`)
		  WHERE request_main = '".$id."'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	function save_equipment_list(){

			$this->db->trans_begin();

            $data = array(
             'equipment_request_no'=>$this->input->post('ref_no'),             
             'to_location'=>$this->input->post('to_profit_center'),
             'to_receiver'=>$this->input->post('issue_to'),             
             'approver_id'=>$this->input->post('approved_by'),
             'equipment_status_id'=>$this->input->post('equipment_status_id'),
             'requested_by'=>$this->input->post('requested_by'),
             'date_requested'=>$this->input->post('ref_date'),                          
             'from_location'=>$this->input->post('from_profit_center'),
             'remarks'=>$this->input->post('purpose'),
             'MR_ID'=>$this->input->post('mr_id'),
             'title_id'=>$this->input->post('from_project'),
             'to_title_id'=>$this->input->post('to_project'),
             'equipment_id'=>$this->input->post('equipment_id'),
             'recommend_by'=>$this->input->post('recommended_by'));

            $this->db->insert('equipment_request_main',$data);
            
            $details = array(
	            	'request_main'=>$this->db->insert_id(),
					'equipment_id'=>$this->input->post('equipment_id'),
					'date_created'=>$this->input->post('ref_date'),
					'remarks'     =>$this->input->post('purpose'),
					'project_id'  =>$this->input->post('to_project'),
					'item_no'     =>$this->input->post('item_no'),
					'division'    =>'1',
					'account'     =>'',
					'quantity'    =>'1',
					'inventory_id'=>$this->input->post('inventory_id'),
					'title_id'    =>$this->input->post('from_project'),
					'to_title_id' =>$this->input->post('to_project'),
            );

			$this->db->insert('equipment_request_details',$details);

			$sql = "UPDATE db_equipmentlist SET equipment_status = 'REQUESTED',equipment_statuscode = '5' WHERE equipment_id = ?";
			$this->db->query($sql,array($this->input->post('equipment_id')));

			$this->db->trans_complete();
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

	public function changeEqStatus(){

		$sql = "UPDATE equipment_request_main SET request_status = '".$this->input->post('status')."' WHERE equipment_request_id = '".$this->input->post('id')."'";
		$this->db->query($sql);
		$this->db->close();
		return $this->input->post('status');

	}

	public function get_main_table($id){

		$sql    = "SELECT * FROM equipment_request_main WHERE equipment_request_id = '".$id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	function get_details_table($id){

		$sql = "SELECT
				*
		  FROM `equipment_request_details`
		  INNER JOIN `setup_group_detail`
		    ON (`equipment_request_details`.`item_no` = `setup_group_detail`.`group_detail_id`)
		  WHERE request_main = '".$id."'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}

	function get_db_equipmentlist($id){
		$sql = "SELECT * FROM db_equipmentlist WHERE equipment_id = '".$id."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}
	

	function update_equipment_list(){

			$this->db->trans_begin();

            $data = array(
             'equipment_request_no'=>$this->input->post('ref_no'),             
             'to_location'=>$this->input->post('to_profit_center'),
             'to_receiver'=>$this->input->post('issue_to'),
             'approver_id'=>$this->input->post('approved_by'),
             'equipment_status_id'=>$this->input->post('equipment_status_id'),
             'requested_by'=>$this->input->post('requested_by'),
             'date_requested'=>$this->input->post('ref_date'),
             'from_location'=>$this->input->post('from_profit_center'),
             'remarks'=>$this->input->post('purpose'),
             'MR_ID'=>$this->input->post('mr_id'),
             'title_id'=>$this->input->post('from_project'),
             'to_title_id'=>$this->input->post('to_project'),
             'equipment_id'=>$this->input->post('equipment_id'),
             'recommend_by'=>$this->input->post('recommended_by'));
			
            $this->db->where('equipment_request_id',$this->input->post('equipment_request_id'));
            $this->db->update('equipment_request_main',$data);
                        
            $sql = "DELETE FROM equipment_request_details WHERE request_main = '".$this->input->post('equipment_request_id')."'";
            $this->db->query($sql);

            $details = array(
	            	'request_main'=>$this->input->post('equipment_request_id'),
					'equipment_id'=>$this->input->post('equipment_id'),
					'date_created'=>$this->input->post('ref_date'),
					'remarks'     =>$this->input->post('purpose'),
					'project_id'  =>$this->input->post('to_project'),
					'item_no'     =>$this->input->post('item_no'),
					'division'    =>'1',
					'account'     =>'',
					'quantity'    =>'1',
					'inventory_id'=>$this->input->post('inventory_id'),
					'title_id'    =>$this->input->post('from_project'),
					'to_title_id' =>$this->input->post('to_project'),
            );

			$this->db->insert('equipment_request_details',$details);
			
			$this->db->trans_complete();
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