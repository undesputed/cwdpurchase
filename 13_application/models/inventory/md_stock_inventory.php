<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_inventory extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_beginning($location_id = ''){

		if(empty($location_id)){
			$location_id = $this->session->userdata('Proj_Code');
		}
		
		/*$sql = "
			SELECT
				*
				FROM inventory_main im 
				JOIN
				(
					SELECT
					MAX(inventory_id)'inventory_id'
					FROM inventory_main
					WHERE project_location_id = '".$location_id."'
					    AND `type` = 'DR'
					GROUP BY item_no
				) AS t
				ON (im.inventory_id = t.inventory_id)
		";
		*/

		/*$sql = "
				SELECT
					im.inventory_id,
					sgd.item_no,
					sgd.item_description,
					im.item_cost,
					sgd.unit_measure 'item_measurement',
					im.supplier_id,
					im.received_quantity,
					im.withdrawn_quantity,
					im.receipt_no,
					im.withdraw_no,
					im.registered_no,
					im.division_code,
					im.account_code,
					im.tstamp,
					im.claim_status,
					im.item_code,
					im.project_location_id,
					im.title_id,
					im.status,
					im.type,
					im.inventory_no,
					im.prepared_by,
					im.received_by,
					im.location_remarks,
					im.reference_no,
					im.property_no,
					im.remarks1,
					im.current_quantity,
					im.inventory_id
					FROM inventory_main im 
					JOIN
					(
						SELECT
						MAX(inventory_id)'inventory_id'
						FROM inventory_main
						WHERE project_location_id = '".$location_id."'
									    AND `type` = 'DR'
						GROUP BY item_no
					) AS t
					ON (im.inventory_id = t.inventory_id)
					INNER JOIN (
							SELECT 
							CONCAT(sg.group_description,' - ',sgd.description) 'item_description',
							sgd.group_detail_id 'item_no',
							sgd.unit_measure
							FROM setup_group_detail sgd
							INNER JOIN setup_group sg
							ON (sgd.group_id = sg.group_id)
						)sgd
					ON (im.item_no = sgd.item_no);
							
		";*/
		$year = date('Y');
		$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			sgd.description 'item_name',
			sg.group_id,
			sg.group_description,
			sgd.unit_measure,
			(SELECT SUM(debit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_debit',
			(SELECT SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_credit'
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			WHERE sgd.item_status = 'ACTIVE'
			AND sgd.type = 'STOCK'			
			ORDER BY item_name ASC,group_description ASC 
			;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_inventory($arg){
		$location_id = "";
		$item_id = "";
		$where = "";

		if(empty($arg[0])){
			$location_id = $this->session->userdata('Proj_Code');
		}else{
			$location_id = $arg;
		}

		/*if(!empty($arg[1])){
			$item_id = $arg[1];
			$where = " AND im.item_no = '{$item_id}'";
		}*/

		/*$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
			sg.group_id,
			sg.group_description,
			sg.setup_group_head,
			sgd.unit_measure,
			im.receive_qty,
			im.withdraw_qty,
			im.current_qty,
			im.location_id,
			im.project_id,
			im.transfer_qty
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			INNER JOIN inventory_master im
			ON (im.item_no = sgd.group_detail_id)
			WHERE im.location_id = '".$location_id."'
			{$where}
			ORDER BY item_name ASC,group_description ASC 
		";*/

		$year = date('Y');

		$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
			sg.group_id,
			sg.group_description,
			sg.setup_group_head,
			sgd.unit_measure,
			(SELECT SUM(debit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_debit',
			(SELECT SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_credit'
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			ORDER BY item_name ASC,group_description ASC 
		";

		$result = $this->db->query($sql);
		
		return $result->result_array();

	}


	public function get_inventory_power($arg){

		$location_id = "";
		$item_id = "";
		$where = "";

		if(empty($arg[0])){
			$location_id = $this->session->userdata('Proj_Code');
		}else{
			$location_id = $this->uri->segment(3);
		}

		if(!empty($arg[1])){
			$item_id = $arg[1];
			$where = " WHERE sgd.group_detail_id = '{$item_id}'";
		}

		/*$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
			sg.group_id,
			sg.group_description,
			sgd.unit_measure,
			im.receive_qty,
			im.withdraw_qty,
			im.current_qty,
			im.location_id,
			im.project_id
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			INNER JOIN inventory_master im
			ON (im.item_no = sgd.group_detail_id)
			WHERE im.location_id = '".$location_id."'
			{$where}
			GROUP BY sg.group_id
			ORDER BY item_name ASC,group_description ASC 
			;
		";*/

		$year = date('Y');

		$sql = "SELECT
					*
				FROM setup_group";

		$result = $this->db->query($sql);	

		return $result->result_array();

	}


	public function get_inventory_items($location_id,$group_id){

		if(empty($location_id)){
			$location_id = $this->session->userdata('Proj_Code');
		}

		$year = date('Y');

		/*$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
			sg.group_id,
			sg.group_description,
			sgd.unit_measure,
			im.receive_qty,
			im.withdraw_qty,
			im.current_qty,
			im.location_id,
			im.project_id,
			im.transfer_qty
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			INNER JOIN inventory_master im
			ON (im.item_no = sgd.group_detail_id)
			WHERE im.location_id = '".$location_id."' AND sg.group_id like '".$group_id."'			
			ORDER BY item_name ASC,group_description ASC 
			;
		";*/

		$sql = "
			SELECT 
			sgd.group_detail_id 'item_no',
			CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
			sg.group_id,
			sg.group_description,
			sgd.unit_measure,
			(SELECT SUM(debit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_debit',
			(SELECT SUM(credit) FROM inventory_stock_card WHERE inventory_stock_card.item_id = sgd.group_detail_id AND year = '{$year}' AND location_id = '{$location_id}') 'total_credit'
			FROM setup_group_detail sgd
			INNER JOIN setup_group sg
			ON (sgd.group_id = sg.group_id)
			WHERE sgd.item_status = 'ACTIVE'
			AND sgd.type = 'STOCK'			
			ORDER BY item_name ASC,group_description ASC 
			;
		";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}
	
	public function get_items($location){
		/*$sql = "CALL display_sms_F_Nacky('".$location."')";*/

		$sql = "
			SELECT 
			a.inventory_id,
			a.item_no,
			a.item_description,
			a.item_cost,
			a.item_measurement,
			a.`Received Quantity`,
			a.withdrawn_quantity,
			a.receipt_no,
			a.withdraw_no,
			a.registered_no,
			a.division_code,
			a.account_code,
			a.tstamp,
			a.claim_status,
			a.`item_code`,
			a.`Group ID`,
			a.project_location_id,
			(a.`Received Quantity` - IFNULL(a.withdrawn_quantity,0) - IFNULL(a.`Transferred Amount`,0)) AS 'Quantity at Hand'
			FROM(
				SELECT
				  inventory_main.inventory_id,
				  inventory_main.item_no,
				  inventory_main.item_description,
				  inventory_main.item_cost,
				  inventory_main.item_measurement,
				  SUM(inventory_main.received_quantity) 'Received Quantity',
				  SUM(inventory_main.withdrawn_quantity) 'withdrawn_quantity',
				  inventory_main.receipt_no,
				  inventory_main.withdraw_no,
				  inventory_main.registered_no,
				  inventory_main.division_code,
				  inventory_main.account_code,
				  inventory_main.tstamp,
				  inventory_main.claim_status,
				  (SELECT item_code FROM inventory_setup WHERE item_no = inventory_main.item_no LIMIT 1) AS 'item_code',
				  (SELECT group_id FROM setup_group_detail WHERE group_detail_id = inventory_main.item_no) AS 'Group ID',
				  inventory_main.project_location_id,
				  (SELECT SUM(withdraw_details.withdrawn_quantity) FROM withdraw_details WHERE withdraw_details.item_no = inventory_main.item_no AND withdraw_details.division = inventory_main.division_code AND withdraw_details.account = inventory_main.account_code AND withdraw_details.project_location_id) AS 'Withdrawn Amount',
				  (SELECT SUM(quantity) FROM equipment_request_details WHERE equipment_request_details.item_no = inventory_main.item_no AND equipment_request_details.division = inventory_main.division_code AND equipment_request_details.account = inventory_main.account_code AND equipment_request_details.project_id = inventory_main.project_location_id) AS 'Transferred Amount'
				FROM inventory_main
				WHERE inventory_main.project_location_id = '".$location."'
				GROUP BY inventory_main.item_no,inventory_main.account_code,inventory_main.division_code )	
				a
		";

		$result = $this->db->query($sql);
		$this->db->close();		
		return $result;
	}


	public function get_details($item_id,$location,$project){
		
		/*$sql = "CALL display_details_sms_report('".$item_id."','".$location."','".$project."');";*/

		/*$sql = "CALL display_details_sms_report_ben('".$item_id."','".$location."','".$project."')";*/

		$year = date('Y');
		$sql = "SELECT * FROM inventory_stock_card WHERE item_id = '{$item_id}' AND location_id = '{$location}' AND year = '{$year}' ORDER BY id ASC";


		$result = $this->db->query($sql);		
		return $result;

	}


	public function get_withdrawal_history($item_id,$location,$project){

		$sql = "
			SELECT
				withdraw_main.withdraw_no AS 'Reference No',
				withdraw_main.tstamp AS 'Date Received',
				withdraw_details.remarks AS 'Remarks',
				'' AS 'Prev Quantity',
				'MINUS' AS 'Legend',
				withdrawn_quantity AS 'Quantity on Hand',
				'' AS 'Balance'
			FROM
			withdraw_details
			INNER JOIN withdraw_main
			ON(withdraw_details.withdraw_main_id = withdraw_main.withdraw_id)
			WHERE withdraw_details.item_no = '".$item_id."'
			AND withdraw_main.location = '".$location."'
			AND withdraw_main.status <> 'CANCELLED'
			AND withdraw_main.title_id = '".$project."'
		";		
		$result = $this->db->query($sql);
		return $result;
	}

	public function get_issuance_history($item_id,$location,$project){

		$sql = "
			SELECT
			issuance_no 'Reference No',
			date_issued 'Date Received',
			'' AS 'Prev Quantity',
			'' AS 'Legend',
			SUM(iid.issued_qty) 'Quantity on Hand',
			'' AS 'Balance'
			FROM item_issuance_main iim
			INNER JOIN item_issuance_details iid
			ON (iim.id = iid.issuance_id)
			WHERE iid.item_no = '".$item_id."'
			AND iim.project_id = '".$location."'
			AND iim.title_id = '".$project."'
			GROUP BY iim.id;
		";

		$result = $this->db->query($sql);
		return $result;

	}
		

	public function update_stock_inventory(){		
		$sql = "SELECT * FROM inventory_setup WHERE item_no = '".$this->input->post('item_no')."' AND division_code = '".$this->input->post('division_code')."' AND account_code = '" .$this->input->post('account'). "' AND project_location = '" .$this->input->post('project_location'). "'";		
		$result = $this->db->query($sql);		

		if($result->num_rows <= 0){

			$insert = array(
				'item_no'=>$this->input->post('item_no'),
				'item_code'=>$this->input->post('item_code'),
				'maximum_amount'=>$this->input->post('maximum_amount'),
				'minimum_amount'=>$this->input->post('minimum_amount'),
				'remarks'=>$this->input->post('remarks'),
				'division_code'=>$this->input->post('division_code'),
				'account_code'=>$this->input->post('account'),
				'project_location'=>$this->input->post('project_location'),
				'serial_no'=>$this->input->post('serial_no'),
				);
			$this->db->insert('inventory_setup',$insert);		
			

		}else{

			$insert = array(
				'item_no'=>$this->input->post('item_no'),
				'item_code'=>$this->input->post('item_code'),
				'maximum_amount'=>$this->input->post('maximum_amount'),
				'minimum_amount'=>$this->input->post('minimum_amount'),
				'remarks'=>$this->input->post('remarks'),
				'division_code'=>$this->input->post('division_code'),
				'account_code'=>$this->input->post('account'),
				'project_location'=>$this->input->post('project_location'),
				'serial_no'=>$this->input->post('serial_no'),
				);

			$where = array(
				'item_no'=>$this->input->post('item_no'),
				'division_code'=>$this->input->post('division_code'),
				'account_code'=>$this->input->post('account'),
				'project_location'=>$this->input->post('project_location'),
			);

			$this->db->where($where);
			$this->db->update('inventory_setup',$insert);
			
		}
		return true;

	}	


	public function project_withdrawal($location_id){

		$sql = "
			SELECT 
			wm.withdraw_id,
			wm.location,
			wm.title_id,
			wd.item_no,
			wd.item_description,
			SUM(wd.withdrawn_quantity)'withdraw_quantity'
			FROM withdraw_main wm
			INNER JOIN withdraw_details wd
			ON (wm.withdraw_id = wd.withdraw_main_id)
			WHERE wm.location = '".$location_id."'
			GROUP BY item_no;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_item($id){
		$this->db->select("*,(SELECT group_description FROM setup_group WHERE setup_group.group_id = setup_group_detail.group_id) 'group_description'");
		$this->db->from('setup_group_detail');
		$this->db->where('group_detail_id',$id);
		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

	public function get_inventory_stock_card($arg){
		$item_id = $arg['item_id'];
		$location_id = $arg['location_id'];

		$sql = "SELECT
					*,
					(SELECT project_name FROM setup_project WHERE project_id = office_id) 'office'
				FROM inventory_stock_card
				WHERE item_id = '{$item_id}'
				AND location_id = '{$location_id}'
				ORDER BY id ASC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

}