<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_material_requisition extends CI_Model {

	
	/*CALL material_display_monthly_new_rose('2014-08-05','2');
	CALL material_display_main_new_rose('2');*/


	public function get_material_requisition($arg){

		switch($arg['type']){
			case"all":
				$sql = "CALL material_display_main_new_rose('2')";
			break;			
			case"daily":
				$sql = "CALL material_display_monthly_new_rose('".$arg['date']."','2')";
			break;
		}
				
		$result = $this->db->query($sql);		
		return $result->result_array();		
		
	}


	public function get_request_details($id){

		$sql = "
				SELECT
				  pr_id,
				  itemNo,
				  itemDesc,
				  (SELECT
				     _get_unit_measure(itemNo))    'umsr',
				  groupID,
				  qty,
				  ((SELECT IFNULL(SUM(received_quantity),0) FROM inventory_main WHERE inventory_main.item_no = purchaserequest_details.itemNo) - (SELECT IFNULL(SUM(withdrawn_quantity),0) FROM withdraw_details INNER JOIN withdraw_main ON (withdraw_main.withdraw_id = withdraw_details.withdraw_main_id)WHERE withdraw_details.item_no = purchaserequest_details.itemNo AND withdraw_main.status = 'ACTIVE'))    'available',
				  modelNo,
				  serialNo,
				  remarkz,
				  req_qty,
				  unit_cost,
				  rem_qty,
				  dtl_status,
				  availble_qty,
				  needed_qty,
				  approved_cost,
				  approved_supplier,
				  supplier_type
				FROM purchaserequest_details
				WHERE pr_id = '".$id."'
		";

		$result = $this->db->query($sql);
		return $result->result_array();	

	}
	

	public function approved($id){

		$sql = "UPDATE purchaserequest_main SET approvedMR = 'True' WHERE pr_id = '".$id."'";
		$this->db->query($sql);
		
		$sql = "SELECT person_code FROM signatory_approval WHERE form_type = 4 LIMIT 1;";
		$return = $this->db->query($sql);
		$row = $return->row_array();

		$sql = "UPDATE purchaserequest_main SET department_head = '".$row['person_code']."' WHERE pr_id = '".$id."'";
		$this->db->query($sql);	
		
		return true;

	}



	/** MIS REPORT**/


	public function get_mis_report($arg){

		$sql    = "CALL DISPLAY_ISSUANCE_REPORT('".$arg['from']."','".$arg['to']."','%%','%')";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result->result_array();		

	}

		
		


}