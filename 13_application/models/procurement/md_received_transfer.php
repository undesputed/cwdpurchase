<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_received_transfer extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_received_transfer($id){
		$sql = "CALL display_receiving_transfers('".$id."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	public function get_cumulative(){
		$sql = "CALL display_received_main1(3)";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}
	
	public function get_details($id){
		$sql = "CALL display_receiving_transfers_F('".$id."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_names(){
		$sql = "CALL _display_person_fullname;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
	

	public function get_singleMain($id,$location){
		$sql = "SELECT
				dispatch_main.dispatch_id,
				dispatch_main.dispatch_no,
				dispatch_main.to_location,
				equipment_request_details.project_id,
				equipment_request_main.equipment_request_id,
				equipment_request_main.equipment_request_no,
				equipment_request_main.request_status,
				equipment_request_details.equipment_id,
				equipment_request_details.item_no,
				equipment_request_details.division,
				equipment_request_details.account,
				equipment_request_details.quantity,
				(SELECT equipment_status FROM db_equipmentlist WHERE db_equipmentlist.equipment_id = equipment_request_details.equipment_id) AS 'Equipment Status',
				(SELECT item_description FROM inventory_main WHERE inventory_main.inventory_id = equipment_request_details.inventory_id) AS 'Item Description',
				(SELECT item_cost FROM inventory_main WHERE inventory_main.inventory_id = equipment_request_details.inventory_id) AS 'Item Cost',
				(SELECT item_measurement FROM inventory_main WHERE inventory_main.inventory_id = equipment_request_details.inventory_id) AS 'Item Measurement',
				(SELECT supplier_id FROM inventory_main WHERE inventory_main.inventory_id = equipment_request_details.inventory_id) AS 'Supplier ID',
				dispatch_main.dispatch_no 'REF. NO.',
				dispatch_main.date_created 'REF. DATE',
				dispatch_main.dispatch_status 'STATUS',
				CONCAT(project,' - ',project_name,' - ',project_location) AS 'LOCATION FROM',
				`setup_project`.`project_id` AS 'LOCATION FROM ID'
				FROM dispatch_main
				INNER JOIN dispatch_detail
				ON(dispatch_main.dispatch_id = dispatch_detail.dispatch_main)
				INNER JOIN equipment_request_main
				ON(dispatch_detail.request_id = equipment_request_main.equipment_request_id)
				INNER JOIN equipment_request_details
				ON(equipment_request_main.equipment_request_id = equipment_request_details.request_main)
				 INNER JOIN `setup_project` 
			        ON (`dispatch_main`.`from_location` = `setup_project`.`project_id`)
				WHERE dispatch_main.to_location = '".$location."'
				AND dispatch_main.dispatch_id = '".$id."'
				AND dispatch_main.dispatch_status = 'ACTIVE'
				AND dispatch_main.dispatch_type = 'YES'
				GROUP BY dispatch_id
				ORDER BY dispatch_main.dispatch_id DESC";				
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}


	public function save(){
	
		$data = array(
			'receipt_no'=>$this->input->post('receipt_no'),
			'supplier_id'=>$this->input->post('supplier_id'),
			'employee_receiver_id'=>$this->input->post('employee_receiver_id'),
			'employee_checker_id'=>$this->input->post('employee_checker_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'date_received'=>$this->input->post('date_received'),
			'project_id'=>$this->input->post('project_id'),
			'title_id'=>$this->input->post('title_id'),
			'rr_type'=>'DR',
			'location_from'=>$this->input->post('location_from'),
			);

		$this->db->insert('receiving_main',$data);
		$id = $this->db->insert_id();
				
		$details = $this->input->post('details');

		foreach($details as $row){

				$insert = array(
					'receipt_id'=>$id,
					'po_id'=>'0',
					'item_id'=>$row['item_no'],
					'item_quantity_ordered'=>$row['quantity'],
					'item_quantity_actual'=>$row['quantity'],
					'item_name_ordered'=>$row['item_description'],
					'item_name_actual'=>$row['item_description'],
					'item_cost_ordered'=>$row['item_cost'],
					'item_cost_actual'=>$row['item_cost'],
					'discrepancy'=>$row['discrepancy'],
					'discrepancy_remarks'=>'',
					'po_number'=>'0',
					'unit_msr'=>$row['item_measurement'],
					'dispatch_id'=>$row['dispatch_id'],
				);	

				$this->db->insert('receiving_details',$insert);

				if($row['equipment_id']==0){

					$sql = "CALL insert_to_inventory_from_receive1(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
					$inst = array(
							$insert['item_id'],
							$insert['item_name_ordered'],
							$insert['item_cost_ordered'],
							$insert['unit_msr'],
							$data['supplier_id'],
							$insert['item_quantity_ordered'],
							'0',
							$insert['receipt_id'],
							'0',
							'0',
							$this->input->post('division'),
							$row['account'],
							$this->input->post('title_id'),
							$this->input->post('project_id'),
					);

					$this->db->query($sql,$inst);					
				}else{

					$sql = "SELECT inventory_id FROM db_equipmentlist WHERE equipment_id = '".$row['equipment_id']."'";	
					$result = $this->db->query($sql);
					$row = $result->row_array();
	                $sql = "UPDATE inventory_main SET project_location_id = '".$this->input->post('project_id')."', title_id = '".$this->input->post('title_id')."' WHERE inventory_id = '".$row['inventory']."'";
					$this->db->query($sql);
					

					$sql = "CALL display_receiving_transfers_for_history('".$row['dispatch_id']."','".$row['equipment_id']."')";
					$result = $this->db->query($sql);					
					$row = $result->row_array();

					$insert_history = array(
							'mr_id'=>$row['MR_ID'],
							'from_location'=>$row['from_location'],
							'to_location'=>$this->input->post('from_location'),
							'to_assignee'=>$row['to_receiver'],
							'date_transferred'=>date('Y-m-d'),
						);
					$this->db->insert('mr_history',$insert_history);

				}

		}			

		$insert = array(
			'receipt_id'=>$id,
			'receipt_no'=>$this->input->post('receipt_no'),
			'supplier_id'=>$this->input->post('supplier_id'),
			'employee_receiver_id'=>$this->input->post('employee_receiver_id'),
			'employee_checker_id'=>$this->input->post('employee_checker_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'date_received'=>$this->input->post('date_received'),
			'project_id'=>$this->input->post('project_id'),
			'title_id'=>$this->input->post('title_id'),
			'rr_type'=>'DR',
			'location_from'=>$this->input->post('location_from'),
			);

		$this->db->insert('receiving_main_copy',$insert);

		foreach($details as $row){

			$insert = array(
					'receipt_id'=>$id,
					'po_id'=>'0',
					'item_id'=>$row['item_no'],
					'item_quantity_ordered'=>$row['quantity'],
					'item_quantity_actual'=>$row['quantity'],
					'item_name_ordered'=>$row['item_description'],
					'item_name_actual'=>$row['item_description'],
					'item_cost_ordered'=>$row['item_cost'],
					'item_cost_actual'=>$row['item_cost'],
					'discrepancy'=>$row['discrepancy'],
					'discrepancy_remarks'=>'',
					'po_number'=>'0',
					'unit_msr'=>$row['item_measurement'],
					'dispatch_id'=>$row['dispatch_id'],
				);

				$this->db->insert('receiving_details_copy',$insert);				
		}


		$sql = "UPDATE dispatch_main SET dispatch_status = 'RECEIVED' WHERE dispatch_id = '".$this->input->post('dispatch_id')."'";
		$this->db->query($sql);

		foreach($details as $row){

			$sql = "UPDATE equipment_request_main SET request_status = 'RECEIVED' WHERE equipment_status_id = '".$row['supplier_id']."'";
			$this->db->query($sql);



			$sql = "CALL display_receiving_transfers_for_history('".$row['dispatch_id']."','".$row['equipment_id']."')";
			$result = $this->db->query($sql);
			$this->db->close();
			$mr =  $result->row_array();

	        $sql = "UPDATE mr_main SET project_id = '".$this->input->post('project_id')."', title_id = '".$this->input->post('title_id'). "' WHERE mr_id = '".$mr['MR_ID']."'";
			
	        if($row['equipment_request_id'] == 0){

	        }else{
	        		
	                $sql   = "SELECT to_receiver FROM equipment_request_main WHERE equipment_request_id = '".$row['equipment_request_id']."'";
	                $result =  $this->db->query($sql);	                
	                $assignee = $result->row_array();
	             
	                $sql = "UPDATE db_equipmentlist SET equipment_status = 'AVAILABLE', equipment_statuscode = '1', equipment_location = '".$this->input->post('title_id')."', title_id = '".$this->input->post('project_id')."', assignee = '".$assignee['to_receiver']."' WHERE equipment_id = '".$row['equipment_id']."'";
	                $this->db->query($sql);

	        }

    	}



		return true;


	}







}