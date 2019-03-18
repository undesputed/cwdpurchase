<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class md_ap_aging extends CI_Model{

	function __construct(){
		parent::__construct();
				
	}
	
	function suppliers($checked=null,$Main=null){
		$result = $this->db->query("CALL accounting_ap_display_supplierswunpaid('".$checked."','".$Main."','AP AGING')");
		if($result->num_rows > 0)
			return $result->result_array();
		else
			return false;
	}
	

	function All_Data($Main=null){

		$sql = "
			SELECT 
				z.name_id,
				z.name_type,
				z.SUPPLIER,
				SUM(z.CURRENT) 'CURRENT',
				SUM(z.`1-30`) '1-30',
				SUM(z.`31-60`) '31-60',
				SUM(z.`61-90`) '61-90',
				SUM(z.`90+`) '90+',
				SUM(z.`TOTAL`) 'TOTAL'
			FROM (
				SELECT
					main.name_id,
					main.name_type,
					IF(main.name_type = 'PERSON',_get_person_name(main.name_id),_get_BusinessName(main.name_id)) 'SUPPLIER',
					main.trans_date 'DATE',
					_due_date AS 'DUE DATE', #detail.due_date 'DUE DATE',
					IF(main._balance = main._amount,'UNPAID',IF(main._balance = 0,'FULLY PAID','PARTIALLY PAID')) 'STATUS',
					main._balance 'TOTAL',
					IF((DATEDIFF(CURDATE(),main.trans_date) BETWEEN 0 AND 29),main._balance,0) 'CURRENT',  
					IF((DATEDIFF(CURDATE(),main.trans_date) BETWEEN 30 AND 59),main._balance,0) '1-30',
					IF((DATEDIFF(CURDATE(),main.trans_date) BETWEEN 60 AND 89),main._balance,0) '31-60',
					IF((DATEDIFF(CURDATE(),main.trans_date) BETWEEN 90 AND 119),main._balance,0) '61-90',
					IF((DATEDIFF(CURDATE(),main.trans_date) > 119),main._balance,0) '90+'
				FROM
					journal_main1 AS main
				WHERE					
					main.status <> 'CANCELLED'
					AND trans_type = 'ENTER PAYABLE'
				  )z 
			WHERE (z.`STATUS` <> 'FULLY PAID' AND z.`STATUS` <> 'PAID')
			GROUP BY z.name_id,z.name_type
			ORDER BY z.SUPPLIER;
		";
		
		$result = $this->db->query($sql);

		if($result->num_rows > 0)
			return $result->result_array();
		else
			return false;
	}
	

	function by_Supplier($Supplier=null,$checked=null,$Main=null){
		if($Supplier == null)
			$Supplier = 0;
		$result = $this->db->query("CALL accounting_display_ap_aging_supplier('".$Supplier."','".$checked."','".$Main."')");
		if($result->num_rows > 0)
			return $result->result_array();
		else
			return false;
	}
}
