<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_stock_receiving extends CI_Model {


	public function __construct(){
		parent :: __construct();		
	}


	public function get_item($type){

		$sql = "SELECT group_detail_id AS 'Item No.',description AS 'Item Description',quantity AS 'Quantity',unit_cost AS 'Unit Cost',unit_measure AS 'Unit Measure' FROM setup_group_detail WHERE  group_id1 = '".$type."'";
		$result = $this->db->query($sql);
		$this->db->close();

		return $result;
	}


	public function get_itemType(){
		$sql = "SELECT `id`,`type` FROM setup_group_type WHERE `status` = 'ACTIVE' ORDER BY `type` asc";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_itemID($id){
		$sql = "SELECT * FROM setup_group_detail  WHERE group_detail_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();
	}

	public function get_supplier(){
		$sql = "SELECT business_number,business_name FROM business_list";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}

	public function get_employee(){
		$sql = "CALL gp_display_employee";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}
		

	public function get_stationary($indicator = 0){

		if($indicator == 1){
			$sql = "SELECT id,equipmenttype FROM db_equipmenttype WHERE id <> 1";	
		}else if($indicator == 2){
			$sql = "SELECT id,equipmenttype FROM db_equipmenttype WHERE id = 1";
		}else{
			$sql = "SELECT id,equipmenttype FROM db_equipmenttype";	
		}
		
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

		
	}

	public function get_fuel(){

		$sql = "CALL display_allitems_fuel";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function get_model(){
		$sql = "SELECT pm_model_id,`code` FROM pm_model_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}


	public function get_MR_FORMAT($date){

		$date  = explode('-',$date);
		$month  = $date[1];
		$year   = $date[0];

		$sql = "SELECT MAX(`MR_no`) as max FROM `mr_main` WHERE type = '{0}' and SUBSTRING(`MR_no`,4,2) = ? AND SUBSTRING(`MR_no`,11,4) = ? AND title_id = ? ";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));
		$data = $query->row_array();
		$return = null;

		if(empty($data['max'])){
				$return = "MR-".$month."-".$this->str_pad('1').'-'.$year.'';
		}else{
				$date = explode('-',$data[0]['max']);
				$return = "MR-".$month."-".$this->str_pad(($date[2]+1)).'-'.$year.'';
		}
		return $return;

	}


	private function str_pad($num){
		 return str_pad($num, 3, '0', STR_PAD_LEFT);
	}
	

	public function save(){
		$itemType = $this->input->post('itemType');

		$mr_format = $this->get_MR_FORMAT($this->input->post('equipment_purchase'));

		if($itemType == 2 || $itemType == 4 || $itemType == 8){

			$insert = array(
				'item_no'             =>$this->input->post('item_no'),
				'item_description'    =>$this->input->post('item_description'),
				'item_cost'           =>$this->input->post('item_cost'),
				'item_measurement'    =>$this->input->post('item_measurement'),
				'supplier_id'         =>$this->input->post('supplier_id'),
				'received_quantity'   =>$this->input->post('received_quantity'),
				'withdrawn_quantity'  =>$this->input->post('withdrawn_quantity'),
				'receipt_no'          =>$this->input->post('receipt_no'),
				'withdraw_no'         =>$this->input->post('withdraw_no'),
				'registered_no'       =>$this->input->post('registered_no'),
				'division_code'       =>$this->input->post('division_code'),
				'account_code'        =>$this->input->post('account_code'),
				'project_location_id' =>$this->input->post('location'),
				'title_id'            =>$this->input->post('project'),
				'item_code'           =>$this->input->post('item_code'),
				'ref_po'              =>$this->input->post('ref_po'),
			);
			
			$this->db->insert('inventory_main',$insert);

			$sql = "SELECT MAX(inventory_id) as max FROM inventory_main";
			$result = $this->db->query($sql);
			$inventory_id = $result->row_array();

			$insert_equipment = array(
					 'equipment_description'=>$this->input->post('equipment_description'),
					 'equipment_type'=>$this->input->post('equipment_type'), 
					 'equipment_typecode'=>$this->input->post('equipment_typecode'), 
					 'equipment_fueltype'=>$this->input->post('equipment_fueltype'), 
					 'equipment_fueltypecode'=>$this->input->post('equipment_fueltypecode'), 
					 'equipment_platepropertyno'=>$this->input->post('equipment_platepropertyno'), 
					 'equipment_chassisno'=>$this->input->post('equipment_chassisno'), 
					 'equipment_costcenter'=>$this->input->post('equipment_costcenter'), 
					 'equipment_costcentercode'=>'MINING',
					 'equipment_engineno'=>'1',
					 'equipment_department'=>"",
					 'equipment_departmentcode'=>"",
					 'equipment_driver'=>"ADMIN A. ADMIN",
					 'equipment_drivercode'=>"1",
					 'equipment_cost'=>$this->input->post('equipment_cost'),
					 'equipment_life'=>$this->input->post('equipment_life'),
					 'equipment_purchase'=>$this->input->post('equipment_purchase'),
					 'equipment_remarks'=>$this->input->post('equipment_remarks'),
					 'equipment_fulltank'=>$this->input->post('equipment_fulltank'),
					 'equipment_accountcode'=>$this->input->post('equipment_accountcode'),
					 'equipment_itemno'=>$this->input->post('equipment_itemno'),
					 'inventory_id'=>$inventory_id['max'],
					 'equipment_model'=>$this->input->post('equipment_model'),
					 'equipment_location'=>$this->input->post('equipment_location'),
					 'referrence_no'=>$this->input->post('referrence_no'),
					 'equipment_brand'=>$this->input->post('equipment_brand'),
					 'made_in'=>$this->input->post('made_in'),
					 'assignee'=>'0',
					 'title_id '=>$this->input->post('title'),
					 'body_type'=>"TRANSPORATION",
					 'equipment_smr'=>$this->input->post('equipment_smr'),
					 'year_model'=>$this->input->post('year_model'),
					 'program_hrs'=>$this->input->post('program_hrs'),
					 'equipment_factor'=>$this->input->post('equipment_factor'),
					 'smr_balance'=>$this->input->post('smr_balance'),
				);
			
			$this->db->insert('db_equipmentlist',$insert_equipment);

			if($itemType == 4){

				$sql = "SELECT IFNULL(MAX(tank_id)+1,0) as max FROM setup_tank";
				$result = $this->db->query($sql);
				$tank_id = $result->row_array();

				$sql = "SELECT IFNULL(MAX(equipment_id),0) as max FROM db_equipmentlist";
				$result = $this->db->query($sql);
				$equipment_id = $result->row_array();

				$max_id = (!empty($tank_id['max']))?  $tank_id['max'] : 0 ;
				$db_id  = (!empty($equipment_id['max']))?  $equipment_id['max'] : 0 ;

				$insert_tank = array(
					'receipt_id'=>0,
					'tank_number'=>$max_id,
					'tank_description'=>$this->input->post('equipment_description'),
					'tank_type'=>$this->input->post('equipment_type'),
					'capacity'=>$this->input->post('equipment_fueltype'),
					'item_id'=>$this->input->post('item_no'),
					'tank_location_project_id'=>$this->input->post('location'),
					'equipment_id'=>$db_id,
					'date_saved'=>$this->input->post('equipment_purchase'),
					'fuel_type'=>$this->input->post('equipment_fueltypecode'),
					'inventory_id'=>$inventory_id['max'],
					);

				$this->db->insert('setup_tank',$insert_tank);

				$sql = "UPDATE inventory_main SET `claim_status` = 'TRUE' WHERE inventory_id = '".$inventory_id['id']."'";
				$this->db->query($sql);

			}else if($itemType == 8){

				$sql = "SELECT IFNULL(MAX(equipment_id),0) as max FROM db_equipmentlist";
				$result = $this->db->query($sql);
				$db_max = $result->row_array();

				$insert_tire = array(
					'reference_no' =>$input->input->post('equipment_platepropertyno'),
					'trans_date'   =>$input->input->post('equipment_purchase'),
					'remarks'      =>$input->input->post('remarks'),
					'previous'     =>$input->input->post('equipment_factor'),
					'current'      =>$input->input->post('equipment_factor'),
					'equipment_id' =>$db_max['max'],
					'inventory_id' =>$inventory_id['max'],
					'item_no'      =>$input->input->post('item_no'),
					);

				$this->db->insert('pms_tire_details',$insert_tire);

			}else if($itemType == 2){

				$sql = "SELECT equipment_id FROM db_equipmentlist WHERE inventory_id = '".$inventory_id['max']."'";
				$result = $this->db->query($sql);
				$equipment_id = $result->row_array();

				$sql = "SELECT IFNULL(MAX(mr_id)+1,1) as max FROM mr_main";
				$result = $this->db->query($sql);
				$mr_getter = $result->row_array();

				$insert_mrMain = array(
						'MR_no'=>$mr_format,
						'person_id'=>$this->input->post('equipment_drivercode'),
						'equipment_id'=>$equipment_id['equipment_id'],
						'item_no'=>$this->input->post('item_no'),
						'project_id'=>$this->input->post('location'),
						'made_in'=>$this->input->post('made_in'),
						'date_saved'=>$this->input->post('equipment_purchase'),
						'item_cost'=>$this->input->post('equipment_cost'),
						'title_id'=>$this->input->post('project'),
						'mr_status'=>'UNRELEASED',
					);

				$this->db->insert('mr_main',$insert_mrMain);

				$insert_mrHistory = array(
						'mr_id'=>$mr_getter['max'],
						'from_location'=>$this->input->post('location'),
						'to_location'=>$this->input->post('location'),
						'to_assignee'=>$this->input->post('equipment_driver'),
						'date_transferred'=>$this->input->post('equipment_purchase'),
						'remarks'=>"NEW",
						'equipment_id'=>$equipment_id['equipment_id'],
					);
				$this->db->insert('mr_history',$insert_mrHistory);

				$insert_mrDetails = array(
					'mr_id'=>$mr_getter['max'],
					'equipment_id'=>$equipment_id['equipment_id'],
					'item_description'=>$this->input->post('item_description'),
					'item_cost'=>$this->input->post('item_no'),
					'remarks'=>"WAREHOUSE",
					'plate_propertyno'=>$this->input->post('equipment_platepropertyno'),
					'date_saved'=>$this->input->post('equipment_purchase'),
					'location_id'=>$this->input->post('location'),
					'title_id'=>$this->input->post('project'),
					'equipment_location'=>0,
					'mr_no'=>$mr_format,
					);
				$this->db->insert('mr_details_copy',$insert_mrDetails);
			}

		}else if($itemType == 1 || $itemType == 3){
							
				$insert = array(
					'item_no'             =>$this->input->post('item_no'),
					'item_description'    =>$this->input->post('item_description'),
					'item_cost'           =>$this->input->post('item_cost'),
					'item_measurement'    =>$this->input->post('item_measurement'),
					'supplier_id'         =>$this->input->post('supplier_id'),
					'received_quantity'   =>$this->input->post('received_quantity'),
					'withdrawn_quantity'  =>$this->input->post('withdrawn_quantity'),
					'receipt_no'          =>$this->input->post('receipt_no'),
					'withdraw_no'         =>$this->input->post('withdraw_no'),
					'registered_no'       =>$this->input->post('registered_no'),
					'division_code'       =>$this->input->post('division_code'),
					'account_code'        =>$this->input->post('account_code'),
					'project_location_id' =>$this->input->post('location'),
					'title_id'            =>$this->input->post('project'),
					'item_code'           =>$this->input->post('item_code'),
					'ref_po'              =>$this->input->post('ref_po'),
				);

				$this->db->insert('inventory_main',$insert);

				$insert_receivingMain = array(
						'receipt_no'=>$this->input->post('receipt'),
						'supplier_id'=>$this->input->post('supplier_id'),
						'employee_receiver_id'=>$this->input->post('equipment_drivercode'),
						'employee_checker_id'=>$this->input->post('equipment_drivercode'),
						'delivered_by'=>$this->input->post('remarks'),
						'date_received'=>$this->input->post('equipment_purchase'),
						'project_id'=>$this->input->post('location'),
						'supplier_invoice'=>$this->input->post('remarks'),
						'title_id'=>$this->input->post('project'),
						'posted_by'=>$this->input->post('equipment_drivercode'),
						'invoice_date'=>$this->input->post('equipment_purchase'),
						'received_status'=>'FULL',
				);

				$this->db->insert('receiving_main',$insert_receivingMain);

				$sql = "SELECT MAX(receipt_id) as max FROM receiving_main WHERE receipt_no = '".$this->input->post('receipt')."'";
				$result = $this->db->query($sql);
				$receipt_id = $result->row_array();

				$insert_receivingDetails = array(
					'receipt_id'=>$receipt_id['max'],
					'po_id'=>'0',
					'item_id'=>$this->input->post('item_no'),
					'item_quantity_ordered'=>$this->input->post('received_quantity'),
					'item_quantity_actual'=>$this->input->post('received_quantity'),
					'item_name_ordered'=>$this->input->post('item_description'),
					'item_name_actual'=>$this->input->post('item_description'),
					'item_cost_ordered'=>$this->input->post('item_cost'),
					'item_cost_actual'=>$this->input->post('item_cost'),
					'discrepancy'=>"0",
					'discrepancy_remarks'=>"",
					'po_number'=>$this->input->post('referense'),
					'unit_msr'=>$this->input->post('unit_msr'),
					'received'=>'TRUE',
					'item_remarks'=>$this->input->post('remarks'),
					);

				$this->db->insert('receiving_details',$insert_receivingDetails);

				$sql = "SELECT inv_master_id  FROM inventory_master WHERE item_no = '' AND location_id = ''";
				$result = $this->db->query($sql);
				$inv_master_id = $result->row_array();

				if($inv_master_id['inv_master_id'] !=""){

					$sql = "UPDATE inventory_master SET receive_qty = receive_qty + '".$this->input->post('quantity')."',current_qty = current_qty + '".$$this->input->post('quantity')."' WHERE inv_master_id = '".$inv_master_id['inv_master_id']."'";

				}else{

					$insert_inventoryMaster = array(
						'item_no'     =>$this->input->post('item_no'),
						'location_id' =>$this->input->post('location'),
						'receive_qty' =>$this->input->post('received_quantity'),
						'withdraw_qty'=>'0',
						'current_qty' =>$this->input->post('received_quantity'),
					);

					$this->db->insert('inventory_master',$insert_inventoryMaster);
					
				}

		} /*END IF*/

		return true;

	}/*EOF*/





}