<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_withdrawal extends CI_Model {

	public function __construct(){
		parent :: __construct();				
		$this->load->model('procurement/md_inventory_master');
	}

	
	function get_cumulative($type = ""){


		$sql = "CALL display_withdraw_main1('".$this->input->post('location')."');";
		
				
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}



	function get_item($type = ""){

		if($type == "7")
		{
			$sql = "CAll display_for_item_withdrawal_tire('".$this->input->post('location')."')";
		}else{
			$sql = "CALL display_for_item_withdrawal_new('".$this->input->post('location')."')";
		}
		
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;		
	
	}


	function get_item_withdrawal(){

		/*$sql = "CALL display_for_item_withdrawal_f(".$this->session->userdata('Proj_Code').");";*/
		/*$sql = "
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
				WHERE inventory_main.project_location_id = '".$this->session->userdata('Proj_Code')."'
				GROUP BY inventory_main.item_no,inventory_main.account_code,inventory_main.division_code )	
				a
				
		";*/

		$sql2 = "
				SELECT 
				im.item_no 'item_no',
				CONCAT(sg.group_description,' - ',sgd.description) 'item_description',
				sgd.account_id,
				sgd.unit_measure 'item_measurement',
				im.current_qty 'Quantity at Hand',
				sgd.unit_cost 'item_cost',
				im.inv_master_id 'inventory_id'
				FROM inventory_master im
				INNER JOIN setup_group_detail sgd
				ON (sgd.group_detail_id = im.item_no)
				INNER JOIN setup_group sg
				ON (sgd.group_id = sg.group_id)
				WHERE im.location_id = '".$this->session->userdata('Proj_Code')."'
		";

		$result = $this->db->query($sql2);
		return $result->result_array();

	}


	function signatory(){
		$sql = "CALL gp_display_employee1()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	

	function division(){
		$sql = "SELECT division_id,division_name FROM division_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}	
	
	function account(){
		$sql = "CALL display_account_setup('2')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
	

	function cost_center(){
		$sql = "SELECT id, itemdescription FROM pay_item ORDER BY itemdescription ASC";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function get_withdraw_main($page = ''){

		$sql2 = "
			SELECT 
			withdraw_main.withdraw_id,
			withdraw_main.withdraw_no 'WS NUMBER',
			withdraw_main.division,
			withdraw_main.account_code,
			withdraw_main.withdraw_person_id,
			(SELECT
			    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			        WHERE `hr_employee`.`emp_number` = withdraw_main.withdraw_person_id
			) 'requested_By',
			(SELECT
			    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			        WHERE `hr_employee`.`emp_number` = withdraw_main.withdraw_person_incharge
			) 'approved_By',
			withdraw_main.withdraw_person_incharge,			
			withdraw_main.remarks,
			withdraw_main.date_withdrawn 'WS DATE',
			withdraw_main.location,
			withdraw_main.status 'WS STATUS',
			COUNT(withdraw_details.withdraw_main_id) 'no_item_withdrawn'	
			FROM withdraw_main
			INNER JOIN withdraw_details
			ON(withdraw_main.withdraw_id = withdraw_details.withdraw_main_id)
			WHERE withdraw_main.location = '".$this->session->userdata('Proj_Code')."'
			GROUP BY withdraw_main.withdraw_id			
			ORDER BY withdraw_main.withdraw_id DESC

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

	function get_withdraw_main_no($no){

		$sql = "
			SELECT 
			withdraw_main.withdraw_id,
			withdraw_main.withdraw_no 'WS NUMBER',
			withdraw_main.division,
			withdraw_main.account_code,
			withdraw_main.withdraw_person_id,
			(SELECT
			    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			        WHERE `hr_employee`.`emp_number` = withdraw_main.withdraw_person_id
			) 'requested_By',
			(SELECT
			    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			        WHERE `hr_employee`.`emp_number` = withdraw_main.withdraw_person_incharge
			) 'approved_By',
			(SELECT
			    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			        WHERE `hr_employee`.`emp_number` = withdraw_main.withdraw_receive_by
			) 'received_By',
			(SELECT CONCAT(project,' - ',project_name) AS 'Project_F' FROM setup_project WHERE setup_project.project_id = withdraw_main.location) 'project',
			(SELECT project_location FROM setup_project WHERE setup_project.project_id = withdraw_main.location) 'project_location',
			withdraw_main.equipment_id,
			withdraw_main.withdraw_person_incharge,			
			withdraw_main.remarks,
			withdraw_main.date_withdrawn 'WS DATE',
			withdraw_main.location,
			withdraw_main.status 'WS STATUS',
			withdraw_main.tenant_name,
			COUNT(withdraw_details.withdraw_main_id) 'no_item_withdrawn',
			withdraw_main.withdraw_receive_by
			FROM withdraw_main
			INNER JOIN withdraw_details
			ON(withdraw_main.withdraw_id = withdraw_details.withdraw_main_id)
			WHERE withdraw_main.location = '".$this->session->userdata('Proj_Code')."'
			AND withdraw_main.withdraw_no = '".$no."'
			GROUP BY withdraw_main.withdraw_id			
			ORDER BY withdraw_main.withdraw_id DESC	
		";

		$result = $this->db->query($sql);
		return $result->row_array();

	}

	public function get_withdraw_details($withdraw_id){
		$sql = "
			SELECT * FROM withdraw_details WHERE withdraw_main_id = '".$withdraw_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}


	function save_withdrawal2(){

		$insert = array(
			'withdraw_no'=>$this->input->post('withdraw_no'),
			'withdraw_person_id'=>$this->input->post('withdraw_person_id'),
			'withdraw_person_incharge'=>$this->input->post('withdraw_person_incharge'),
			'date_withdrawn'=>$this->input->post('date_withdrawn'),
			'remarks'=>$this->input->post('remarks'),
			'location'=>$this->input->post('location'),
			'title_id'=>$this->input->post('title_id'),
			'tenant_id'=>$this->input->post('tenant_id'),
			'tenant_name'=>$this->input->post('tenant_name'),
			);

		$this->db->insert('withdraw_main',$insert);
		$last_id = $this->db->insert_id();
		$details = $this->input->post('details');

		foreach($details as $row){

			$insert_details = array(
				'withdraw_main_id' =>$last_id,
				'item_no'=>$row['item_no'],
				'item_description'=>$row['item_description'],
				'withdrawn_quantity'=>$row['withdraw_qty'],
				'unit_measure'=>$row['unit_measure'],
				'item_cost'=>$row['item_cost'],
				'inventory_id'=>$row['inventory_id'],
				'date_withdrawn'=>$this->input->post('date_withdrawn'),
				'project_location_id'=>$this->input->post('location'),
				'title_id'=>$this->input->post('title_id'),
			);

			$this->db->insert('withdraw_details',$insert_details);	

			/*	
			$insert_inventory = array(
				'item_no'=>$row['item_no'],
				'Item_description'=>$row['item_description'],
				'item_cost'=>$row['item_cost'],
				'supplier_id'=>'0',
				'received_quantity'=>'0',
				'item_measurement'=>$row['unit_measure'],
				'withdrawn_quantity'=>$row['withdraw_qty'],
				'withdraw_no'=>$last_id,
				'registered_no'=>'0',
				'division_code'=>'0', 
				'account_code'=>'0',
				'project_location_id'=>$this->input->post('location'),
				'title_id'=>$this->input->post('title_id'),
				'type'=>'WS',
				);
			
			$this->db->insert('inventory_main',$insert_inventory);
			*/

			$arg['withdraw_qty'] = $row['withdraw_qty'];
			$arg['item_no']      = $row['item_no'];
			$arg['location_id']  = $this->input->post('location');
			$this->md_inventory_master->withdrawal($arg);
			
						
		}

		return true;

	}

	function save_withdrawal(){
		
		$insert = array(
			'withdraw_no'=>$this->input->post('withdraw_no'),
			'division'=>$this->input->post('division'),
			'account_code'=>$this->input->post('account_code'),
			'withdraw_person_id'=>$this->input->post('withdraw_person_id'),
			'withdraw_person_incharge'=>$this->input->post('withdraw_person_incharge'),
			'date_withdrawn'=>$this->input->post('date_withdrawn'),
			'location'=>$this->input->post('location'),
			'title_id'=>$this->input->post('title_id'),
			'remarks'=>$this->input->post('remarks'),
			'withdraw_receive_by'=>$this->input->post('withdraw_receive_by'),
			'db_equipment_id'=>$this->input->post('db_equipment_id'),
			'pay_item'=>$this->input->post('pay_item'),
			);

		$this->db->insert('withdraw_main',$insert);
	
		$sql = "SELECT withdraw_id FROM withdraw_main WHERE withdraw_no = '".$this->input->post('withdraw_no')."'";
		$result = $this->db->query($sql);
		$withdraw_id = $result->row_array();


		foreach ($this->input->post('details') as $key => $value){
						
			$insert_details = array(
				'withdraw_main_id'=>$withdraw_id['withdraw_id'],
				'item_no'=>$value['item_no'],
				'item_description'=>$value['item_description'],
				'withdrawn_quantity'=>$value['withdrawn_qty'],
				'item_cost'=>$value['item_cost'],
				'inventory_id'=>$value['inventory_id'],
				'date_withdrawn'=>$this->input->post('date_withdrawn'),
				'division'=>$this->input->post('division'),
				'account'=>$this->input->post('account'),
				'remarks'=>$this->input->post('remarks'),
				'item_code'=>$this->input->post('i'),
				'project_location_id'=>$this->input->post('location'),
				'title_id'=>$this->input->post('title_id'),
				'db_equipment_id'=>$this->input->post('db_equipment_id'),
				'scope_equip_id'=>'0',
				);
				
				$this->db->insert('withdraw_details',$insert_details);
				$item_check = "";

				if($this->input->post('cost_center')===7){
					$sql    = "SELECT item_no FROM inventory_main WHERE inventory_id = '".$value['inventory_id']."'";
					$result =  $this->db->query($sql);
					$item_no = $result->row_array();
					$item_check = $item_no['item_no'];
				}else{
					$item_check = $value['inventory_id'];
				}

				
			 	$sql = "CALL check_inventory_master('".$item_check."','".$this->input->post('location')."')";
			 	$result = $this->db->query($sql);
			 	$this->db->close();
				$_invIDm = $result->row_array();

				if($_invIDm['inv_master_id'] == ""){
					$sql = "CALL insert_inventory_master ('".$item_check."','".$this->input->post('location')."','".$value['withdrawn_qty']."','0','".$value['withdrawn_qty']."')";	
					$this->db->query($sql);
					$this->db->close();

				}else{
					$sql = "CALL mis_update_inventory_master ('".$item_check."','".$this->input->post('location')."','".$value['withdrawn_qty']."')";
					
					$this->db->query($sql);
					$this->db->close();
				}

				$sql = "UPDATE inventory_main SET `status` = 'UNUSED' WHERE inventory_id = '".$value['inventory_id']."'";
				$this->db->query($sql);

		}

		return true;

	}


	function update_withdrawal(){
		
		$insert = array(
			'withdraw_no'=>$this->input->post('withdraw_no'),
			'division'=>$this->input->post('division'),
			'account_code'=>$this->input->post('account_code'),
			'withdraw_person_id'=>$this->input->post('withdraw_person_id'),
			'withdraw_person_incharge'=>$this->input->post('withdraw_person_incharge'),
			'date_withdrawn'=>$this->input->post('date_withdrawn'),
			'location'=>$this->input->post('location'),
			'title_id'=>$this->input->post('title_id'),
			'remarks'=>$this->input->post('remarks'),
			'withdraw_receive_by'=>$this->input->post('withdraw_receive_by'),
			'db_equipment_id'=>$this->input->post('db_equipment_id'),
			'pay_item'=>$this->input->post('pay_item'),
			);
		$this->db->where('withdraw_id',$this->input->post('withdraw_id'));
		$this->db->update('withdraw_main',$insert);
	
	
		$sql = "DELETE from withdraw_details where withdraw_main_id = '".$this->input->post('withdraw_id')."'";
		$this->db->query($sql);



		foreach ($this->input->post('details') as $key => $value){
						
			$insert_details = array(
				'withdraw_main_id'=>$this->input->post('withdraw_id'),
				'item_no'=>$value['item_no'],
				'item_description'=>$value['item_description'],
				'withdrawn_quantity'=>$value['withdrawn_qty'],
				'item_cost'=>$value['item_cost'],
				'inventory_id'=>$value['inventory_id'],
				'date_withdrawn'=>$this->input->post('date_withdrawn'),
				'division'=>$this->input->post('division'),
				'account'=>$this->input->post('account'),
				'remarks'=>$this->input->post('remarks'),
				'item_code'=>$this->input->post('i'),
				'project_location_id'=>$this->input->post('location'),
				'title_id'=>$this->input->post('title_id'),
				'db_equipment_id'=>$this->input->post('db_equipment_id'),
				'scope_equip_id'=>'0',
				);
				
				$this->db->insert('withdraw_details',$insert_details);
				/*$item_check = "";

				if($this->input->post('cost_center')===7){
					$sql    = "SELECT item_no FROM inventory_main WHERE inventory_id = '".$value['inventory_id']."'";
					$result =  $this->db->query($sql);
					$item_no = $result->row_array();
					$item_check = $item_no['item_no'];
				}else{
					$item_check = $value['inventory_id'];
				}
					
			 	$sql = "CALL check_inventory_master('".$item_check."','".$this->input->post('location')."')";
			 	$result = $this->db->query($sql);
			 	$this->db->close();
				$_invIDm = $result->row_array();

				if($_invIDm['inv_master_id'] == ""){
					$sql = "CALL insert_inventory_master ('".$item_check."','".$this->input->post('location')."','".$value['withdrawn_qty']."','0','".$value['withdrawn_qty']."')";	
					$this->db->query($sql);
					$this->db->close();

				}else{
					$sql = "CALL mis_update_inventory_master ('".$item_check."','".$this->input->post('location')."','".$value['withdrawn_qty']."')";
					
					$this->db->query($sql);
					$this->db->close();
				}

				$sql = "UPDATE inventory_main SET `status` = 'UNUSED' WHERE inventory_id = '".$value['inventory_id']."'";
				$this->db->query($sql);*/

		}

		return true;

	}


	public function get_details($withdraw_id){
		$sql = "SELECT inventory_id,item_no,item_description,withdrawn_quantity AS 'withdrawn_qty',item_cost FROM withdraw_details WHERE withdraw_main_id = '".$withdraw_id."'";
		$result = $this->db->query($sql);
		return $result;
	}


	public function get_main($withdraw_id){
		$sql = "SELECT * FROM withdraw_main where withdraw_id = '".$withdraw_id."'";
		$result = $this->db->query($sql);
		return $result->row_array();		
	}
	
	public function get_request_main($id){
		$sql = "SELECT
					*,
					(SELECT project_name FROM setup_project WHERE setup_project.project_id = item_request_main.project_id) 'project_name',
					(SELECT project_location FROM setup_project WHERE setup_project.project_id = item_request_main.project_id) 'project_location'
				FROM item_request_main
				WHERE id = '{$id}'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->row_array();
		}

	}

}