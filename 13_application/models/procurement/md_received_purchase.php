<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_received_purchase extends CI_Model {

	public function __construct(){
		parent :: __construct();				

		$this->load->model('procurement/md_inventory_master');
		$this->load->model('procurement/md_accounting');

	}
	
	public function get_po($location){
		$sql = "CALL display_receiving_po_list('%','".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;
	}
		
	function get_po_details($po_id){
		$sql = "CALL display_receiving_po_details(?)";
		$result = $this->db->query($sql,array($po_id));
		$this->db->close();
		return $result->result_array();
	}

	function get_po_details_edit($po_id){
		$sql = "SELECT 
		purchase_order_main.po_id,
		purchase_order_main.po_number,
		DATE_FORMAT(purchase_order_main.po_date,'%m/%d/%Y') AS po_date, 
		purchase_order_main.supplierID,
		purchase_order_details.itemNo,
		purchase_order_details.item_name,
		purchase_order_details.quantity,
		purchase_order_details.unit_msr,
		purchase_order_details.unit_cost,
		purchase_order_details.total_unitcost,
		purchaserequest_main.pr_id,
		purchaserequest_main.accountCode,
		account_setup.account_description,
		account_setup.classification_code,
		account_setup.sub_class_code,
		purchaserequest_main.department,
		purchase_order_main.project_id
		FROM 
		purchase_order_main
		INNER JOIN purchase_order_details
		ON(purchase_order_main.po_id = purchase_order_details.po_id)
		INNER JOIN purchaserequest_main
		ON(purchase_order_main.pr_id = purchaserequest_main.pr_id)
		INNER JOIN account_setup
		ON(purchaserequest_main.account_id = account_setup.account_id)
		WHERE purchase_order_main.po_id = '".$po_id."'";

		$result = $this->db->query($sql);		

		$this->db->close();
		return $result->result_array();

	}

	function get_rr_main($rr_id){
		$sql = "SELECT * FROM receiving_main WHERE receipt_id = '".$rr_id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}


	function cumulative_main($profit_center){

		$date = '';
		$type = 'all';
		$result = null;
		if($type=='month'){
			$sql = "CALL display_receiving_main_monthly(?,?)";
			$result = $this->db->query($sql,array($date,$profit_center));
		}else{
			$sql = "CALL display_receiving_main(?)";
			$result = $this->db->query($sql,array($profit_center));
		}
		return $result;

	}


	function cumulative_details($rr_id){
		$sql = "CALL display_received_details('".$rr_id."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function save_receiving(){

		$data = array(
			'receipt_no'=>$this->input->post('receipt_no'),
			'supplier_id'=>$this->input->post('supplier_id'),
			'employee_receiver_id'=>$this->input->post('employee_receiver_id'),
			'employee_checker_id'=>$this->input->post('employee_checker_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'date_received'=>$this->input->post('date_received'),
			'project_id'=>$this->input->post('project_id'),
			'supplier_invoice'=>$this->input->post('supplier_invoice'),
			'title_id'=>$this->input->post('title_id'),
			'posted_by'=>$this->input->post('posted_by'),
			'invoice_date'=>$this->input->post('invoice_date'),
			'received_status'=>strtoupper($this->input->post('status')),
		);

		$this->db->insert('receiving_main',$data);

		$sql = "SELECT MAX(receipt_id) as max FROM receiving_main WHERE receipt_no = ? ";
		$result = $this->db->query($sql,array($data['receipt_no']));
		$received_no = $result->row_array();
				
		$details = $this->input->post('details');

		foreach($details as $row){

				$insert = array(
						'receipt_id'=>$received_no['max'],
						'po_id'=>$row['po_id'],
						'item_id'=>$row['itemNo'],
						'item_quantity_ordered'=>$row['quantity'],
						'item_quantity_actual'=>$row['quantity'],
						'item_name_ordered'=>$row['item_name'],
						'item_name_actual'=>$row['item_name'],
						'item_cost_ordered'=>$row['unit_cost'],
						'item_cost_actual'=>$row['unit_cost'],
						'discrepancy'=>$row['discrepancy'],
						'discrepancy_remarks'=>$row['discrepancy_remarks'],
						'po_number'=>$row['po_number'],
						'unit_msr'=>$row['unit_msr'],
						'received'=>'TRUE',
						'item_remarks'=>'',
					);				
				$this->db->insert('receiving_details',$insert);

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
							$row['department'],
							$row['accountCode'],
							$this->input->post('title_id'),
							$this->input->post('project_id'),
					);					
				$this->db->query($sql,$inst);

		}

		if($this->input->post('status')=='Full'){
			$sql = "UPDATE purchase_order_main SET status = 'RECEIVE',po_checker = '1' WHERE po_number = ? ";
			$this->db->query($sql,array($details[0]['po_number']));
		}else{
			$sql = "UPDATE purchase_order_main SET status = 'PARTIAL' WHERE po_number = ?";
			$this->db->query($sql,array($details[0]['po_number']));
		}
		return true;

	}

	/***USED FUNCTION FOR RECEIVING***/
	public function save_receiving_2(){

		$this->db->trans_begin();

		$details = $this->input->post('details');
				
		/*$sql = "SELECT * FROM journal_main WHERE rr_id = '1167'";*/

		$data = array(
			'receipt_no'=>$this->input->post('receipt_no'),
			'supplier_id'=>$this->input->post('supplier_id'),
			'employee_receiver_id'=>$this->input->post('employee_receiver_id'),
			'employee_checker_id'=>$this->input->post('employee_checker_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'date_received'=>$this->input->post('date_received'),
			'project_id'=>$this->input->post('project_id'),
			'supplier_invoice'=>$this->input->post('supplier_invoice'),
			'dr_no'=>$this->input->post('dr_no'),
			'title_id'=>$this->input->post('title_id'),
			'posted_by'=>$this->input->post('posted_by'),
			'invoice_date'=>$this->input->post('invoice_date'),
			'received_status'=>strtoupper($this->input->post('status')),
		);

		$this->db->insert('receiving_main',$data);

		$sql = "SELECT MAX(receipt_id) as max FROM receiving_main WHERE receipt_no = ? ";
		$result = $this->db->query($sql,array($data['receipt_no']));
		$received_no = $result->row_array();


		foreach($details as $row){
			$insert = array(
						'receipt_id'=>$received_no['max'],
						'po_id'=>$row['po_id'],
						'item_id'=>$row['item_id'],
						'item_quantity_ordered'=>$row['item_quantity_ordered'],
						'item_quantity_actual'=>$row['item_quantity_actual'],
						'item_name_ordered'=>$row['item_name_ordered'],
						'item_name_actual'=>$row['item_name_actual'],
						'item_cost_ordered'=>$row['item_cost_ordered'],
						'item_cost_actual'=>$row['item_cost_actual'],
						'discrepancy'=>$row['discrepancy'],
						'discrepancy_remarks'=>$row['discrepancy_remarks'],
						'po_number'=>$row['po_number'],
						'unit_msr'=>$row['unit_msr'],
						'received'=>'TRUE',
						'item_remarks'=>'',
					);
			$this->db->insert('receiving_details',$insert);

			$po_id = $row['po_id'];

			$sq = "SELECT
						from_id
					FROM purchase_order_main
					WHERE po_id = '{$po_id}'";
			$qr = $this->db->query($sq);

			$from_id = '';
			foreach($qr->result_array() as $rw){
				$from_id = $rw['from_id'];
			}

			$insert_inventory = array(
					'item_id' => $row['item_id'],
					'location_id' => $this->session->userdata('Proj_Code'),
					'debit' => $row['item_quantity_actual'],
					'credit' => '0',
					'year' => date('Y'),
					'type' => 'RECEIVING',
					'trans_id' => $received_no['max'],
					'trans_date' => $this->input->post('date_received'),
					'emp_id' => $this->session->userdata('emp_id'),
					'reference_no' => $this->input->post('receipt_no'),
					'office_id' => $from_id
				);
			$this->db->insert('inventory_stock_card',$insert_inventory);

			/*$insert_inventory = array(
				'item_no'=>$row['item_id'],
				'item_description'=>$row['item_name_ordered'],
				'item_cost'=>$row['item_cost_actual'],
				'item_measurement'=>$row['unit_msr'],
				'supplier_id'=>0,
				'received_quantity'=>$row['item_quantity_actual'],
				'withdrawn_quantity'=>0,
				'receipt_no'=>$received_no['max'],
				'withdraw_no'=>0,
				'registered_no'=>0,
				'division_code'=>0,
				'account_code'=>0,
				'project_location_id'=>$this->session->userdata('Proj_Code'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				);

			$this->db->insert('inventory_main',$insert_inventory);

			 $arg['receive_qty'] = $row['item_quantity_actual'];
			 $arg['item_no']     = $row['item_id'];
			 $arg['location_id'] = $this->session->userdata('Proj_Code');
			 $arg['project_id'] = $this->session->userdata('Proj_Main');
			 $this->md_inventory_master->received($arg);*/

		}

		if(strtoupper($this->input->post('status'))=='COMPLETE'){
			$sql = "UPDATE purchase_order_main SET status = 'COMPLETE',po_checker = '1' WHERE po_number = ? ";
			$this->db->query($sql,array($details[0]['po_number']));
		}else{
			$sql = "UPDATE purchase_order_main SET status = 'PARTIAL' WHERE po_number = ?";
			$this->db->query($sql,array($details[0]['po_number']));
		}
		

		$po_main    = $this->getBypo_id($details[0]['po_id']);
		$po_details = $this->getBypo_id_details($details[0]['po_id']);		
		$rr_details = $this->get_rr_details_group($received_no['max']);

		
		if(!$this->md_accounting->journal_entry($po_main,$rr_details,$received_no['max'])){
			echo "FAILED";
		}

		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return true;
		}		

	}



	public function save_receiving_3($arg){

		$this->db->trans_begin();

		$data = array(
			'receipt_no'=>$arg['receipt_no'],
			'supplier_id'=>$arg['supplier_id'],
			'employee_receiver_id'=>$arg['employee_receiver_id'],
			'employee_checker_id'=>$arg['employee_checker_id'],
			'delivered_by'=>$arg['delivered_by'],
			'date_received'=>$arg['date_received'],
			'project_id'=>$arg['project_id'],
			'supplier_invoice'=>$arg['supplier_invoice'],
			'title_id'=>$arg['title_id'],
			'posted_by'=>$arg['posted_by'],
			'invoice_date'=>$arg['invoice_date'],
			'received_status'=>strtoupper($arg['status']),
		);

		$this->db->insert('receiving_main',$data);

		$sql         = "SELECT MAX(receipt_id) as max FROM receiving_main WHERE receipt_no = ? ";
		$result      = $this->db->query($sql,array($data['receipt_no']));
		$received_no = $result->row_array();

		$details = $arg['details'];
		foreach($details as $row){
			$insert = array(
						'receipt_id'=>$received_no['max'],
						'po_id'=>$row['po_id'],
						'item_id'=>$row['item_id'],
						'item_quantity_ordered'=>$row['item_quantity_ordered'],
						'item_quantity_actual'=>$row['item_quantity_actual'],
						'item_name_ordered'=>$row['item_name_ordered'],
						'item_name_actual'=>$row['item_name_actual'],
						'item_cost_ordered'=>$row['item_cost_ordered'],
						'item_cost_actual'=>$row['item_cost_actual'],
						'discrepancy'=>$row['discrepancy'],
						'discrepancy_remarks'=>$row['discrepancy_remarks'],
						'po_number'=>$row['po_number'],
						'unit_msr'=>$row['unit_msr'],
						'received'=>'TRUE',
						'item_remarks'=>'',
					);
			
			$this->db->insert('receiving_details',$insert);
			
			$insert_inventory = array(
					'item_id' => $row['item_id'],
					'location_id' => $this->session->userdata('Proj_Code'),
					'debit' => $row['item_quantity_actual'],
					'credit' => '0',
					'year' => date('Y'),
					'type' => 'RECEIVING'
				);
			$this->db->insert('inventory_stock_card',$insert_inventory);

			/*$insert_inventory = array(
				'item_no'=>$row['item_id'],
				'item_description'=>$row['item_name_ordered'],
				'item_cost'=>$row['item_cost_actual'],
				'item_measurement'=>$row['unit_msr'],
				'supplier_id'=>0,
				'received_quantity'=>$row['item_quantity_actual'],
				'withdrawn_quantity'=>0,
				'receipt_no'=>$received_no['max'],
				'withdraw_no'=>0,
				'registered_no'=>0,
				'division_code'=>0,
				'account_code'=>0,
				'project_location_id'=>$this->session->userdata('Proj_Code'),
				'title_id'=>$this->session->userdata('Proj_Main'),
				);

			$this->db->insert('inventory_main',$insert_inventory);

			 $arg['receive_qty'] = $row['item_quantity_actual'];
			 $arg['item_no']     = $row['item_id'];
			 $arg['location_id'] = $this->session->userdata('Proj_Code');
			 $arg['project_id'] = $this->session->userdata('Proj_Main');
			 $this->md_inventory_master->received($arg);*/

		}

		$sql = "UPDATE purchase_order_main SET status = 'COMPLETE',po_checker = '1' WHERE po_id = ? ";
		$this->db->query($sql,array($details[0]['po_id']));
		/* if(strtoupper($this->input->post('status'))=='COMPLETE'){
			$sql = "UPDATE purchase_order_main SET status = 'COMPLETE',po_checker = '1' WHERE po_number = ? ";
			$this->db->query($sql,array($details[0]['po_number']));
		}else{
			$sql = "UPDATE purchase_order_main SET status = 'PARTIAL' WHERE po_number = ?";
			$this->db->query($sql,array($details[0]['po_number']));
		}*/
					
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    return false;
		}
		else
		{
		    $this->db->trans_commit();		
		    return $received_no['max'];
		}	

	}

	
	public function update_receiving(){
		
		$data = array(
			'receipt_no'=>$this->input->post('receipt_no'),
			'supplier_id'=>$this->input->post('supplier_id'),
			'employee_receiver_id'=>$this->input->post('employee_receiver_id'),
			'employee_checker_id'=>$this->input->post('employee_checker_id'),
			'delivered_by'=>$this->input->post('delivered_by'),
			'date_received'=>$this->input->post('date_received'),
			'project_id'=>$this->input->post('project_id'),
			'supplier_invoice'=>$this->input->post('supplier_invoice'),
			'dr_no'=>$this->input->post('dr_no'),
			'title_id'=>$this->input->post('title_id'),
			'posted_by'=>$this->input->post('posted_by'),
			'invoice_date'=>$this->input->post('invoice_date'),
			'received_status'=>strtoupper($this->input->post('status')),
		);
		$rr_id = $this->input->post('rr_id');
		$po_id = $this->input->post('po_id');

		$this->db->where('receipt_id',$rr_id);
		$this->db->update('receiving_main',$data);


		$this->db->where('po_id', $po_id);
		$this->db->where('receipt_id', $rr_id);	
		$this->db->delete('receiving_details'); 		


		$details = $this->input->post('details');
		foreach($details as $row){

			$insert = array(
						'receipt_id'=>$rr_id,
						'po_id'=>$row['po_id'],
						'item_id'=>$row['item_id'],
						'item_quantity_ordered'=>$row['item_quantity_ordered'],
						'item_quantity_actual'=>$row['item_quantity_actual'],
						'item_name_ordered'=>$row['item_name_ordered'],
						'item_name_actual'=>$row['item_name_actual'],
						'item_cost_ordered'=>$row['item_cost_ordered'],
						'item_cost_actual'=>$row['item_cost_actual'],
						'discrepancy'=>$row['discrepancy'],
						'discrepancy_remarks'=>$row['discrepancy_remarks'],
						'po_number'=>$row['po_number'],
						'unit_msr'=>$row['unit_msr'],
						'received'=>'TRUE',
						'item_remarks'=>'',
					);
			$this->db->insert('receiving_details',$insert);

		}

		if($this->input->post('status')=='COMPLETE'){
			$sql = "UPDATE purchase_order_main SET status = 'COMPLETE',po_checker = '1' WHERE po_id = ? ";
			$this->db->query($sql,array($po_id));
		}else{
			$sql = "UPDATE purchase_order_main SET status = 'PARTIAL' WHERE po_id = ?";
			$this->db->query($sql,array($po_id));
		}

		$po_main    = $this->getBypo_id($po_id);
		$po_details = $this->getBypo_id_details($details[0]['po_id']);		
		$rr_details = $this->get_rr_details_group($rr_id);

		$arg = array(
				'rr_id'=>$rr_id,
				'po_id'=>$po_id,
			);

		$this->md_accounting->update_journal_entry($arg,$rr_details,$po_main);
		return true;

	}



	public function get_po_received($po_no){

		$sql = "
				SELECT 
				*,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_receiver_id) 'rr_received_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_checker_id) 'rr_checked_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.posted_by) 'rr_posted_by'
				FROM receiving_details rd
				INNER JOIN purchase_order_main pom
				ON (pom.po_id = rd.po_id)
				LEFT JOIN receiving_main rm
				ON (rm.receipt_id =rd.receipt_id)
				WHERE pom.po_number = '".$po_no."'
				AND rm.received_status <> 'CANCELLED'
				GROUP BY rd.receipt_id
				ORDER BY (rd.receipt_id * 1) DESC;
		";		
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_po_received_rr_id($rr_id){

		$sql = "
				SELECT 
				*,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_receiver_id) 'rr_received_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_checker_id) 'rr_checked_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.posted_by) 'rr_posted_by'
				FROM receiving_details rd
				INNER JOIN purchase_order_main pom
				ON (pom.po_id = rd.po_id)
				LEFT JOIN receiving_main rm
				ON (rm.receipt_id =rd.receipt_id)
				WHERE rm.received_status <> 'CANCELLED'
				AND rm.receipt_id = '{$rr_id}'
				GROUP BY rd.receipt_id
				ORDER BY (rd.receipt_id * 1) DESC;
		";
		$result = $this->db->query($sql);
		return $result->row_array();
		
	}






	public function get_po_received_id($po_id){
		$sql = "
				SELECT 
				*,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_receiver_id) 'rr_received_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_checker_id) 'rr_checked_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.posted_by) 'rr_posted_by'
				FROM receiving_details rd
				INNER JOIN purchase_order_main pom
				ON (pom.po_id = rd.po_id)
				LEFT JOIN receiving_main rm
				ON (rm.receipt_id =rd.receipt_id)
				WHERE pom.po_id = '".$po_id."'
				GROUP BY rd.receipt_id
				ORDER BY (rd.receipt_id * 1) DESC;
		";		
		$result = $this->db->query($sql);
		return $result->result_array();
	}



	public function get_po_received_single($po_no,$receipt_id){

		$sql = "
				SELECT 
				*,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_receiver_id) 'rr_received_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_checker_id) 'rr_checked_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.posted_by) 'rr_posted_by'
				FROM receiving_details rd
				INNER JOIN purchase_order_main pom
				ON (pom.po_id = rd.po_id)
				LEFT JOIN receiving_main rm
				ON (rm.receipt_id =rd.receipt_id)
				WHERE pom.po_number = '".$po_no."'
				AND rm.receipt_id = '".$receipt_id."'
				GROUP BY rd.receipt_id
				ORDER BY (rd.receipt_id * 1) DESC;
		";

		$result = $this->db->query($sql);
		return $result->row_array();
	}



	public function get_rr_details($receipt_id = ''){
		$sql = "
			SELECT a.*,b.`account_id` 
			FROM receiving_details a
			INNER JOIN setup_group_detail b
			ON (a.item_id = b.`group_detail_id`)
			WHERE a.receipt_id  = '".$receipt_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	public function get_rr_details_group($receipt_id = ""){

		$sql = "
		SELECT a.*,b.`account_id`,(a.item_quantity_actual * a.`item_cost_actual`)'total_cost'
		FROM receiving_details a
		INNER JOIN setup_group_detail b
		ON (a.item_id = b.`group_detail_id`)
		WHERE a.receipt_id = '{$receipt_id}'
		GROUP BY b.`account_id`
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}



	public function get_total_delivered($po_id){

		$sql = "
			SELECT
			*,
			SUM(item_quantity_actual) 'total_delivered'
			FROM receiving_details a
			INNER JOIN receiving_main b
			ON (b.`receipt_id` = a.`receipt_id`)
			WHERE b.`received_status` <> 'CANCELLED' AND  po_id = '{$po_id}' 
			GROUP BY item_id,item_cost_actual
						
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_rr_main_no($rr_no){

		$sql = "SELECT * FROM receiving_main WHERE receipt_no = '".$rr_no."' AND title_id = '".$this->session->userdata('Proj_Main')."';";
		$result = $this->db->query($sql);		
		return $result->row_array();

	}





	public function getBypo_id($po_id){
		$sql = "
		SELECT 
			*,
			pom.po_id 'po_id_main',
			pom.status 'p_status',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = pom.approvedBy
			) 'approvedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = pom.recommendedBy
			) 'recommendedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = pom.preparedBy
			) 'preparedBy_name',			
			(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier',
			(SELECT vat FROM business_list WHERE business_number = pom.supplierID) 'vat',
			(CASE (SELECT for_usage FROM purchaserequest_details WHERE pr_id = pom.pr_id GROUP BY for_usage LIMIT 1)
			   WHEN 'Plumbing' THEN 1
			   WHEN 'Fire Protection' THEN 2
			   WHEN 'MECHANICAL' THEN 3
			   WHEN 'Electrical' THEN 4
			   ELSE 0
			 END) 'pay_item'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			INNER JOIN (
				SELECT
				 *,
				(SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName',
				(SELECT title_name FROM project_title  WHERE title_id = th.from_projectMain)'from_projectMainName',
				(SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.to_projectCode)'to_projectCodeName',
				(SELECT title_name FROM project_title  WHERE title_id = th.to_projectMain)'to_projectMainName',
				(SELECT COUNT(*) 'cnt' FROM purchaserequest_details WHERE pr_id = pm.pr_id) 'item_cnt'
				FROM purchaserequest_main pm
				INNER JOIN transaction_history th
				ON (pm.pr_id = th.reference_id) 
				INNER JOIN (	
				SELECT
				    `hr_employee`.`emp_number`
				    , `hr_person_profile`.`pp_person_code`
				    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'preparedbyName'   
				FROM
				    `hr_employee`
				    INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				) signatory
				ON (signatory.emp_number = pm.preparedBy)			
				GROUP BY pm.pr_id
				ORDER BY purchaseDate DESC, pr_id DESC
			) purchase_request
			ON (purchase_request.pr_id = pom.pr_id)
			LEFT JOIN po_status ps
			ON (ps.po_id = pom.po_id)
			WHERE pom.po_id = '".$po_id."'
		";
		$result = $this->db->query($sql);		
		return $result->row_array();

	}

	public function getBypo_id_details($po_id){

		$sql = "
			SELECT 
			* 
			FROM purchase_order_details pod
			INNER JOIN setup_group_detail sgd
			ON (pod.itemNo = sgd.group_detail_id)
			WHERE po_id = '".$po_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	/***UNDELIVERED**/


	public function get_undelivered($arg){

		$supplier_id = '';
		$location    = '';

		if($arg['supplier_id'] == 'ALL'){
			$supplier_id = '';
		}

		if($arg['location'] != 'all'){
			$location = $arg['location'];
		}

		
		$sql = "
			SELECT
			a.po_id,
			reference_no,
			a.po_date,
			SUM(b.total_unitcost) 'total_cost',
			COUNT(b.itemNo) 'total_item',
			from_name 'project_requestor',
			(SELECT business_name FROM business_list WHERE business_number = a.supplierID)'Supplier',
			(DATEDIFF('{$arg['date_to']}',a.po_date)) 'overdue_day'
			FROM 
			purchase_order_main a
			INNER JOIN (purchase_order_details b)
			ON (a.po_id = b.po_id) 
			LEFT JOIN (
				SELECT 
				c.* 
				FROM receiving_details c
				INNER JOIN receiving_main d
				ON (c.`receipt_id` = d.`receipt_id`)
				WHERE d.`received_status` <> 'CANCELLED'
			) c 
			ON (c.po_id = a.po_id)
			WHERE a.status = 'APPROVED' AND c.`receipt_id` IS NULL AND (DATEDIFF('{$arg['date_to']}',a.po_date)) > 3 
			AND a.supplierID like '%{$supplier_id}%' AND a.from_id like '%{$location}%'
			GROUP BY a.po_id
			ORDER BY a.po_id DESC;
		";
		$result = $this->db->query($sql);				
		return $result->result_array();

	}

	public function get_rr_received($rr_id){

		$sql = "
				SELECT 
				*,
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_receiver_id) 'rr_received_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.employee_checker_id) 'rr_checked_by',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = rm.posted_by) 'rr_posted_by'
				FROM receiving_details rd
				INNER JOIN purchase_order_main pom
				ON (pom.po_id = rd.po_id)
				LEFT JOIN receiving_main rm
				ON (rm.receipt_id =rd.receipt_id)
				WHERE rm.receipt_id = '".$rr_id."'				
				GROUP BY rd.receipt_id
				ORDER BY (rd.receipt_id * 1) DESC;
		";		
		$result = $this->db->query($sql);
		return $result->row_array();
	}


	public function cancel_rr($arg){

		
		$sql = "
			SELECT * FROM receiving_main a
			INNER JOIN receiving_details b
			ON (a.`receipt_id` = b.`receipt_id`)
			WHERE po_id = '{$arg['po_id']}' AND `status`= 'ACTIVE'
			GROUP BY a.receipt_id 	
		";
		$result = $this->db->query($sql);

		if($result->num_rows() == 1){

			$po_update = array(
				'status'=>'APPROVED',
				);			
			$this->db->where('po_id',$arg['po_id']);
			$this->db->update('purchase_order_main',$po_update);			
		}


		$update = array(
				'status'=>'CANCELLED',
				'received_status'=>'CANCELLED',
			);
		$this->db->where('receipt_id',$arg['id']);
		$this->db->update('receiving_main',$update);		



		$sql = "SELECT `status` FROM purchase_order_main WHERE po_id = '{$arg['po_id']}'";
		$result = $this->db->query($sql);
		$row = $result->row_array();



		$update = array(
			'status'=>'CANCELLED'
			);

		$this->db->where('rr_id',$arg['id']);
		$this->db->update('journal_main',$update);


		
		return array(
			'status'=>$row['status'],
			'msg'=>'1',
			);


	}


}