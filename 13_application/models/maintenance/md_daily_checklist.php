<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_daily_checklist extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}


	public function cbxName(){

		$sql = "SELECT group_detail_id,description FROM setup_group_detail WHERE group_id1 = 2";
		$result = $this->db->query($sql);
		$this->db->close();		
		return $result->result_array();		

	}


	function cbxCategory(){
		$sql = "SELECT id,`type` FROM fvc_category_setup WHERE `status` = 'ACTIVE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function cbxCheckedBy(){		
		$sql = "SELECT emp_number,(SELECT pp_fullname FROM hr_person_profile WHERE pp_person_code = person_profile_no) AS 'pp_fullname' FROM hr_employee";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	function cbxShift(){		
		$sql = "SELECT id,TYPE FROM fvc_shift_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function cbxEquipment($id){
		$sql = "SELECT equipment_id, equipment_brand, equipment_chassisno, equipment_driver, equipment_drivercode FROM db_equipmentlist  WHERE equipment_itemno ='".$id."' and equipment_status = 'AVAILABLE'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function model_no($id){
		$sql = "SELECT code FROM pm_model_setup WHERE pm_model_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();
	}


	function checklist($id){
		$sql = "SELECT
			  (SELECT
			     id
			   FROM fvc_checklist_setup
			   WHERE fvc_checklist_setup.id = setup.checklist_id) AS 'checklist_id',
			  (SELECT
			     `type`
			   FROM fvc_checklist_setup
			   WHERE fvc_checklist_setup.id = setup.checklist_id) AS 'Type',
			  ''      AS 'category',
			  'FALSE' AS 'ok',
			  'FALSE' AS 'no',
			  'FALSE' AS 'na',
			  ''      AS 'remarks',
			  (SELECT
			     utilization_main_id
			   FROM fvc_utilization_checklist
			   WHERE fvc_utilization_checklist.item_id = setup.item_id
			   LIMIT 1) AS 'utilization_main_id',
			  (SELECT
			     db_equipment_id
			   FROM fvc_utilization_checklist
			   WHERE fvc_utilization_checklist.item_id = setup.item_id
			   LIMIT 1) AS 'db_equipment_id'
			FROM fvc_utilization_setup setup
			WHERE setup.checked = 'TRUE'
			    AND setup.item_id = '".$id."'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	function get_signatory(){

		$sql = "SELECT emp_number,(SELECT pp_fullname FROM hr_person_profile WHERE pp_person_code = person_profile_no) AS 'pp_fullname' FROM hr_employee";
		$result = $this->db->query($sql);
		return $result->result_array();

	}






	function save($form,$list){
		$this->db->trans_begin();


		$insert = array(
			 'transdate'=>$form['dtpDate'],
             'operators_name'=>$form['txtOperator'],
             'operators_id'=>$form['txtOperatorID'],
             'shift_id'=>$form['cbxShift'],
             'main_remarks'=>$form['txtMainRemarks'],
             'checked_by'=>$form['cbxCheckedby'],
             'inspected_by'=>$form['cbxInspectedby'],
             'approved_by'=>$form['cbxInspectedby'],
             'user_id'=>$this->session->userdata('user'),
             'location'=>$form['profit_center'],
             'title'=>$form['project'],
			);

		$this->db->insert('fvc_utilization_main',$insert);

		$sql = "SELECT (IFNULL(MAX(utilization_id), '0')) AS 'max' FROM fvc_utilization_main";
		$result = $this->db->query($sql);
		$utilization_id = $result->row_array();

		$date = explode('-',$form['dtpDate']);

		$year  = $date[0];
		$month = $date[1];
		$day   = $date[2];

		$sql = "DELETE FROM fvc_utilization_checklist WHERE item_id = '".$form['cbxName']."' AND db_equipment_id = '".$form['cbxEquipment']."' AND (SELECT shift_id FROM fvc_utilization_main WHERE utilization_id = utilization_main_id) = '".$form['cbxShift']."' AND YEAR(savedate) = '".$year."' AND MONTH(savedate) = '".$month."' AND DAY(savedate) = '".$day."'";
		
		$this->db->query($sql);
		
		foreach($list as $row){

			$insert = array(
				'item_id'=>$form['cbxName'],
				'db_equipment_id'=>$form['cbxEquipment'],
				'checklist_id'=>$row[0],
				'category'=>$row[2],
				'ok'=>$row[3],
				'`NO`'=>$row[4],
				'NA'=>$row[5],
				'remarks'=>$row[6],
				'utilization_main_id'=>$utilization_id['max']
				);

			$this->db->insert('fvc_utilization_checklist',$insert);

		}

		$sql = "UPDATE `db_equipmentlist` SET `checklist` = 'DONE' WHERE `equipment_id` = '".$form['cbxEquipment']."'";		
		$this->db->query($sql);


		if($this->db->trans_status() === TRUE ){
			$this->db->trans_commit();
			return true;
		}else{
			$this->db->trans_rollback();
			return false;
		}
		

	}


	/***
	CUMULATIVE
	=================
	****/


	function cumulative(){

		if($this->input->post('type')==='all'){
			$sql = "CALL display_daily_equipment_checklist();";
		}else{
			$sql = "CALL display_daily_equipment_checklist_monthly('".$this->input->post('date')."')";
		}
					
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();


	}


	function cumulative_details($id){
		$tbl = array ( 'table_open'  => '<table class="table table-striped">' );
		$this->table->set_template($tbl);

		$sql = "SELECT (SELECT id FROM fvc_checklist_setup WHERE fvc_checklist_setup.id = fvc_utilization_checklist.checklist_id) AS 'checklist_id', (SELECT `type` FROM fvc_checklist_setup WHERE fvc_checklist_setup.id = fvc_utilization_checklist.checklist_id) AS 'Type', fvc_utilization_checklist.category, fvc_utilization_checklist.ok, fvc_utilization_checklist.no, fvc_utilization_checklist.na FROM fvc_utilization_checklist WHERE fvc_utilization_checklist.utilization_main_id = '".$id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();


	}

	


}