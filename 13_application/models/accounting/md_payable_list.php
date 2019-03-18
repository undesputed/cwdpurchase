<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_payable_list extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}
	
	public function get_payable_list($params){

		$where = "";

		if($params['view_type'] == 'date_range')
		{
				$where .=" AND pom.po_date BETWEEN '".$params['date_from']."' AND '".$params['date_to']."'";
		}else{
				if(isset($params['year'])){
					$where .=" AND YEAR(pom.po_date) = '".$params['year']."'";
				}

				if(isset($params['month'])){
					$where .=" AND MONTH(pom.po_date) = '".$params['month']."'";
				}
		}

		if(isset($params['location'])){

			if($params['location'] != 'all')
			{
				$where .="AND th.from_projectCode = '{$params['location']}'";		
			}

		}

		if(isset($params['supplier_id']))
		{
			if($params['supplier_id'] != 'all')
			{
				$where .="AND pom.supplierID = '{$params['supplier_id']}'";		
			}

		}

		if(isset($params['paid_status']))
		{
			if($params['paid_status']!= 'all')
			{
				if($params['paid_status'] == 'paid'){
					$where .=" AND (_check.check_no IS NOT NULL OR receiving_main.`paid_status` IS NOT NULL )";
				}else{
					$where .=" AND (_check.check_no IS NULL AND receiving_main.`paid_status` IS NULL )";
				}
			}
		}

		$sql = "
			 SELECT 
			   IF(
			      _check.check_no IS NULL 
			      AND receiving_main.`paid_status` IS NULL,
			      NULL,
			      'paid'
			    ) 'paid_status',
			    IF(
			      _check.bank IS NULL,
			      IF(
			        receiving_main.check_no IS NULL,
			        NULL,
			        receiving_main.check_no
			      ),
			      _check.bank
			    ) AS bank,
			    _check.check_no '_check_no',
			    _check.check_date,
			    _check.total_amount 'paid_amount',
			    _check.journal_id 'journal_id',
			    pom.po_id,
			    pom.reference_no,
			    pom.po_date,
			    pom.po_number,
			    pom.po_remarks,
			    pom.cancel_remarks,
			    pom.dtDelivery,
			    po_details.total_cost,
			    po_details.total_item,
			    pom.status 'p_status',
			    receiving_main.receipt_id,
			    receiving_main.supplier_invoice,
			    receiving_main.invoice_date,
			    receiving_main.total_cost 'si_amount',
			    receiving_main.date_received 'rr_date',
			    pom.from_name,
			    (SELECT 
			      CONCAT(
			        `hr_person_profile`.`pp_lastname`,
			        ', ',
			        `hr_person_profile`.`pp_firstname`,
			        ' ',
			        `hr_person_profile`.`pp_middlename`
			      ) 
			    FROM
			      `hr_employee` 
			      INNER JOIN `hr_person_profile` 
			        ON (
			          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
			        ) 
			    WHERE `hr_employee`.`emp_number` = pom.approvedBy) 'approvedBy_name',
			    (SELECT 
			      CONCAT(
			        `hr_person_profile`.`pp_lastname`,
			        ', ',
			        `hr_person_profile`.`pp_firstname`,
			        ' ',
			        `hr_person_profile`.`pp_middlename`
			      ) 
			    FROM
			      `hr_employee` 
			      INNER JOIN `hr_person_profile` 
			        ON (
			          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
			        ) 
			    WHERE `hr_employee`.`emp_number` = pom.recommendedBy) 'recommendedBy_name',
			    (SELECT 
			      CONCAT(
			        `hr_person_profile`.`pp_lastname`,
			        ', ',
			        `hr_person_profile`.`pp_firstname`,
			        ' ',
			        `hr_person_profile`.`pp_middlename`
			      ) 
			    FROM
			      `hr_employee` 
			      INNER JOIN `hr_person_profile` 
			        ON (
			          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
			        ) 
			    WHERE `hr_employee`.`emp_number` = pom.preparedBy) 'preparedBy_name',   
			      (SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier' 
			  FROM
			    purchase_order_main pom 
			    INNER JOIN 
			      (SELECT 
			        *,
			        COUNT(DISTINCT itemNo) 'total_item',
			        SUM(total_unitcost) 'total_cost' 
			      FROM
			        purchase_order_details 
			      GROUP BY po_id) po_details 
			      ON (pom.po_id = po_details.po_id) 
			    LEFT JOIN 
			      (SELECT 
			        * 
			      FROM
			        receiving_main rm 
			        INNER JOIN 
			          (SELECT 
			            SUM(
			              item_cost_actual * item_quantity_actual
			            ) 'total_cost',
			            po_id,
			            receipt_id 'r_id' 
			          FROM
			            receiving_details 
			          GROUP BY receipt_id) details 
			          ON (rm.receipt_id = details.r_id)
			          WHERE rm.received_status <> 'CANCELLED' 
			          ) receiving_main 
			      ON (receiving_main.po_id = pom.po_id)    
			     LEFT JOIN cash_voucher_main _check
			      ON FIND_IN_SET(receiving_main.receipt_id,_check.rr_id) > 0 
			  WHERE  pom.status IN ('PARTIAL', 'COMPLETE')
			  {$where}			  
			  ORDER BY pom.po_id ASC
		";
	
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_supplier(){

		$sql = "
			SELECT * FROM business_list WHERE TYPE = 'BUSINESS' AND status = 'ACTIVE'  ORDER BY business_name
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function get_affiliate(){

		$sql = "
			SELECT * FROM business_list WHERE TYPE = 'AFFILIATE' AND status = 'ACTIVE'  ORDER BY business_name
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}



	public function update_status($arg){

		$this->db->trans_begin();

		$this->load->model('procurement/md_purchase_order');
		$this->load->model('procurement/md_accounting');
		
		$sql = "SELECT * FROM journal_main WHERE po_id = '{$arg['po_id']}' AND status <> 'EXPENSE';";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0){

					if($arg['proceed']=='true'){

						/***PROCEED INVOICE***/

						$this->load->model('procurement/md_received_purchase');

						$po_main    = $this->getBypo_id($arg['po_id']);
						$po_details = $this->getBypo_id_details($arg['po_id']);

						$post['receipt_no']    = $this->extra->get_max_rr(date('Y-m-d'));
						$post['supplier_id']   = $po_main['supplierID'];
						$post['employee_receiver_id'] = "0";
						$post['employee_checker_id']  = "0";
						$post['delivered_by']  = "-";
						$post['date_received'] = date('Y-m-d');
						$post['project_id']    = $po_main['from_projectCode'];
						$post['supplier_invoice'] = $arg['invoice_number'];
						$post['title_id']      = $po_main['from_projectMain'];
						$post['posted_by']     = "0";
						$post['invoice_date']  = $arg['invoice_date'];
						$post['status']        = "COMPLETE";

						$details = array();
						$cnt = 0;			
						foreach($po_details as $row){

							$details[$cnt]['po_id']                 = $row['po_id'];
							$details[$cnt]['item_id']               = $row['itemNo'];
							$details[$cnt]['item_quantity_ordered'] = $row['quantity'];
							$details[$cnt]['item_quantity_actual']  = $row['quantity'];
							$details[$cnt]['item_name_ordered']     = $row['item_name'];
							$details[$cnt]['item_name_actual']      = $row['item_name'];
							$details[$cnt]['item_cost_ordered']     = $row['unit_cost'];
							$details[$cnt]['item_cost_actual']      = $row['unit_cost'];
							$details[$cnt]['discrepancy']           = '';
							$details[$cnt]['discrepancy_remarks']   = '';
							$details[$cnt]['po_number']             = $po_main['reference_no'];
							$details[$cnt]['unit_msr']              = $row['unit_msr'];
							$cnt++;

						}

						$post['details']  = $details;
						$rr_id = $this->md_received_purchase->save_receiving_3($post);						
						$this->md_accounting->journal_entry($po_main,$po_details,$rr_id,$arg['check_date']);

						/**END**/						
				}

				

				/**INSERT JOURNAL**/
					$journal_main = $result->row_array();
					$po_info      = $this->md_purchase_order->getBypo_id($arg['po_id']);
					$date         = $arg['check_date'];
					$reference_no = $this->extra->get_journal_code($date,'PAY PAYABLE',$po_info['from_projectCode'],'JRN');
					$balance      = $po_info['total_cost'] - $arg['amount_paid']; 
					$rr_id = (isset($rr_id)? $rr_id : $journal_main['rr_id']);
					
					$insert = array(
						'reference_no'=>$reference_no,
						'trans_date'=>$date,
						'type'=>'PAYMENT',
						'trans_type'=>'PAY PAYABLE',
						'status'=>'POSTED',
						'location'=>$po_info['from_projectCode'],
						'title_id'=>$this->session->userdata('Proj_Main'),
						'userid'=>$this->session->userdata('emp_id'),
						'division'=>$this->session->userdata('division'),
						'po_id'=>$arg['po_id'],
						'name_id'=>$po_info['supplierID'],
						'name_type'=>'BUSINESS',
						'_Amount'=>$arg['amount_paid'],
						'_bAlance'=>$balance,
						'rr_id'=>$rr_id,
						'pay_item'=>$po_info['pay_item']						
					);

					$this->db->insert('journal_main',$insert);
					$id = $this->db->insert_id();

						$insert_details   = array();
						$insert_details[] = array(
								'journal_id'=>$id,
								'account_id'=>'47',
								'amount'=>$arg['amount_paid'],
								'dr_cr'=>'DEBIT',
								'type'=>'SUPPLIER',
								'subsidiary_type'=>'SUPPLIER',
								'subsidiary'=>$po_info['Supplier'],
								'supplier_id'=>$po_info['supplierID'],
								'bank'=>'False',
								'dtl_id'=>'0',
								'chkdtl_id'=>'0',
								'cv_id'=>'0',
								'check_date'=>'',
							);

						if($arg['type']=='cash'){
							$insert_details[] = array(
								'journal_id'=>$id,
								'account_id'=>'2',
								'amount'=>$arg['amount_paid'],
								'dr_cr'=>'CREDIT',
								'type'=>'CASH',
								'supplier_id'=>$po_info['supplierID'],
								'bank'=>'false',
								'dtl_id'=>'0',
								'chkdtl_id'=>'0',
								'cv_id'=>'0',
								'check_date'=>'',
								'subsidiary'=>$arg['affiliate'],
								'subsidiary_type'=>'CASH',
								'check_no'=>'CASH',
							);

						}else{

							$insert_details[] = array(
								'journal_id'=>$id,
								'account_id'=>'4',
								'amount'=>$arg['amount_paid'],
								'dr_cr'=>'CREDIT',
								'type'=>'BANK',
								'supplier_id'=>$po_info['supplierID'],
								'bank'=>'TRUE',
								'dtl_id'=>'0',
								'chkdtl_id'=>'0',
								'cv_id'=>'0',
								'check_date'=>$arg['check_date'],
								'subsidiary'=>$arg['bank_name'],
								'subsidiary_type'=>'BANK',
								'check_no'=>$arg['check_no'],
							);							
							
						}
												
						foreach($insert_details as $row){
							$this->db->insert('journal_detail',$row);
						}

						$update = array(
							'p_status'=>$id
							);
						$this->db->where('po_id',$arg['po_id']);
						$this->db->update('purchase_order_main',$update);
						
		}else{
			
			$this->load->model('procurement/md_received_purchase');

			$po_main    = $this->getBypo_id($arg['po_id']);
			$po_details = $this->getBypo_id_details($arg['po_id']);

			$sql = "SELECT * FROM receiving_details WHERE po_id = '{$arg['po_id']}'";
			$result = $this->db->query($sql);
			
			if($result->num_rows() > 0){
				$rr_d = $result->row_array();

				if(!empty($arg['invoice_number'])){
					$update = array(
						'supplier_invoice'=>$arg['invoice_number']
					);
					$this->db->where('receipt_id',$rr_d['receipt_id']);
					$this->db->update('receiving_main',$update);
				}

				$this->md_accounting->journal_entry($po_main,$po_details,$rr_d['receipt_id'],$arg['check_date']);
				$this->update_status($arg);

			}else{

				$post['receipt_no']    = $this->extra->get_max_rr(date('Y-m-d'));
				$post['supplier_id']   = $po_main['supplierID'];
				$post['employee_receiver_id'] = "0";
				$post['employee_checker_id']  = "0";
				$post['delivered_by']  = "-";
				$post['date_received'] = date('Y-m-d');
				$post['project_id']    = $po_main['from_projectCode'];
				$post['supplier_invoice'] = $arg['invoice_number'];
				$post['title_id']      = $po_main['from_projectMain'];
				$post['posted_by']     = "0";
				$post['invoice_date']  = $arg['invoice_date'];
				$post['status']        = "COMPLETE";
				
				$details = array();
				$cnt = 0;			
				foreach($po_details as $row){

					$details[$cnt]['po_id']   = $row['po_id'];
					$details[$cnt]['item_id'] = $row['itemNo'];
					$details[$cnt]['item_quantity_ordered'] = $row['quantity'];
					$details[$cnt]['item_quantity_actual']  = $row['quantity'];
					$details[$cnt]['item_name_ordered']     = $row['item_name'];
					$details[$cnt]['item_name_actual']      = $row['item_name'];
					$details[$cnt]['item_cost_ordered']     = $row['unit_cost'];
					$details[$cnt]['item_cost_actual']      = $row['unit_cost'];
					$details[$cnt]['discrepancy']           = '';
					$details[$cnt]['discrepancy_remarks']   = '';
					$details[$cnt]['po_number']             = $po_main['reference_no'];
					$details[$cnt]['unit_msr']              = $row['unit_msr'];
					$cnt++;

				}
				
				$post['details']  = $details;
				$rr_id = $this->md_received_purchase->save_receiving_3($post);
				
				$this->md_accounting->journal_entry($po_main,$po_details,$rr_id,$arg['check_date']);
				$this->update_status($arg);
				
			}

			/*$main,$details,$rr_id;*/
			
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


	public function _update_status($arg){

		$output = "";

		if($arg['status'] == 'unpaid'){
			$arg['status'] = 'paid';
			$output = "<span class='label label-success' data-id='".$arg['po_id']."' data-value='paid'>paid</span>";
		}else{
			$arg['status'] = 'unpaid';
			$output = "<span class='label label-warning'>unpaid</span>";
		}

		$update = array(
				'p_status'=>$arg['status'],
			);
		$this->db->where('po_id',$arg['po_id']);
		$this->db->update('purchase_order_main',$update);		
		return true;

	}


	public function get_bank_setup(){

		$sql = "SELECT bank_id,bank_name,bank_address FROM bank_setup;";
		$result = $this->db->query($sql);
		return $result->result_array();

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
			(IF(pom.supplierType='BUSINESS',(SELECT business_name FROM business_list WHERE business_number = pom.supplierID),
			(SELECT CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename)) 'Supplier'
			FROM hr_person_profile
			WHERE (hr_person_profile.pp_person_code = pom.supplierID)
			ORDER BY `Supplier` ASC))) 'Supplier',
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
			pod.*,
			sgd.account_id
			FROM purchase_order_details pod
			INNER JOIN setup_group_detail sgd
			ON (pod.itemNo = sgd.group_detail_id)
			WHERE po_id = '".$po_id."';
		";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function supplier_balance($params){

		
		$where = "";

		/*	
		if($params['view_type'] == 'date_range')
		{
				$where .=" AND ADDDATE(pom.po_date,INTERVAL pom.indays DAY) BETWEEN '{$params['date_from']}' AND '{$params['date_to']}'";
		}else{	

				$from = date('Y-m-d',strtotime($params['year'].'-'.$params['month'].'-01'));
				$to   = date('Y-m-t',strtotime($params['year'].'-'.$params['month'].'-01'));

				$where .="AND  ADDDATE(pom.po_date,INTERVAL pom.indays DAY) BETWEEN '{$from}' AND '{$to}'";
		}*/

		if(isset($params['location'])){

			if($params['location'] != 'all')
			{
				$where .="AND pom.from_id = '{$params['location']}'";		
			}

		}

		if(isset($params['supplier_id']))
		{
			if($params['supplier_id'] != 'all')
			{
				$where .= "AND pom.supplierID = '{$params['supplier_id']}'";		
			}

		}
				
		$sql = "
				SELECT
				*
				from (
				 SELECT 
				   IF(
				      _check.check_no IS NULL 
				      AND receiving_main.`paid_status` IS NULL,
				      NULL,
				      'paid'
				    ) 'paid_status',
				    IF(
				      _check.bank IS NULL,
				      IF(
				        receiving_main.check_no IS NULL,
				        NULL,
				        receiving_main.check_no
				      ),
				      _check.bank
				    ) AS bank,
				    _check.check_no '_check_no',
				    _check.check_date,
				    _check.total_amount 'paid_amount',
				    _check.journal_id 'journal_id',
				    pom.po_id,
				    pom.reference_no,
				    pom.po_date,
				    pom.po_number,
				    pom.po_remarks,
				    pom.cancel_remarks,
				    pom.dtDelivery,
				    po_details.total_cost,
				    po_details.total_item,
				    pom.`supplierID`,
				    pom.status 'p_status',
				    receiving_main.receipt_id,
				    receiving_main.supplier_invoice,
				    receiving_main.invoice_date,
				    SUM(receiving_main.total_cost) 'payables',
				    receiving_main.date_received 'rr_date',
				    pom.from_name,
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.approvedBy) 'approvedBy_name',
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.recommendedBy) 'recommendedBy_name',
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.preparedBy) 'preparedBy_name',   
				      (SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier' 
				  FROM
				    purchase_order_main pom 
				    INNER JOIN 
				      (SELECT 
				        *,
				        COUNT(DISTINCT itemNo) 'total_item',
				        SUM(total_unitcost) 'total_cost' 
				      FROM
				        purchase_order_details 
				      GROUP BY po_id) po_details 
				      ON (pom.po_id = po_details.po_id) 
				    LEFT JOIN 
				      (SELECT 
				        * 
				      FROM
				        receiving_main rm 
				        INNER JOIN 
				          (SELECT 
				            SUM(
				              item_cost_actual * item_quantity_actual
				            ) 'total_cost',
				            po_id,
				            receipt_id 'r_id' 
				          FROM
				            receiving_details 
				          GROUP BY receipt_id) details 
				          ON (rm.receipt_id = details.r_id)
				          WHERE rm.received_status <> 'CANCELLED' 
				          ) receiving_main 
				      ON (receiving_main.po_id = pom.po_id)    
				     LEFT JOIN cash_voucher_main _check
				      ON FIND_IN_SET(receiving_main.receipt_id,_check.rr_id) > 0 
				  WHERE  pom.status IN ('PARTIAL', 'COMPLETE')         
				    AND ( _check.check_no IS NULL AND receiving_main.`paid_status` IS NULL )    
				   GROUP BY pom.`supplierID`
				   ) a ORDER BY Supplier ASC				  

		";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function view_info($params){

		$where = "";

	/*	if($params['view_type'] == 'date_range')
		{
				$where .=" AND ADDDATE(pom.po_date,INTERVAL pom.indays DAY) BETWEEN '{$params['date_from']}' AND '{$params['date_to']}'";
		}else{	

				$from = date('Y-m-d',strtotime($params['year'].'-'.$params['month'].'-01'));
				$to   = date('Y-m-t',strtotime($params['year'].'-'.$params['month'].'-01'));

				$where .="AND  ADDDATE(pom.po_date,INTERVAL pom.indays DAY) BETWEEN '{$from}' AND '{$to}'";
		}*/

		if(isset($params['location'])){

			if($params['location'] != 'all')
			{
				$where .="AND pom.from_id = '{$params['location']}'";		
			}

		}

		if(isset($params['supplier_id']))
		{
			if($params['supplier_id'] != 'all')
			{
				$where .= "AND pom.supplierID = '{$params['supplier_id']}'";		
			}

		}


		$sql = "
				 SELECT 
				   IF(
				      _check.check_no IS NULL 
				      AND receiving_main.`paid_status` IS NULL,
				      NULL,
				      'paid'
				    ) 'paid_status',
				    IF(
				      _check.bank IS NULL,
				      IF(
				        receiving_main.check_no IS NULL,
				        NULL,
				        receiving_main.check_no
				      ),
				      _check.bank
				    ) AS bank,
				    _check.check_no '_check_no',
				    _check.check_date,
				    _check.total_amount 'paid_amount',
				    _check.journal_id 'journal_id',
				    pom.po_id,
				    pom.reference_no,
				    pom.po_date,
				    pom.po_number,
				    pom.po_remarks,
				    pom.cancel_remarks,
				    pom.dtDelivery,
				    po_details.total_cost,
				    po_details.total_item,
				    pom.`supplierID`,
				    pom.status 'p_status',
				    receiving_main.receipt_id,
				    receiving_main.supplier_invoice,
				    receiving_main.invoice_date,
				    receiving_main.total_cost 'si_amount',
				    receiving_main.date_received 'rr_date',
				    pom.from_name as 'project_requestor',
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.approvedBy) 'approvedBy_name',
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.recommendedBy) 'recommendedBy_name',
				    (SELECT 
				      CONCAT(
				        `hr_person_profile`.`pp_lastname`,
				        ', ',
				        `hr_person_profile`.`pp_firstname`,
				        ' ',
				        `hr_person_profile`.`pp_middlename`
				      ) 
				    FROM
				      `hr_employee` 
				      INNER JOIN `hr_person_profile` 
				        ON (
				          `hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`
				        ) 
				    WHERE `hr_employee`.`emp_number` = pom.preparedBy) 'preparedBy_name',   
				      (SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier' 
				  FROM
				    purchase_order_main pom 
				    INNER JOIN 
				      (SELECT 
				        *,
				        COUNT(DISTINCT itemNo) 'total_item',
				        SUM(total_unitcost) 'total_cost' 
				      FROM
				        purchase_order_details 
				      GROUP BY po_id) po_details 
				      ON (pom.po_id = po_details.po_id) 
				    LEFT JOIN 
				      (SELECT 
				        * 
				      FROM
				        receiving_main rm 
				        INNER JOIN 
				          (SELECT 
				            SUM(
				              item_cost_actual * item_quantity_actual
				            ) 'total_cost',
				            po_id,
				            receipt_id 'r_id' 
				          FROM
				            receiving_details 
				          GROUP BY receipt_id) details 
				          ON (rm.receipt_id = details.r_id)
				          WHERE rm.received_status <> 'CANCELLED' 
				          ) receiving_main 
				      ON (receiving_main.po_id = pom.po_id)    
				     LEFT JOIN cash_voucher_main _check
				      ON FIND_IN_SET(receiving_main.receipt_id,_check.rr_id) > 0 
				  WHERE  pom.status IN ('PARTIAL', 'COMPLETE')         
				    AND ( _check.check_no IS NULL AND receiving_main.`paid_status` IS NULL )
				    {$where}
				  ORDER BY pom.po_id ASC  			
		";
				
		$result = $this->db->query($sql);		

		return $result->result_array();

	}


	public function check_multiple_rr($arg){
		$sql = "SELECT * FROM receiving_details WHERE po_id = '{$arg['po_id']}'";
		$result = $this->db->query($sql);
		
		if($result->num_rows() > 0){
			return false;
		}else{			
			return true;
		}

	}


	public function check_multiple_journal($arg){

		$sql = "SELECT * FROM journal_main WHERE po_id = '{$arg['po_id']}' AND status <> 'EXPENSE' AND type ='PAYMENT'";
		$result = $this->db->query($sql);
		
		if($result->num_rows() > 0){
			return false;
		}else{
			return true;
		}
		
	}


	public function edit($arg){
		$data = array();

		$sql = "SELECT * FROM receiving_main WHERE receipt_id = '{$arg['receipt_id']}';";
		$result = $this->db->query($sql);
		$data['receiving_main'] =  $result->row_array();
		
		$sql = "SELECT * FROM journal_main WHERE journal_id = '{$arg['journal_id']}';";
		$result = $this->db->query($sql);
		$data['journal_main'] =  $result->row_array();

		$sql = "SELECT * FROM journal_detail WHERE journal_id = '{$arg['journal_id']}' ";
		$result = $this->db->query($sql);
		$data['journal_details'] =  $result->result_array();

		$sql = "
			SELECT
			*,
			(SELECT SUM(total_unitcost) FROM purchase_order_details WHERE po_id = po.po_id) 'total_cost'
			FROM purchase_order_main po
			WHERE po_id = '{$arg['po_id']}'
		";

		$result = $this->db->query($sql);
		$data['po_main'] =  $result->row_array();

		return $data;
	}


	public function do_edit($arg){

		/*
		po_id
		receipt_id
		journal_id
		*/

		$update_receiving = array(
			'supplier_invoice'=>$arg['invoice_number'],
			'invoice_date'=>$arg['invoice_date'],
		);

		$this->db->where('receipt_id',$arg['receipt_id']);
		$this->db->update('receiving_main',$update_receiving);
		
		$po_main    = $this->getBypo_id($arg['po_id']);
		$po_details = $this->getBypo_id_details($arg['po_id']);
		$this->md_accounting->update_journal_entry($arg,$po_details,$po_main);

		/*
		$po_main    = $this->getBypo_id($arg['po_id']);
		$po_details = $this->getBypo_id_details($arg['po_id']);
		$this->md_accounting->update_journal_entry($po_main,$po_details,$arg['receipt_id'],$arg['check_date']);
		*/

		echo true;

	}


	public function delete($arg){

		$this->db->where('journal_id',$arg['journal_id']);
		$this->db->delete('journal_main');

		$this->db->where('journal_id',$arg['journal_id']);
		$this->db->delete('journal_detail');
		
		$update = array(
			'status'=>'CANCELLED',
			'received_status'=>'CANCELLED',
		);		
		$this->db->where('receipt_id',$arg['receipt_id']);		
		$this->db->update('receiving_main',$update);

		echo true;

	}

	

}