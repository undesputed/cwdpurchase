<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class md_inventory_entry extends CI_Model{

	function __construct(){
		parent::__construct();
				
	}

	function btnSave_Click($form,$obj){
		/*
		print_r($form);
		print_r($obj);*/

		if($obj['cmbItemType_SelectedIndex'] == 0 || $obj['cmbItemType_SelectedIndex'] == 3 || $obj['cmbItemType_SelectedIndex'] == 4){
			$this->nonSupply($form,$obj);
		}elseif($obj['cmbItemType_SelectedIndex'] == 1 || $obj['cmbItemType_SelectedIndex'] == 2 ){
			$this->Supply($form,$obj);
		}

		$invID=$this->DataObject("SELECT inv_master_id FROM inventory_master WHERE item_no = '".$form['txtItemNo']."' AND location_id = '".$obj['cmbprojectlocation']."'");

		$txtqty = (isset($form['txtqty'])) ? $form['txtqty'] : 1;
		if($invID!="" || $invID != false){
			$this->db->query("UPDATE inventory_master SET receive_qty = receive_qty + '".$txtqty."',current_qty = current_qty + '".$txtqty."' WHERE inv_master_id = '".$invID."'");
		}else{
			$inventory_array=array('item_no' 		=> 	$form['txtItemNo'],
									'location_id'	=> 	$obj['cmbprojectlocation'],
									'receive_qty' 	=> 	$txtqty,
									'withdraw_qty'	=> 	0,
									'current_qty'	=> 	$txtqty
			);
			$this->db->insert('inventory_master',$inventory_array);
		}

		return true; 
	}

	function Supply($form,$obj){

		$insert = array('item_no'				=>		(isset($form['txtItemNo'])) ? $form['txtItemNo'] : "0",
						'item_description'		=>		str_replace("'", "\'", (isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : "0"),
						'item_cost'				=>		(isset($form['txtunitcost'])) ? $form['txtunitcost'] : "0",
						'item_measurement'		=>		str_replace("'", "\'", (isset($form['txtMeasurement'])) ? $form['txtMeasurement'] : "0"),
						'supplier_id'			=>		(isset($form['cmbSupplier'])) ? $form['cmbSupplier'] : "0",
						'received_quantity'		=>		(isset($form['txtqty'])) ? $form['txtqty'] : "0",
						'withdrawn_quantity'	=>		(isset($form['txtWithdrawNo'])) ? $form['txtWithdrawNo'] : "0",
						'receipt_no'			=>		(isset($form['txtReceiptNo'])) ? $form['txtReceiptNo'] : "0",
						'withdraw_no'			=>		(isset($form['txtWithdrawNo'])) ? $form['txtWithdrawNo'] : "0",
						'registered_no'			=>		(isset($form['txtRegisteredNo'])) ? $form['txtRegisteredNo'] : "0",
						'division_code'			=>		(isset($form['txtDivisionNo'])) ? $form['txtDivisionNo'] : "0",
						'account_code'			=>		(isset($form['cmbaccount'])) ? $form['cmbaccount'] : "0",
						'project_location_id'	=>		(isset($obj['cmbprojectlocation'])) ? $obj['cmbprojectlocation'] : "0",
						'title_id'				=>		(isset($obj['cmbMainProject_cumulative'])) ? $obj['cmbMainProject_cumulative'] : "0",
						'item_code'				=>		(isset($form['txtitemcode'])) ? $form['txtitemcode'] : "0",
						'ref_po'				=>		(isset($form['txtreferenceno'])) ? $form['txtreferenceno'] : "0"
		);
		$this->db->insert('inventory_main',$insert);

		$receiving_array = array(
						'receipt_no' 			 => 	$form['txtreceipt'],
						'supplier_id'			 => 	$form['cmbSupplier'],
						'employee_receiver_id'	 => 	$form['cmbdriver'],
						'employee_checker_id'	 => 	$form['cmbdriver'],
						'delivered_by' 			 => 	$form['txtremarks'],
						'date_received' 		 => 	$form['dtpdateofpurchase'],
						'project_id'			 => 	$obj['cmbprojectlocation'], 
						'supplier_invoice'		 => 	$form['txtremarks'],
						'title_id'				 => 	$obj['cmbMainProject_cumulative'], 
						'posted_by' 			 => 	$form['cmbdriver'],
						'invoice_date'			 => 	$form['dtpdateofpurchase'],
						'received_status'		 => 	"FULL"
		);

		$this->db->insert('receiving_main',$receiving_array);
		
		$receipt_id = $this->DataObject("SELECT MAX(receipt_id) FROM receiving_main WHERE receipt_no = '".$form['txtreceipt']."'");

		$savereceive = array(
							'receipt_id'						=>		$receipt_id,
							'po_id'								=>		0,
							'item_id'							=>		$form['txtItemNo'],
							'item_quantity_ordered'				=>		$form['txtqty'],
							'item_quantity_actual'				=>		$form['txtqty'],
							'item_name_ordered'					=>		str_replace("'", "\'", (isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : "0"),
							'item_name_actual'					=>		str_replace("'", "\'", (isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : "0"),
							'item_cost_ordered'					=>		$form['txtunitcost'],
							'item_cost_actual'					=>		$form['txtunitcost'],
							'discrepancy'						=>		0,
							'discrepancy_remarks'				=>		"",
							'po_number'							=>		$form['txtreferenceno'],
							'unit_msr'							=>		str_replace("'", "\'", (isset($form['txtMeasurement'])) ? $form['txtMeasurement'] : "0"),
							'received'							=>		"TRUE",
							'item_remarks'						=>		$form['txtremarks']
		);                       

		$this->db->insert('receiving_details',$savereceive);

		return true;
	}

	function nonSupply($form,$obj){

		$insert = array('item_no'				=>		(isset($form['txtItemNo'])) ? $form['txtItemNo'] : "0",
						'item_description'		=>		str_replace("'", "\'", (isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : "0"),
						'item_cost'				=>		(isset($form['txtunitcost'])) ? $form['txtunitcost'] : "0",
						'item_measurement'		=>		str_replace("'", "\'", (isset($form['txtMeasurement'])) ? $form['txtMeasurement'] : "0"),
						'supplier_id'			=>		(isset($form['cmbSupplier'])) ? $form['cmbSupplier'] : "0",
						'received_quantity'		=>		(isset($form['txtqty'])) ? $form['txtqty'] : "0",
						'withdrawn_quantity'	=>		(isset($form['txtWithdrawNo'])) ? $form['txtWithdrawNo'] : "0",
						'receipt_no'			=>		(isset($form['txtReceiptNo'])) ? $form['txtReceiptNo'] : "0",
						'withdraw_no'			=>		(isset($form['txtWithdrawNo'])) ? $form['txtWithdrawNo'] : "0",
						'registered_no'			=>		(isset($form['txtRegisteredNo'])) ? $form['txtRegisteredNo'] : "0",
						'division_code'			=>		(isset($form['txtDivisionNo'])) ? $form['txtDivisionNo'] : "0",
						'account_code'			=>		(isset($form['txtAccountCode'])) ? $form['txtAccountCode'] : "100-10-040-0140",
						'project_location_id'	=>		(isset($obj['cmbprojectlocation'])) ? $obj['cmbprojectlocation'] : "0",
						'title_id'				=>		(isset($obj['cmbMainProject_cumulative'])) ? $obj['cmbMainProject_cumulative'] : "0",
						'item_code'				=>		(isset($form['txtitemcode'])) ? $form['txtitemcode'] : "0",
						'ref_po'				=>		(isset($form['txtreferenceno'])) ? $form['txtreferenceno'] : "0"
		);

		$this->db->insert('inventory_main',$insert);

		$inventory_id_ = $this->DataObject("SELECT MAX(inventory_id) FROM inventory_main");
		

		$sql = "CALL insert_db_equipment_list(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$sql_array=array(
						str_replace("'", "\'", (isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : ""),
	                    (isset($obj['cmbequipmenttype_text'])) 	? $obj['cmbequipmenttype_text'] : "",
	                    (isset($form['cmbequipmenttype'])) 		? $form['cmbequipmenttype'] : "-1",
	                    (isset($obj['cmbfueltype_text']))		? $obj['cmbfueltype_text'] : "",
	                    (isset($form['cmbfueltype'])) 			? $form['cmbfueltype'] : "-1",
	                    (isset($form['txtpropertyno'])) 		? $form['txtpropertyno'] : "",
	                    (isset($form['txtchassisno'])) 			? $form['txtchassisno'] : "",
	                    "MINING",
	                    "1",
	                    (isset($form['txtengineno'])) 			? $form['txtengineno'] : "",
	                    "",
	                    "",
	                    "ADMIN A. ADMIN",
	                    "1",
	                    (isset($form['txtunitcost'])) 		? $form['txtunitcost'] : "0",
	                    (isset($form['txtlife'])) 			? $form['txtlife'] : "0",
	                    (isset($form['dtpdateofpurchase'])) ? $form['dtpdateofpurchase'] : "",
	                    str_replace("'", "\'", (isset($form['txtremarks'])) ? $form['txtremarks'] : ""),
	                     (isset($form['txtfulltank'])) 		? $form['txtfulltank'] : "0",
	                     (isset($form['txtAccountCode'])) 	? $form['txtAccountCode'] : "",
	                     (isset($form['txtItemNo'])) 		? $form['txtItemNo'] : "",
	                     $inventory_id_,
	                     (isset($form['cmbModel'])) 		? $form['cmbModel'] : "-1",
	                     $obj['cmbprojectlocation'],
	                     (isset($form['txtreferenceno'])) 	? $form['txtreferenceno'] : "",
	                     (isset($form['txtbrand'])) 		? $form['txtbrand'] : "",
	                     (isset($form['txtmadein'])) 		? $form['txtmadein'] : "",
	                     (isset($form['cmbassignee'])) 		? $form['cmbassignee'] : "-1",
	                     $obj['cmbMainProject_cumulative'],
	                     "TRANSPORATION", 
	                     (isset($form['txtSMR'])) 			? $form['txtSMR'] : "0",
	                     (isset($form['txtyear'])) 			? $form['txtyear'] : "0",
	                     (isset($form['txtproghrs'])) 		? $form['txtproghrs'] : "0",
	                     (isset($form['txttruckfactor'])) 	? $form['txttruckfactor'] : "0",
	                     (isset($form['txtSMR']))			? $form['txtSMR'] : "0"
						);

		$this->db->query($sql,$sql_array);

		if($obj['cmbItemType_SelectedIndex'] == 3){
			$check_max = $this->DataObject("SELECT IFNULL(MAX(tank_id)+1,0) FROM setup_tank");
			$max_id = ($check_max != 0) ? $check_max : 1;
			$db_max = $this->DataObject("SELECT IFNULL(MAX(equipment_id),0) FROM db_equipmentlist");
			$db_id = ($db_max != 0) ? $db_max : 1;

			$sub_insert=array(
							'receipt_id'				=>	"0",
							'tank_number'				=>	$max_id,
							'tank_description'			=>	(isset($form['txtequipmentname'])) ? $form['txtequipmentname'] : "",
							'tank_type'					=>	(isset($obj['cmbfueltype_text'])) ? $obj['cmbfueltype_text'] : "",
							'capacity'					=>	(isset($form['txtfulltank'])) ? $form['txtfulltank'] : "0",
							'item_id'					=>	(isset($form['txtItemNo'])) ? $form['txtItemNo'] : "",
							'tank_location_project_id'	=>	(isset($obj['cmbprojectlocation'])) ? $obj['cmbprojectlocation'] : "-1",
							'equipment_id'				=>	$db_id
							);
			
			$this->db->insert('setup_tank',$sub_insert);
		}

		if($obj['cmbItemType_SelectedIndex'] == 0){
			$equip_id_getter = $this->DataObject("SELECT equipment_id FROM db_equipmentlist WHERE inventory_id = '".$inventory_id_."'");
			$MR_getter = $this->DataObject("SELECT IFNULL(MAX(mr_id)+1,1) FROM mr_main WHERE YEAR(date_saved) = YEAR(CURDATE()) AND MONTH(date_saved) = MONTH(CURDATE())");
			$MR_FORMAT = "MR - ".str_pad($MR_getter, 4,"0",STR_PAD_LEFT)." - ".date('Y');
			$cmbassignee=0;				 /*ninja; 0  as default*/
			$txtequipmentname_Tag=0;	/*ninja; default*/
			$this->insert_to_MR1($MR_FORMAT,
								$cmbassignee,
								$equip_id_getter,
								$txtequipmentname_Tag,
								$obj['cmbprojectlocation'],
								$form['txtmadein'],
								date('Y-m-d'),
								$form['txtunitcost'],
								$obj['cmbMainProject_cumulative']);
		}
		return true;
	}	

	function insert_to_MR1($_MRno="" , $_personid="" , $_equipmentid="" , $_item_no="" , $_project_id="" , $_madein="" , $_datesaved="" , $_itemcost="" , $_titleid="" ){
		$mr_array=array("MR_no" 		=> $_MRno,
						"person_id" 	=> $_personid,
						"equipment_id" 	=> $_equipmentid,
						"item_no" 		=> $_item_no,
						"project_id" 	=> $_project_id,
						"made_in" 		=> $_madein,
						"date_saved" 	=> $_datesaved,
						"item_cost" 	=> $_itemcost,
						"title_id"		=> $_titleid
						);
		
		$this->db->insert('mr_main',$mr_array);
		return true;
	}


	function txtitemcode($data=""){
		$result = $this->call_query("SELECT item_code FROM setup_group_detail WHERE group_detail_id = '".$data."'")->row_array();
		$result = array_values($result);
		return $result[0];
	}

	function dtpdateofpurchase($Proj_Main,$month,$year){
		$arr=array("prNoStr1"=>$this->DataObject("SELECT MAX(`receipt_no`) FROM `receiving_main` WHERE title_id = '".$Proj_Main."' AND SUBSTRING(`receipt_no`,4,2) = '".$month."' AND SUBSTRING(`receipt_no`,13,2) = '".$year."'"),
				   "prNoStr"=>$this->DataObject("SELECT MAX(`equipment_platepropertyno`) FROM `db_equipmentlist` WHERE SUBSTRING(`equipment_platepropertyno`,1,2) = '".$month."' AND SUBSTRING(`equipment_platepropertyno`,10,2) = '".$year."'")
				);
		return $arr;
	}

	function cmbdriver($sql=""){
		return $this->call_query("CALL gp_display_employee");
	}

	function cmbModel($item_no=""){
		return $this->call_query("SELECT pm_model_id,`code` FROM pm_model_setup WHERE item_id = '".$item_no."'");
	}

	function c1display($sql=""){
		return $this->call_query("SELECT group_detail_id AS 'Item No.',description AS 'Item Description',quantity AS 'Quantity',unit_cost AS 'Unit Cost',unit_measure AS 'Unit Measure' FROM setup_group_detail WHERE  group_id1 = '".$this->input->post('cmbItemType')."'");
	}

	function cmbdivision($sql=""){
		return $this->call_query("SELECT division_id,division_code,division_name,title_id FROM division_setup");
	}

	function cmbfueltype($sql=""){
		return $this->call_query("CALL display_allitems_fuel");
	}

	function cmbequipmenttype($sql=""){
		return $this->call_query("SELECT id,equipmenttype FROM db_equipmenttype");
	}

	function cmbItemType($sql=""){
		return $this->call_query("SELECT `id`,`type` FROM setup_group_type WHERE `status` = 'ACTIVE' ORDER BY `type` asc");
	}

	function cmbSupplier($sql=""){
		return $this->call_query("SELECT business_number,business_name FROM business_list WHERE `status`='ACTIVE'");
	}

	function cmbaccount($sql=""){
		return $this->call_query("SELECT account_code, account_description, CONCAT(account_code,' - ',account_description) AS 'Account Description' FROM account_setup");
	}


	function DataObject($sql=""){
		$result=$this->call_query($sql);
		$result = ($result->num_rows > 0) ? array_values($result->row_array()): false;
		if($result!= false)
			return $result[0];
		else
			return false;
	}


	function call_query($sql=""){
		$result=$this->db->query($sql);
		$this->db->close();
		return $result;
	}
	
}

