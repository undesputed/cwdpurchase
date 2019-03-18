<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_item_request extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_series_no(){
		$today = date('Y-m-d');

		$sql = "SELECT 
					withdraw_no 'ws_no'
				FROM withdraw_main
				WHERE 
				SUBSTRING(withdraw_no,9,2) = SUBSTRING('{$today}',6,2)
				AND SUBSTRING(withdraw_no,4,4) = YEAR('{$today}')
				ORDER BY withdraw_id DESC
				LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$jo_no = $row['jo_no'];
				$sq = "SELECT IF((@r:=CAST(SUBSTRING('{$ws_no}',12,LENGTH('{$ws_no}') - 8) AS UNSIGNED) + 1) < 1000,LPAD(@r,3,'0'),@r) AS 'ws_no'";
				$qry = $this->db->query($sq);

				foreach($qry->result_array() as $rw){
					return 'WS-'.date('Y').'-'.date('m').'-'.$rw['jo_no'];
				}
			}
		}else{
			return 'WS-'.date('Y').'-'.date('m').'-001';
		}
	}

	function get_main($transfer_no){
		$sql = "
			SELECT *,
			(SELECT CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE project_id = itm.to_project_id) 'request_to_name',
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
			FROM item_request_main itm
			INNER JOIN (
				SELECT COUNT(item_no)'no_items',transfer_id FROM item_request_details GROUP BY transfer_id
			) itd
			ON (itm.id = itd.transfer_id)
			WHERE transfer_no = '".$transfer_no."';
		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}

	function get_details($transfer_id){		
		$sql = "
			SELECT * FROM item_request_details WHERE transfer_id = '".$transfer_id."'
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}
		public function save_request(){
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

		$this->db->insert('item_request_main',$insert);		
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
			
			$this->db->insert('item_request_details',$insert_details);
						
		}

		return true;
	}

	public function status_change($arg){

		$update = array(
			'request_status'=>$arg['transaction_status']
			);

		$this->db->where('id',$arg['id']);
		$this->db->update('item_request_main',$update);

	}
	public function get_list(){
			$sql="
					SELECT
					*,
					current_qty 'stocks'
					FROM 
					(
						SELECT 
						irm.*,
						item_no,
						request_qty,
						item_description,
						unit_measure
						FROM item_request_main irm
						INNER JOIN item_request_details ird
						ON (irm.id = ird.transfer_id)
						WHERE to_project_id = '".$this->session->userdata('Proj_Code')."'
					)a
					LEFT JOIN inventory_master b
					ON (a.to_project_id = b.location_id AND a.item_no = b.item_no)
			";
			
			$result = $this->db->query($sql);
			return $result->result_array();
	}
	public function get_item_request(){

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
			FROM item_request_main itm
			INNER JOIN (
				SELECT COUNT(item_no)'no_items',transfer_id FROM item_request_details GROUP BY transfer_id
			) itd
			ON (itm.id = itd.transfer_id)
			WHERE title_id = '".$this->session->userdata('Proj_Main')."' AND ( project_id = '".$this->session->userdata('Proj_Code')."' OR to_project_id = '".$this->session->userdata('Proj_Code')."' )
			ORDER BY transaction_date desc			
			;
		";
		$result = $this->db->query($sql);
		return $result->result_array();
		}


	  public function get_request_list(){
		$sql="

				SELECT 
				*
				FROM (
					SELECT
					a.group_detail_id 'item_no',
					CONCAT(a.group_description,' - ',a.description) 'item_description',
					a.description,
					a.unit_measure 'item_measurement',
					IFNULL(im.current_qty,0) 'Quantity at Hand',
					a.unit_cost 'item_cost',
					im.inv_master_id 'inventory_id'
					FROM (
						SELECT
						sgd.*,
						sg.group_description
						FROM 
						setup_group_detail sgd
						INNER JOIN setup_group sg
						ON (sgd.group_id = sg.group_id)
					) a
					LEFT JOIN (SELECT * FROM inventory_master WHERE location_id = '".$this->session->userdata('Proj_Code')."') im
					ON (a.group_detail_id = im.item_no) 
				) a 
				ORDER BY a.item_description ASC				
		";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}



	public function get_request_list_item($location_id){
		/*$sql="

				SELECT 
				*
				FROM (
					SELECT
					a.group_detail_id 'item_no',
					CONCAT(a.group_description,' - ',a.description) 'item_description',
					a.description,
					a.unit_measure 'item_measurement',
					IFNULL(im.current_qty,0) 'Quantity at Hand',
					a.unit_cost 'item_cost',
					im.inv_master_id 'inventory_id'
					FROM (
						SELECT
						sgd.*,
						sg.group_description
						FROM 
						setup_group_detail sgd
						INNER JOIN setup_group sg
						ON (sgd.group_id = sg.group_id)
					) a
					INNER JOIN (SELECT * FROM inventory_master WHERE location_id = '".$location_id."') im
					ON (a.group_detail_id = im.item_no) 
				) a 
				ORDER BY a.item_description ASC				
		";*/
		$year = date('Y');
		$sql = "SELECT
					setup_group_detail.group_detail_id 'item_no',
					CONCAT(setup_group.group_description, ' - ',setup_group_detail.description) 'item_description',
					setup_group_detail.unit_measure 'item_measurement',
					setup_group_detail.unit_cost 'item_cost',
					setup_group_detail.description,
					(SELECT SUM(debit) - SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = setup_group_detail.group_detail_id AND inventory_stock_card.location_id = '{$location_id}' AND inventory_stock_card.year = '{$year}') 'Quantity at Hand'
				FROM setup_group_detail
				INNER JOIN setup_group
				ON (setup_group.group_id = setup_group_detail.group_id)
				WHERE setup_group_detail.item_status = 'ACTIVE'
				AND (SELECT SUM(debit) - SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = setup_group_detail.group_detail_id AND inventory_stock_card.location_id = '{$location_id}' AND inventory_stock_card.year = '{$year}') > '0'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_withdraw_list_item(){
		/*$sql="

				SELECT 
				*
				FROM (
					SELECT
					a.group_detail_id 'item_no',
					CONCAT(a.group_description,' - ',a.description) 'item_description',
					a.description,
					a.unit_measure 'item_measurement',
					IFNULL(im.current_qty,0) 'Quantity at Hand',
					a.unit_cost 'item_cost',
					im.inv_master_id 'inventory_id'
					FROM (
						SELECT
						sgd.*,
						sg.group_description
						FROM 
						setup_group_detail sgd
						INNER JOIN setup_group sg
						ON (sgd.group_id = sg.group_id)
					) a
					INNER JOIN (SELECT * FROM inventory_master WHERE location_id = '".$this->session->userdata('Proj_Code')."') im
					ON (a.group_detail_id = im.item_no) 
				) a 
				ORDER BY a.item_description ASC				
		";*/
		$year = date('Y');
		$location_id = $this->session->userdata('Proj_Code');
		$sql = "SELECT
					setup_group_detail.group_detail_id 'item_no',
					CONCAT(setup_group.group_description, ' - ',setup_group_detail.description) 'item_description',
					setup_group_detail.unit_measure 'item_measurement',
					setup_group_detail.unit_cost 'item_cost',
					setup_group_detail.description,
					'' AS 'inventory_id',
					(SELECT SUM(debit) - SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = setup_group_detail.group_detail_id AND inventory_stock_card.location_id = '{$location_id}' AND inventory_stock_card.year = '{$year}') 'Quantity at Hand'
				FROM setup_group_detail
				INNER JOIN setup_group
				ON (setup_group.group_id = setup_group_detail.group_id)
				WHERE setup_group_detail.item_status = 'ACTIVE'
				AND (SELECT SUM(debit) - SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = setup_group_detail.group_detail_id AND inventory_stock_card.location_id = '{$location_id}' AND inventory_stock_card.year = '{$year}') > '0'";


		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function save_withdraw($arg){
		$this->db->select('*');
		$this->db->from('item_request_main');
		$this->db->where('id',$arg['id']);
		$query = $this->db->get();

		$remarks = "";
		$project_id = "";
		foreach($query->result_array() as $row){
			$remarks = $row['remarks'];
			$project_id = $row['project_id'];
		}

		$insert = array(
				'withdraw_no' => $this->get_series_no(),
				'status' => 'APPROVED',
				'date_withdrawn' => date('Y-m-d'),
				'withdraw_person_id' => $this->session->userdata('emp_id'),
				'location' => $this->session->userdata('Proj_Code'),
				'equipment_id' => $arg['id'],
				'remarks' => $remarks,
				'title_id' => $this->session->userdata('Proj_Main')
			);
		$this->db->insert('withdraw_main',$insert);		
		
		$last_id = $this->db->insert_id();

		$this->db->select('withdraw_no');
		$this->db->from('withdraw_main');
		$this->db->where('withdraw_id',$last_id);
		$query = $this->db->get();

		$withdraw_no = "";
		foreach($query->result_array() as $row){
			$withdraw_no = $row['withdraw_no'];
		}

		$this->db->select('*');
		$this->db->from('item_request_details');
		$this->db->where('transfer_id',$arg['id']);
		$details = $this->db->get();

		foreach($details->result_array() as $row){
						
			$insert_details = array(
				'withdraw_main_id' => $last_id,
				'item_no' => $row['item_no'],
				'item_description' => $row['item_description'],
				'unit_measure' => $row['unit_measure'],
				'withdrawn_quantity' => $row['request_qty'],
				'date_withdrawn' => date('Y-m-d'),
				'project_location_id' => $this->session->userdata('Proj_Code'),
				'title_id' => $this->session->userdata('Proj_Main')
				);
			
			$this->db->insert('withdraw_details',$insert_details);

			$inserts = array(
						'item_id' => $row['item_no'],
						'location_id' => $this->session->userdata('Proj_Code'),
						'debit' => '0',
						'credit' => $row['request_qty'],
						'type' => 'WITHDRAW',
						'year' => date('Y'),
						'trans_id' => $last_id,
						'trans_date' => date('Y-m-d'),
						'reference_no' => $withdraw_no,
						'emp_id' => $this->session->userdata('emp_id'),
						'office_id' => $project_id
				);
			$this->db->insert('inventory_stock_card',$inserts);
						
		}

		return true;
	}





	
}