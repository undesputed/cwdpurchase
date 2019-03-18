<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_equipment_registry extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_cumulative($location = ""){
		$sql = "CALL display_FAR_F_Nacky1('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function get_equipment($location = ""){
		$sql = "CALL display_inventory_checker_forequipment('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}

	function equipment_type(){
		$sql = "SELECT id,equipmenttype FROM db_equipmenttype;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
	

	function save_equipmentRegistry(){

		$this->db->trans_begin();

		$txtinventory = $this->input->post('txtinventory');
		
		$sql = "SELECT received_quantity FROM inventory_main WHERE inventory_id = '".$txtinventory."'";
		$result = $this->db->query($sql);
		$this->db->close();
		$quantity_getter =  $result->row_array();
		
		if($quantity_getter['received_quantity'] != 1){
			$quantity_getter = ($quantity_getter['received_quantity'] - 1);

			$sql = "SELECT * FROM inventory_main WHERE inventory_id = '".$txtinventory."'";
			$result = $this->db->query($sql);
			$ge = $result->row_array();
			
			$sql = "CALL insert_inventory_from_FAR1(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$data = array(
					$ge['item_no'],
					$ge['item_description'],
					$ge['item_cost'],
					$ge['item_measurement'],
					$ge['supplier_id'],
					"1",
					$ge['withdrawn_quantity'],
					$ge['receipt_no'],
					$ge['withdraw_no'],
					$ge['registered_no'],
					$ge['division_code'],
					$ge['account_code'],
					$ge['project_location_id'],
					$this->input->post('cmbMainProject'),
				);
			$this->db->query($sql,$data);
			$this->db->close();

			$sql = "UPDATE inventory_main SET received_quantity = '".$quantity_getter."' WHERE inventory_id = '".$txtinventory."'";
			$this->db->query($sql);
			
			$sql = "SELECT MAX(inventory_id)  as max FROM inventory_main WHERE item_no = '".$ge['item_no']."' AND receipt_no = '".$ge['receipt_no']."'";
			$result = $this->db->query($sql);
			$inventory_id_getter = $result->row_array();		
			
			$txtinventory = $inventory_id_getter['max'];

		}

			$sql = "CALL insert_FAR_NEW(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
			$data = array(
					$this->input->post('txtequipmentname'),
					$this->input->post('cmbequipmenttype'),
					$this->input->post('cmbequipmenttype_option'),
					$this->input->post('cmbfueltype'),
					$this->input->post('cmbfueltype_option'),
					$this->input->post('txtpropertyno'),
					$this->input->post('txtchassisno'),
					$this->input->post('cmbdivision'),
					$this->input->post('cmbdivision_option'),
					$this->input->post('txtengineno'),
					$this->input->post('cmbdriver'),
					$this->input->post('cmbdriver_option'),
					$this->input->post('txtunitcost'),
					$this->input->post('txtlife'),
					$this->input->post('dtpdateofpurchase'),
					$this->input->post('txtremarks'),
					$this->input->post('txtfulltank'),
					$this->input->post('cmbaccount_option'),
					$this->input->post('txtequipmentname_tag'),
					$txtinventory,
					$this->input->post('cmbModel_option'),
					$this->input->post('cmblocation_option'),
					$this->input->post('txtreferenceno'),
					$this->input->post('txtbrand'),
					$this->input->post('txtmadein'),
					$this->input->post('cmbassignee_option'),
					$this->input->post('cmbMainProject_option'),
					$this->input->post('txtSMR'),
					$this->input->post('txtyear'),
				);
		$this->db->query($sql,$data);
				

		$sql = "SELECT equipment_id FROM db_equipmentlist WHERE inventory_id = '".$txtinventory."'";
		$result = $this->db->query($sql);
		
		$equip_id_getter = $result->row_array();

		$sql = "SELECT IFNULL(MAX(mr_id)+1,1) as max FROM mr_main WHERE YEAR(date_saved) = YEAR('".$this->input->post('dtpdateofpurchase')."') AND MONTH(date_saved) = MONTH('".$this->input->post('dtpdateofpurchase')."')";
		$result = $this->db->query($sql);		
		$mr_getter = $result->row_array();
		
		$insert = array(
			'MR_no'=>$this->input->post('mr_code'),
			'person_id'=>$this->input->post('cmbdriver'),
			'equipment_id'=>$equip_id_getter['equipment_id'],
			'item_no'=>$this->input->post('txtequipmentname_value'),
			'project_id'=>$this->input->post('cmblocation'),
			'made_in'=>$this->input->post('txtmadein'),
			'date_saved'=>$this->input->post('dtpdateofpurchase'),
			'item_cost'=>$this->input->post('txtunitcost'),
			'title_id'=>$this->input->post('cmbMainProject'),
			'mr_status'=>"RELEASE",
			);		
		$this->db->insert('mr_main',$insert);
		
		$insert = array(
			'mr_id'=>$mr_getter['max'],
			'from_location'=>$this->input->post('cmblocation'),
			'to_location'=>$this->input->post('cmblocation'),
			'to_assignee'=>$this->input->post('cmbdriver'),
			'date_transferred'=>$this->input->post('dtpdateofpurchase'),
			'remarks'=>$this->input->post('txtremarks'),
			'equipment_id'=>$this->input->post('equip_id_getter)'),
			);

		$this->db->insert('mr_history',$insert);
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    echo "roll out";
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
	}

	function update_equipmentRegistry(){

		$this->db->trans_begin();
	
		$sql = "CALL update_FAR_NEW(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		$data = array(
				$this->input->post('txtequipmentname'),  		   
				$this->input->post('cmbequipmenttype'),  		   
				$this->input->post('cmbequipmenttype_option'),  
				$this->input->post('cmbfueltype'),  		       
				$this->input->post('cmbfueltype_option'),  		   
				$this->input->post('txtpropertyno'),  		       
				$this->input->post('txtchassisno'),  		       
				$this->input->post('cmbdivision'),  		       
				$this->input->post('cmbdivision_option'),  		   
				$this->input->post('txtengineno'),  		       
				$this->input->post('cmbdriver'),  		           
				$this->input->post('cmbdriver_option'),  		   
				$this->input->post('txtunitcost'),  		       
				$this->input->post('txtlife'),  		           
				$this->input->post('dtpdateofpurchase'),  		   
				$this->input->post('txtremarks'),  		           
				$this->input->post('txtfulltank'),  		       
				$this->input->post('cmbaccount_option'),  		   
				$this->input->post('txtequipmentname_tag'),  	
				$this->input->post('txtinventory'),  		                           
				$this->input->post('cmbModel_option'),  		   
				$this->input->post('cmblocation_option'),  		   
				$this->input->post('txtreferenceno'),  		       
				$this->input->post('txtbrand'),  		           
				$this->input->post('txtequipmendid'),  		       
				$this->input->post('cmbMainProject_option'),  	
				$this->input->post('txtSMR'),  		               
				$this->input->post('txtyear'),     
			);                
		 $this->db->query($sql,$data);			 
		
		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		    echo "roll out";
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}
	}





	function get_details($id,$location,$project){
		$sql    = "CALL display_details_far_report('".$id."','".$location."','".$project."')";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result;

	}

	function get_mainData($id){
		$sql = "SELECT * FROM db_equipmentlist WHERE equipment_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}


}