<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_item_issuance extends CI_Model {

	public function __construct(){
		parent :: __construct();				
	}


	function get_issuance_main($issuance_no){
		$sql = "
				SELECT *,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = iim.prepared_by
				) 'preparedBy_name',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = iim.approved_by
				) 'approvedBy_name'
				FROM item_issuance_main iim
				INNER JOIN  (
				SELECT COUNT(item_no)'no_items',issuance_id FROM item_issuance_details GROUP BY issuance_id
				) iid
				ON (iim.id = iid.issuance_id)
				WHERE issuance_no = '".$issuance_no."'
				AND title_id = '".$this->session->userdata('Proj_Main')."';
		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}

	function get_issuance_details($issuance_id){

		$sql = "
			SELECT * FROM item_issuance_details WHERE issuance_id = '".$issuance_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}
	


	function get_issuance_list($page = ""){

		$sql2 = "
			SELECT *,
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = iim.prepared_by
			) 'preparedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = iim.approved_by
			) 'approvedBy_name'
			FROM item_issuance_main iim
			INNER JOIN  (
			SELECT COUNT(item_no)'no_items',issuance_id FROM item_issuance_details GROUP BY issuance_id
			) iid
			ON (iim.id = iid.issuance_id)
		";
		
		$limit = $this->config->item('limit');

		$start = ($page * $limit) - $limit;
		$next = '';
		$result = $this->db->query($sql2);		
		if($result->num_rows() > ($page * $limit) ){

			$next = ++$page;
		}

		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;

	}

	function save_issuance(){

		$insert = array(
			'issuance_no'=>$this->input->post('issuance_no'),
			'prepared_by'=>$this->input->post('prepared_by'),
			'approved_by'=>$this->input->post('approved_by'),
			'issued_to'=>$this->input->post('issued_to'),
			'date_issued'=>$this->input->post('date_issued'),
			'remarks'=>$this->input->post('remarks'),
			'project_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			);

		$this->db->insert('item_issuance_main',$insert);		
		$last_id = $this->db->insert_id();
		$details = $this->input->post('details');
		foreach($details as $row){

			$insert_details = array(
				'issuance_id'=>$last_id,
				'item_no'=>$row['item_no'],
				'item_description'=>$row['item_description'],
				'unit_measure'=>$row['unit_measure'],
				'issued_qty'=>$row['issued_qty'],
				'project_id'=>$this->session->userdata('Proj_Code'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				);
			$this->db->insert('item_issuance_details',$insert_details);
						
		}

		return true;

	}


	public function get_all_item_issuance_power($location_id){

		if(empty($location_id)){
			$location_id = $this->session->userdata('Proj_Code');
		}

		$sql = "
			SELECT 
				iid.item_no,
				iid.item_description,
				item.group_description,
				item.group_id,
				iid.issued_qty
				FROM item_issuance_main iim
				INNER JOIN item_issuance_details iid
				ON (iim.id = iid.issuance_id)
				LEFT JOIN (
					SELECT 
					sgd.group_detail_id 'item_no',	
					sgd.description,
					sgd.unit_measure,
					sgd.group_id,
					sg.group_description
					FROM setup_group_detail sgd
					LEFT JOIN setup_group sg
					ON (sgd.group_id = sg.group_id)
				)item
				ON (item.item_no = iid.item_no)
				WHERE iim.project_id = '".$location_id."'
				GROUP BY item.group_id;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_all_item_issuance($location_id,$group_id){

		$sql = "
			SELECT 
			iid.item_no,
			iid.item_description 'item_name',
			item.group_description,
			item.group_id,
			item.unit_measure,
			sum(iid.issued_qty) 'issued_qty'
			FROM item_issuance_main iim
			INNER JOIN item_issuance_details iid
			ON (iim.id = iid.issuance_id)
			LEFT JOIN (
				SELECT 
				sgd.group_detail_id 'item_no',
				sgd.description,
				sgd.unit_measure,
				sgd.group_id,
				sg.group_description
				FROM setup_group_detail sgd
				LEFT JOIN setup_group sg
				ON (sgd.group_id = sg.group_id)
			)item
			ON (item.item_no = iid.item_no)
			WHERE iim.project_id = '".$location_id."'
			AND item.group_id like '".$group_id."'
			GROUP BY item.group_id;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}
		

	public function update($arg){

		if(count($arg['data']) > 0)
		{
			$bool = false;
			foreach($arg['data'] as $row)
			{
				
				if($row['issued_qty'] == $row['return_qty']){
					$bool = true;
				}else{
					$bool = false;
				}

				$return_qty = $row['return_qty'];
				$item_no = $row['item_no'];
				$id = $arg['is_id'];
				$project = $this->session->userdata('Proj_Code');

				$sql = "UPDATE item_issuance_details SET issued_qty = issued_qty - '{$return_qty}' WHERE item_no = '{$item_no}' AND issuance_id = '{$id}'";
				$this->db->query($sql);

				$sql = "UPDATE inventory_master SET withdraw_qty = withdraw_qty - '{$return_qty}', current_qty = current_qty + '{$return_qty}' WHERE item_no = '{$item_no}' AND project_id = '{$project}' AND location_id = '{$project}'";
				$this->db->query($sql);
			
			}

			if($bool){

				$update = array(
					'status'=>'RETURNED'
				);

			}else{

				$update = array(
					'status'=>'PARTIAL RETURN'
				);

			}
				$this->db->where('id',$arg['is_id']);
				$this->db->update('item_issuance_main',$update);


			return true;
		}
		

	}



}