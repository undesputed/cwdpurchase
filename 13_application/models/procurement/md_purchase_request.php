<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_purchase_request extends CI_Model {

	public function __construct(){
		parent :: __construct();		

		$this->load->model('procurement/md_transaction_history');
	}

	public function get_series_no(){
		$today = date('Y-m-d');

		$sql = "SELECT 
					job_order_no 'jo_no'
				FROM job_order_main
				WHERE 
				SUBSTRING(job_order_no,9,2) = SUBSTRING('{$today}',6,2)
				AND SUBSTRING(job_order_no,4,4) = YEAR('{$today}')
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$jo_no = $row['jo_no'];
				$sq = "SELECT IF((@r:=CAST(SUBSTRING('{$jo_no}',12,LENGTH('{$jo_no}') - 8) AS UNSIGNED) + 1) < 1000,LPAD(@r,3,'0'),@r) AS 'jo_no'";
				$qry = $this->db->query($sq);

				foreach($qry->result_array() as $rw){
					return 'JO-'.date('Y').'-'.date('m').'-'.$rw['jo_no'];
				}
			}
		}else{
			return 'JO-'.date('Y').'-'.date('m').'-001';
		}
	}

	public function get_rr_no(){
		$today = date('Y-m-d');

		$sql = "SELECT 
					receipt_no 'rr_no'
				FROM receiving_main
				WHERE 
				SUBSTRING(receipt_no,9,2) = SUBSTRING('{$today}',6,2)
				AND SUBSTRING(receipt_no,4,4) = YEAR('{$today}')
				ORDER BY receipt_id DESC
				LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$rr_no = $row['rr_no'];
				$sq = "SELECT IF((@r:=CAST(SUBSTRING('{$rr_no}',12,LENGTH('{$rr_no}') - 8) AS UNSIGNED) + 1) < 1000,LPAD(@r,3,'0'),@r) AS 'rr_no'";
				$qry = $this->db->query($sq);

				foreach($qry->result_array() as $rw){
					return 'RR-'.date('Y').'-'.date('m').'-'.$rw['rr_no'];
				}
			}
		}else{
			return 'RR-'.date('Y').'-'.date('m').'-001';
		}
	}

	public function get_rx_no(){
		$today = date('Y-m-d');

		$sql = "SELECT 
					receipt_no 'rr_no'
				FROM return_main
				WHERE 
				SUBSTRING(receipt_no,9,2) = SUBSTRING('{$today}',6,2)
				AND SUBSTRING(receipt_no,4,4) = YEAR('{$today}')
				ORDER BY receipt_id DESC
				LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$rr_no = $row['rr_no'];
				$sq = "SELECT IF((@r:=CAST(SUBSTRING('{$rr_no}',12,LENGTH('{$rr_no}') - 8) AS UNSIGNED) + 1) < 1000,LPAD(@r,3,'0'),@r) AS 'rr_no'";
				$qry = $this->db->query($sq);

				foreach($qry->result_array() as $rw){
					return 'RX-'.date('Y').'-'.date('m').'-'.$rw['rr_no'];
				}
			}
		}else{
			return 'RX-'.date('Y').'-'.date('m').'-001';
		}
	}

	public function get_tr_no(){
		$today = date('Y-m-d');

		$sql = "SELECT 
					transfer_no 'tr_no'
				FROM item_transfer_main
				WHERE 
				SUBSTRING(transfer_no,9,2) = SUBSTRING('{$today}',6,2)
				AND SUBSTRING(transfer_no,4,4) = YEAR('{$today}')
				ORDER BY id DESC
				LIMIT 1";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			foreach($query->result_array() as $row){
				$tr_no = $row['tr_no'];
				$sq = "SELECT IF((@r:=CAST(SUBSTRING('{$tr_no}',12,LENGTH('{$tr_no}') - 8) AS UNSIGNED) + 1) < 1000,LPAD(@r,3,'0'),@r) AS 'tr_no'";
				$qry = $this->db->query($sq);

				foreach($qry->result_array() as $rw){
					return 'TR-'.date('Y').'-'.date('m').'-'.$rw['tr_no'];
				}
			}
		}else{
			return 'TR-'.date('Y').'-'.date('m').'-001';
		}
	}

	function get_all_pr_out($page,$params = ""){

		$where  = "";
		$search = "";
		$page = (int)$page;

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" AND th.status = 'PENDING' ";
				break;
				case "approved":
					$where .=" AND th.status = 'APPROVED' ";
				break;
				case "rejected":
					$where .=" AND th.status = 'REJECTED' ";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$search .= "
					 WHERE (
							purchaseNo LIKE '%{$params['search']}%' OR
							from_projectCodeName LIKE '%{$params['search']}%' OR 
							preparedbyName LIKE '%{$params['search']}%' OR
							purchaseDate LIKE'%{$params['search']}%'
						)
				";				
		}

		$sql2 = "
			SELECT
			*
			FROM 
			(
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
			  LEFT JOIN (	
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
			  
			WHERE from_projectCode = '".$this->session->userdata('Proj_Code')."'
			    AND from_projectMain = '".$this->session->userdata('Proj_Main')."'
			    {$where}
			GROUP BY pm.pr_id
			ORDER BY purchaseDate DESC, pr_id DESC
			) a
			{$search}
		";

		
		$limit = $this->config->item('limit');

		$start = ($page * $limit) - $limit;
		$next = '';
		$result = $this->db->query($sql2);		
		if($result->num_rows() > ($page * $limit) ){

			$next = $page + 1;
		}

		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );				
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;
	}

	function get_all_pr_in($page,$params){
		$page = (int)$page;

		$branch_type = $this->session->userdata('branch_type');
		$where = "";
		$search = "";
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				    to_projectMain = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = " ( to_projectCode = '".$this->session->userdata('Proj_Code')."'
							AND to_projectMain = '".$this->session->userdata('Proj_Main')."') ";
			break;			
			default:
				$where = " ( to_projectCode = '".$this->session->userdata('Proj_Code')."'
							AND to_projectMain = '".$this->session->userdata('Proj_Main')."') ";
			break;

		}	

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" AND th.status = 'PENDING' ";
				break;
				case "approved":
					$where .=" AND th.status = 'APPROVED' ";
				break;
				case "rejected":
					$where .=" AND th.status = 'REJECTED' ";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$search .= "
					 WHERE (
							purchaseNo LIKE '%{$params['search']}%' OR
							from_projectCodeName LIKE '%{$params['search']}%' OR 
							preparedbyName LIKE '%{$params['search']}%' OR
							purchaseDate LIKE'%{$params['search']}%'
						)
				";

		}

		

		$sql2 = "
			SELECT
			*
			FROM
			(
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

			WHERE 
				".$where."
			GROUP BY pm.pr_id
			ORDER BY purchaseDate DESC, pr_id DESC 
			) a 
			{$search}
		";		

		$limit = $this->config->item('limit');	
		// print_r($page);	
		$start = ($page * $limit) - $limit;		
		$result = $this->db->query($sql2);

		$next = "";
		if($result->num_rows() > ($page * $limit) ){
			$next = $page + 1;
		}		
		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);	
		return $output;

	}


	function item_name(){

	/*	$sql = "
		SELECT 
		*,
		(SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ) 'group_description'
		FROM setup_group_detail
		ORDER BY group_id,description ASC;"
		;*/

		$sql  ="
		SELECT 
		group_detail_id,
		group_id,
		description 'item_description',
		quantity,
		unit_cost,
		unit_measure,
		classification,
		account_code,
		date_saved,
		group_id1,
		title_id,
		account_id,
		item_status,
		item_code,
		concat((SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ),' - ',setup_group_detail.description)'description',
		(SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ) 'group_description'
		FROM setup_group_detail
		WHERE item_status = 'active'
		ORDER BY group_id,description ASC
		;
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	
	public function select2($where){
		$sql = "
				SELECT
			*
			FROM (
			SELECT 
				group_detail_id,
				group_id,
				description 'item_description',
				quantity,
				unit_cost,
				unit_measure,
				classification,
				account_code,
				date_saved,
				group_id1,
				title_id,
				account_id,
				item_status,
				item_code,
				CONCAT((SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ),' - ',setup_group_detail.description)'description',
				(SELECT group_description FROM setup_group WHERE group_id = setup_group_detail.group_id ) 'group_description'
				FROM setup_group_detail
				WHERE item_status = 'active'
				) a
				WHERE {$where}
				ORDER BY group_id,description ASC
		";
		$result = $this->db->query($sql);				
		return $result->result_array();

	}

	function item_category($group_id){
		$sql = "SELECT * FROM setup_group where group_id = ? ";		
		$result = $this->db->query($sql,array($group_id));		
		return $result->result_array();
	}

	function account_setup(){		

		$sql = "CALL accounting_display_accounts_title(?)";
		$result = $this->db->query($sql,array($this->session->userdata('Proj_Main')));		
		$this->db->close();
		return $result;
	}

	function save_purchaseRequest(){
				
		$this->db->trans_begin();

		$project_code = $this->session->userdata('Proj_Code');

		if($project_code == 0){
			return false;
			exit(0);
		}


		$purchase_no = '';
		if($this->check_pr_no($this->input->post('purchaseNo')))
		{
			$purchase_no = $this->extra->get_pr_code($this->input->post('purchaseDate'));
		}else{
			$purchase_no = $this->input->post('purchaseNo');
		}
				
		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;
		
		$insert = array(
			 'purchaseNo'=>$purchase_no,
		     'purchaseDate'=>$this->input->post('purchaseDate'),
		     'department'=>$this->input->post('department'),
		     'accountCode'=>$this->input->post('accountCode'),
		     'approvedBy'=>$this->input->post('approvedBy'),
		     'preparedBy'=>$prepared_by,
		     'recommendedBy'=>$this->input->post('recommendedBy'),
		     'checked_by'=>$this->input->post('checked_by'),
		     'project_id'=>$this->input->post('project_id'),
		     'location'=>$this->input->post('location'),
		     'to_'=>$this->input->post('to_'),
		     'legend_'=>$this->input->post('legend'),
		     'title_id'=>$this->input->post('title_id'),
		     'account_id'=>$this->input->post('account_id'),
		     'pr_remarks'=>$this->input->post('pr_remarks'),
		     'approvedPR'=>($this->input->post('approvedBy')!=' ')? 'True': 'False',
		     'requiredDate'=>$this->input->post('requiredDate'),
			);

		$this->db->insert('purchaserequest_main',$insert);

		/**transaction**/
		/* 
		$sql    = "SELECT MAX(pr_id) as max FROM purchaserequest_main WHERE purchaseNo = ?";
		$result = $this->db->query($sql,array($this->input->post('purchaseNo')));
		$pr_id  = $result->row_array();
		*/

		$pr_id = $this->db->insert_id();
		
				
		$history['to_projectCode'] = $this->input->post('project_id');
		$history['to_projectMain'] = $this->input->post('title_id');
		$history['type'] = 'Purchase Request';
		$history['status'] = 'PENDING';
		$history['reference_id'] = $pr_id;
		
		if($this->md_transaction_history->insertion($history))
		{
			if(is_array($this->input->post('data'))){
				foreach($this->input->post('data') as $key=>$row_data){				
					$model = (isset($row_data['model_no']))? $row_data['model_no'] : '' ;
					$serial = (isset($row_data['serial_no']))? $row_data['serial_no'] : '' ;
					$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
					$qty = str_replace(',','',$row_data['qty']);
					$data[$key] = array(
						'pr_id'=>$pr_id,
						'itemNo'=>$row_data['item_no'],
						'itemDesc'=>$row_data['item_description'],
						'groupID'=>$row_data['groupID'],
						'qty'=>$qty,
						'unitmeasure'=>$row_data['unit'],
						'modelNo'=>$model,
						'serialNo'=>$serial,
						'remarkz'=>$remarks,
						'req_qty'=>$qty,
						'unit_cost'=>(double)$row_data['unit_cost'],
						'rem_qty'=>$qty,
						'for_usage'=>$row_data['for_usage'],
						'charging'=>$row_data['charging']
					);
				}
				$this->db->insert_batch('purchaserequest_details',$data);
			}
		}else{
			  $this->db->trans_rollback();
		      return false;
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


	public function update_purchaseRequest(){
		$this->db->trans_begin();


		$update = array(
			 'purchaseNo'=>$this->input->post('purchaseNo'),
		     'purchaseDate'=>$this->input->post('purchaseDate'),
		     'department'=>'1',
		     'accountCode'=>'1',
		     'approvedBy'=>$this->input->post('approvedBy'),
		     'preparedBy'=>$this->input->post('prepared_by'),
		     'recommendedBy'=>$this->input->post('recommendedBy'),
		     'project_id'=>$this->input->post('project_id'),
		     'location'=>$this->input->post('location'),
		     'to_'=>$this->input->post('to_'),
		     'legend_'=>$this->input->post('legend'),
		     'title_id'=>$this->input->post('title_id'),
		     'account_id'=>$this->input->post('account_id'),
		     'pr_remarks'=>$this->input->post('pr_remarks'),
		     'approvedPR'=>($this->input->post('approvedBy')!=' ')? 'True': 'False',
		     'requiredDate'=>$this->input->post('requiredDate'),
			);
		$this->db->where('pr_id',$this->input->post('pr_id'));
		$this->db->update('purchaserequest_main',$update);

		$this->db->query("DELETE FROM `purchaserequest_details` WHERE `pr_id` = '".$this->input->post('pr_id')."'");

		$sql = "SELECT MAX(pr_id) as max FROM purchaserequest_main WHERE purchaseNo = ?";
		$result = $this->db->query($sql,array($this->input->post('purchaseNo')));
		$pr_id = $result->row_array();


		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $key=>$row_data){				
				$model = (isset($row_data['model_no']))? $row_data['model_no'] : '' ;
				$serial = (isset($row_data['serial_no']))? $row_data['serial_no'] : '' ;
				$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
				$qty = str_replace(',','',$row_data['qty']);
				$data[$key] = array(
					'pr_id'=>$pr_id['max'],
					'itemNo'=>$row_data['item_no'],
					'itemDesc'=>$row_data['item_description'],
					'groupID'=>$row_data['groupID'],
					'qty'=>$qty,
					'modelNo'=>$model,
					'serialNo'=>$serial,
					'remarkz'=>$remarks,
					'req_qty'=>$qty,
					'unit_cost'=>(double)$row_data['unit_cost'],
					'rem_qty'=>$qty,
				);				
			}
			$this->db->insert_batch('purchaserequest_details',$data);
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

		public function routing($pr_id){

		$sql = "SELECT
				IFNULL(MAX(routingNo) + 1,1)
				AS max
				FROM routing_slip_main
				WHERE YEAR(date_saved) = YEAR(NOW())
				AND MONTH(date_saved)  = MONTH(NOW())
				AND title_id = ?
				";

		$data = array(
					$this->session->userdata('Proj_Main')
				);
		$result = $this->db->query($sql,$data);
		$row = $result->row_array();

		$sql  = "CALL routing_insert_main('','',?,?,?,?)";
		$data = array(
			$row['max'],
			$pr_id,
			$this->session->userdata('person_code'),
			$this->session->userdata('Proj_Main'),
		);
		$this->db->query($sql,$data);


		$sql = "SELECT MAX(routing_id) as max FROM routing_slip_main WHERE pr_id = ?";
		$result = $this->db->query($sql,array($pr_id));

		$row = $result->row_array();


		$sql = "CALL routing_insert_details(?,?,?,?,?,?,?,?)";
	
		$data = array(
				$row['max'],
				$_POST['department'],              	
                date("Y-m-d"),
                date('H:i:s A'),
                date("Y-m-d"),
                date('H:i:s A'),
                "1",
                "PR",
			);
		$this->db->query($sql,$data);

		//echo $this->db->last_query();
		return true;




		//, Now.Year, Now.Month, MAIN_FORM.LBLMAIN.Text)";	
	}


	function cumulative(){
		//$sql = "CALL purchase_display_main1(?)";
		
		$sql = "

			SELECT	  
			  main.approvedPR 'APPROVED',
			  main.pr_id,
			  main.purchaseNo 'PR NO',
			  main.purchaseDate 'PR DATE',
			  main.project_id,
			  (SELECT project_name FROM setup_project WHERE project_id = main.project_id) 'PROJECT',
			  main.department 'DEPT CODE',
			  (SELECT division_name FROM division_setup WHERE division_code = main.department) 'DEPARTMENT',
			  main.pr_status 'PR STATUS',
			  main.accountCode 'ACCOUNT CODE',
			  (SELECT account_description FROM account_setup WHERE account_id = main.account_id) 'ACCOUNT NAME',
			  approvedBy,
			  _get_person_name(approvedBy) 'APPROVED BY',
			  preparedBy,
			  _get_person_name(preparedBy) 'PREPARED BY',
			  recommendedBy,
			  _get_person_name(recommendedBy) 'RECOMMENDED BY',
			  location 'LOCATION',
			  to_ 'TO',
			  legend_ 'LEGEND',
			  pr_remarks 'PR Remarks',
			  main.account_id,
			  main.requiredDate 'REQUIRED DATE'
			FROM purchaserequest_main AS main
			WHERE main.project_id = ? 			
			ORDER BY main.pr_id DESC,
			main.purchaseDate DESC,
			main.date_saved DESC;			
		";

		$result = $this->db->query($sql,array($this->input->post('location')));
		$this->db->close();
		return $result;
	}
	
	function cumulative_details(){
		$sql = "SELECT * FROM purchaserequest_details where pr_id ='".$this->input->post('pr_id')."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function pr_main(){		
		$sql = "SELECT * FROM purchaserequest_main where pr_id = '".$this->input->post('pr_id')."'";
		$result =  $this->db->query($sql);		
		$this->db->close();
		return $result->row_array();
	}


	public function get_pr_main($id){
		
		$sql = "
		SELECT
		 *, 
		(SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName',
		(SELECT project_no FROM setup_project WHERE project_id = th.from_projectCode)'project_no',
		(SELECT title_name FROM project_title  WHERE title_id = th.from_projectMain)'from_projectMainName',
		(SELECT project_no FROM setup_project WHERE project_id = th.from_projectCode)'from_projectNo',
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
		WHERE pr_id = '".$id."'	
		GROUP BY pm.pr_id
		ORDER BY purchaseDate DESC, pr_id DESC
		";


		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	public function get_purchaseNo($purchaseNo){

	
		$sql1 = "
				SELECT
				  *,
				  (SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName',
				  (SELECT project_no FROM setup_project WHERE project_id = th.from_projectCode)'project_no',
				  (SELECT title_name FROM project_title  WHERE title_id = th.from_projectMain)'from_projectMainName',
				  (SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.to_projectCode)'to_projectCodeName',
				  (SELECT title_name FROM project_title  WHERE title_id = th.to_projectMain)'to_projectMainName',
				  (SELECT COUNT(*) 'cnt' FROM purchaserequest_details WHERE pr_id = pm.pr_id) 'item_cnt',  
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = approvedBy) 'person_approvedBy',
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = preparedBy) 'person_preparedBy',
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = recommendedBY) 'person_recommendedBy',
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = checked_by) 'person_checked_by'
				 FROM purchaserequest_main pm
				  INNER JOIN transaction_history th
				    ON (pm.pr_id = th.reference_id AND th.type = 'Purchase Request')
				WHERE pm.purchaseNo = '".$purchaseNo."'
				AND title_id = '".$this->session->userdata('Proj_Main')."'
				GROUP BY pm.pr_id
				ORDER BY purchaseDate DESC;
			";


		$result = $this->db->query($sql1);
		$this->db->close();		
		return $result->row_array();		
	}

	public function get_pr_details($pr_id){
		
		$sql    = "SELECT * FROM purchaserequest_details WHERE pr_id = '".$pr_id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	function approvePR(){
		$sql = "Update purchaserequest_main set approvedPR='True' WHERE pr_id = '".$this->input->post('id')."'";
		$this->db->query($sql);
		return true;
	}

	function do_cancel($arg){
		$update = array(
			'pr_status'=>$arg['status']
			);
		$this->db->where('pr_id',$arg['pr_id']);
		$this->db->update('purchaserequest_main',$update);		
		return true;
	}

	function changePrStatus(){
		$sql  = "CALL purchase_cancelMain(?,?)";
		$data = array(
			$this->input->post('status'),
			$this->input->post('id'),
			);
		$this->db->query($sql,$data);
		return $this->input->post('status');
	}


	function pay_center()
	{
		$sql = "SELECT id, paycenter FROM pay_center";
		$result = $this->db->query($sql);	
		return $result->result_array();
	}

	public function division()
	{				
		$sql    = "SELECT * FROM division_setup WHERE title_id = '".$this->session->userdata('project_main')."'";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_incomeAcct($account_id)
	{
		$sql = "select get_accountcode_by_accountid(".$account_id.")";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}


	public function update_details($update,$id)
	{
		foreach($update as $row){
			$this->db->where('pr_id',$id);
			$this->db->where('itemNo',$row['itemNo']);
			$this->db->update('purchaserequest_details',$row);	
		}
		
		return true;

	}


	public function changestatus($arg){
		
		$approved_status = 'False';

		if(strtoupper($arg['transaction_status']) == 'APPROVED')
		{
			$approved_status = 'True';
		}

		$update = array(
			'pr_status'=>$arg['pr_status'],
			'approvedPR'=>$approved_status,
		);

		$this->db->where('pr_id',$arg['id']);
		$this->db->update('purchaserequest_main',$update);

		$update2 = array(
			'status'=>$arg['transaction_status']
			);
		$this->db->where('reference_id',$arg['id']);
		$this->db->where('type','Purchase Request');
		$this->db->update('transaction_history',$update2);

		return true;

	}

	public function update_pr_qty($data,$pr_id){
		
		foreach($data as $row){
			$update = array(
				'qty'=>$row['qty']
			);
			$this->db->where('pr_id',$pr_id);
			$this->db->where('itemNo',$row['itemNo']);
			$this->db->update('purchaserequest_details',$update);
		}
		
	}

	public function update_pr($arg,$details){

		$update = array(
			'pr_remarks'=>$arg['pr_remarks'],
			);

		$this->db->where('pr_id',$arg['pr_id']);
		$this->db->update('purchaserequest_main',$update);

		foreach($details as $row){

			$update_details = array(
				'qty'=> $row['qty'],
				'modelNo'=> $row['model_no'],
				'serialNo'=> $row['serial_no'],
				'remarkz'=> $row['remarks'],
				'req_qty'=> $row['qty'],
				'rem_qty'=> $row['qty'],		
			);
						
			$this->db->where('pr_id',$arg['pr_id']);
			$this->db->where('itemNo',$row['itemNo']);
			$this->db->update('purchaserequest_details',$update_details);

		}	
	}


	public function check_pr_no($pr_no){
		$sql = "
			SELECT * FROM purchaserequest_main WHERE purchaseNo = '".$pr_no."' AND title_id = '".$this->session->userdata('Proj_Main')."';
		";		
		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			return true;
		}else{
			return false;
		}

	}

	public function get_lookup($arg){
		$item_no = $arg['item_no'];

		$sql = "SELECT
					purchase_order_main.po_date,
					CONCAT('PO ',purchase_order_main.reference_no) 'po_no',
					(SELECT business_name FROM business_list WHERE business_list.business_number = purchase_order_main.supplierID) 'supplier',
					purchase_order_details.itemNo,
					(SELECT description FROM setup_group_detail WHERE setup_group_detail.group_detail_id = purchase_order_details.itemNo) 'item_name',
					purchase_order_details.unit_cost
				FROM purchase_order_details
				INNER JOIN purchase_order_main
				ON (purchase_order_details.po_id = purchase_order_main.po_id)
				WHERE purchase_order_main.status IN ('APPROVED','COMPLETE')
				AND purchase_order_main.reference_no <> 'DIRECT PO'
				AND purchase_order_details.itemNo = '{$item_no}'
				ORDER BY purchase_order_main.po_date DESC";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	function save_joborder(){
				
		$this->db->trans_begin();

		$project_code = $this->session->userdata('Proj_Code');

		if($project_code == 0){
			return false;
			exit(0);
		}
				
		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;
		
		$insert = array(
		     'job_order_date'=>$this->input->post('Date'),
		     'approved_by'=>$this->input->post('approvedBy'),
		     'prepared_by'=>$prepared_by,
		     'attention'=>$this->input->post('attention'),
		     'remarks'=>$this->input->post('remarks'),
		     'project_id'=>$this->input->post('project_id'),
		     'supplier_id'=>$this->input->post('supplier_id'),
		     'supplier_name'=>$this->input->post('supplier')
			);

		$this->db->insert('job_order_main',$insert);

		$jo_id = $this->db->insert_id();

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $key=>$row_data){				
				$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
				$amount = str_replace(',','',$row_data['amount']);
				$data[$key] = array(
					'job_order_id'=>$jo_id,
					'item_description'=>$row_data['item_description'],
					'amount'=>$amount,
					'remark'=>$remarks
				);
			}
			$this->db->insert_batch('job_order_details',$data);
		}

		$sql = "SELECT SUM(amount) AS 'total' FROM job_order_details WHERE job_order_id = '{$jo_id}'";
		$result = $this->db->query($sql);

		$total = 0;
		foreach($result->result_array() as $row){
			$total = $row['total'];
		}

		$update = array(
					'job_order_no' => $this->get_series_no(),
					'total_amount' => $total
			);
		$this->db->where('id',$jo_id);
		$this->db->update('job_order_main',$update);
		
		
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

	function get_job_order($page = '',$params){
		$page = 1;
		$where = "";
		$search = "";

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" status = 'ACTIVE' ";
				break;
				case "approved":
					$where .=" status = 'APPROVED' ";
				break;
				case "rejected":
					$where .=" status = 'REJECTED' ";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$search .= "
					 	AND (
							job_order_no LIKE '%{$params['search']}%' OR
							job_order_date LIKE'%{$params['search']}%'
						)
				";

		}

		

		$sql2 = "
			SELECT
				*,
				(SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE setup_project.project_id = job_order_main.project_id)'project_name',
				(SELECT COUNT(*) 'cnt' FROM job_order_details WHERE job_order_details.job_order_id = job_order_main.id) 'item_cnt'
			FROM job_order_main
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
			  ON (signatory.emp_number = job_order_main.prepared_by)
			WHERE 
				{$where}
				{$search}
			ORDER BY job_order_date DESC, id DESC  
		";		

		$limit = $this->config->item('limit');	
			
		$start = ($page * $limit) - $limit;		
		$result = $this->db->query($sql2);

		$next = "";
		if($result->num_rows() > ($page * $limit) ){
			$next = $page + 1;
		}		
		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);	
		return $output;

	}

	public function get_jo_main($id){
		
		$sql = "
		SELECT
		 *,
		(SELECT project_no FROM setup_project WHERE setup_project.project_id = job_order_main.project_id) 'project_no',
		(SELECT date_started FROM setup_project WHERE setup_project.project_id = job_order_main.project_id) 'date_started',
		(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'supplier', 
		(SELECT CONCAT(project_no,' - ',project_name) AS 'Project_F' FROM setup_project WHERE setup_project.project_id = job_order_main.project_id)'project_name',
		(SELECT COUNT(*) 'cnt' FROM job_order_details WHERE job_order_id = id) 'item_cnt',
		(SELECT
		    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
		FROM 
		    `hr_employee`
		    INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = approved_by) 'approvedByName',
		(SELECT
		    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
		FROM 
		    `hr_employee`
		    INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = prepared_by) 'preparedByName'
		FROM job_order_main
		WHERE job_order_no = '{$id}'	
		ORDER BY job_order_date DESC, id DESC
		";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	public function get_jo_details($id){
		
		$sql    = "SELECT * FROM job_order_details WHERE job_order_id = '".$id."';";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function jostatus($arg){
		
		$update = array(
			'status'=>$arg['jo_status'],
		);

		$this->db->where('id',$arg['id']);
		$this->db->update('job_order_main',$update);

		return true;

	}

	function save_receiving(){
				
		$this->db->trans_begin();

		$project_code = $this->session->userdata('Proj_Code');

		if($project_code == 0){
			return false;
			exit(0);
		}
				
		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;
		
		$insert = array(
		     'date_received'=>$this->input->post('Date'),
		     'employee_receiver_id'=>$this->input->post('receivedBy'),
		     'employee_checker_id'=>$prepared_by,
		     'delivered_by'=>$this->input->post('remarks'),
		     'project_id'=>$this->input->post('project_id'),
		     'supplier_id'=>$this->input->post('supplier_id'),
		     'supplier_invoice'=>'DIRECT RECEIVING',
		     'title_id' => '1',
		     'invoice_date' => $this->input->post('Date'),
		     'received_status' => 'COMPLETE'
			);

		$this->db->insert('receiving_main',$insert);

		$rr_id = $this->db->insert_id();

		$data = array();
		$data1 = array();

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $key=>$row_data){				
				$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
				$unit_cost = str_replace(',','',$row_data['unit_cost']);
				$amount = str_replace(',','',$row_data['amount']);
				$data[$key] = array(
					'receipt_id'=>$rr_id,
					'po_id' => '0',
					'item_id' => $row_data['item_id'],
					'item_name_ordered'=>$row_data['item_description'],
					'item_name_actual'=>$row_data['item_description'],
					'item_quantity_ordered'=>$row_data['quantity'],
					'item_quantity_actual'=>$row_data['quantity'],
					'item_cost_ordered'=>$unit_cost,
					'item_cost_actual'=>$unit_cost,
					'unit_msr' => $row_data['unit_measure'],
					'received' => 'TRUE',
					'item_remarks' => $remarks
				);

				/*$data1[$key] = array(
					'item_no' => $row_data['item_id'],
					'item_description' => $row_data['item_description'],
					'item_cost' => $unit_cost,
					'supplier_id' => $this->input->post('supplier_id'),
					'received_quantity' => $row_data['quantity'],
					'withdrawn_quantity' => '0',
					'receipt_no' => $rr_id,
					'withdraw_no' => '0',
					'registered_no' => '0',
					'division_code' => '0',
					'account_code' => '0',
					'project_location_id' => $this->input->post('project_id'),
					'title_id' => '1',
					'prepared_by' => $prepared_by
				);*/
			}
			$this->db->insert_batch('receiving_details',$data);
			/*$this->db->insert_batch('inventory_main',$data1);*/
		}

		$update = array(
					'receipt_no' => $this->get_rr_no()
			);
		$this->db->where('receipt_id',$rr_id);
		$this->db->update('receiving_main',$update);

		$this->db->select('receipt_no');
		$this->db->from('receiving_main');
		$this->db->where('receipt_id',$rr_id);
		$query = $this->db->get();

		$receipt_no = "";
		foreach($query->result_array() as $row){
			$receipt_no = $row['receipt_no'];
		}

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $row_data){
				$item_id = $row_data['item_id'];
				$location_id = $this->input->post('project_id');
				$quantity = $row_data['quantity'];

				$insert = array(
						'item_id' => $item_id,
						'location_id' => $location_id,
						'debit' => $quantity,
						'credit' => '0',
						'year' => date('Y'),
						'type' => 'DIRECT RECEIVING',
						'trans_id' => $rr_id,
						'trans_date' => $this->input->post('Date'),
						'reference_no' => $receipt_no,
						'emp_id' => $this->session->userdata('emp_id'),
						'office_id' => '1'
					);
				$this->db->insert('inventory_stock_card',$insert);

				/*$this->db->select('*');
				$this->db->from('inventory_master');
				$this->db->where('item_no',$row_data['item_id']);
				$this->db->where('location_id',$this->input->post('project_id'));
				$result = $this->db->get();

				if($result->num_rows() > 0){
					$sql = "UPDATE inventory_master SET receive_qty = ROUND(receive_qty + '{$quantity}',2),current_qty = ROUND(current_qty + '{$quantity}',2) WHERE item_no = '{$item_id}' AND location_id = '{$location_id}'";
					$this->db->query($sql);
				}else{
					$insert = array(
							'item_no' => $item_id,
							'location_id' => $location_id,
							'project_id' => '1',
							'receive_qty' => $quantity,
							'withdraw_qty' => '0',
							'current_qty' => $quantity
						);
					$this->db->insert('inventory_master',$insert);
				}*/
			}
			
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

	public function get_receiving_list($page,$params = ""){
				
		$where  = "";
		$search = "";
		$page = (int)$page;

		$branch_type = $this->session->userdata('branch_type');		
		switch($branch_type){
			case "MAIN OFFICE":
				 $where .= "
				   title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where .= "
				   (project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."' )
				";				
			break;
			default:
				$where .= "
				   (project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."' )
				";	
			break;
		}

	

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":
					$where .= "AND supplier_invoice = 'DIRECT RECEIVING'";
				break;
				case "pending":
					$where .=" AND  received_status = 'ACTIVE' AND supplier_invoice = 'DIRECT RECEIVING'";
				break;
				case "approved":
					$where .=" AND received_status = 'COMPLETE' AND supplier_invoice = 'DIRECT RECEIVING'";
				break;
				case "rejected":
					$where .=" AND received_status = 'CANCELLED' AND supplier_invoice = 'DIRECT RECEIVING'";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$params['search'] = trim(str_replace('%20','',$params['search']));

				$search .= "
						(
							receipt_no LIKE '%{$params['search']}%' OR
							date_received LIKE '%{$params['search']}%' 							
						)
				";
		}

		$sql2 = "SELECT
					*,
					receipt_no 'po_number',
					delivered_by 'po_remarks',
					date_received 'dtDelivery',
					received_status 'p_status',
					'' AS  'cancel_remarks',
					(SELECT CONCAT(project_name,' - ',project_location)  FROM setup_project WHERE setup_project.project_id = receiving_main.project_id) 'project_requestor',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_receiver_id) 'receivedBy_name',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_checker_id) 'preparedBy_name',
					(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'Supplier',
					(SELECT SUM(item_quantity_actual * item_cost_actual) FROM receiving_details WHERE receiving_main.receipt_id = receiving_details.receipt_id) 'total_cost',
					(SELECT COUNT(*) FROM receiving_details WHERE receiving_main.receipt_id = receiving_details.receipt_id) 'total_item'
				FROM receiving_main
				WHERE {$where} {$search}
				ORDER BY receipt_id DESC, date_received DESC";


		$limit = $this->config->item('limit');
		$start = ($page * $limit) - $limit;
		$next = '';
		$result = $this->db->query($sql2);			
		if($result->num_rows() > ($page * $limit)){
			$next = $page + 1;
		}
		
		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );
		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;

	}

	public function get_receiving_main($rr_no){
		$sql = "SELECT
					*,
					'' AS 'pr_id',
					receipt_id 'po_id',
					receipt_no 'po_number',
					delivered_by 'po_remarks',
					date_received 'po_date',
					received_status 'p_status',
					'' AS  'cancel_remarks',
					(SELECT CONCAT(project_name,' - ',project_location)  FROM setup_project WHERE setup_project.project_id = receiving_main.project_id) 'project_requestor',
					(SELECT project_no FROM setup_project WHERE project_id = receiving_main.project_id)'project_no',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_receiver_id) 'receivedBy_name',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_checker_id) 'preparedBy_name',
					(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'Supplier',
					(SELECT SUM(item_quantity_actual * item_cost_actual) FROM receiving_details WHERE receiving_main.receipt_id = receiving_details.receipt_id) 'total_cost',
					(SELECT COUNT(*) FROM receiving_details WHERE receiving_main.receipt_id = receiving_details.receipt_id) 'total_item'
				FROM receiving_main
				WHERE receipt_no = '{$rr_no}'
				AND title_id = '".$this->session->userdata('Proj_Main')."'";

		$result = $this->db->query($sql);		
		$this->db->close();				
		return $result->row_array();

	}

	public function get_rr_details($receipt_id = ''){
		$sql = "
			SELECT 
				a.*,
				b.`account_id`,
				a.item_quantity_actual 'quantity',
				a.item_cost_actual 'unit_cost',
				a.item_name_actual 'item_name' 
			FROM receiving_details a
			INNER JOIN setup_group_detail b
			ON (a.item_id = b.`group_detail_id`)
			WHERE a.receipt_id  = '".$receipt_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	function save_transfer(){
		$this->db->trans_begin();

		$project_code = $this->session->userdata('Proj_Code');

		if($project_code == 0){
			return false;
			exit(0);
		}

		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;

		$insert = array(
					'transfer_no' => $this->get_tr_no(),
					'transaction_date' => $this->input->post('Date'),
					'request_status' => 'APPROVED',
					'prepared_by' => $prepared_by,
					'request_by' => 'DIRECT TRANSFER',
					'approved_by' => $prepared_by,
					'remarks' => $this->input->post('remarks'),
					'to_project_id' => $this->input->post('project_id'),
					'to_title_id' => $this->session->userdata('Proj_Main'),
					'project_id' => $project_code,
					'title_id' => $this->session->userdata('Proj_Main'),
					'status' => 'APPROVED'
			);
		$this->db->insert('item_transfer_main',$insert);

		$tr_id = $this->db->insert_id();

		$data = array();
		$data1 = array();

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $key=>$row_data){				
				$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
			 
				$data[$key] = array(
					'transfer_id'=>$tr_id,
					'item_no' => $row_data['item_id'],
					'item_description' => $row_data['item_description'],
					'unit_measure' => $row_data['unit_measure'],
					'request_qty' => $row_data['quantity']
				);

				/*$data1[$key] = array(
					'item_no' => $row_data['item_id'],
					'item_description' => $row_data['item_description'],
					'item_cost' => '0',
					'supplier_id' => '0',
					'received_quantity' => $row_data['quantity'],
					'withdrawn_quantity' => '0',
					'receipt_no' => $tr_id,
					'withdraw_no' => '0',
					'registered_no' => '0',
					'division_code' => '0',
					'account_code' => '0',
					'project_location_id' => $this->input->post('project_id'),
					'title_id' => '1',
					'prepared_by' => $prepared_by
				);*/
			}
			$this->db->insert_batch('item_transfer_details',$data);
			/*$this->db->insert_batch('inventory_main',$data1);*/
		}

		$this->db->select('transfer_no');
		$this->db->from('item_transfer_main');
		$this->db->where('id',$tr_id);
		$query = $this->db->get();

		$transfer_no = "";
		foreach($query->result_array() as $row){
			$transfer_no = $row['transfer_no'];
		}

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $row_data){
				$qty = $row_data['quantity'];
				$item_id = $row_data['item_id'];
				$title_id = $this->session->userdata('Proj_Main');
				$project_id = $this->input->post('project_id');

				$insert = array(
						'item_id' => $item_id,
						'location_id' => $project_id,
						'debit' => '0',
						'credit' => $qty,
						'year' => date('Y'),
						'type' => 'TRANSFER',
						'trans_id' => $tr_id,
						'trans_date' => $this->input->post('Date'),
						'reference_no' => $transfer_no,
						'emp_id' => $this->session->userdata('emp_id')
					);
				$this->db->insert('inventory_stock_card',$insert);
				
				/*$sql = "UPDATE inventory_master SET withdraw_qty = ROUND((withdraw_qty + '{$qty}'),2),transfer_qty = ROUND((transfer_qty + '{$qty}'),2),current_qty = ROUND((current_qty - '{$qty}'),2) WHERE item_no = '{$item_id}' AND location_id = '{$project_code}' LIMIT 1";
				$this->db->query($sql);

				$this->db->select('*');
				$this->db->from('inventory_master');
				$this->db->where('location_id',$project_id);
				$this->db->where('item_no',$item_id);
				$r = $this->db->get();

				if($r->num_rows() > 0){
					$sql = "UPDATE inventory_master SET receive_qty = ROUND((receive_qty + '{$qty}'),2),current_qty = ROUND((current_qty + '{$qty}'),2) WHERE item_no = '{$item_id}' AND location_id = '{$project_id}' LIMIT 1";
					$this->db->query($sql);
				}else{
					$ins = array(
							'item_no' => $item_id,
							'location_id' => $project_id,
							'project_id' => $title_id,
							'receive_qty' => $qty,
							'withdraw_qty' => '0',
							'current_qty' => $qty
						);
					$this->db->insert('inventory_master',$ins);
				}*/
			}
			
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

	function save_return(){
				
		$this->db->trans_begin();

		$project_code = $this->session->userdata('Proj_Code');

		if($project_code == 0){
			return false;
			exit(0);
		}
				
		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;
		
		$insert = array(
		     'date_received'=>$this->input->post('Date'),
		     'employee_receiver_id'=>$this->input->post('receivedBy'),
		     'employee_checker_id'=>$prepared_by,
		     'delivered_by'=>$this->input->post('remarks'),
		     'project_id'=>$this->input->post('project_id'),
		     'supplier_id'=>'0',
		     'supplier_invoice'=>'RETURN',
		     'title_id' => '1',
		     'invoice_date' => $this->input->post('Date'),
		     'received_status' => 'COMPLETE'
			);

		$this->db->insert('return_main',$insert);

		$rr_id = $this->db->insert_id();

		$data = array();
		$data1 = array();

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $key=>$row_data){				
				$remarks = (isset($row_data['remarks']))? $row_data['remarks'] : '' ;
				$unit_cost = str_replace(',','',$row_data['unit_cost']);
				$amount = str_replace(',','',$row_data['amount']);
				$data[$key] = array(
					'receipt_id'=>$rr_id,
					'po_id' => '0',
					'item_id' => $row_data['item_id'],
					'item_name_ordered'=>$row_data['item_description'],
					'item_name_actual'=>$row_data['item_description'],
					'item_quantity_ordered'=>$row_data['quantity'],
					'item_quantity_actual'=>$row_data['quantity'],
					'item_cost_ordered'=>$unit_cost,
					'item_cost_actual'=>$unit_cost,
					'unit_msr' => $row_data['unit_measure'],
					'received' => 'TRUE',
					'item_remarks' => $remarks
				);

				/*$data1[$key] = array(
					'item_no' => $row_data['item_id'],
					'item_description' => $row_data['item_description'],
					'item_cost' => $unit_cost,
					'supplier_id' => $this->input->post('supplier_id'),
					'received_quantity' => $row_data['quantity'],
					'withdrawn_quantity' => '0',
					'receipt_no' => $rr_id,
					'withdraw_no' => '0',
					'registered_no' => '0',
					'division_code' => '0',
					'account_code' => '0',
					'project_location_id' => $this->input->post('project_id'),
					'title_id' => '1',
					'prepared_by' => $prepared_by
				);*/
			}
			$this->db->insert_batch('return_details',$data);
			/*$this->db->insert_batch('inventory_main',$data1);*/
		}

		$update = array(
					'receipt_no' => $this->get_rx_no()
			);
		$this->db->where('receipt_id',$rr_id);
		$this->db->update('return_main',$update);

		$this->db->select('receipt_no');
		$this->db->from('return_main');
		$this->db->where('receipt_id',$rr_id);
		$query = $this->db->get();

		$receipt_no = "";
		foreach($query->result_array() as $row){
			$receipt_no = $row['receipt_no'];
		}

		if(is_array($this->input->post('data'))){
			foreach($this->input->post('data') as $row_data){
				$item_id = $row_data['item_id'];
				$location_id = $this->input->post('project_id');
				$quantity = $row_data['quantity'];

				$insert = array(
						'item_id' => $item_id,
						'location_id' => $location_id,
						'debit' => $quantity,
						'credit' => '0',
						'year' => date('Y'),
						'type' => 'RETURN',
						'trans_id' => $rr_id,
						'trans_date' => $this->input->post('Date'),
						'reference_no' => $receipt_no,
						'emp_id' => $this->session->userdata('emp_id'),
						'office_id' => '1'
					);
				$this->db->insert('inventory_stock_card',$insert);

				/*$this->db->select('*');
				$this->db->from('inventory_master');
				$this->db->where('item_no',$row_data['item_id']);
				$this->db->where('location_id',$this->input->post('project_id'));
				$result = $this->db->get();

				if($result->num_rows() > 0){
					$sql = "UPDATE inventory_master SET receive_qty = ROUND(receive_qty + '{$quantity}',2),current_qty = ROUND(current_qty + '{$quantity}',2) WHERE item_no = '{$item_id}' AND location_id = '{$location_id}'";
					$this->db->query($sql);
				}else{
					$insert = array(
							'item_no' => $item_id,
							'location_id' => $location_id,
							'project_id' => '1',
							'receive_qty' => $quantity,
							'withdraw_qty' => '0',
							'current_qty' => $quantity
						);
					$this->db->insert('inventory_master',$insert);
				}*/
			}
			
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

	public function get_return_list($page = "",$params = ""){
		$page = 1;		
		$where  = "";
		$search = "";

		$branch_type = $this->session->userdata('branch_type');		
		switch($branch_type){
			case "MAIN OFFICE":
				 $where .= "
				   title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where .= "
				   (project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."' )
				";				
			break;
			default:
				$where .= "
				   (project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."' )
				";	
			break;
		}

	

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":
					$where .= "AND supplier_invoice = 'RETURN'";
				break;
				case "pending":
					$where .=" AND  received_status = 'ACTIVE' AND supplier_invoice = 'RETURN'";
				break;
				case "approved":
					$where .=" AND received_status = 'COMPLETE' AND supplier_invoice = 'RETURN'";
				break;
				case "rejected":
					$where .=" AND received_status = 'CANCELLED' AND supplier_invoice = 'RETURN'";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$params['search'] = trim(str_replace('%20','',$params['search']));

				$search .= "
						(
							receipt_no LIKE '%{$params['search']}%' OR
							date_received LIKE '%{$params['search']}%' 							
						)
				";
		}

		$sql2 = "SELECT
					*,
					receipt_no 'po_number',
					delivered_by 'po_remarks',
					date_received 'dtDelivery',
					received_status 'p_status',
					'' AS  'cancel_remarks',
					(SELECT CONCAT(project_name,' - ',project_location)  FROM setup_project WHERE setup_project.project_id = return_main.project_id) 'project_requestor',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_receiver_id) 'receivedBy_name',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_checker_id) 'preparedBy_name',
					(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'Supplier',
					(SELECT SUM(item_quantity_actual * item_cost_actual) FROM return_details WHERE return_main.receipt_id = return_details.receipt_id) 'total_cost',
					(SELECT COUNT(*) FROM return_details WHERE return_main.receipt_id = return_details.receipt_id) 'total_item'
				FROM return_main
				WHERE {$where} {$search}
				ORDER BY receipt_id DESC, date_received DESC";


		$limit = $this->config->item('limit');
		$start = ($page * $limit) - $limit;
		$next = '';
		$result = $this->db->query($sql2);			
		if($result->num_rows() > ($page * $limit)){
			$next = $page + 1;
		}
		
		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );
		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;

	}

	public function get_return_main($rr_no){
		$sql = "SELECT
					*,
					'' AS 'pr_id',
					receipt_id 'po_id',
					receipt_no 'po_number',
					delivered_by 'po_remarks',
					date_received 'po_date',
					received_status 'p_status',
					'' AS  'cancel_remarks',
					(SELECT CONCAT(project_name,' - ',project_location)  FROM setup_project WHERE setup_project.project_id = return_main.project_id) 'project_requestor',
					(SELECT project_no FROM setup_project WHERE project_id = return_main.project_id)'project_no',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_receiver_id) 'receivedBy_name',
					(SELECT
						CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
						FROM 
						`hr_employee`
						INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_checker_id) 'preparedBy_name',
					(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'Supplier',
					(SELECT SUM(item_quantity_actual * item_cost_actual) FROM return_details WHERE return_main.receipt_id = return_details.receipt_id) 'total_cost',
					(SELECT COUNT(*) FROM return_details WHERE return_main.receipt_id = return_details.receipt_id) 'total_item'
				FROM return_main
				WHERE receipt_no = '{$rr_no}'
				AND title_id = '".$this->session->userdata('Proj_Main')."'";

		$result = $this->db->query($sql);		
		$this->db->close();				
		return $result->row_array();

	}

	public function get_return_details($receipt_id = ''){
		$sql = "
			SELECT 
				a.*,
				b.`account_id`,
				a.item_quantity_actual 'quantity',
				a.item_cost_actual 'unit_cost',
				a.item_name_actual 'item_name',
				b.unit_measure 'unit_measure' 
			FROM return_details a
			INNER JOIN setup_group_detail b
			ON (a.item_id = b.`group_detail_id`)
			WHERE a.receipt_id  = '".$receipt_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	public function get_returnNo($returnNo){

	
		$sql1 = "
				SELECT
				  *,
				  (SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = return_main.project_id)'from_projectCodeName',
				  (SELECT project_no FROM setup_project WHERE project_id = return_main.project_id)'project_no',
				  (SELECT title_name FROM project_title  WHERE title_id = return_main.project_id)'from_projectMainName',
				  (SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = return_main.project_id)'to_projectCodeName',
				  (SELECT title_name FROM project_title  WHERE title_id = return_main.project_id)'to_projectMainName',
				  (SELECT COUNT(*) 'cnt' FROM return_details WHERE return_details.receipt_id = return_main.return_id) 'item_cnt',  
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_checker_id) 'person_preparedBy',
					(SELECT
					    CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					    `hr_employee`
					    INNER JOIN `hr_person_profile` 
						ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
						WHERE `hr_employee`.`emp_number` = employee_receiver_id) 'person_receivedBy'
				 FROM return_main
				WHERE receipt_no = '".$returnNo."'
				AND title_id = '".$this->session->userdata('Proj_Main')."'
				GROUP BY receipt_id
				ORDER BY date_received DESC;
			";


		$result = $this->db->query($sql1);
		$this->db->close();		
		return $result->row_array();		
	}

}