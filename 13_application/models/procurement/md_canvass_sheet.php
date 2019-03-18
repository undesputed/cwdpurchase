<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_canvass_sheet extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative(){
		$sql    = "CALL canvass_display_main1(?);";
		$data   = array($this->input->post('location'));
		$result = $this->db->query($sql,$data);		
		$this->db->close();
		return $result;
	}


	public function get_pr(){		
		$sql = "CALL purchase_display_main()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_canvassMain($page,$params = ""){

		$where  = "";
		$search = "";
		$page = (int)$page;

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" AND canvass_main.approval = '0' AND canvass_main.status <> 'CANCELLED' ";
				break;
				case "approved":
					$where .=" AND canvass_main.approval <> '0' ";
				break;
				case "rejected":
					$where .=" AND canvass_main.status = 'CANCELLED'";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$search .= "
					 WHERE (
							c_number LIKE '%{$params['search']}%' OR
							purchaseNo LIKE '%{$params['search']}%' OR
							from_projectCodeName LIKE '%{$params['search']}%' OR 
							preparedBy_name LIKE '%{$params['search']}%' OR
							c_date LIKE'%{$params['search']}%'
						)
				";	
		}
		

		/*$sql2 = "
				SELECT
				*
				FROM 
				(
				SELECT 
				canvass_main.can_id,
				canvass_main.c_number,
				canvass_main.c_date,
				canvass_main.pr_id,
				canvass_main.status,
				canvass_main.approvedBy,
				canvass_main.preparedBy,
				canvass_main.date_saved,
				canvass_main.title_id,
				canvass_main.project_id,
				canvass_main.po_no,
				canvass_details.no_supplier,
				canvass_details.no_items,
				pm.purchaseNo,
				transaction_history.from_projectCodeName,
				IF(canvass_main.approval = 0,'FALSE','TRUE') 'approval',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = canvass_main.preparedBy
				) 'preparedBy_name'			
				FROM canvass_main
				INNER JOIN (
				SELECT *,COUNT(DISTINCT itemNo)'no_items',COUNT(DISTINCT supplier_id)'no_supplier' FROM canvass_details 
				GROUP BY can_id
				)canvass_details
				ON (canvass_details.can_id = canvass_main.can_id)
				LEFT JOIN purchaserequest_main pm
				ON (pm.pr_id = canvass_main.pr_id)
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

				) transaction_history
				ON (pm.pr_id = transaction_history.pr_id)
				WHERE canvass_main.title_id = '".$this->session->userdata('Proj_Main')."' AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
				{$where}
				ORDER BY c_date DESC,can_id desc
				) a
				{$search}
		";*/

		$sql2 = "
		SELECT
				*
				FROM 
				(
				SELECT 
				canvass_main.can_id,
				canvass_main.c_number,
				canvass_main.c_date,
				canvass_main.pr_id,
				canvass_main.status,
				canvass_main.approvedBy,
				canvass_main.preparedBy,
				canvass_main.date_saved,
				canvass_main.title_id,
				canvass_main.project_id,
				canvass_main.po_no,
				canvass_details.no_supplier,
				canvass_details.no_items,
				pm.purchaseNo,				
				(SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName',	
				IF(canvass_main.approval = 0,'FALSE','TRUE') 'approval',
				(SELECT
				CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
				FROM 
				`hr_employee`
				INNER JOIN `hr_person_profile` 
				ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				WHERE `hr_employee`.`emp_number` = canvass_main.preparedBy
				) 'preparedBy_name'			
				FROM canvass_main
				INNER JOIN (
					SELECT *,COUNT(DISTINCT itemNo)'no_items',COUNT(DISTINCT supplier_id)'no_supplier' FROM canvass_details 
					GROUP BY can_id
				)canvass_details
				ON (canvass_details.can_id = canvass_main.can_id)
				LEFT JOIN purchaserequest_main pm
				ON (pm.pr_id = canvass_main.pr_id)
				INNER JOIN transaction_history th
				ON (pm.pr_id = th.reference_id)								
				WHERE canvass_main.title_id = '".$this->session->userdata('Proj_Main')."' AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
				{$where}
				GROUP BY can_id
				ORDER BY c_date DESC,can_id desc
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

	public function get_canvassMain_notification(){

		/*$sql = "SELECT * FROM canvass_main WHERE title_id = '".$this->session->userdata('Proj_Main')."' AND project_id = '".$this->session->userdata('Proj_Code')."';";*/

		$sql1 = "
			SELECT 
			canvass_main.*,
			canvass_details.no_supplier,
			canvass_details.no_items,
			pm.purchaseNo,
			IF(canvass_main.approval = 0,'FALSE','TRUE') 'approval',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.preparedBy
			) 'preparedBy_name'
			FROM canvass_main
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo)'no_items',COUNT(DISTINCT supplier_id)'no_supplier' FROM canvass_details 
			GROUP BY can_id
			)canvass_details
			ON (canvass_details.can_id = canvass_main.can_id)
			LEFT JOIN purchaserequest_main pm
			ON (pm.pr_id = canvass_main.pr_id)
			WHERE canvass_main.title_id = '".$this->session->userdata('Proj_Main')."' AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
			AND STATUS <> 'CANCELLED'
			AND canvass_main.approval = 0
			ORDER by c_date DESC
			;
		";

		$result = $this->db->query($sql1);		
		return $result->num_rows();
		
	}




	public function get_canvassMain_no($cv_number){
		$sql = "
			SELECT 
			canvass_main.*,
			canvass_main.approval 'final_approval',
			canvass_details.no_supplier,
			canvass_details.no_items,
			pm.purchaseNo,
			IF(canvass_main.approval = 0,'FALSE','TRUE') 'approval',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.preparedBy
			) 'preparedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.approvedBy
			) 'approvedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.approval
			) 'final_approval_name'			
			FROM canvass_main
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo)'no_items',COUNT(DISTINCT supplier_id)'no_supplier' FROM canvass_details 
			GROUP BY can_id
			)canvass_details
			ON (canvass_details.can_id = canvass_main.can_id)
			LEFT JOIN purchaserequest_main pm
			ON (pm.pr_id = canvass_main.pr_id)
			WHERE canvass_main.c_number = '".$cv_number."'
			AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
			AND canvass_main.title_id = '".$this->session->userdata('Proj_Main')."'
			;
		";

		$result = $this->db->query($sql);		
		return $result->row_array();

	}


	public function get_canvassMain_no_po($cv_number){

		$sql = "
			SELECT 
			canvass_main.*,
			canvass_main.approval 'final_approval',
			canvass_details.no_supplier,
			canvass_details.no_items,
			canvass_details.status_code,
			pm.purchaseNo,
			IF(canvass_main.approval = 0,'FALSE','TRUE') 'approval',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.preparedBy
			) 'preparedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = canvass_main.approvedBy
			) 'approvedBy_name',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 
			FROM 
			`hr_employee` 
			INNER JOIN `hr_person_profile`  
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`) 
			WHERE `hr_employee`.`emp_number` = canvass_main.approval 
			) 'final_approval_name' 
			FROM canvass_main 
			INNER JOIN ( 
			SELECT
			*,
			IF(no_approved = 0,0,IF(no_supplier = no_approved,1,2)) 'status_code'
			FROM(
			SELECT *,COUNT(DISTINCT itemNo)'no_items',COUNT(DISTINCT supplier_id)'no_supplier',COUNT(DISTINCT po_id)'no_approved'
			FROM canvass_details WHERE approvedSupplier = 'TRUE'
			GROUP BY can_id )a
			)canvass_details 
			ON (canvass_details.can_id = canvass_main.can_id) 
			LEFT JOIN purchaserequest_main pm 
			ON (pm.pr_id = canvass_main.pr_id)
			WHERE canvass_main.c_number = '".$cv_number."'
			AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
			AND canvass_main.title_id = '".$this->session->userdata('Proj_Main')."'
		";		
		$result = $this->db->query($sql);
		return $result->row_array();		
	}


	public function get_canvassMain_id($can_id){

		$sql = "SELECT * FROM canvass_main WHERE can_id = '".$can_id."'";
		$result = $this->db->query($sql);
		return $result->row_array();

	}


	public function get_approved_pr(){
		$result = $this->get_pr();
		$approved_pr = array();

		foreach($result as $key => $value){
			if($value['APPROVED'] == 'True'){
				$approved_pr[] = $value;
			}
		}
		return $approved_pr;
	}

		
	public function business(){
		$sql = "SELECT business_number 'Supplier ID',business_name 'Supplier',trade_name,address,contact_no,term_type,term_days FROM business_list WHERE `status` = 'ACTIVE'";
		$result = $this->db->query($sql);

		return $result->result_array();
	}


	public function supplier(){
		$sql = "CALL purchase_order_supplier_person()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_pr_details($pr_id){
		
		$sql = "SELECT * FROM purchaserequest_details WHERE pr_id = ? AND rem_qty > 0 AND dtl_status <> 'HOLD'";
		$result = $this->db->query($sql,array($pr_id));
		$this->db->close();
		return $result->result_array();
		
	}


	/** SAVE **/	
	
	public function save_canvass(){		

		$data = array(
						'c_number'  =>$this->input->post('canvass_no'),
						'c_date'    =>$this->input->post('date'),
						'pr_id'     =>$this->input->post('pr_id'),					
						'approvedBy'=>$this->input->post('approved_by'),
						'preparedBy'=>$this->input->post('prepared_by'),
						'title_id  '=>$this->input->post('title_id'),
        );

		$this->db->insert('canvass_main',$data);		
		$sql = "SELECT MAX(can_id) as max FROM canvass_main WHERE pr_id = ?";
		$result = $this->db->query($sql,array($this->input->post('pr_id')));
		$row = $result->row_array();		
		
		$sql  = "UPDATE purchaserequest_main SET canvass_no = ? WHERE pr_id = ?";

		$data = array(	
				$row['max'],
				$this->input->post('pr_id'),
			);
		$this->db->query($sql,$data);

		$cnt = 0;		
		foreach ($this->input->post('details') as $key => $value){
					$length = count($value);
					$supplier_array = array();
					$data = array();
					for ($i=0; $i < $length; $i++) { 
						if($i<5){
								$data['item'][] = $value[$i];
						}else{
							$cnt++;								
							if($cnt==7){
								$cnt = 0;
								$supplier_array[] = $value[$i];
								$data['supplier'][] = $supplier_array;
								$supplier_array = array();
							}else{
								$supplier_array[] = $value[$i];
							}
						}
					}

					foreach($data['supplier'] as $data_row){
		
						$sql = "CALL canvass_insert_details('".$row['max']."','".$data['item'][1]."','".$data_row[3]."','".$data_row[0]."','".$data_row[1]."','FALSE','".$data_row[2]."','".$data['item'][4]."','".$data_row[5]."','".$data_row[6]."');";		
						$this->db->query($sql);						
						$this->db->close();	

					}						
		}		

		return true;
		
	}

	public function saveCanvass($arg){

		$this->db->trans_begin();

		$sql_checker = "SELECT c_number FROM canvass_main where c_number='".$arg['c_number']."'";
		$result_checker = $this->db->query($sql_checker);
		
		if($result_checker->num_rows() > 0){
			return false;
		}
				
		$data = array(
					'c_number'  =>$arg['c_number'],
					'c_date'    =>$arg['c_date'],
					'pr_id'     =>$arg['pr_id'],
					'approvedBy'=>$arg['approvedBy'],
					'preparedBy'=>$arg['preparedBy'],
					'title_id'  =>$arg['title_id'],
					'project_id'=>$arg['project_id'],
					'is_boq_new'=>$arg['is_boq_new']
        );

		$this->db->insert('canvass_main',$data);		
		$sql  = "SELECT MAX(can_id) as max FROM canvass_main WHERE pr_id = ?";
		$result = $this->db->query($sql,array($arg['pr_id']));
		$row  = $result->row_array();		
		$sql  = "UPDATE purchaserequest_main SET canvass_no = ? WHERE pr_id = ?";
		$data = array(
				$row['max'],
				$arg['pr_id'],
			);
		$this->db->query($sql,$data);
		$can_id =  $row['max'];
		$data_post = json_decode($this->input->post('data'),true);
		$details = array();
		foreach($data_post as $key=>$row){
			foreach($row['items'] as $row1){
				$this->db->select('is_boq_new,boq_id,charging');
				$this->db->from('purchaserequest_details');
				$this->db->where('pr_id',$arg['pr_id']);
				$this->db->where('itemNo',$row1['itemNo']);
				$res = $this->db->get();

				$is_boq_new = "0";
				$boq_id = '(NULL)';
				$charging = "(NULL)";
				foreach($res->result_array() as $r){
					$is_boq_new = $r['is_boq_new'];
					$boq_id = $r['boq_id'];
					$charging = $r['charging'];
				}


				$details[] = array(
					'can_id'=>$can_id,
					'itemNo'=>$row1['itemNo'],
					'unit_cost'=>$row1['unit_cost'],
					'supplier_id'=>$row['supplier_id'],
					'supplierType'=>$row['type'],
					'stocks_sup'=>$row1['sup_qty'],
					'sup_qty'=>$row1['sup_qty'],
					'pr_qty'=>$row1['pr_qty'],
					'rem_qty'=>$row1['sup_qty'],
					'c_remarks'=>$row1['remarks'],
					'c_terms'=>$row1['supplier_remarks'],
					'percentage'=>$row1['percentage'],
					'discounted'=>$row1['discount'],
					'discounted_price'=>$row1['discounted_price'],
					'discounted_total'=>$row1['total_discounted_price'],
					'is_boq_new'=>$is_boq_new,
					'boq_id'=>$boq_id,
					'charging'=>$charging
				);
			}
		}

		$this->db->insert_batch('canvass_details', $details); 

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

	public function saveCanvass_detail($arg){
		
		/*can_id
		itemNo
		unit_cost
		supplier_id
		supplierType
		approvedSupplier
		stocks_sup
		sup_qty
		pr_qty
		rem_qty
		c_remarks*/

		$this->db->insert_batch('canvass_details', $arg); 

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

	public function update_canvass(){

		$data = array(
						'c_number'  =>$this->input->post('canvass_no'),
						'c_date'    =>$this->input->post('date'),
						'pr_id'     =>$this->input->post('pr_id'),					
						'approvedBy'=>$this->input->post('approved_by'),
						'preparedBy'=>$this->input->post('prepared_by'),
						'title_id  '=>$this->input->post('title_id'),
        );

        $this->db->where('can_id',$this->input->post('can_id'));
		$this->db->update('canvass_main',$data);


		$sql  = "UPDATE purchaserequest_main SET canvass_no = ? WHERE pr_id = ?";

		$data = array(	
				$this->input->post('can_id'),
				$this->input->post('pr_id'),
			);
		$this->db->query($sql,$data);


		$sql = "DELETE FROM `canvass_details` WHERE `can_id` = '".$this->input->post('can_id')."';";
		$this->db->query($sql);


		$cnt = 0;		
		foreach ($this->input->post('details') as $key => $value){
					$length = count($value);
					$supplier_array = array();
					$data = array();

					for ($i=0; $i < $length; $i++) { 
						if($i<5){
								$data['item'][] = $value[$i];
						}else{
							$cnt++;								
							if($cnt==7){
								$cnt = 0;
								$supplier_array[] = $value[$i];
								$data['supplier'][] = $supplier_array;
								$supplier_array = array();
							}else{
								$supplier_array[] = $value[$i];
							}
						}
					}

					foreach($data['supplier'] as $data_row){
						
						$sql = "CALL canvass_insert_details('".$this->input->post('can_id')."','".$data['item'][1]."','".$data_row[3]."','".$data_row[0]."','".$data_row[1]."','FALSE','".$data_row[2]."','".$data['item'][4]."','".$data_row[5]."','".$data_row[6]."');";		
						$this->db->query($sql);
						$this->db->close();

					}
		}



	}



	public function routing($pr_id){

		$sql = "SELECT routing_id FROM routing_slip_main WHERE pr_id = '".$pr_id."'";
		
		$result = $this->db->query($sql);
		$row = $result->row_array();
	
		$sql = "SELECT * FROM routing_slip_details WHERE time_released = '' AND routing_id = ?";

		$result = $this->db->query($sql,array($row['routing_id']));


		if($row['routing_id']> 0){

			if($result->num_rows() >0){
				$data = $result->$row_array();
				$sql = "UPDATE routing_slip_details SET date_released = ?, time_released = ?,notify = '1',TRANSACTION = ? WHERE detail_id = ?";
				$this->db->query($sql,array(date("Y-m-d"), date('H:i:s A'),'CANVASS',$data['detail_id']));
			}else{
				$sql = "CALL routing_insert_details(?,?,?,?,?,?,?,?)";	
				$data = array(
						$row['routing_id'],
						$this->session->userdata('Proj_Main'),              	
		                date("Y-m-d"),
		                date('H:i:s A'),
		                date("Y-m-d"),
		                date('H:i:s A'),
		                "1",
		                "CANVASS",
					);
				$this->db->query($sql,$data);				
			}
		}

		return true;		
		
	}



	function cumulative_details(){
		$sql    = "CALL canvass_supplier_SQL_items('".$this->input->post('canvass_id')."')";
		$result = $this->db->query($sql);		
		$this->db->close();		
		$result = $result->row_array();
		if($result['RESULT']==''){
			return false;
		}
		$result = $this->db->query($result['RESULT']);
		return $result;
	}

	function canvass_main(){
		$sql    = "SELECT * FROM canvass_main where can_id = '".$this->input->post('canvass_id')."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}


	function canvass_main_id(){
		$sql    = "SELECT * FROM canvass_main where can_id = '".$this->input->post('id')."'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}

	function get_canvassDetails($id = ''){
		if(empty($id)){
			$id = $this->input->post('id');	
		}
		
		$sql = "
				SELECT *,(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
				WHERE can_id = '".$id."'
				AND approvedSupplier = 'TRUE'
			   ";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}


	function get_canvassDetails_2($id = ''){
		
		$sql = "
				SELECT *,(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
				WHERE can_id = '".$id."'
			   ";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}

	function get_canvassDetails_2_approved($id = ''){
		
		$sql = "
				SELECT *,(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
				WHERE can_id = '".$id."'
				AND canvass_details.approvedSupplier = 'TRUE'
			   ";
		$result = $this->db->query($sql); 
		return $result->result_array(); 
	}

	function get_canvass_details3($arg){
		/*	
				$sql = "
				SELECT *,
				sgd.description 'itemName',
				sgd.unit_measure 'unit_measure',
				(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail sgd
				ON (sgd.group_detail_id = canvass_details.itemNo)
				WHERE can_id = '".$arg['can_id']."'
				AND canvass_details.supplier_id = '".$arg['supplier_id']."'
				AND approvedSupplier = 'TRUE'
		";
		*/

		$sql = " 

			SELECT
			  *,
			  item.item_name 'itemName',
			  sgd.unit_measure             'unit_measure',
			  (IF(canvass_details.supplierType='BUSINESS', (SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename)) 'Supplier' FROM hr_person_profile WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id) ORDER BY `Supplier` ASC)))    'Supplier',
			  canvass_details.unit_cost    'supplier_cost'
			  FROM canvass_details
			  INNER JOIN setup_group_detail sgd
			    ON (sgd.group_detail_id = canvass_details.itemNo)
			  INNER JOIN (
				SELECT 
				sgd.group_detail_id 'item_no',
				CONCAT(sg.group_description,' - ',sgd.description) 'item_name',
				sg.group_description,
				sgd.unit_measure
				FROM setup_group_detail sgd
				INNER JOIN setup_group sg
				ON (sgd.group_id = sg.group_id)
			  ) item
			  ON (item.item_no = canvass_details.itemNo)
			   INNER JOIN canvass_main
			   ON (canvass_main.can_id = canvass_details.`can_id`)   
			   INNER JOIN purchaserequest_details 
			   ON (purchaserequest_details.`pr_id` = canvass_main.`pr_id` AND purchaserequest_details.`itemNo` = item.item_no)
			  WHERE canvass_details.can_id = '".$arg['can_id']."'
			  AND canvass_details.supplier_id = '".$arg['supplier_id']."'
			  AND approvedSupplier = 'TRUE'
			  GROUP BY canvass_details.itemNo,canvass_details.unit_cost;
			";
			
		$result = $this->db->query($sql);				
		return $result->result_array();
	}

	function get_canvassDetails_2approved($id = ''){
				
		$sql = "
				SELECT *,(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
				WHERE can_id = '".$id."'
				AND approvedSupplier ='TRUE'				
			   ";
		$result = $this->db->query($sql);		
		return $result->result_array();
	}

	function get_supplier_canvass($can_id = ''){
		$sql = "
		SELECT *,(IF(canvass_details.supplierType='BUSINESS',
		(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
		CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
		FROM hr_person_profile
		WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
		ORDER BY `Supplier` ASC))) 'Supplier',
		canvass_details.unit_cost 'supplier_cost'
		FROM canvass_details 
		INNER JOIN setup_group_detail
		ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
		WHERE can_id = '".$can_id."'
		AND approvedSupplier ='TRUE'
		GROUP BY supplier_id,supplierType
		";

		$result = $this->db->query($sql);
		return $result->result_array();
		
	}



	function get_canvassDetails_supplier($arg = array()){
				
		$sql = "
				SELECT *,(IF(canvass_details.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = canvass_details.supplier_id)
				ORDER BY `Supplier` ASC))) 'Supplier',
				canvass_details.unit_cost 'supplier_cost'
				FROM canvass_details 
				INNER JOIN setup_group_detail
				ON (setup_group_detail.group_detail_id = canvass_details.itemNo)
				WHERE 
				can_id = '".$arg['can_id']."'
				AND supplier_id = '".$arg['supplier_id']."'	
				AND approvedSupplier = 'TRUE'
			   ";		
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	function approveCS(){
		$sql = "Update purchaserequest_main set approvedPR='True' where pr_id = '".$this->input->post('id')."'";
		$this->db->query($sql);
		return true;		
	}

	function canvass_display_item($id = ''){
		$id = ($this->input->post('id'))? $this->input->post('id')  : $id ;
		$sql = "CALL canvass_display_items_can_id('".$id."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_supplier($can_id,$item_no){
		$sql = "CALL canvass_display_supplier_items('".$can_id."','".$item_no."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function update_approvedBy($data){
		$sql = "UPDATE canvass_main SET approvedBy='".$data[0]['approval_by']."' WHERE can_id = '".$data[0]['can_id']."'";
		$this->db->query($sql);
		return true;
	}

	function update_canvass_details_status($data){
		$update = array(
			'approvedSupplier'=>'FALSE'
			);

		$this->db->where('can_id',$data['can_id']);
		$this->db->where('itemNo',$data['item_no']);
		$this->db->update('canvass_details',$update);

		$this->db->set(array(
			'approvedSupplier'=>strtoupper($data['status']),
		));

		$this->db->where('can_id',$data['can_id']);
		$this->db->where('itemNo',$data['item_no']);
		$this->db->where('supplier_id',$data['supplier_id']);
		$this->db->update('canvass_details');
		
		return array('status'=>$data['status']);		
	}

	function update_canvass_details_supplier($data){		

		$update = array(
			'approvedSupplier'=>'FALSE'
			);

		$this->db->where('can_id',$data['can_id']);		
		$this->db->update('canvass_details',$update);

		

		$update = array(
			'approvedSupplier'=>$data['status']
			);

		$this->db->where('can_id',$data['can_id']);
		$this->db->where('unit_cost >',0);
		$this->db->where('supplier_id',$data['supplier_id']);
		$this->db->update('canvass_details',$update);

		$sql = "
			SELECT 
			can_id,
			itemNo,
			supplier_id,
			approvedSupplier
			FROM canvass_details 
			WHERE can_id = '".$data['can_id']."' AND supplier_id = '".$data['supplier_id']."';
		";

		$result = $this->db->query($sql);
		return json_encode($result->result_array());


	}


	function update_canvass_details($data){
		
		$this->db->set(array(
			'approvedSupplier'=>strtoupper($data['status']),
			'sup_qty'=>$data['sup_qty']
			));

		$this->db->where('can_id',$data['can_id']);
		$this->db->where('itemNo',$data['item_no']);
		$this->db->where('supplier_id',$data['supplier_id']);
		$this->db->update('canvass_details');
				
		$rem_qty =  $data['qty'] - $data['stocks'];
		/***rem QTY **/
		$this->db->set(array(
			'rem_qty'=>$rem_qty,
			));
		$this->db->where('can_id',$data['can_id']);
		$this->db->where('itemNo',$data['item_no']);
		$this->db->update('canvass_details');

	}

	public function changeCsStatus(){
		$sql = "UPDATE canvass_main SET status='".$this->input->post('status')."' WHERE can_id = '".$this->input->post('id')."'";
		$this->db->query($sql);		
		return $this->input->post('status');
	}

	public function approveCanvass($arg){
		$update = array(
			'approval'=>$arg['approval']
			);
		$this->db->where('can_id',$arg['can_id']);
		$this->db->update('canvass_main',$update);
		return true;
	}

	public function update_canvass_main($arg,$id){

		$this->db->where('can_id',$id);
		$this->db->update('canvass_main',$arg);
		return true;

	}
	

	public function for_canvass($page = 1,$params = ""){

		$where  = "";
		$search = "";


		$branch_type = $this->session->userdata('branch_type');		
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
					$where .=" AND can_id is NULL ";
				break;
				case "approved":
					$where .=" AND can_id is NOT NULL";
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
			  (SELECT CONCAT(project,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.to_projectCode)'to_projectCodeName',
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
			  LEFT JOIN (SELECT can_id,pr_id 'canvass_pr_id'  FROM canvass_main) canvass_main
				ON (canvass_main.canvass_pr_id = pm.pr_id)
			WHERE 
				".$where." 	
				AND approvedPR = 'True' AND status <> 'CANCEL' AND th.status <> 'PENDING' AND th.type = 'Purchase Request'
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


	public function for_canvass_notification(){

		$branch_type = $this->session->userdata('branch_type');
		$where = '';
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

		$sql2 = "
			SELECT
			  *,			
			  (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName',
			  (SELECT title_name FROM project_title  WHERE title_id = th.from_projectMain)'from_projectMainName',
			  (SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.to_projectCode)'to_projectCodeName',
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
				AND approvedPR = 'True' AND status <> 'CANCEL'
				AND canvass_no IS NULL 
				AND `status` = 'APPROVED'
			GROUP BY pm.pr_id
			ORDER BY purchaseDate DESC, pr_id DESC;
		";

		$result = $this->db->query($sql2);		
		return $result->result_array();

	}


	public function cancel_canvass($arg){
		
		$update = array(
				'status'=>'CANCELLED'
			);
		$this->db->where('can_id',$arg['id']);
		$this->db->update('canvass_main',$update);

		return true;

	}



	public function search_item(){
		
		$sql = "
			SELECT 
			item_no,
			item,
			unit_measure
			FROM canvass_main cm
			INNER JOIN canvass_details cd
			ON (cd.can_id = cm.can_id)
			INNER JOIN (
				SELECT 
				group_detail_id AS item_no,
				CONCAT(group_description,'-',description) AS item,
				unit_measure
				FROM setup_group_detail sgd
				INNER JOIN setup_group sg 
				ON (sg.group_id = sgd.group_id)
			) AS item
			ON (item.item_no = cd.itemNo)
			GROUP BY item_no
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function supplier_item($arg){
		$sql = "
				SELECT 
				*,
				(IF(cd.supplierType='BUSINESS',
				(SELECT business_name FROM business_list WHERE business_number = cd.supplier_id),(SELECT
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename))    'Supplier'
				FROM hr_person_profile
				WHERE (hr_person_profile.pp_person_code = cd.supplier_id)
				ORDER BY `Supplier` ASC))) 'supplier'
				FROM canvass_main cm
				INNER JOIN canvass_details cd
				ON (cd.can_id = cm.can_id)
				INNER JOIN (
					SELECT 
					group_detail_id AS item_no,
					CONCAT(group_description,'-',description) AS item,
					unit_measure
					FROM setup_group_detail sgd
					INNER JOIN setup_group sg 
					ON (sg.group_id = sgd.group_id)
				) AS item
				ON (item.item_no = cd.itemNo)
				WHERE item_no = '".$arg['item_no']."'				
				ORDER BY cm.can_id DESC 
			";

		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function count_items($id){
		$this->db->select('*');
		$this->db->from('canvass_details');
		$this->db->where('can_id',$id);
		$this->db->group_by('itemNo');
		$query = $this->db->get();

		return $query->num_rows();
	}

	public function count_approved_items($id){
		$this->db->select('*');
		$this->db->from('canvass_details');
		$this->db->where('can_id',$id);
		$this->db->where('approvedSupplier','TRUE');
		$query = $this->db->get();

		return $query->num_rows();
	}

	public function get_approved_suppliers($id){
		$this->db->select("itemNo,supplier_id,(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'supplier',SUM(discounted_total) AS 'total'");
		$this->db->from('canvass_details');
		$this->db->where('can_id',$id);
		$this->db->where('approvedSupplier','TRUE');
		$this->db->group_by('supplier_id');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	public function get_approved_items($id){
		$this->db->select("itemNo,supplier_id");
		$this->db->from('canvass_details');
		$this->db->where('can_id',$id);
		$this->db->where('approvedSupplier','TRUE');
		$this->db->group_by('itemNo');
		$query = $this->db->get();

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	public function get_chairperson($form){
		$sql = "SELECT
					*,
					(SELECT
					    CONCAT(hr_person_profile.pp_lastname,', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) 'PersonName'   
					FROM hr_employee
					INNER JOIN hr_person_profile
					ON (hr_employee.person_profile_no = hr_person_profile.pp_person_code)
					WHERE hr_employee.emp_number = setup_signatory_print.employee_id
					) 'chairperson'
				FROM setup_signatory_print
				WHERE form = '{$form}'
				AND signatory = 'bidding_committee'
				AND designation = 'chairperson'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

	public function get_vice_chairperson($form){
		$sql = "SELECT
					*,
					(SELECT
					    CONCAT(hr_person_profile.pp_lastname,', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) 'PersonName'   
					FROM hr_employee
					INNER JOIN hr_person_profile
					ON (hr_employee.person_profile_no = hr_person_profile.pp_person_code)
					WHERE hr_employee.emp_number = setup_signatory_print.employee_id
					) 'vice_chairperson'
				FROM setup_signatory_print
				WHERE form = '{$form}'
				AND signatory = 'bidding_committee'
				AND designation = 'vice chairperson'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

	public function get_member($form){
		$sql = "SELECT
					*,
					(SELECT
					    CONCAT(hr_person_profile.pp_lastname,', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) 'PersonName'   
					FROM hr_employee
					INNER JOIN hr_person_profile
					ON (hr_employee.person_profile_no = hr_person_profile.pp_person_code)
					WHERE hr_employee.emp_number = setup_signatory_print.employee_id
					) 'member'
				FROM setup_signatory_print
				WHERE form = '{$form}'
				AND signatory = 'bidding_committee'
				AND designation = 'member'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->result_array();
		}
	}

	public function get_end_user($form){
		$sql = "SELECT
					*,
					(SELECT
					    CONCAT(hr_person_profile.pp_lastname,', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) 'PersonName'   
					FROM hr_employee
					INNER JOIN hr_person_profile
					ON (hr_employee.person_profile_no = hr_person_profile.pp_person_code)
					WHERE hr_employee.emp_number = setup_signatory_print.employee_id
					) 'end_user'
				FROM setup_signatory_print
				WHERE form = '{$form}'
				AND signatory = 'bidding_committee'
				AND designation = 'end user'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

	public function get_general_manager($form){
		$sql = "SELECT
					*,
					(SELECT
					    CONCAT(hr_person_profile.pp_lastname,', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) 'PersonName'   
					FROM hr_employee
					INNER JOIN hr_person_profile
					ON (hr_employee.person_profile_no = hr_person_profile.pp_person_code)
					WHERE hr_employee.emp_number = setup_signatory_print.employee_id
					) 'general_manager'
				FROM setup_signatory_print
				WHERE form = '{$form}'
				AND signatory = 'approved_by'
				AND designation = 'general manager'";
		$query = $this->db->query($sql);

		if($query->num_rows() > 0){
			return $query->row_array();
		}
	}

}