<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_item_transfer extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	function get_main($transfer_no){
		$sql = "
			SELECT *,
			(SELECT CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.to_project_id) 'request_to_name',
			(SELECT CONCAT(project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.to_project_id) 'request_to',
			(SELECT CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.project_id) 'created_from',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = itm.prepared_by
			) 'preparedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = itm.approved_by
			) 'approvedBy_name'
			FROM item_transfer_main itm
			INNER JOIN (
				SELECT COUNT(item_no)'no_items',transfer_id FROM item_transfer_details GROUP BY transfer_id
			) itd
			ON (itm.id = itd.transfer_id)
			WHERE transfer_no = '".$transfer_no."';
		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}

	function get_details($transfer_id){		
		$sql = "
			SELECT *,(SELECT unit_measure FROM setup_group_detail WHERE group_detail_id = item_no) 'unit_measure' FROM item_transfer_details WHERE transfer_id = '".$transfer_id."'
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
	


	function get_transfer_list(){

		$sql = "
			SELECT *,
			(SELECT CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.to_project_id) 'request_to_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = itm.prepared_by
			) 'preparedBy_name'
			FROM item_transfer_main itm
			INNER JOIN (
				SELECT COUNT(item_no)'no_items',transfer_id FROM item_transfer_details GROUP BY transfer_id
			) itd
			ON (itm.id = itd.transfer_id)
			WHERE title_id = '".$this->session->userdata('Proj_Main')."' AND project_id = '".$this->session->userdata('Proj_Code')."'	 
					;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	function save(){
		/*$this->input->post('prepared_by')*/
		$insert = array(
			'transfer_no'=>$this->input->post('transfer_no'),
			'transaction_date'=>$this->input->post('transaction_date'),
			'prepared_by'=>$this->session->userdata('emp_id'),
			'request_by'=>$this->input->post('request_by'),
			'approved_by'=>$this->input->post('approved_by'),
			'remarks'=>$this->input->post('remarks'),
			'to_project_id'=>$this->input->post('to_project_id'),
			'to_title_id '=>$this->session->userdata('Proj_Main'),
			'project_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			);

		$this->db->insert('item_transfer_main',$insert);		
		$last_id = $this->db->insert_id();
		$details = $this->input->post('details');
		foreach($details as $row){
						
			$insert_details = array(
				'transfer_id' =>$last_id,
				'item_no' =>$row['item_no'],
				'item_description' =>$row['item_description'],
				'unit_measure' =>$row['unit_measure'],
				'request_qty' =>$row['request_qty']
				);
			
			$this->db->insert('item_transfer_details',$insert_details);
						
		}


		$this->sent_item($this->input->post('transfer_no'));

		return true;

	}

	public function sent_item($transfer_no){

			$sql     = "
					SELECT * FROM item_transfer_main a
					INNER JOIN item_transfer_details b
					ON (a.id = b.transfer_id)
					WHERE transfer_no = '$transfer_no'
					;
				";

			$result = $this->db->query($sql);
			$transfer_details = $result->result_array();

			foreach($transfer_details as $row)
			{
						
			$item_no = $row['item_no'];
			$sql     = "SELECT * FROM inventory_master WHERE item_no ='".$item_no."'";
			$result  = $this->db->query($sql);		
			$array   =  $result->row_array();
		

			$current_qty = $array['current_qty'] - $row['request_qty'];
			$transfer_qty = $array['transfer_qty'] + $row['request_qty'];
			if($current_qty >=0)
			{
				$sql     = "
						UPDATE inventory_master set current_qty = '".$current_qty."', transfer_qty =  '".$row['request_qty']."'
						WHERE item_no   = '".$row['item_no']."'
						AND location_id = '".$this->session->userdata('Proj_Code')."'
						AND project_id  = '".$this->session->userdata('Proj_Main')."'
						";

						$this->db->query($sql);


			}else{
				echo "Invalid Request Qty";
			}

		}


	}
	

	function approve(){
			$sql = "Update item_transfer_main set approved='True' WHERE id = '".$this->input->post('id')."'";
			$this->db->query($sql);
			return true;
		}

	function cancel($arg){
		$update = array(
			'status'=>$arg['status']
			);
		$this->db->where('id',$arg['id']);
		$this->db->update('item_transfer_main',$update);		
		return true;
	}
	public function changestatus($arg){

		$update = array(
			'request_status'=>$arg['transaction_status']
			);

		$this->db->where('id',$arg['id']);
		$this->db->update('item_transfer_main',$update);

	}
	public function get_receive_item(){
		
		$sql="
		SELECT *,
			(SELECT CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.to_project_id) 'request_to_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = itm.prepared_by
			) 'preparedBy_name'
			FROM item_transfer_main itm
			INNER JOIN (
				SELECT COUNT(item_no)'no_items',transfer_id FROM item_transfer_details GROUP BY transfer_id
			) itd
			ON (itm.id = itd.transfer_id)
			WHERE to_title_id = '".$this->session->userdata('Proj_Main')."' AND to_project_id = '".$this->session->userdata('Proj_Code')."'	 
					;
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}



	public function compute_itemtransfer($transfer_id){


				$sql     = "
					SELECT * FROM item_transfer_main a
					INNER JOIN item_transfer_details b
					ON (a.id = b.transfer_id)
					WHERE a.id = '$transfer_id'
					;
				";

			$result = $this->db->query($sql);
			$transfer_details = $result->result_array();

			foreach($transfer_details as $row)
			{
						
			$item_no = $row['item_no'];
			$sql     = "
						SELECT * FROM inventory_master WHERE item_no ='".$item_no."' 
						AND location_id = '".$this->session->userdata('Proj_Code')."'
						AND project_id  = '".$this->session->userdata('Proj_Main')."'";

			$result  = $this->db->query($sql);
			$array   =  $result->row_array();
			



			if($result->num_rows() == 0 )
			{
				$sql = "
					INSERT INTO `inventory_master`
					            (
					             `item_no`,
					             `location_id`,
					             `project_id`,
					             `receive_qty`,
					             `withdraw_qty`,
					             `transfer_qty`,
					             `current_qty`,
					             `transfered`)
					VALUES (
					        '".$item_no."',
					        '".$this->session->userdata('Proj_Code')."',
					        '".$this->session->userdata('Proj_Main')."',
					        '0',
					        '0',
					        '".$row['request_qty']."',
					        '".$row['request_qty']."',
					        'NO');
				";

				$this->db->query($sql);

			}else
			{
					$current_qty = $array['current_qty'] + $row['request_qty'];
				

					$sql="
							UPDATE `inventory_master`
							SET `transfer_qty` = '".$row['request_qty']."',
							    `current_qty`  = '".$current_qty."'
							WHERE `item_no`    = '".$row['item_no']."'
							AND location_id    = '".$this->session->userdata('Proj_Code')."'
							AND project_id     = '".$this->session->userdata('Proj_Main')."'
					";

					$this->db->query($sql);
					
			}
			
		
		}

		

	}


}
