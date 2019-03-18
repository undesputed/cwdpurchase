<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_project_expense extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_expense2($arg){

		$where = "";
		$where1 = "";
		if($arg['location'] != 'all'){
			$where = " AND cvm.project_id = '{$arg['location']}' ";
			$where1 = "WHERE z.project_id = '{$arg['location']}'";
		}

		if($arg['view_type']=="monthly"){
			$date = $arg['year']."-".$arg['month'].'-01';
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));
		}else{
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}

		/*$sql = "				
			SELECT
			z.*,
			pe.payroll_amount 'PAYROLL'
			FROM 
			( 
			SELECT 
			cvm.`voucher_no`,
			cvm.`check_date`,
			cvm.`voucher_date`,
			cvm.project_type,
			sp.project_name,
			sp.project_id,
			pc.`id`,
			cvm.total_amount 'total_amount',
			cvm.rr_id,
			cvm.po_id,
			SUM(IF(a.others IS NULL AND a.boq IS NULL ,cvm.total_amount,a.others))'OTHERS',
			SUM(a.boq) 'BOQ'
			FROM setup_project sp 
			INNER JOIN  cash_voucher_main cvm
			ON(sp.project_id = cvm.project_id)
			LEFT JOIN project_category pc
			ON (pc.id = cvm.`project_type`)
			LEFT JOIN (
				SELECT 
				rd.item_id,
				rm.receipt_id,
				rm.`project_id`,
				SUM(IF(boq.type_id IS NULL,(item_quantity_actual * item_cost_actual),0))'others',
				SUM(IF(boq.type_id IS NOT NULL,(item_quantity_actual * item_cost_actual),0))'boq'
				FROM receiving_details rd
				INNER JOIN receiving_main rm
				ON (rd.receipt_id = rm.receipt_id)
				LEFT JOIN view_display_boq boq
				ON (rd.item_id = boq.type_id AND rm.project_id = boq.project_id)
				GROUP BY receipt_id
			) a
			ON (cvm.rr_id = a.receipt_id)
			WHERE cvm.`status` = 'APPROVED'
			AND cvm.type <> 'CASH ADVANCE'
			AND (IF(cvm.`check_date` IS NULL,(cvm.voucher_date BETWEEN '{$from}' AND '{$to}'),(cvm.check_date BETWEEN '{$from}' AND '{$to}'))
			)
			GROUP BY sp.project_name,pc.`project_name`
			)z 
			LEFT JOIN (
				SELECT 
				project_id,
				project_type_id,
				SUM(payroll_amount)'payroll_amount'
				FROM payroll_entry
				WHERE payroll_date BETWEEN '{$from}' AND '{$to}'	
				GROUP BY project_id,project_type_id
			) pe
			ON (z.project_id = pe.project_id AND z.id = pe.project_type_id)
			{$where1}
		";*/

		$sql = "SELECT 
					z.project_id,
					z.type 'project_type',
					z.project 'project_name',
					z.others 'OTHERS',
					z.payroll 'PAYROLL'
				FROM (
					SELECT 
						a.project_id,
						a.type,
						a.project,
						a.others,
						a.payroll
					FROM (
						SELECT
							cvm.project_id,
							pc.id 'type',
							sp.project_name 'project',
							ROUND(SUM(cvm.total_amount),2) 'others',
							'0.00' AS 'payroll'
						FROM cash_voucher_main cvm
						INNER JOIN setup_project sp
						ON (sp.project_id = cvm.project_id)
						INNER JOIN project_category pc
						ON (cvm.project_type = pc.id)
						WHERE cvm.status = 'APPROVED'
						AND cvm.type <> 'CASH ADVANCE'
						AND (cvm.voucher_date BETWEEN '{$from}' AND '{$to}')
						GROUP BY cvm.project_id,cvm.project_type
						
						UNION 
						
						SELECT
							m.project_id,
							m.type,
							m.project,
							m.others,
							m.payroll
						FROM (
							SELECT
								pe.project_id,
								pc.id 'type',
								sp.project_name 'project',
								'0.00' AS 'others',
								ROUND(SUM(pe.payroll_amount),2) AS 'payroll'
							FROM payroll_entry pe
							INNER JOIN setup_project sp
							ON (sp.project_id = pe.project_id)
							INNER JOIN project_category pc
							ON (pe.project_type_id = pc.id)
							WHERE (pe.payroll_date BETWEEN '{$from}' AND '{$to}')
							GROUP BY pe.project_id,pe.project_type_id
						)m
						
						UNION
						
						SELECT
							b.project_id,
							b.type,
							b.project,
							b.others,
							b.payroll
						FROM (
							SELECT
								cvm.project_id,
								'0' AS 'type',
								sp.project_name 'project',
								ROUND(SUM(cvm.total_amount),2) AS 'others',
								'0.00' AS 'payroll'
							FROM cash_voucher_main cvm
							INNER JOIN setup_project sp
							ON (sp.project_id = cvm.project_id)
							WHERE cvm.status = 'APPROVED'
							AND cvm.type <> 'CASH ADVANCE'
							AND cvm.project_type = '0'
							AND (cvm.voucher_date BETWEEN '{$from}' AND '{$to}')
							GROUP BY cvm.project_id
						)b
						
						UNION
						
						SELECT
							n.project_id,
							n.type,
							n.project,
							n.others,
							n.payroll
						FROM (
							SELECT
								pe.project_id,
								'0' AS 'type',
								sp.project_name 'project',
								'0.00' AS 'others',
								ROUND(SUM(pe.payroll_amount),2) AS 'payroll'
							FROM payroll_entry pe
							INNER JOIN setup_project sp
							ON (sp.project_id = pe.project_id)
							WHERE (pe.payroll_date BETWEEN '{$from}' AND '{$to}')
							AND pe.project_type_id = '0'
							GROUP BY pe.project_id
						)n
					) a
				)z
				{$where1}
				ORDER BY z.project,z.type";
		$result = $this->db->query($sql);

		return $result->result_array();
		
	}

	public function get_expense($arg){
		
		$where = "";
		$where1 = "";
		if($arg['location'] != 'all'){
			$where = " AND sp.project_id = '{$arg['location']}' ";
			$where1 = "WHERE from_projectCode = '{$arg['location']}'";
		}

		if($arg['view_type']=="monthly"){
			$date = $arg['year']."-".$arg['month'].'-01';
			$from = date('Y-m-01',strtotime($date));
			$to   = date('Y-m-t',strtotime($date));
		}else{
			$from = $arg['date_from'];
			$to   = $arg['date_to'];
		}

		$sql = "
			SELECT
			b.project_id,
			b.pay_item,
			b.project_name,
			b.PAYROLL,
			IF(BOQ_1 = 0 OR BOQ_1 IS NULL,(IFNULL(OTHERS,0) + IFNULL(AX,0)),OTHERS) 'OTHERS',			
			IF(BOQ_1 > 0,(IFNULL(BOQ,0) + IFNULL(AX,0)),BOQ)'BOQ',
			SUM(AX),
			OTHERS_1,
			BOQ_1
		FROM
			(SELECT 
					sp.project_id,
					pay_item,
					CONCAT(sp.project_name,'(',IFNULL(jm.project_name,''),')') AS 'project_name',
					SUM((CASE 
						WHEN jm.memo LIKE 'PAYROLL%' THEN jm.amount
						END))'PAYROLL',
					SUM((CASE 
						WHEN jm.memo LIKE 'OTHERS%' THEN jm.amount
						END))'OTHERS',
					SUM((CASE 
						WHEN jm.memo LIKE 'BOQ%' THEN jm.amount
						END))'BOQ',
					SUM((CASE
						WHEN jm.type LIKE 'PAYMENT%' AND jm.`status`='POSTED' THEN jm.amount
						END))'AX'
				FROM
					setup_project sp
					LEFT JOIN (
						SELECT 
							jm.*,
							jd.amount,
							project_category.project_name
						FROM
							journal_main jm 
							INNER JOIN journal_detail jd /*(SELECT * FROM journal_detail WHERE dr_cr = 'DEBIT')*/
								ON (jm.journal_id = jd.journal_id AND dr_cr = 'DEBIT')
							LEFT JOIN project_category 
								ON (project_category.id = jm.pay_item)
						WHERE 
							jm.status <> 'CANCELLED' AND trans_date BETWEEN '{$from}' AND '{$to}') jm
						ON (sp.project_id = jm.location)
				WHERE
					sp.status = 'ACTIVE'  {$where}
				GROUP BY
					sp.project_id,jm.project_name) b				
			LEFT JOIN 
			(
			SELECT
				from_projectCode 'project_id',
				IF(for_usage_id NOT IN (SELECT project_category_id FROM boq_main WHERE project_id = from_projectCode),
						'0',
						for_usage_id)'pay_item',
				CONCAT(project_name,
					'(',
					IF(for_usage_id NOT IN (SELECT project_category_id FROM boq_main WHERE project_id = from_projectCode),
						'OTHERS',
						UPPER(for_usage)),
					')') 'project_name',
				0 'PAYROLL',
				IFNULL(SUM(other_expense),0) 'OTHERS_1',
				IFNULL(SUM(BOQ),0) 'BOQ_1',
				0'A'
			FROM
				(SELECT
					po.reference_no,
					po.po_date,
					po.paymentTerm,
					po.indays,
					pr.from_projectCode,
					pr.from_projectCodeName,
					pr.for_usage,
					pr.for_usage_id,
					boq.project_category_id,
					pod.itemNo,
					boq.type_id,
					project_name,
					(CASE 
						WHEN boq.type_id IS NULL THEN pod.total_unitcost
						END )'other_expense',
					(CASE 
						WHEN boq.type_id IS NOT NULL THEN pod.total_unitcost
						END )'BOQ'
				FROM 
					purchase_order_main po
					INNER JOIN purchase_order_details pod
						ON (po.po_id = pod.po_id)
					INNER JOIN view_display_transaction pr
						ON (pr.pr_id = po.pr_id)
					LEFT JOIN view_display_boq boq
						ON (pod.itemNo = boq.type_id AND 
							pr.from_projectCode = boq.project_id  AND 
							pr.for_usage_id  =  boq.project_category_id)
					INNER JOIN setup_project
						ON(setup_project.project_id = pr.from_projectCode)
				WHERE 
					ADDDATE(po.po_date,INTERVAL po.indays DAY) BETWEEN '{$from}' AND '{$to}' AND
					po.status <> 'CANCELLED' AND po.p_status IS NOT NULL) a
				{$where1}
			GROUP BY
				from_projectCode,for_usage_id)qq
			ON (qq.project_id = b.project_id AND qq.pay_item = b.pay_item)							
		GROUP BY project_id,pay_item
		ORDER BY project_name ASC
		";

		/*$sql = "
			SELECT
			project_id,
			pay_item,
			project_name,
			PAYROLL,
			SUM(OTHERS) 'OTHERS',
			SUM(BOQ) 'BOQ'
		FROM
			(SELECT 
				sp.project_id,
				pay_item,
				CONCAT(sp.project_name,'(',IFNULL(jm.project_name,''),')') AS 'project_name',
				SUM((CASE 
					WHEN jm.memo LIKE 'PAYROLL%' THEN jm.amount
					END))'PAYROLL',
				SUM((CASE 
					WHEN jm.memo LIKE 'OTHERS%' THEN jm.amount
					END))'OTHERS',
				SUM((CASE 
					WHEN jm.memo LIKE 'BOQ%' THEN jm.amount
					END))'BOQ'
			FROM
				setup_project sp
				LEFT JOIN (
					SELECT 
						jm.*,
						jd.amount,
						project_category.project_name
					FROM
						journal_main jm 	
						INNER JOIN journal_detail jd 
							ON (jm.journal_id = jd.journal_id AND dr_cr = 'DEBIT')
						LEFT JOIN project_category 
							ON (project_category.id = jm.pay_item)
					WHERE 
						trans_date BETWEEN '{$from}' AND '{$to}') jm
					ON (sp.project_id = jm.location)
			WHERE
				sp.status = 'ACTIVE' {$where}
			GROUP BY
				sp.project_id,jm.project_name

			UNION

			SELECT
				from_projectCode 'project_id',
				IF(for_usage_id NOT IN (SELECT project_category_id FROM boq_main WHERE project_id = from_projectCode),
						'0',
						for_usage_id)'pay_item',
				CONCAT(project_name,
					'(',
					IF(for_usage_id NOT IN (SELECT project_category_id FROM boq_main WHERE project_id = from_projectCode),
						'OTHERS',
						UPPER(for_usage)),
					')') 'project_name',
				0 'PAYROLL',
				IFNULL(SUM(other_expense),0) 'OTHERS',
				IFNULL(SUM(BOQ),0) 'BOQ'
			FROM
				(SELECT
					po.reference_no,
					po.po_date,
					po.paymentTerm,
					po.indays,
					pr.from_projectCode,
					pr.from_projectCodeName,
					pr.for_usage,
					pr.for_usage_id,
					boq.project_category_id,
					pod.itemNo,
					boq.type_id,
					project_name,
					(CASE 
						WHEN boq.type_id IS NULL THEN pod.total_unitcost
						END )'other_expense',
					(CASE 
						WHEN boq.type_id IS NOT NULL THEN pod.total_unitcost
						END )'BOQ'
				FROM 
					purchase_order_main po
					INNER JOIN purchase_order_details pod
						ON (po.po_id = pod.po_id)
					INNER JOIN view_display_transaction pr
						ON (pr.pr_id = po.pr_id)
					LEFT JOIN view_display_boq boq
						ON (pod.itemNo = boq.type_id AND 
							pr.from_projectCode = boq.project_id  AND 
							pr.for_usage_id  =  boq.project_category_id)
					INNER JOIN setup_project
						ON(setup_project.project_id = pr.from_projectCode)
				WHERE 
					ADDDATE(po.po_date,INTERVAL po.indays DAY) BETWEEN '{$from}' AND '{$to}' AND
					po.status <> 'CANCELLED' AND po.p_status IS NOT NULL) a

			{$where1}

			GROUP BY
				from_projectCode,for_usage_id)qq
		GROUP BY project_id,pay_item
		ORDER BY project_name ASC
		";*/
		$result = $this->db->query($sql);
		
		/*echo "<pre>";
		print_r($this->db->last_query());
		echo "</pre>";*/
		
		return $result->result_array();
		
	}


	public function _get_expense($arg){
		$where = "";
		if($arg['location'] != 'all'){
			$where = " AND sp.project_id = '{$arg['location']}' ";
		}




		if($arg['view_type'] == 'monthly'){
				$sql = "
						SELECT 
						CONCAT(sp.project_name,'(',IFNULL(jm.project_name,''),')') AS 'project_name',
						SUM((CASE 
						   WHEN jm.memo LIKE 'PAYROLL%' THEN jm.amount
						END))'PAYROLL',
						SUM((CASE 
						   WHEN jm.memo LIKE 'OTHERS%' THEN jm.amount
						END))'OTHERS',
						SUM((CASE 
						   WHEN jm.memo LIKE 'BOQ%' THEN jm.amount
						END))'BOQ'
						FROM setup_project sp
						LEFT JOIN (	
							SELECT 
							jm.*,
							jd.amount,
							project_category.project_name
							FROM
							journal_main jm 	
							INNER JOIN (SELECT * FROM journal_detail WHERE dr_cr = 'DEBIT') jd
							ON (jm.journal_id = jd.journal_id)
							LEFT JOIN project_category 
							 ON (project_category.id = jm.pay_item)
								WHERE YEAR(trans_date) = '{$arg['year']}' 
								AND MONTH(trans_date) = '{$arg['month']}' 
						) jm
						ON (sp.project_id = jm.location)
						WHERE sp.status = 'ACTIVE'
						{$where}
						GROUP BY sp.project_id,jm.project_name;
						";						
		}else{
				$sql = "

						SELECT 
						CONCAT(sp.project_name,'(',IFNULL(jm.project_name,''),')') AS 'project_name',
						SUM((CASE 
						   WHEN jm.memo LIKE 'PAYROLL%' THEN jm.amount
						END))'PAYROLL',
						SUM((CASE 
						   WHEN jm.memo LIKE 'OTHERS%' THEN jm.amount
						END))'OTHERS',
						SUM((CASE 
						   WHEN jm.memo LIKE 'BOQ%' THEN jm.amount
						END))'BOQ'
						FROM setup_project sp
						LEFT JOIN (	
							SELECT 
							jm.*,
							jd.amount,
							project_category.project_name
							FROM
							journal_main jm 	
							INNER JOIN (SELECT * FROM journal_detail WHERE dr_cr = 'DEBIT') jd	
							ON (jm.journal_id = jd.journal_id)
							LEFT JOIN project_category 
							 ON (project_category.id = jm.pay_item)
								WHERE trans_date BETWEEN '{$arg['date_from']}' AND '{$arg['date_to']}'
						) jm
						ON (sp.project_id = jm.location)
						WHERE sp.status = 'ACTIVE'
						{$where}
						GROUP BY sp.project_id,jm.project_name;					
						";

		}

	

		$result = $this->db->query($sql);		
		return $result->result_array();

	}

}