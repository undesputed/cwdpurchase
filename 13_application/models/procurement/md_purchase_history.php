<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_purchase_history extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}


	public function item(){
		$sql = "CALL purchasehistory_display_items_paid()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
		
	}

	public function supplier(){
		$sql = "CALL purchasehistory_display_suppliers_receive()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function get_itemDetails($item_no){
		$sql = "CALL purchasehistory_supplier_SQL_items('".$item_no."')";
		$result = $this->db->query($sql);
		$this->db->close();
		$result = $result->row_array();

		$result = $this->db->query($result['RESULT']);

		$this->db->close();
		return $result;
	}

	public function get_supplierDetails($supplier_id,$type){		
		$sql = "CALL purchasehistory_display_po_supplier('".$supplier_id."','".$type."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}



	




}