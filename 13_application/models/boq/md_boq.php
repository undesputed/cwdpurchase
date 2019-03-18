<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_boq extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	public function get_main_category(){

		$sql = "SELECT id, paycenter FROM pay_center;";

		$result =$this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}


	public function get_sub_category(){

		$sql = "SELECT id, itemdescription FROM  pay_item;";

		$result =$this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}
	public function save_contract($arg){

		$update = array(
				'contract_amt'=>$arg['contract_amt'],
				);
			$this->db->where('project_category_id',$arg['project_category_id']);
			$this->db->where('project_id',$this->session->userdata('Proj_Code'));
			$this->db->where('title_id',$this->session->userdata('Proj_Main'));
			$this->db->update('boq_main',$update);	
				/*echo $this->db->last_query();*/
			return true;
	}
	public function get_contract_amt($id){

		$sql = "
			SELECT contract_amt 
			FROM boq_main 
			WHERE `project_category_id` = '".$id."'
			 AND `project_id` = '".$this->session->userdata('Proj_Code')."' 
			 AND `title_id` = '".$this->session->userdata('Proj_Main')."'
		";
		$result = $this->db->query($sql);	
		return $result->row_array();
	}

	public function get_items(){
		
		$sql = "
			SELECT
			  group_detail_id    'id',
			  CONCAT((SELECT group_description FROM setup_group WHERE group_id=setup_group_detail.group_id),' ',setup_group_detail.description)    'description',
			  unit_measure
			FROM setup_group_detail
			WHERE item_status = 'ACTIVE'
			ORDER BY description;
		";

		$result =$this->db->query($sql);
		$this->db->close();

		return $result->result_array();

	}

	public function check_boq_main($id,$floor = '',$project_code = ''){
		
		$sql = "
			SELECT id 
			FROM boq_main 
			WHERE project_category_id = '".$id."' 
			AND project_id = '{$project_code}' 
			AND title_id   = '".$this->session->userdata('Proj_Main')."';
		";
		$result = $this->db->query($sql);		
		if($result->num_rows() <= 0)
		{			
			return 0;

		}else
		{
			$row = $result->row_array();
			$this->delete_details($row['id'],$floor);
			return $row['id'];
		}
				
	}

	public function delete_details($id,$floor){
		$this->db->where('ref_id',$id);
		$this->db->where('flr_id',$floor);
		$this->db->delete('boq_details');
	}

	public function save_main($arg){

		$id = $this->check_boq_main($arg['project_category_id'],$arg['floor_id'],$arg['project_code']);
		if($id == 0){
			if($arg['project_category_id'] == 0)
			{	
				echo $arg['project_category_id'];
				//exit(0);
			}

			$insert = array(
				'description'=>$arg['description'],
				'project_category_id'=>$arg['project_category_id'],
				'project_category'=>$arg['project_category'],
				'project_id'=>$arg['project_code'],
				'title_id'=>$this->session->userdata('Proj_Main'),
				'contract_amt'=>$arg['contract_amt'],
				);

			if($insert['project_id']=='0'){
				exit(0);
			}

			$this->db->insert('boq_main',$insert);
			$id = $this->db->insert_id();
		}else{
			$update = array(
				'contract_amt'=>$arg['contract_amt'],
				);
			$this->db->where('project_category_id',$arg['project_category_id']);
			$this->db->where('project_id',$this->session->userdata('Proj_Code'));
			$this->db->where('title_id',$this->session->userdata('Proj_Main'));
			$this->db->update('boq_main',$update);			
		}
		return $id;

	}

	public function save_details($arg = array()){

		$insert = array(
			'ref_id'   => $arg['ref_id'],
			'type'     => $arg['type'],
			'type_id'  => $arg['type_id'],
			'description'  => $arg['description'],
			'qty'      => $arg['qty'],
			'unit'     => $arg['unit'],
			'material' => $arg['material'],
			'labor'    => $arg['labor'],
			'others'   => $arg['others'],
			'no'       => $arg['no'],
			'total'    => $arg['total'],
			'amount'   => $arg['amount'],
		);

		$this->db->insert('boq_details',$insert);

	}

	public function save_batch($insert = array()){		
		$this->db->insert_batch('boq_details',$insert);
	}


	public function get_boq($ref_id = '',$floor = ''){

		$project_site = $this->input->post('project_site');


		if(empty($project_site)){
			$project_site = $this->session->userdata('Proj_Code');
		}

		/*$sql = "SELECT * FROM boq_details;";*/
		$sql = "
			SELECT boq_details.* FROM boq_details 
			INNER JOIN boq_main
			ON(boq_details.ref_id = boq_main.id)
			WHERE project_category_id = '".$ref_id."' 
			AND project_id = '".$project_site."' 
			AND title_id = '".$this->session->userdata('Proj_Main')."'
			AND flr_id = '".$floor."'
			;
		";

		$result = $this->db->query($sql);				
		return $result->result_array();

	}

	public function get_report($type_id,$project_id,$type_name){

		if($project_id == "")
		{
			$project_id = $this->session->userdata('Proj_Code');
		}


		
		$sql = "
			SELECT boq_details.*,po.* 
			FROM (
					SELECT
					ref_id,
					 CONCAT(flr_id,'.',`no`)`no`,
					 `no` as `no_type`,
					 `type`,
					 type_id,
					 description,
					 qty,
					 unit,
					 material,
					 labor,
					 others,
					 total,
					 amount,
					 flr_id
					FROM boq_details					
     		) AS boq_details
			INNER JOIN boq_main
			ON(boq_details.ref_id = boq_main.id)
			LEFT JOIN (
				SELECT
				quantity,
				unit_msr,
				b.itemNo,
				item_name,
				unit_cost,
				SUM(total_unitcost)    'total_unitcost',
				a.po_id,
				SUM(quantity)     'total_quantity'
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
			                    WHERE from_projectMain = '".$this->session->userdata('Proj_Main')."'
			                        AND pd.for_usage LIKE '{$type_name}%'
			                    GROUP BY pm.pr_id,pd.itemNo
			                    ORDER BY purchaseDate DESC, pm.pr_id DESC) pr
			          ON (pr.pr_id = a.pr_id AND b.itemNo = pr.itemNo) 
				WHERE (a.status = 'APPROVED' OR a.status = 'COMPLETE') AND pr.from_projectCode ='".$project_id."'				
				GROUP BY itemno
			)po 
			ON (po.itemNo = boq_details.type_id AND boq_details.type ='items')
			WHERE project_category_id = '".$type_id."' AND project_id = '".$project_id."' AND title_id = '".$this->session->userdata('Proj_Main')."'
			ORDER BY NO,no_type;
			;			
		";

		$result = $this->db->query($sql);		
		return $result->result_array();
	}


	public function get_po_no_boq($type_id,$project_id,$type_name){

	

		$sql = "
			SELECT
			* FROM
			(
				SELECT
				quantity,
				unit_msr,
				b.itemNo,
				item_name,
				SUM(total_unitcost)    'total_unitcost',
				a.po_id,
				SUM(quantity)     'total_quantity'
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
			                    WHERE from_projectMain = '".$this->session->userdata('Proj_Main')."'
			                        AND pd.for_usage LIKE '{$type_name}%'
			                    GROUP BY pm.pr_id,pd.itemNo
			                    ORDER BY purchaseDate DESC, pm.pr_id DESC) pr
			          ON (pr.pr_id = a.pr_id AND b.itemNo = pr.itemNo) 
				WHERE (a.status = 'APPROVED' OR a.status = 'COMPLETE') AND pr.from_projectCode ='".$project_id."'				
				GROUP BY itemno
			) po
			LEFT JOIN (
				SELECT
				  boq_details.*  
				FROM boq_details
				  INNER JOIN boq_main
				    ON (boq_details.ref_id = boq_main.id)
				    WHERE boq_main.project_id = '".$project_id."' AND boq_main.title_id = '".$this->session->userdata('Proj_Main')."' AND boq_main.project_category_id = '".$type_id."'
			) boq
			ON (po.itemNo = boq.type_id AND boq.type ='items')
			WHERE boq.type_id IS NULL
		";

		$result = $this->db->query($sql);				
		return $result->result_array();		
	}


	public function get_project_boq($type_id,$type_name){
		
		
		/*$sql = "
				SELECT
				project_id,
				(SELECT project FROM setup_project WHERE project_id = a.project_id) 'project',
				(SELECT project_name FROM setup_project WHERE project_id = a.project_id) 'project_name',
				(SELECT project_location FROM setup_project WHERE project_id = a.project_id) 'project_location',
				SUM(boq) 'boq',
				SUM(actual) 'actual',
				(boq - SUM(actual)) 'discrepancy'
				FROM(				
				SELECT
				boq_main.project_id,
				SUM(boq_details.amount) 'boq',
				SUM(po.total_unitcost) 'actual'
				FROM (SELECT SUM(amount)'amount',type_id,`type`,ref_id FROM boq_details GROUP BY ref_id,type_id) boq_details 
				INNER JOIN boq_main
				ON(boq_details.ref_id = boq_main.id)
				LEFT JOIN (
					SELECT
					quantity,
					unit_msr,
					itemNo,
					item_name,
					SUM(total_unitcost)    'total_unitcost',
					a.po_id,
					SUM(quantity)     'total_quantity',
					a.project_id,
					a.title_id,
					pr.from_projectCode
					FROM purchase_order_main a
					INNER JOIN purchase_order_details b
					 ON (a.po_id = b.po_id)
					INNER JOIN (
						 SELECT
						  *,
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
						   WHERE title_id = th.to_projectMain)    'to_projectMainName',
						  (SELECT
						     COUNT(*)    'cnt'
						   FROM purchaserequest_details
						   WHERE pr_id = pm.pr_id)    'item_cnt'
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
						WHERE  from_projectMain = '".$this->session->userdata('Proj_Main')."'
						GROUP BY pm.pr_id
						ORDER BY purchaseDate DESC, pr_id DESC
					) pr
					ON (pr.pr_id = a.pr_id)
					WHERE a.status <> 'CANCELLED' AND a.title_id = '".$this->session->userdata('Proj_Main')."'
					GROUP BY itemno,pr.from_projectCode
				)po
				ON (po.itemNo = boq_details.type_id AND boq_details.type ='items' AND po.from_projectCode = boq_main.project_id)
				WHERE boq_main.title_id = '".$this->session->userdata('Proj_Main')."'
				AND boq_main.project_category_id = '".$type_id."'
				GROUP BY boq_main.project_id,ref_id	
				UNION 
				SELECT 		
				po.from_projectCode 'project_id',
				'' AS 'boq',
				SUM(total_unitcost) 'actual'
				FROM
				(
					SELECT
					quantity,
					unit_msr,
					itemNo,
					item_name,
					SUM(total_unitcost)    'total_unitcost',
					a.po_id,
					SUM(quantity)     'total_quantity',
					a.project_id,
					a.title_id,
					pr.from_projectCode
					FROM purchase_order_main a
					INNER JOIN purchase_order_details b
					 ON (a.po_id = b.po_id)
					INNER JOIN (
						 SELECT
						  *,
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
						   WHERE title_id = th.to_projectMain)    'to_projectMainName',
						  (SELECT
						     COUNT(*)    'cnt'
						   FROM purchaserequest_details
						   WHERE pr_id = pm.pr_id)    'item_cnt'
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
						WHERE  from_projectMain = '".$this->session->userdata('Proj_Main')."'
						GROUP BY pm.pr_id
						ORDER BY purchaseDate DESC, pr_id DESC
					) pr
					ON (pr.pr_id = a.pr_id)
					WHERE a.status <> 'CANCELLED' AND a.title_id = '".$this->session->userdata('Proj_Main')."'
					GROUP BY itemno,pr.from_projectCode
				) po
				LEFT JOIN (
					SELECT
					  boq_details.*,
					  boq_main.project_id,
					  boq_main.title_id,
					  boq_main.project_category_id
					FROM boq_details
					  INNER JOIN boq_main
					    ON (boq_details.ref_id = boq_main.id)
					WHERE boq_main.title_id = '".$this->session->userdata('Proj_Main')."' AND boq_main.project_category_id = '".$type_id."'						
				) boq
				ON (po.itemNo = boq.type_id AND boq.type ='items' AND boq.project_id = po.from_projectCode)
				WHERE boq.type_id IS NULL
				AND po.title_id   = '".$this->session->userdata('Proj_Main')."'
				GROUP BY po.from_projectCode
				UNION
				SELECT
				location 'project_id',
				' ' AS boq,
				(SUM(total_cost)) 'actual' 
				FROM (
				SELECT 
				tenant.name,
				tenant.amount,
				wd.item_no,
				wd.item_description,
				wd.unit_measure,
				wm.location,
				SUM(wd.withdrawn_quantity) 'total_withdraw_qty',
				(SUM(wd.withdrawn_quantity) * po.unit_cost) 'total_cost',
				(tenant.amount - (SUM(wd.withdrawn_quantity) * po.unit_cost)) 'remaining_balance'
				FROM withdraw_main wm
				INNER JOIN withdraw_details wd
				ON (wm.withdraw_id = wd.withdraw_main_id)
				INNER JOIN(
					SELECT itemNo,unit_msr,unit_cost 
					FROM purchase_order_main pom
					INNER JOIN purchase_order_details pod
					ON (pom.po_id = pod.po_id)
					WHERE pom.status = 'COMPLETE'
				) po
				ON (po.itemNo = wd.item_no)
				INNER JOIN(
					SELECT tenant_id,`name`,amount FROM withdraw_tenant wt
					INNER JOIN withdraw_tenant_details wtd
					ON (wt.id =  wtd.tenant_id)
				) tenant 
				ON (tenant.tenant_id = wm.tenant_id)
				WHERE wm.title_id = '1'
				GROUP BY po.itemNo,wm.tenant_id,wm.location)tenant
				UNION 
				SELECT 
				project_id,
				'' AS boq,
				SUM(cost) 'actual' 
				FROM cost_entry 
				WHERE STATUS = 'ACTIVE' 
				GROUP BY project_id
				)a
				GROUP BY project_id	
		";*/


		$sql = "
			 SELECT
				project_id,
				(SELECT project FROM setup_project WHERE project_id = a.project_id) 'project',
				(SELECT project_name FROM setup_project WHERE project_id = a.project_id) 'project_name',
				(SELECT project_location FROM setup_project WHERE project_id = a.project_id) 'project_location',
				SUM(boq) 'boq',
				SUM(actual) 'actual',
				(boq - SUM(actual)) 'discrepancy'
				FROM(				
					SELECT
					boq_main.project_id,
					SUM(boq_details.amount) 'boq',
					SUM(po.total_unitcost) 'actual'
					FROM (SELECT SUM(amount)'amount',type_id,`type`,ref_id FROM boq_details GROUP BY ref_id,type_id) boq_details
					INNER JOIN boq_main
					ON(boq_details.ref_id = boq_main.id)
					LEFT JOIN (
						SELECT
						quantity,
						unit_msr,
						itemNo,
						item_name,
						SUM(total_unitcost)    'total_unitcost',
						a.po_id,
						SUM(quantity)     'total_quantity',
						a.project_id,
						a.title_id,
						pr.from_projectCode
						FROM purchase_order_main a
						INNER JOIN purchase_order_details b
						 ON (a.po_id = b.po_id)
						INNER JOIN (
							SELECT
							  pm.*,
							  th.*,		  
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
							WHERE  from_projectMain = '".$this->session->userdata('Proj_Main')."' AND pd.for_usage LIKE '".$type_name."%'
							GROUP BY pm.pr_id
							ORDER BY purchaseDate DESC, pm.pr_id DESC
						) pr
						ON (pr.pr_id = a.pr_id)
						WHERE a.status = 'APPROVED' AND a.title_id = '".$this->session->userdata('Proj_Main')."'
						GROUP BY itemno,pr.from_projectCode
					)po
					ON (po.itemNo = boq_details.type_id AND boq_details.type ='items' AND po.from_projectCode = boq_main.project_id)
					WHERE boq_main.title_id = '".$this->session->userdata('Proj_Main')."'
					AND boq_main.project_category_id = '".$type_id."'
					GROUP BY boq_main.project_id,ref_id
					UNION 
					SELECT 		
					po.from_projectCode 'project_id',
					'' AS 'boq',
					SUM(total_unitcost) 'actual'
					FROM
					(
								SELECT
								quantity,
								unit_msr,
								itemNo,
								item_name,
								SUM(total_unitcost)    'total_unitcost',
								a.po_id,
								SUM(quantity)     'total_quantity',
								a.project_id,
								a.title_id,
								pr.from_projectCode
								FROM purchase_order_main a
								INNER JOIN purchase_order_details b
								 ON (a.po_id = b.po_id)
								INNER JOIN (
									SELECT
							  pm.*,
							  th.*,		  
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
							WHERE  from_projectMain = '".$this->session->userdata('Proj_Main')."' AND pd.for_usage LIKE '".$type_name."%'
							GROUP BY pm.pr_id
							ORDER BY purchaseDate DESC, pm.pr_id DESC
						) pr
						ON (pr.pr_id = a.pr_id)
						WHERE a.status = 'APPROVED' AND a.title_id = '".$this->session->userdata('Proj_Main')."'
						GROUP BY itemno,pr.from_projectCode
					) po
					LEFT JOIN (
						SELECT
						  boq_details.*,
						  boq_main.project_id,
						  boq_main.title_id,
						  boq_main.project_category_id
						FROM boq_details
						  INNER JOIN boq_main
						    ON (boq_details.ref_id = boq_main.id)
						WHERE boq_main.title_id = '".$this->session->userdata('Proj_Main')."' AND boq_main.project_category_id = '".$type_id."'
					) boq
					ON (po.itemNo = boq.type_id AND boq.type ='items' AND boq.project_id = po.from_projectCode)
					WHERE boq.type_id IS NULL
					AND po.title_id   = '".$this->session->userdata('Proj_Main')."'
					GROUP BY po.from_projectCode
					UNION
					SELECT
					location 'project_id',
					' ' AS boq,
					(SUM(total_cost)) 'actual' 
					FROM (
					SELECT 
					tenant.name,
					tenant.amount,
					wd.item_no,
					wd.item_description,
					wd.unit_measure,
					wm.location,
					SUM(wd.withdrawn_quantity) 'total_withdraw_qty',
					(SUM(wd.withdrawn_quantity) * po.unit_cost) 'total_cost',
					(tenant.amount - (SUM(wd.withdrawn_quantity) * po.unit_cost)) 'remaining_balance'
					FROM withdraw_main wm
					INNER JOIN withdraw_details wd
					ON (wm.withdraw_id = wd.withdraw_main_id)
					INNER JOIN(
						SELECT itemNo,unit_msr,unit_cost 
						FROM purchase_order_main pom
						INNER JOIN purchase_order_details pod
						ON (pom.po_id = pod.po_id)
						WHERE pom.status = 'COMPLETE'
					) po
					ON (po.itemNo = wd.item_no)
					INNER JOIN(
						SELECT tenant_id,`name`,amount FROM withdraw_tenant wt
						INNER JOIN withdraw_tenant_details wtd
						ON (wt.id =  wtd.tenant_id)
					) tenant 
					ON (tenant.tenant_id = wm.tenant_id)
					WHERE wm.title_id = '".$this->session->userdata('Proj_Main')."'
					GROUP BY po.itemNo,wm.tenant_id,wm.location)tenant
					UNION 
					SELECT 
					project_id,
					'' AS boq,
					SUM(cost) 'actual' 
					FROM cost_entry 
					WHERE STATUS = 'ACTIVE' 
					AND project_category_id = '".$type_id."'
					GROUP BY project_id
				)a
				WHERE project_id IS NOT NULL
				GROUP BY project_id		
		";


		$result = $this->db->query($sql);		
		return $result->result_array();

	}


	public function tenant($project_id){

		$sql = "			
			SELECT 
			tenant.name,
			tenant.amount,
			wd.item_no,
			wd.item_description,
			wd.unit_measure,
			SUM(wd.withdrawn_quantity) 'total_withdraw_qty',
			(SUM(wd.withdrawn_quantity) * po.unit_cost) 'total_cost',
			(tenant.amount - (SUM(wd.withdrawn_quantity) * po.unit_cost)) 'remaining_balance'
			FROM withdraw_main wm
			INNER JOIN withdraw_details wd
			ON (wm.withdraw_id = wd.withdraw_main_id)
			INNER JOIN(
				SELECT itemNo,unit_msr,unit_cost 
				FROM purchase_order_main pom
				INNER JOIN purchase_order_details pod
				ON (pom.po_id = pod.po_id)
				WHERE pom.status = 'COMPLETE'
			) po
			ON (po.itemNo = wd.item_no)
			INNER JOIN(
				SELECT tenant_id,`name`,amount FROM withdraw_tenant wt
				INNER JOIN withdraw_tenant_details wtd
				ON (wt.id =  wtd.tenant_id)
			) tenant 
			ON (tenant.tenant_id = wm.tenant_id)
			WHERE wm.title_id = '".$this->session->userdata('Proj_Main')."' AND wm.location = '".$project_id."'
			GROUP BY po.itemNo,wm.tenant_id;
			
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	public function get_labor_cost(){

		$id = $this->input->post('id');
		if($id == ''){
			$insert = array(
				'description'=>$this->input->post('description'),
				'cost'=>$this->input->post('cost'),
				'project_id'=>$this->input->post('project_id'),
				'title_id'=>$this->input->post('title_id'),
				'project_category_id'=>$this->input->post('main_category'),
				'project_category'=>$this->input->post('main_category_name')				
			);
		$this->db->insert('cost_entry', $insert);
		return true;
		}else{
			$arg = $this->input->post();
			$this->update($arg);
			return true;
		}
		
	}

	public function save_cost($project_id,$type_id = ''){
		$extend = '';
		if($type_id !=''){
			$extend .="AND project_category_id = '".$type_id."';";
		}
		$sql = "
		SELECT * FROM cost_entry
		WHERE STATUS = 'ACTIVE' AND project_id = '".$project_id."' ".$extend."";

		$result = $this->db->query($sql);
		return $result;
	}


	public function update($arg){

		$insert = array(
			'description'=>$arg['description'],
			'cost'=>$arg['cost'],
			'project_id'=>$arg['project_id'],
			'title_id'=>$arg['title_id'],
			'project_category_id'=>$this->input->post('main_category'),
			'project_category'=>$this->input->post('main_category_name')
			);

		$this->db->where('id',$arg['id']);
		$this->db->update('cost_entry', $insert);
		return true;
	}

	public function remove_cost($id){
		
		$update = array(
			'status'=> 'INACTIVE',
			);
		$this->db->where('id',$id);
		$this->db->update('cost_entry',$update);
		return true;

	}

	public function get_pr_items($item_no,$project_code){

		$sql = "
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
			  INNER JOIN purchaserequest_details pd
			  ON (pm.pr_id = pd.pr_id)
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
			  WHERE pd.itemNo = '".$item_no."'
			  AND th.type   = 'Purchase Request'
			  AND th.status = 'APPROVED'
			  AND th.from_projectCode = '".$project_code."'
  			  AND th.from_projectMain = '".$this->session->userdata('Proj_Main')."';
		";

		$result = $this->db->query($sql);		
		return $result->result_array();		
	}

	public function get_po_items($item_no,$project_code){

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
				    WHERE from_projectMain = '".$this->session->userdata('Proj_Main')."' 
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
			WHERE (a.status = 'APPROVED' OR a.status = 'COMPLETE') AND pr.from_projectCode ='{$project_code}' AND b.itemNo = '{$item_no}'
		";
		$result = $this->db->query($sql);		
		return $result->result_array();	

	}



	public function get_floor(){

		$sql = "SELECT * FROM boq_floor";
		$result = $this->db->query($sql);
		return $result->result_array();

	}
	
	// Added by RCANZ 03.13.2017
	public function get_row($table, $where) { 
		$this->db->where($where);
		return $this->db->get($table)->row(0);
	}
	
	public function get_data($table, $where) { 
		$this->db->where($where);
		return $this->db->get($table)->result();
	}
	
	public function insert($table, $data) { 
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}
	
	public function insert_batch($table, $data) { 
		$this->db->insert_batch($table, $data);
	}
	
	public function update2($table, $data, $where) { 
		$this->db->where($where);
		$this->db->update($table, $data);
		
		return $this->db->affected_rows();
	}
	
	public function delete($table, $where) { 
		$this->db->where($where);
		$this->db->delete($table);
		
		return $this->db->affected_rows();
	}
	
	public function get_boq_records($where = array()) {
		if ( isset($where['status']) && $where['status'] != '' ) { 
			$this->db->where('boq_main.status', $where['status']);
		}
		if ( isset($where['project_id']) && $where['project_id'] != '' ) { 
			$this->db->where('boq_main.project_id', $where['project_id']);
		}
		if ( isset($where['project_type_id']) && $where['project_type_id'] != '' ) { 
			$this->db->where('boq_main.project_category_id', $where['project_type_id']);
		}
		
		$this->db->where('boq_main.status !=', 'DELETED');
		$this->db->select('boq_main.*, project.project_name, project_cat.project_name AS project_category_name');
		$this->db->from('boq_main_new AS boq_main');
		$this->db->join('setup_project AS project', 'project.project_id = boq_main.project_id', 'LEFT');
		$this->db->join('project_category AS project_cat', 'project_cat.id = boq_main.project_category_id', 'LEFT');
		return $this->db->get()->result();
	}
	
	public function get_boq_main_new_data($boq_main_id) { 
		$this->db->where('boq_main.id', $boq_main_id);
		$this->db->select('boq_main.*, project.project_name, project.project_location, project_cat.project_name AS project_category_name');
		$this->db->from('boq_main_new AS boq_main');
		$this->db->join('setup_project AS project', 'project.project_id = boq_main.project_id', 'LEFT');
		$this->db->join('project_category AS project_cat', 'project_cat.id = boq_main.project_category_id', 'LEFT');
		return $this->db->get()->row_array(0);
	}
	
	public function has_existing_boq_details($boq_main_id) { 
		$this->db->where('boq_main_id', $boq_main_id);
		$total_rows = $this->db->get('boq_details_new')->num_rows();
		
		$ctr = false;
		if ( $total_rows ) { 
			$ctr = true; 
		}
		
		return $ctr;
	}
	
	public function has_novalue_boq_details($boq_main_id) { 
		$this->db->or_where('item_no', '#VALUE!');
		$this->db->or_where('description', '#VALUE!');
		$this->db->or_where('unit', '#VALUE!');
		$this->db->or_where('qty', '#VALUE!');
		$this->db->or_where('material', '#VALUE!');
		$this->db->or_where('labor_and_other_cost', '#VALUE!');
		$this->db->or_where('total', '#VALUE!');
		$this->db->or_where('amount', '#VALUE!');
		$this->db->where('boq_main_id', $boq_main_id);
		$total_rows = $this->db->get('boq_details_new')->num_rows();
		/* echo $this->db->last_query(); */
		
		$ctr = false;
		if ( $total_rows ) { 
			$ctr = true;
		}
		
		return $ctr;
	}
	
	public function get_employee_data($employee_id) { 
		$this->db->where('hr_employee.emp_number', $employee_id);
		
		$this->db->select("hr_employee.*, hr_person_profile.pp_person_code, CONCAT(hr_person_profile.pp_lastname, ', ', hr_person_profile.pp_firstname, ' ', hr_person_profile.pp_middlename) AS person_name", FALSE);
		$this->db->from('hr_employee');
		$this->db->join('hr_person_profile', 'hr_person_profile.pp_person_code = hr_employee.person_profile_no', 'LEFT');
		$result = $this->db->get()->result();
		
		return $result;
	}
	
	public function get_pr_websignatory($form, $signatory) { // Signatory values: recommended_by, approved_by, requested_by, issued_by, noted_by, received_by, checked_by 
		/* $this->db->where('form', $form);
		$this->db->where('signatory', $signatory);
		$this->db->where('project_id', $this->session->userdata('Proj_Code'));
		$this->db->where('title_id', $this->session->userdata('Proj_Main'));
		$this->db->select("hr_employee.emp_number, hr_person_profile.pp_person_code, CONCAT(hr_person_profile.pp_lastname, ', ', hr_person_profile.pp_firstname,' ', hr_person_profile.pp_middlename) AS person_name");
		$this->db->from('web_signatory');
		$this->db->join(); */
		
		$sql = "SELECT 
			* 
			FROM web_signatory
			 INNER JOIN (	
				SELECT
				    `hr_employee`.`emp_number`
				    , `hr_person_profile`.`pp_person_code`
				    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'person_name'   
				FROM
				    `hr_employee`
				    INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			   ) signatory
			 ON (signatory.emp_number = web_signatory.employee_id)
			 WHERE form = '$form' AND signatory = '$signatory' AND project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."'";
 		
 		$result = $this->db->query($sql)->result();
		return $result;
	}

	//added skip

	public function get_boq_new_details($arg){
		$project = $arg['project'];
		$category = $arg['category'];

		$sql = "SELECT  
					boqd.id,
					boqd.boq_main_id,
					boqd.item_no,
					boqd.description,
					boqd.unit,
					boqd.qty,
					boqd.material,
					boqd.labor_and_other_cost AS 'labor',
					boqd.total,
					boqd.amount,
					boqd.sequence_no,
					(SELECT SUM(quantity) FROM purchase_order_details AS pod LEFT JOIN purchase_order_main AS pom ON (pod.po_id = pom.po_id) WHERE pod.is_boq_new = '1' AND pod.boq_id = boqd.id AND pom.status <> 'CANCELLED') AS 'actual_qty',
					(SELECT SUM(unit_cost) FROM purchase_order_details AS pod LEFT JOIN purchase_order_main AS pom ON (pod.po_id = pom.po_id) WHERE pod.is_boq_new = '1' AND pod.boq_id = boqd.id AND pom.status <> 'CANCELLED') AS 'actual_cost'
				FROM boq_details_new AS boqd
				LEFT JOIN boq_main_new AS boqm
				ON (boqd.boq_main_id = boqm.id)
				WHERE boqm.project_id = '{$project}'
				AND boqm.project_category_id = '{$category}'
				AND boqm.status = 'COMPLETED'
				ORDER BY boqd.sequence_no ASC";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0){
			return $result->result_array();
		}
	}

	public function get_po_details($arg){
		$boq_id = $arg['boq_id'];

		$sql = "SELECT 
					*,
					(SELECT business_name FROM business_list WHERE business_number = pom.supplierID) 'supplier'
				FROM purchase_order_details AS pod
				LEFT JOIN purchase_order_main AS pom
				ON (pod.po_id = pom.po_id)
				WHERE pod.is_boq_new = '1'
				AND pod.boq_id = '{$boq_id}'
				AND pom.status <> 'CANCELLED'";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0){
			return $result->result_array();
		}

	}

	//end added skip
	
}