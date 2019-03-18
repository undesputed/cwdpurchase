<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_itemmonitoring extends CI_Model {

	public function __construct(){
		parent :: __construct();		
		
	}



	function get_item($arg){
		$where = "";
		if($arg['location']!='all'){
			$where .= "AND pr.from_projectCode ='{$arg['location']}'";
		}

		if($arg['view_type'] == 'monthly'){

			$date = $arg['year']."-".$arg['month']."-01";
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));		

		}else{

			$from = $arg['date_from'];
			$to   = $arg['date_to'];

		}

		$where .= " AND a.po_date between '{$from}' AND '{$to}'";


		$sql = "
			SELECT
			a.reference_no,
			a.po_date,
			quantity,
			unit_msr,
			b.itemNo,
			item_name,
			unit_cost,
			supplier,
			total_unitcost,
			a.po_id,
			from_projectCodeName,
			supplier
			FROM purchase_order_main a
			INNER JOIN purchase_order_details b
			 ON (a.po_id = b.po_id)
				INNER JOIN (
				  SELECT
					pm.*,
					th.*, 
				      pd.for_usage,
				      pd.itemNo,
				      (SELECT
					 CONCAT(project_name,' - ',project_location) AS 'Project_F'
				       FROM setup_project
				       WHERE project_id = th.from_projectCode)    'from_projectCodeName',
				      (SELECT
					 title_name
				       FROM project_title
				       WHERE title_id = th.from_projectMain)    'from_projectMainName',
				      (SELECT
					 CONCAT(project_name,' - ',project_location) AS 'Project_F'
				       FROM setup_project
				       WHERE project_id = th.to_projectCode)    'to_projectCodeName',
				      (SELECT
					 title_name
				       FROM project_title
				       WHERE title_id = th.to_projectMain)    'to_projectMainName'
				    FROM purchaserequest_main pm
				      INNER JOIN transaction_history th
					ON (pm.pr_id = th.reference_id)
				      LEFT JOIN (SELECT
						   `hr_employee`.`emp_number`,
						   `hr_person_profile`.`pp_person_code`,
						   CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`)    'preparedbyName'
						 FROM `hr_employee`
						   INNER JOIN `hr_person_profile`
						     ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)) signatory
					ON (signatory.emp_number = pm.preparedBy)
				      INNER JOIN purchaserequest_details pd
					ON (pd.pr_id = pm.pr_id)
				    WHERE from_projectMain = '1' 
				    GROUP BY pm.pr_id,pd.itemNo
				    ORDER BY purchaseDate DESC, pm.pr_id DESC) pr
			  ON (pr.pr_id = a.pr_id AND b.itemNo = pr.itemNo) 
			  INNER JOIN (
				SELECT 
				(SELECT business_name FROM business_list WHERE business_number = supplier_id) 'supplier',
				d.po_id,
				d.itemNo
				FROM canvass_main  c
				INNER JOIN canvass_details d
				ON (c.can_id = d.can_id)
				WHERE po_no = po_id
			  ) canvass 
			  ON (canvass.po_id = a.po_id AND canvass.itemNo = b.itemNo)
			WHERE (a.status = 'APPROVED' OR a.status = 'COMPLETE') 
			{$where}
		";

		$result = $this->db->query($sql);
		return $result->result_array();	

	}



}
