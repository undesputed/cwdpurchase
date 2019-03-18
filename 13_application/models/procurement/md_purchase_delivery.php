<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_purchase_delivery extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_data($location){
		$sql = "CALL purchaseOrder_display_main_new('".$location."');";		
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result;
	}


	function get_rr($location){
		$sql = "CALL purchaseOrder_display_main_new('".$location."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	function rr_details($po_id){		
		$sql = "SELECT
		  `receiving_details`.`po_id`,
		  `receiving_main`.`receipt_id`,
		  `receiving_main`.`receipt_no`        'RR No.',
		  `receiving_main`.`date_received`     'RR Date',
		  supplier_invoice                     'INV/DR NO.',
		  `business_list`.`business_number`,
		  `business_list`.`business_name`      'Supplier Name'
		FROM `receiving_details`
		  INNER JOIN `receiving_main`
		    ON (`receiving_details`.`receipt_id` = `receiving_main`.`receipt_id`)
		  INNER JOIN `business_list`
		    ON (`receiving_main`.`supplier_id` = `business_list`.`business_number`)
		WHERE `receiving_details`.`po_id` = '".$po_id."'
		GROUP BY `receiving_details`.`receipt_id`";
		$result = $this->db->query($sql);
		$this->db->close();		
		return $result;

	}

	function po_details($po_id){
		$sql = "CALL po_display_rrdetails_po1('".$po_id."')";
		$result =  $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	



}