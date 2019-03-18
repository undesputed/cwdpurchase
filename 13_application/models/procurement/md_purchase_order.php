<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_purchase_order extends CI_Model {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('procurement/md_transaction_history');
		$this->load->model('procurement/md_canvass_sheet');
		$this->load->model('procurement/md_accounting');

	}

	public function get_all_po(){

		$sql1 ="
			SELECT 
			 *,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."'
			AND pom.project_id = '".$this->session->userdata('Proj_Code')."'
			ORDER BY po_date DESC;
		";

		$result = $this->db->query($sql1);		
		return $result->result_array();

	}


	public function get_all_po2($page = "1",$params = ""){

		$branch_type = $this->session->userdata('branch_type');
		$where  = "";
		$search = "";
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   WHERE (pom.from_id = '".$this->session->userdata('Proj_Code')."')
				";

			break;			
			default:
				$where = "
				   WHERE (pom.from_id = '".$this->session->userdata('Proj_Code')."')
				";				
			break;

		}


		if(isset($params['search']) && $params['search'] != '')
			{	

				$params['search'] = trim(str_replace('%20', '', $params['search']));

					$search .= "
						 WHERE (
								po_number LIKE '%{$params['search']}%' OR
								po_date LIKE '%{$params['search']}%' OR 
								preparedBy_name LIKE '%{$params['search']}%' OR
								from_projectCodeName LIKE '%{$params['search']}%' OR
								purchaseNo LIKE '%{$params['search']}%' OR
								Supplier LIKE '%{$params['search']}%' OR
								po_remarks LIKE '%{$params['search']}%'
							)
					";				
			}

		if(isset($params['filter'])){
			$filter = $params['filter'];
				switch($filter){
					case"all":
						$sql2 = "
							   	SELECT
							    *
								FROM(
								SELECT 
								pom.reference_no,
								pom.po_date,
								pom.po_number,
								pom.po_remarks,
								pom.dtDelivery,
								purchase_request.purchaseNo,
								pom.from_name 'from_projectCodeName',
								ps.is_print,
								ps.is_email,
								po_details.total_cost,
								po_details.total_item,
								pom.status 'p_status',	
								(SELECT
								CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
								FROM 
								`hr_employee`
								INNER JOIN `hr_person_profile` 
								ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
								WHERE `hr_employee`.`emp_number` = pom.preparedBy
								) 'preparedBy_name',
								(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
								FROM purchase_order_main pom
								INNER JOIN (
								SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
								GROUP BY po_id
								)po_details
								ON (pom.po_id = po_details.po_id)
								INNER JOIN purchaserequest_main purchase_request
								ON (purchase_request.pr_id = pom.pr_id)		
								LEFT JOIN po_status ps
								ON (ps.po_id = pom.po_id)			
								ORDER BY pom.po_date DESC,pom.po_id DESC
								) a 			
							";
					break;
					case "pending":
						/*$where .=" AND pom.status = 'ACTIVE' ";*/
						$sql2 = "
							   	SELECT
							    *
								FROM(
								SELECT 
								pom.reference_no,
								pom.po_date,
								pom.po_number,
								pom.po_remarks,
								pom.dtDelivery,
								purchase_request.purchaseNo,
								pom.from_name 'from_projectCodeName',
								ps.is_print,
								ps.is_email,
								po_details.total_cost,
								po_details.total_item,
								pom.status 'p_status',	
								(SELECT
								CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
								FROM 
								`hr_employee`
								INNER JOIN `hr_person_profile` 
								ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
								WHERE `hr_employee`.`emp_number` = pom.preparedBy
								) 'preparedBy_name',
								(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
								FROM purchase_order_main pom
								INNER JOIN (
								SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
								GROUP BY po_id
								)po_details
								ON (pom.po_id = po_details.po_id)
								INNER JOIN purchaserequest_main purchase_request
								ON (purchase_request.pr_id = pom.pr_id)		
								LEFT JOIN po_status ps
								ON (ps.po_id = pom.po_id)
								{$where} AND pom.status = 'ACTIVE'			
								ORDER BY pom.po_date DESC,pom.po_id DESC
								) a 			
							";
					break;
					case "approved":
						/*$where .=" AND pom.status IN ('APPROVED','COMPLETE')";*/
						$sql2 = "
							   	SELECT
							    *
								FROM(
								SELECT 
								pom.reference_no,
								pom.po_date,
								pom.po_number,
								pom.po_remarks,
								pom.dtDelivery,
								purchase_request.purchaseNo,
								pom.from_name 'from_projectCodeName',
								ps.is_print,
								ps.is_email,
								po_details.total_cost,
								po_details.total_item,
								pom.status 'p_status',	
								(SELECT
								CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
								FROM 
								`hr_employee`
								INNER JOIN `hr_person_profile` 
								ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
								WHERE `hr_employee`.`emp_number` = pom.preparedBy
								) 'preparedBy_name',
								(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
								FROM purchase_order_main pom
								INNER JOIN (
								SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
								GROUP BY po_id
								)po_details
								ON (pom.po_id = po_details.po_id)
								INNER JOIN purchaserequest_main purchase_request
								ON (purchase_request.pr_id = pom.pr_id)		
								LEFT JOIN po_status ps
								ON (ps.po_id = pom.po_id)
								{$where} AND pom.status IN ('APPROVED','COMPLETE')			
								ORDER BY pom.po_date DESC,pom.po_id DESC
								) a 			
							";
					break;
					case "rejected":
						/*$where .=" AND pom.status = 'CANCELLED' ";*/
						$sql2 = "
							   	SELECT
							    *
								FROM(
								SELECT 
								pom.reference_no,
								pom.po_date,
								pom.po_number,
								pom.po_remarks,
								pom.dtDelivery,
								purchase_request.purchaseNo,
								pom.from_name 'from_projectCodeName',
								ps.is_print,
								ps.is_email,
								po_details.total_cost,
								po_details.total_item,
								pom.status 'p_status',	
								(SELECT
								CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
								FROM 
								`hr_employee`
								INNER JOIN `hr_person_profile` 
								ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
								WHERE `hr_employee`.`emp_number` = pom.preparedBy
								) 'preparedBy_name',
								(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
								FROM purchase_order_main pom
								INNER JOIN (
								SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
								GROUP BY po_id
								)po_details
								ON (pom.po_id = po_details.po_id)
								INNER JOIN purchaserequest_main purchase_request
								ON (purchase_request.pr_id = pom.pr_id)		
								LEFT JOIN po_status ps
								ON (ps.po_id = pom.po_id)
								{$where} AND pom.status = 'CANCELLED'			
								ORDER BY pom.po_date DESC,pom.po_id DESC
								) a 			
							";
					break;
					case "without_print":
						/*$where .=" AND (pom.status = 'APPROVED' OR pom.status = 'COMPLETE')";*/
						/*$where .=" AND (pom.status = 'APPROVED')";*/
						/*if(strlen($search)> 0){
							$search .=" AND is_print IS NULL";
						}else{
							$search .=" WHERE is_print IS NULL";	
						}*/
						
						/*$sql2 = "
							   	SELECT
							    *
								FROM(
								SELECT 
								pom.reference_no,
								pom.po_date,
								pom.po_number,
								pom.po_remarks,
								pom.dtDelivery,
								purchase_request.purchaseNo,
								pom.from_name 'from_projectCodeName',
								ps.is_print,
								ps.is_email,
								po_details.total_cost,
								po_details.total_item,
								pom.status 'p_status',	
								(SELECT
								CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
								FROM 
								`hr_employee`
								INNER JOIN `hr_person_profile` 
								ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
								WHERE `hr_employee`.`emp_number` = pom.preparedBy
								) 'preparedBy_name',
								(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
								FROM purchase_order_main pom
								INNER JOIN (
								SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
								GROUP BY po_id
								)po_details
								ON (pom.po_id = po_details.po_id)
								INNER JOIN purchaserequest_main purchase_request
								ON (purchase_request.pr_id = pom.pr_id)		
								LEFT JOIN po_status ps
								ON (ps.po_id = pom.po_id)
								{$where} 
								AND (ps.is_print IS NULL OR ps.is_print = 'false')
								AND (pom.status = 'APPROVED' OR pom.status = 'COMPLETE')			
								ORDER BY pom.po_date DESC,pom.po_id DESC LIMIT 20
								) a 			
							";*/
						$sql2 = "SELECT 
									pom.reference_no, pom.po_date, pom.po_number, pom.po_remarks,
									pom.from_name AS 'from_projectCodeName', pom.dtDelivery, pom.status AS 'p_status', 
									ps.is_print, ps.is_email, 
									purchase_request.purchaseNo, 
									CONCAT(p_profile.pp_lastname, ', ', p_profile.pp_firstname, ' ', p_profile.pp_middlename) AS preparedBy_name, 
									b_list.business_name AS Supplier, 
									(SELECT COUNT(DISTINCT itemNO) FROM purchase_order_details WHERE po_id = pom.po_id) AS total_item, 
									(SELECT SUM(total_unitcost) FROM purchase_order_details WHERE po_id = pom.po_id) AS total_cost
								FROM 
									(
										SELECT 
											* 
										FROM 
											purchase_order_main 
										WHERE 
											po_id NOT IN (SELECT po_id FROM po_status) 
										UNION 
										SELECT 
											* 
										FROM 
											purchase_order_main 
										WHERE 
											po_id IN (SELECT po_id FROM po_status WHERE is_print = 'false')
									) AS pom
								LEFT JOIN 
									hr_employee AS employee 
								ON 
									employee.emp_number = pom.preparedBy 
								LEFT JOIN 
									hr_person_profile AS p_profile 
								ON 
									p_profile.pp_person_code = employee.person_profile_no 
								LEFT JOIN 
									business_list AS b_list 
								ON 
									b_list.business_number = pom.supplierID 
								LEFT JOIN 
									purchaserequest_main AS purchase_request
								ON 
									purchase_request.pr_id = pom.pr_id 
								LEFT JOIN 
									po_status AS ps
								ON 
									ps.po_id = pom.po_id 
								{$where} 
									AND (pom.status IN ('APPROVED', 'COMPLETE'))
								ORDER BY pom.po_date DESC, pom.po_id ASC";
					break;
				}
		}


		/*$sql2 = "
		   	SELECT
		    *
			FROM(
			SELECT 
			pom.reference_no,
			pom.po_date,
			pom.po_number,
			pom.po_remarks,
			pom.dtDelivery,
			purchase_request.purchaseNo,
			pom.from_name 'from_projectCodeName',
			ps.is_print,
			ps.is_email,
			po_details.total_cost,
			po_details.total_item,
			pom.status 'p_status',	
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = pom.preparedBy
			) 'preparedBy_name',
			(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			INNER JOIN purchaserequest_main purchase_request
			ON (purchase_request.pr_id = pom.pr_id)		
			LEFT JOIN po_status ps
			ON (ps.po_id = pom.po_id)			
				 {$where}
			ORDER BY pom.po_date DESC,pom.po_id DESC
			) a 			
		 		{$search}
		";*/

		/*echo $sql2;*/
	
		/*$limit = $this->config->item('limit');*/

		$limit = 20;

		$start = ($page * $limit) - $limit;
		$next = '';


		$result = $this->db->query($sql2. " LIMIT {$start}, {$limit}" );

		/*echo '<pre>';
		print_r($this->db->last_query());
		echo '</pre>';*/
				
		/*$result = $this->db->query($sql2);*/
		/*if($result->num_rows() > ($page * $limit) ){
			$next = $page + 1;
		}*/

		if($result->num_rows() >= $limit){
			$next = $page + 1;
		}
			
		
		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;



	}

	public function get_all_po_search($params = ""){

		$branch_type = $this->session->userdata('branch_type');
		$where  = "";
		$search = "";
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   WHERE (pom.from_id = '".$this->session->userdata('Proj_Code')."')
				";

			break;			
			default:
				$where = "
				   WHERE (pom.from_id = '".$this->session->userdata('Proj_Code')."')
				";				
			break;

		}

		$params['search'] = trim(str_replace('%20', '', $params['search']));

		$search .= "
			 WHERE (
			 		b_list.business_name LIKE '%{$params['search']}%' OR
					pom.po_number LIKE '%{$params['search']}%'
				)
		";

		/*$sql2 = "
				   	SELECT
				    *
					FROM(
					SELECT 
					pom.reference_no,
					pom.po_date,
					pom.po_number,
					pom.po_remarks,
					pom.dtDelivery,
					purchase_request.purchaseNo,
					pom.from_name 'from_projectCodeName',
					ps.is_print,
					ps.is_email,
					po_details.total_cost,
					po_details.total_item,
					pom.status 'p_status',	
					(SELECT
					CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
					FROM 
					`hr_employee`
					INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
					WHERE `hr_employee`.`emp_number` = pom.preparedBy
					) 'preparedBy_name',
					(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'Supplier'		
					FROM purchase_order_main pom
					INNER JOIN (
					SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
					GROUP BY po_id
					)po_details
					ON (pom.po_id = po_details.po_id)
					INNER JOIN purchaserequest_main purchase_request
					ON (purchase_request.pr_id = pom.pr_id)		
					LEFT JOIN po_status ps
					ON (ps.po_id = pom.po_id)			
					ORDER BY pom.po_date DESC,pom.po_id DESC
					) a
					{$search} 			
				";*/
		$sql2 = "SELECT 
					pom.reference_no, pom.po_date, pom.po_number, pom.po_remarks,
					pom.from_name AS 'from_projectCodeName', pom.dtDelivery, pom.status AS 'p_status', 
					ps.is_print, ps.is_email, 
					purchase_request.purchaseNo, 
					CONCAT(p_profile.pp_lastname, ', ', p_profile.pp_firstname, ' ', p_profile.pp_middlename) AS preparedBy_name, 
					b_list.business_name AS Supplier, 
					(SELECT COUNT(DISTINCT itemNO) FROM purchase_order_details WHERE po_id = pom.po_id) AS total_item, 
					(SELECT SUM(total_unitcost) FROM purchase_order_details WHERE po_id = pom.po_id) AS total_cost
				FROM 
					purchase_order_main AS pom
				LEFT JOIN 
					hr_employee AS employee 
				ON 
					employee.emp_number = pom.preparedBy 
				LEFT JOIN 
					hr_person_profile AS p_profile 
				ON 
					p_profile.pp_person_code = employee.person_profile_no 
				LEFT JOIN 
					business_list AS b_list 
				ON 
					b_list.business_number = pom.supplierID 
				LEFT JOIN 
					purchaserequest_main AS purchase_request
				ON 
					purchase_request.pr_id = pom.pr_id 
				LEFT JOIN 
					po_status AS ps
				ON 
					ps.po_id = pom.po_id 
				{$search}";
		$result = $this->db->query($sql2);

		if($result->num_rows() > 0){
			$output = array(
				'data'=>$result->result_array()
				);
			return $output;
		}
	}
	

	public function get_all_po_summary($arg){
		$branch_type = $this->session->userdata('branch_type');

		$datefrom = $arg['datefrom'];
		$dateto = $arg['dateto'];
		$project = $arg['project'];
		$today = date('Y-m-d');
		
		$where = '';
		switch($branch_type){
			case "MAIN OFFICE":
				if($datefrom == 'undefined' && $dateto == 'undefined' && $project == 'undefined'){
					 $where = "
					   WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."' AND (pom.po_date BETWEEN '{$today}' AND '{$today}')
					 ";
				}elseif($datefrom != 'undefined' && $dateto != 'undefined' && $project != 'undefined'){
					$where = "
					   WHERE pom.title_id = '".$this->session->userdata('Proj_Main')."' AND (pom.po_date BETWEEN '".$datefrom."' AND '".$dateto."')
					 ";
				}
			break;
			case "PROFIT CENTER":
				if($datefrom == 'undefined' && $dateto == 'undefined' && $project == 'undefined'){
					$where = "
					   WHERE (from_projectCode = '".$this->session->userdata('Proj_Code')."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' ) AND (pom.po_date BETWEEN '".$today."' AND '".$today."')
					";
				}elseif($datefrom != 'undefined' && $dateto != 'undefined' && $project != 'undefined'){
					$where = "
					   WHERE (from_projectCode = '".$project."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' ) AND (pom.po_date BETWEEN '".$datefrom."' AND '".$dateto."')
					";
				}

			break;			
			default:
				if($datefrom == 'undefined' && $dateto == 'undefined' && $project == 'undefined'){
					$where = "
					   WHERE (from_projectCode = '".$this->session->userdata('Proj_Code')."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' ) AND (pom.po_date BETWEEN '".$today."' AND '".$today."')
					";
				}elseif($datefrom != 'undefined' && $dateto != 'undefined' && $project != 'undefined'){
					$where = "
					   WHERE (from_projectCode = '".$project."' AND from_projectMain = '".$this->session->userdata('Proj_Main')."' ) AND (pom.po_date BETWEEN '".$datefrom."' AND '".$dateto."')
					";
				}
			break;

		}
		

		$sql2 = "

			SELECT 
			*,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
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
			".$where."
			ORDER BY pom.po_date DESC,pom.po_id DESC
		
		";

		$result = $this->db->query($sql2);

		/*echo $this->db->last_query();*/
		return $result->result_array();
	}

	public function get_all_po_summary_filter($arg){
		$branch_type = $this->session->userdata('branch_type');

		$datefrom = $arg['datefrom'];
		$dateto = $arg['dateto'];
		$project = $arg['project'];
		$today = date('Y-m-d');
		
		$where = '';
		
		
		$where = "
		   WHERE from_projectCode = '".$project."' AND (pom.po_date BETWEEN '".$datefrom."' AND '".$dateto."')
		";
		

		$sql2 = "

			SELECT 
			*,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
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
			".$where."
			ORDER BY pom.po_date DESC,pom.po_id DESC
		
		";

		$result = $this->db->query($sql2);
		return $result->result_array();
	}

	
	public function get_all_approved_po($page = "",$params = ""){
		$page = 1;		
		$where  = "";
		$search = "";

		$branch_type = $this->session->userdata('branch_type');		
		switch($branch_type){
			case "MAIN OFFICE":
				 $where .= "
				   AND pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where .= "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";				
			break;
			default:
				$where .= "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";	
			break;
		}

	

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" AND (pom.status = 'APPROVED' OR pom.status = 'PARTIAL') ";
				break;
				case "approved":
					$where .=" AND (pom.status = 'CLOSED' OR pom.status = 'COMPLETE')";
				break;
				case "rejected":
					$where .=" AND pom.status = 'CANCELLED' ";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{

				$params['search'] = trim(str_replace('%20','',$params['search']));

				$search .= "
					 WHERE (
							po_number LIKE '%{$params['search']}%' OR
							po_date LIKE '%{$params['search']}%' OR 
							preparedBy_name LIKE '%{$params['search']}%' OR
							project_requestor LIKE '%{$params['search']}%' OR
							po_number LIKE '%{$params['search']}%' OR
							dtDelivery LIKE '%{$params['search']}%' 							
						)
				";
		}

		$sql2 = "
			SELECT
			*
			FROM(

			SELECT 
			pom.reference_no,
			pom.po_date,
			pom.po_number,
			pom.po_remarks,
			pom.cancel_remarks,
			pom.dtDelivery,					
			po_details.total_cost,
			po_details.total_item,
			pom.status 'p_status',
			(SELECT CONCAT(project_name,' - ',project_location)  FROM setup_project WHERE project_id = th.from_projectCode) 'project_requestor',
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			LEFT JOIN purchaserequest_main prm
			ON (prm.pr_id = pom.pr_id)
			LEFT JOIN transaction_history th
			ON (th.reference_id = prm.pr_id)
			WHERE th.type = 'Purchase Request'
			AND pom.status <>'ACTIVE' AND pom.status <>'CANCELLED'
			".$where."
			ORDER BY pom.po_id DESC, pom.po_date DESC
			) a
			{$search}
		";


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


	public function get_all_approved_po_notification(){

		$branch_type = $this->session->userdata('branch_type');
		$where = '';

		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   AND pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;
			default:
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;
		}


		$sql = "
			SELECT 
			 *,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			LEFT JOIN purchaserequest_main prm
			ON (prm.pr_id = pom.pr_id)
			LEFT JOIN transaction_history th
			ON (th.reference_id = prm.pr_id)
			WHERE th.type = 'Purchase Request'
			AND (pom.status = 'APPROVED')
			".$where."
		";
		$result = $this->db->query($sql);		
		return $result->result_array();
				
	}


	public function undelivered_notification(){

		$arg['date_to'] = date('Y-m-d');

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
			GROUP BY a.po_id;
		";
		$result = $this->db->query($sql);
		return $result->result_array();
				
	}

	public function get_all_pending_po(){

		$branch_type = $this->session->userdata('branch_type');
		$where = '';
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   AND pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;			
			default:
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;
		}

		$sql = "
				SELECT 
				 *,
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
				ORDER BY `Supplier` ASC))) 'Supplier'
				FROM purchase_order_main pom
				INNER JOIN (
				SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
				GROUP BY po_id
				)po_details
				ON (pom.po_id = po_details.po_id)
				LEFT JOIN purchaserequest_main prm
				ON (prm.pr_id = pom.pr_id)
				LEFT JOIN transaction_history th
				ON (th.reference_id = prm.pr_id)
				WHERE th.type = 'Purchase Request'
				AND pom.status = 'ACTIVE'			
				".$where."
			";

		$result = $this->db->query($sql);				
		return $result->num_rows();
	}

	public function get_all_woprint_po(){

		$branch_type = $this->session->userdata('branch_type');
		$where = '';
		switch($branch_type){
			case "MAIN OFFICE":
				 $where = "
				   AND pom.title_id = '".$this->session->userdata('Proj_Main')."'
				 ";
			break;
			case "PROFIT CENTER":
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;			
			default:
				$where = "
				   AND (th.from_projectCode = '".$this->session->userdata('Proj_Code')."' AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."' )
				";
			break;
		}

		$sql = "
				SELECT 
				 *,
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
				ORDER BY `Supplier` ASC))) 'Supplier'
				FROM purchase_order_main pom
				INNER JOIN (
				SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
				GROUP BY po_id
				)po_details
				ON (pom.po_id = po_details.po_id)
				LEFT JOIN purchaserequest_main prm
				ON (prm.pr_id = pom.pr_id)
				LEFT JOIN transaction_history th
				ON (th.reference_id = prm.pr_id)
				LEFT JOIN po_status ps
				ON (ps.po_id = pom.po_id)
				WHERE (pom.status = 'APPROVED' OR pom.status ='COMPLETE')
				AND is_print IS NULL
				".$where."
			";

		$result = $this->db->query($sql);				
		return $result->num_rows();
	}






	public function get_cumulative(){
		$sql = "CALL purchaseOrder_display_main11(?)";
		$data   = array($this->input->post('location'));
		$result = $this->db->query($sql,$data);		
		$this->db->close();
		return $result;
	}

	function canvass_display_canvass_prz_supplier_month($project,$date){
		$sql = "CALL canvass_display_canvass_prz_supplier_month(?,?)";
		$result = $this->db->query($sql,array($project,$date));				
		$this->db->close();		
		return $result;
	}

	function canvass_display_canvass_prz_supplier($project){
		$sql = "CALL canvass_display_canvass_prz_supplier(?)";
		$result = $this->db->query($sql,array($project));
		$this->db->close();		
		return $result;
	}


	function new_canvass_display_canvass_prz_dtls(){
		$sql="SELECT
			   canvass_details.can_id,
			   canvass_details.supplier_id,
			   (SELECT business_name FROM business_list WHERE business_number = canvass_details.supplier_id) 'SUPPLIER',
			   purchaserequest_details.itemNo,
			   purchaserequest_details.itemDesc 'ITEM DESCRIPTION',
			   (SELECT unit_measure FROM setup_group_detail WHERE group_detail_id = canvass_details.itemNo) 'UNIT',
			   canvass_details.unit_cost 'UNIT PRICE',
			   canvass_details.sup_qty AS 'QTY',			  
			   (purchaserequest_details.qty * canvass_details.unit_cost) 'TOTAL',
			   purchaserequest_details.remarkz 'REMARKS'
			FROM canvass_details
			INNER JOIN canvass_main
			ON (canvass_main.can_id = canvass_details.can_id)
			INNER JOIN purchaserequest_details
			ON (canvass_main.pr_id = purchaserequest_details.pr_id)
			AND canvass_details.approvedSupplier = 'True'
			AND purchaserequest_details.itemNo = canvass_details.itemNo
			AND purchaserequest_details.rem_qty <> 0;";
		$result=$this->db->query($sql);

		return $result;
	
	}

	
	function canvass_display_canvass_prz_dtls($id=null){
		$sql="CALL canvass_display_canvass_prz_dtls('".$id."')";
		$result=$this->db->query($sql);
		$this->db->close();
		if($result->num_rows>0)
			return $result;
		else
			return false;
	}


	function get_supplier($id){

		$sql = "SELECT * FROM business_list WHERE business_number = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
		
	}

	function get_items($can_id,$supplier_id){
		$sql = "CALL canvass_display_canvass_prz_supplier_dtls('".$can_id."','".$supplier_id."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	function save_purchaseOrder(){


		/*$days = (isset($_POST['days']))? $_POST['days'] : '0';	*/

		/*	
			$sql = "SELECT po_id FROM purchase_order_main WHERE pr_id = '".$this->input->post('pr_id')."'";
			$result = $this->db->query($sql);
			if($result->num_rows() > 0)
			{
				return 2;
				exit();
			}
		*/

		$arg['id'] = $this->input->post('pr_id');
		$arg['status'] = 'Approved';
		$arg['bool'] = 'TRUE';
		if(!$this->md_transaction_history->po_history($arg)){
			return "Purchase Request(PR) is Cancelled!";
			exit();
		}

		$po_number = $this->check_dup($this->input->post('po_number'),$this->input->post('po_date'));
		$reference_no = $this->check_dup_reference($this->input->post('reference_no'));

		$prepared_by = $this->input->post('prepared_by');
		$prepared_by = ($prepared_by == 0 || $prepared_by == "")? $this->session->userdata('emp_id') : $prepared_by;

		$this->db->select('is_boq_new');
		$this->db->from('purchaserequest_main');
		$this->db->where('pr_id',$this->input->post('pr_id'));
		$res = $this->db->get();

		$is_boq_new = '0';
		foreach($res->result_array() as $rw){
			$is_boq_new = $rw['is_boq_new'];
		}
				
		$data = array(
				'reference_no'=>$reference_no,
				'po_number'=>$po_number,
				'po_date'=>$this->input->post('po_date'),
				'pr_id'=>$this->input->post('pr_id'),
				'supplierID'=>$this->input->post('supplierID'),
				'supplierType'=>$this->input->post('supplierType'),
				'placeDelivery'=>$this->input->post('placeDelivery'),
				'deliverTerm'=>$this->input->post('deliverTerm'),
				'paymentTerm'=>$this->input->post('paymentTerm'),
				'indays'=>$this->input->post('indays'),
				'dtDelivery'=>$this->input->post('dtDelivery'),
				'approvedBy'=>$this->input->post('approvedBy'),
				'preparedBy'=>$prepared_by,
				'project_id'=>$this->input->post('project_id'),
				'recommendedBy'=>$this->input->post('recommendedBy'),
				'title_id'=>$this->input->post('title_id'),
				'po_remarks'=>$this->input->post('po_remarks'),
				'notedBy'=>'10',
				'from_id'=>$this->input->post('from_id'),
				'from_name'=>$this->input->post('from_name'),
				'is_boq_new'=>$is_boq_new
			);

		$this->db->insert('purchase_order_main',$data);

		$sql="SELECT MAX(po_id) as max FROM purchase_order_main WHERE po_number = ?";
		$result = $this->db->query($sql,array($data['po_number']));
		$po_id  = $result->row_array();		

		$sql = "UPDATE canvass_main SET po_no = ? WHERE can_id = ? ";
		$this->db->query($sql,array($po_id['max'],$this->input->post('can_id')));

		/*$details = $this->md_purchase_order->get_items($this->input->post('can_id'),$this->input->post('supplierID'));*/

		$details = $this->input->post('data');		
		foreach($details as $row){
			
			/*
			  $sql = "CALL purchaseorder_insertdetails('".$po_id['max']."','".$row['itemNo']."','".$row['QTY']."','".$row['UNIT']."','".$row['ITEM DESCRIPTION']."','".$row['UNIT PRICE']."','".$row['TOTAL']."','".$row['REMARKS']."')";			
			  $this->db->query($sql);
			*/
			$this->db->select('is_boq_new,boq_id,charging');
			$this->db->from('purchaserequest_details');
			$this->db->where('pr_id',$this->input->post('pr_id'));
			$this->db->where('itemNo',$row['itemNo']);
			$ress = $this->db->get();

			$isboqnew = "0";
			$boq_id = "(NULL)";
			$charging = "(NULL)";
			foreach($ress->result_array() as $r){
				$isboqnew = $r['is_boq_new'];
				$boq_id = $r['boq_id'];
				$charging = $r['charging'];
			}

			$insert_poDetails = array(
					'po_id'=>$po_id['max'],
					'itemNo'=>$row['itemNo'],
					'quantity'=>$row['qty'],
					'unit_msr'=>$row['unit'],
					'item_name'=>$row['itemDesc'],
					'unit_cost'=>$row['unit_price'],
					'total_unitcost'=>$row['total'],
					'remarks'=>$row['remarks'],
					'change_order'=>$row['change_order'],
					'for_usage'=>$row['for_usage'],
					'is_boq_new'=>$isboqnew,
					'boq_id'=>$boq_id,
					'charging'=>$charging
			);

			$this->db->insert('purchase_order_details',$insert_poDetails);			
			$sql = "UPDATE canvass_details SET po_id = '".$po_id['max']."' WHERE can_id = '".$this->input->post('can_id')."' AND itemNo = '".$row['itemNo']."' AND supplier_id = '".$this->input->post('supplierID')."'";
			$this->db->query($sql);

		}

		

		return true;
	}


	public function save_purchaseOrder2(){

		$sql = "SELECT po_id FROM purchase_order_main WHERE pr_id = '".$this->input->post('pr_id')."'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			return 2;
			exit();
		}

		$arg['id'] = $this->input->post('pr_id');
		$arg['status'] = 'APPROVED';
		$arg['bool'] = 'TRUE';
		
		if(!$this->md_transaction_history->po_history($arg)){
			return "This P.R is Cancelled!";
			exit();
		}


		 $canvass_arg = array(
				'c_number'  =>$this->input->post('c_number'),
				'c_date'    =>$this->input->post('po_date'),
				'pr_id'     =>$this->input->post('pr_id'),
				'approvedBy'=>$this->input->post('approvedBy'),
				'preparedBy'=>$this->input->post('preparedBy'),
				'title_id'=>$this->input->post('title_id'),
			);

		$canvass_id = $this->md_canvass_sheet->saveCanvass($canvass_arg);

			
		$data = array(
				'po_number'=>$this->input->post('po_number'),
				'po_date'=>$this->input->post('po_date'),
				'pr_id'=>$this->input->post('pr_id'),
				'supplierID'=>$this->input->post('supplierID'),
				'supplierType'=>$this->input->post('supplierType'),
				'placeDelivery'=>$this->input->post('placeDelivery'),
				'deliverTerm'=>$this->input->post('deliverTerm'),
				'paymentTerm'=>$this->input->post('paymentTerm'),
				'indays'=>$this->input->post('indays'),
				'dtDelivery'=>$this->input->post('dtDelivery'),
				'approvedBy'=>$this->input->post('approvedBy'),
				'preparedBy'=>$this->input->post('preparedBy'),
				'project_id'=>$this->input->post('project_id'),
				'recommendedBy'=>$this->input->post('recommendedBy'),
				'title_id'=>$this->input->post('title_id'),
				'po_remarks'=>$this->input->post('po_remarks'),
				'notedBy'=>"10",
			);

		$this->db->insert('purchase_order_main',$data);
		
		$sql="SELECT MAX(po_id) as max FROM purchase_order_main WHERE po_number = ?";
		$result = $this->db->query($sql,array($data['po_number']));
		$po_id  = $result->row_array();	
		

	
		$data = $this->input->post('data');
		foreach($data as $row){

			$insert_poDetails = array(
					'po_id'=>$po_id['max'],
					'itemNo'=>$row['itemNo'],
					'quantity'=>$row['qty'],
					'unit_msr'=>$row['unit'],
					'item_name'=>$row['itemDesc'],
					'unit_cost'=>$row['unit_price'],
					'total_unitcost'=>$row['total'],
					'remarks'=>$row['remarks'],				
			);			
			$this->db->insert('purchase_order_details',$insert_poDetails);

			$insert_canvassDetails = array(
			    'can_id'=>$canvass_id,
				'itemNo'=>$row['itemNo'],
				'unit_cost'=>$row['unit_price'],
				'supplier_id'=>$this->input->post('supplierID'),
				'supplierType'=>$this->input->post('supplierType'),
				'approvedSupplier'=>'TRUE',
				'stocks_sup'=>$row['qty'],
				'sup_qty'=>$row['qty'],
				'pr_qty'=>$row['qty'],
				'rem_qty'=>$row['qty'],
				'c_remarks'=>$row['remarks'],				
				'po_id'=>$po_id['max'],
				);

			$this->db->insert('canvass_details',$insert_canvassDetails);

		}
	
		return true;

	}

	public function save_purchaseOrder3(){


		$sql = "SELECT po_id FROM purchase_order_main WHERE pr_id = '".$this->input->post('pr_id')."'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0)
		{
			return 2;
			exit();
		}

		$data = array(
				'po_number'=>$this->input->post('po_number'),
				'po_date'=>$this->input->post('po_date'),
				'pr_id'=>$this->input->post('pr_id'),
				'supplierID'=>$this->input->post('supplierID'),
				'supplierType'=>$this->input->post('supplierType'),
				'placeDelivery'=>$this->input->post('placeDelivery'),
				'deliverTerm'=>$this->input->post('deliverTerm'),
				'paymentTerm'=>$this->input->post('paymentTerm'),
				'indays'=>$this->input->post('indays'),
				'dtDelivery'=>$this->input->post('dtDelivery'),
				'approvedBy'=>$this->input->post('approvedBy'),
				'preparedBy'=>$this->input->post('preparedBy'),
				'project_id'=>$this->input->post('project_id'),
				'recommendedBy'=>$this->input->post('recommendedBy'),
				'title_id'=>$this->input->post('title_id'),
				'po_remarks'=>$this->input->post('po_remarks'),
				'notedBy'=>"10",			
			);

		$this->db->insert('purchase_order_main',$data);
		
		$sql="SELECT MAX(po_id) as max FROM purchase_order_main WHERE po_number = ?";
		$result = $this->db->query($sql,array($data['po_number']));
		$po_id  = $result->row_array();	
		

	
		$data = $this->input->post('data');
		foreach($data as $row){

			$insert_poDetails = array(
					'po_id'=>$po_id['max'],
					'itemNo'=>$row['itemNo'],
					'quantity'=>$row['qty'],
					'unit_msr'=>$row['unit'],
					'item_name'=>$row['itemDesc'],
					'unit_cost'=>$row['unit_price'],
					'total_unitcost'=>$row['total'],
					'remarks'=>$row['remarks'],				
			);			
			$this->db->insert('purchase_order_details',$insert_poDetails);

			$update = array(			
				'po_id'=>$po_id['max'],			
			);			
			$this->db->where('can_id',$this->input->post('can_id'));
			$this->db->where('itemNo',$row['itemNo']);
			$this->db->where('supplier_id',$this->input->post('supplierID'));
			$this->db->update('canvass_details',$update);
			
		}
		
		$arg['id'] = $this->input->post('pr_id');
		$arg['status'] = 'APPROVED';
		$arg['bool'] = 'TRUE';
		$this->md_transaction_history->po_history($arg);
		return true;		
	}



	public function cumulative_details($id){		
		$sql = "SELECT * FROM purchase_order_details WHERE po_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;		
	}

	public function get_main($id){
		$sql = "SELECT * FROM purchase_order_main WHERE po_id='".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	public function get_main_join($id){

		$sql = "
			SELECT 
				*
				FROM purchase_order_main pom
				INNER JOIN purchaserequest_main prm
				ON (pom.pr_id = prm.pr_id)
				where po_id = '".$id."';
			";
		$result = $this->db->query($sql);			
		$this->db->close();
		return $result->row_array();
	}

	public function get_item_details($id){

		$sql = "
			SELECT * FROM purchase_order_details where po_id = '".$id."';
		";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_supplier_po($type,$id){

		if(strtoupper($type) == 'AFFILIATE'){
			$sql = "
					SELECT
					hr_person_profile.pp_person_code 'supplier_id',
					CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename)) 'business_name',
					pp_Address1 'address',
					'' AS 'contact_no'
					FROM hr_person_profile
					WHERE hr_person_profile.pp_person_code = '".$id."' 
					ORDER BY `business_name` ASC
				";
		}else
		{
			$sql = "
					SELECT business_number 'supplier_id',business_name,trade_name,address,contact_no,term_type,term_days,tin_number FROM business_list WHERE `status` = 'ACTIVE' AND business_number = '".$id."';
				   ";
		}
		
		$result = $this->db->query($sql);	
		$output = $result->row_array();
		$output['supplierType'] = strtoupper($type); 
		return $output;		
	}

	public function signatory($id){
		$sql = "CALL display_person_signatory1('".$id."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();		
	}
			
	public function changePoStatus(){
		$sql = "UPDATE purchase_order_main SET status='".$this->input->post('status')."' WHERE po_id = '".$this->input->post('id')."'";
		$this->db->query($sql);		
		return $this->input->post('status');
	}
	
	public function get_canvassByPo($po_id){
		//$sql = "SELECT * FROM canvass_main where po_no = '".$po_id."'";

		$sql = "SELECT
			  canvass_main.can_id,
			  canvass_main.c_number                'canvass_no',
			  canvass_main.c_date                  'canvass_date',
			  purchaserequest_main.purchaseNo      'pr_no',
			  purchaserequest_main.purchaseDate    'pr_date',
			   purchaserequest_main.project_id,
			   (SELECT project FROM setup_project WHERE project_id = purchaserequest_main.project_id) 'PROJECT',
			     purchaserequest_main.pr_id,
			   purchaserequest_main.title_id
			FROM canvass_main
			  INNER JOIN canvass_details
			    ON (canvass_main.can_id = canvass_details.can_id)
			  INNER JOIN purchaserequest_main
			    ON (purchaserequest_main.pr_id = canvass_main.pr_id)
			  INNER JOIN purchaserequest_details
			    ON (purchaserequest_main.pr_id = purchaserequest_details.pr_id)	    
			    WHERE canvass_main.po_no = '".$po_id."'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}
	
	public function update_purchaseOrder(){

		$data = array(
				'po_number'=>$this->input->post('po_number'),
				'po_date'=>$this->input->post('po_date'),
				'pr_id'=>$this->input->post('pr_id'),
				'supplierID'=>$this->input->post('supplierID'),
				'supplierType'=>'BUSINESS',
				'placeDelivery'=>$this->input->post('placeDelivery'),
				'deliverTerm'=>$this->input->post('deliverTerm'),
				'paymentTerm'=>$this->input->post('paymentTerm'),
				'indays'=>$this->input->post('indays'),
				'dtDelivery'=>$this->input->post('dtDelivery'),
				'approvedBy'=>$this->input->post('approvedBy'),
				'preparedBy'=>$this->input->post('preparedBy'),
				'project_id'=>$this->input->post('project_id'),
				'recommendedBy'=>$this->input->post('recommendedBy'),
				'title_id'=>$this->input->post('title_id'),
				'po_remarks'=>$this->input->post('po_remarks'),
				'notedBy'=>"0",
			);
		$this->db->where('po_id',$this->input->post('po_id'));
		$this->db->update('purchase_order_main',$data);

		

		$sql = "UPDATE canvass_main SET po_no = ? WHERE can_id = ? ";
		$this->db->query($sql,array($this->input->post('po_id'),$this->input->post('can_id')));

		$details = $this->md_purchase_order->get_items($this->input->post('can_id'),$this->input->post('supplierID'));

		$this->db->query("DELETE FROM purchase_order_details WHERE po_id = '".$this->input->post('po_id')."'");
		
		foreach($details as $row){
			
			$sql = "CALL purchaseorder_insertdetails('".$this->input->post('po_id')."','".$row['itemNo']."','".$row['QTY']."','".$row['UNIT']."','".$row['ITEM DESCRIPTION']."','".$row['UNIT PRICE']."','".$row['TOTAL']."','".$row['REMARKS']."')";			
			$this->db->query($sql);
			
			$sql = "UPDATE canvass_details SET po_id = '".$this->input->post('po_id')."' WHERE can_id = '".$this->input->post('can_id')."' AND itemNo = '".$row['itemNo']."' AND supplier_id = '".$this->input->post('supplierID')."'";
			$this->db->query($sql);

		}

		return true;
	}


	public function do_cancel($arg){

		$update = array(
			'status'=>$arg['status'],
			);
		$this->db->where('po_id',$arg['po_id']);
		$this->db->update('purchase_order_main',$update);		
		return true;

	}


	public function get_po_main($po_no){
		$sql = "
			SELECT 
			 *,
			(SELECT project_no FROM setup_project WHERE project_id = from_id) 'project_no',
			(SELECT SUM(total_unitcost) FROM purchase_order_details WHERE pom.po_id = purchase_order_details.po_id) 'totalunitcost',
			(SELECT
			CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)
			FROM 
			`hr_employee`
			INNER JOIN `hr_person_profile` 
			ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			WHERE `hr_employee`.`emp_number` = pom.notedby
			) 'notedBy_name',
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			WHERE pom.po_number = '".$po_no."'
			AND pom.title_id = '".$this->session->userdata('Proj_Main')."';
		";

		$result = $this->db->query($sql);		
		$this->db->close();				
		return $result->row_array();

	}

	public function get_po_main_po_id($po_id){
		$sql = "
			SELECT 
			 *,
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
			(SELECT business_name FROM business_list WHERE business_number = pom.supplierID)'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			WHERE pom.po_id = '".$po_id."'			
			;			
		";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}


	public function get_po_main_id($pr_id,$supplier_id){
		$sql = "
			SELECT 
			 *,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
			SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
			GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			WHERE pom.pr_id = '".$pr_id."'
			AND pom.supplierID = '".$supplier_id."'			
			AND pom.title_id = '".$this->session->userdata('Proj_Main')."';			
		";

		$result = $this->db->query($sql);		
		$this->db->close();				
		return $result->row_array();

	}


	public function get_po_main_canvass($arg){

		$sql = "
			SELECT 
			 *,
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
			ORDER BY `Supplier` ASC))) 'Supplier'
			FROM purchase_order_main pom
			INNER JOIN (
				SELECT *,COUNT(DISTINCT itemNo) 'total_item',SUM(total_unitcost) 'total_cost' FROM purchase_order_details 
				GROUP BY po_id
			)po_details
			ON (pom.po_id = po_details.po_id)
			INNER JOIN (
				SELECT 	
				cm.can_id,
				cm.pr_id,
				cd.po_id
				FROM canvass_main cm
				INNER JOIN canvass_details cd
				ON (cm.can_id = cd.can_id)
				WHERE cm.pr_id = '".$arg['pr_id']."'
				AND cd.supplier_id = '".$arg['supplier_id']."'
				AND cm.title_id = '".$this->session->userdata('Proj_Main')."'
			) AS canvass
			ON (canvass.pr_id = pom.pr_id)
			WHERE pom.pr_id = '".$arg['pr_id']."'
			AND pom.supplierID = '".$arg['supplier_id']."'			
			AND pom.title_id = '".$this->session->userdata('Proj_Main')."'
			AND canvass.can_id = '".$arg['can_id']."'
			AND canvass.po_id <> '';
		";
		$result = $this->db->query($sql);		
		return $result->row_array();

	}


	public function get_po_details($po_id){

		$sql = "			
			SELECT 
			pod.*,
			remarks 'remarkz'
			FROM purchase_order_details pod
			INNER JOIN purchase_order_main pom
			ON (pod.po_id = pom.po_id)	
			WHERE pod.po_id = '{$po_id}'
		";
		
		$result = $this->db->query($sql);		
		return $result->result_array();

	}

	public function changestatus($arg){
		

		$update = array(
			'status'=>$arg['po_status']
		);
		$this->db->where('po_id',$arg['id']);
		$this->db->update('purchase_order_main',$update);
				



		/*$po_main = $this->getBypo_id($arg['id']);

		
		$po_details = $this->getBypo_id_details($arg['id']);
		
		if(!$this->md_accounting->journal_entry($po_main,$po_details)){
			echo "FAILED";
		}
				
		$update2 = array(
			'status'=>$arg['transaction_status']
			);
		$this->db->where('reference_id',$arg['id']);
		$this->db->where('type','Purchase Order');
		$this->db->update('transaction_history',$update2);*/
		
		
	}




	public function for_po($page = "1",$params = ""){
		$page = 1;
		$where  = "";
		$search = "";

		if(isset($params['filter']))
		{
			switch($params['filter']){
				case"all":

				break;
				case "pending":
					$where .=" AND po_no IS NULL ";
				break;
				case "approved":
					$where .=" AND po_no IS NOT NULL  ";
				break;
				case "rejected":
					$where .=" AND status = 'CANCELLED' ";
				break;

			}
		}

		if(isset($params['search']) && $params['search'] != '')
		{	
			$search .= "
				 WHERE (
						purchaseNo LIKE '%{$params['search']}%' OR
						c_number LIKE '%{$params['search']}%' OR
						from_projectCodeName LIKE '%{$params['search']}%' OR 
						preparedBy_name LIKE '%{$params['search']}%' OR
						c_date LIKE'%{$params['search']}%'
					)
			";

		}





		$sql = "
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
                canvass_details.status_code,
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
                ) 'preparedBy_name',
                canvass_main.is_boq_new            
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
                INNER JOIN (
                    SELECT
                      pm.pr_id,
                      (SELECT CONCAT(project_name,' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = th.from_projectCode)'from_projectCodeName'                                                                
                    FROM purchaserequest_main pm
                      INNER JOIN transaction_history th
                        ON (pm.pr_id = th.reference_id)                                                    
                    GROUP BY pm.pr_id                    
                ) transaction_history
                ON (pm.pr_id = transaction_history.pr_id)
                WHERE canvass_main.title_id = '".$this->session->userdata('Proj_Main')."' AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."'
                AND canvass_main.approval <> '0' AND canvass_main.status <> 'CANCELLED'
                {$where}                               
                )a 
				{$search}
                ORDER BY c_date DESC,can_id DESC
		";

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
				canvass_details.status_code,
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
				AND canvass_main.approval <> '0' AND canvass_main.status <> 'CANCELLED'
				{$where}
				ORDER BY c_date DESC,can_id desc
				)a
				{$search}			
		";*/

		$limit = $this->config->item('limit');

		$start = ($page * $limit) - $limit;
		$next = '';
		/*$result = $this->db->query($sql2);*/
		$result = $this->db->query($sql. " LIMIT {$start}, {$limit}" );			
		/*
			if($result->num_rows() > ($page * $limit) ){
			$next = $page + 1;
		}
		*/
		
		if($result->num_rows() >= $limit){
			$next = $page + 1;
		}

		$output = array(
			'data'=>$result->result_array(),
			'next'=>$next
			);
		return $output;

	}

	public function for_po_notification(){

		$sql1 = "
			SELECT   
			  can_id
			FROM
			  canvass_main   
			WHERE canvass_main.title_id = '".$this->session->userdata('Proj_Main')."' 
			  AND canvass_main.project_id = '".$this->session->userdata('Proj_Code')."' 
			  AND canvass_main.approval <> '0' 
			  AND canvass_main.status <> 'CANCELLED'
			  AND canvass_main.po_no IS NULL;
		";

		/*$sql1 = "
			SELECT 
				canvass_main.*,
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
				AND canvass_main.approval <> '0' AND canvass_main.status <> 'CANCELLED'
				AND canvass_main.po_no IS NULL
				ORDER BY c_date DESC,can_id desc;
		";*/

		$result = $this->db->query($sql1);		
		return $result->result_array();


	}



	public function edit_po($arg){

		$this->db->trans_begin();

		$update = array(
			'supplierID'=>$arg['supplierID'],
			'address'=>$arg['address'],
			'supplierType'=>'BUSINESS',
			'reference_no'=>$arg['reference_no'],
			);
		$this->db->where('po_id',$arg['po_id']);
		$this->db->update('purchase_order_main',$update);
		
		$sql = "DELETE FROM purchase_order_details where po_id = '{$arg['po_id']}'";
		$this->db->query($sql);

		foreach($arg['data'] as $row){

			$insert_details = array(
				'po_id'=>$arg['po_id'],
				'itemNo'=>$row['item_no'],
				'quantity'=>$row['quantity'],
				'unit_msr'=>$row['unit_measure'],
				'item_name'=>$row['item_name'],
				'unit_cost'=>$row['unit_cost'],
				'total_unitcost'=>$row['total_cost'],
				'remarks'=>$row['remarks'],
				'brand'=>$row['brand']
			);
			
			$this->db->insert('purchase_order_details',$insert_details);
			
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


	public function cancel_po($arg){

		$update = array(
			'status'=>'CLOSED',
			'cancel_remarks'=>'CLOSED with partial receiving',
			);

		$this->db->where('po_id',$arg['po_id']);
		$this->db->update('purchase_order_main',$update);

		return true;
	}




	public function get_max_reference_no(){
		/*$sql = "SELECT MAX(reference_no) + 1 AS MAX FROM purchase_order_main;";*/		
		$sql = "SELECT MAX(SUBSTRING(`reference_no`,7)) + 1 AS MAX FROM purchase_order_main WHERE po_id = (SELECT MAX(po_id)'max' FROM purchase_order_main);";
		$result = $this->db->query($sql);
		$row = $result->row_array();
		return (empty($row['MAX']))? 1 : $row['MAX'];
	}


	public function po_status($po_id,$data){

		$sql    = "SELECT * FROM po_status where po_id='".$po_id."';";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0)
		{
			$this->db->where('po_id',$po_id);
			$this->db->update('po_status',$data);
		}else{
			$this->db->insert('po_status',$data);
		}
		return true;
	}


	public function get_po_status($po_id){
		$sql = "SELECT * FROM po_status where po_id = '".$po_id."'";
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
			(IF(pom.supplierType='BUSINESS' OR pom.supplierType=' ',(SELECT business_name FROM business_list WHERE business_number = pom.supplierID),
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
			* 
			FROM purchase_order_details pod
			INNER JOIN setup_group_detail sgd
			ON (pod.itemNo = sgd.group_detail_id)
			WHERE po_id = '".$po_id."';
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function check_dup($po_number,$date){
		$sql = "SELECT po_id FROM  purchase_order_main WHERE po_number = '".$po_number."'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0 ){
			return $this->extra->get_po_code($date);
		}else{
			return $po_number;
		}
	}

	public function check_dup_reference($reference_no){
		$sql = "SELECT reference_no FROM  purchase_order_main WHERE reference_no = '".$reference_no."'";
		$result = $this->db->query($sql);
		if($result->num_rows() > 0 ){
			$row = $result->row_array();
			$reference_no = (int) $row['reference_no'] + 1;
			return $reference_no;
		}else{
			return $reference_no;
		}
	}

	public function get_projects(){
		$sql = "SELECT 
					project_id,
					CONCAT(project,' - ',project_name,' - ',project_location) 'fullname',
					project_location		
				FROM setup_project
				WHERE status = 'ACTIVE'";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0){
			return $result->result_array();
		}
	}


}