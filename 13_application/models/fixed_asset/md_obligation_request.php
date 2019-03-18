<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_obligation_request extends CI_model {

	public function __construct(){
		parent :: __construct();		
	}


	public function get_cumulative($project,$location,$type = "MR"){

		$sql = "CALL display_mr_main_2('".$project."','".$location."','".$type."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;
	}


	public function get_equipment($location){
		$sql = "CALL display_mr_c1('".$location."')";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}
		

	public function mr_accessories($location){
		$sql = "CALL display_MR_accessories_list_n(".$location.")";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;				
	}
		

	public function save_obligation(){

		$this->db->trans_begin();

	 	$mr_type = "MR";
	 	$mr_status = "RELEASE";
	 	
	 	if($mr_type =='RO'){

	 	}else{

	 	// 	/*START*/
		 		$sql = "UPDATE mr_main SET mr_status = ? WHERE equipment_id = ? AND mr_status = 'ACTIVE'";
		 		$data = array(
		 				$mr_status,
		 				$this->input->post('equipment_id'),
		 			);
		 		$this->db->query($sql,$data);
	 	// 	/*END*/
		 	
	 	// 	/*START INSERT*/
	 		$data = array(
	 			'mr_no'=>$this->input->post('txtmrno'),
	 			'person_id'=>$this->input->post('cmbperson'),
	 			'equipment_id'=>$this->input->post('equipment_id'),
	 			'item_no'=>$this->input->post('itemno'),
	 			'project_id'=>$this->input->post('cmbprojectlocation'),
	 			'made_in'=>$this->input->post('txtmadein'),
	 			'date_saved'=>$this->input->post('dtpmrdate'),
	 			'item_cost'=>$this->input->post('txtvalue'),
	 			'to_project_id'=>$this->input->post('cmbtoprojectlocation'),
	 			'requestedby'=>$this->input->post('cmbrequested'),
	 			'issuedby'=>$this->input->post('cmbissued'),
	 			'approvedby'=>$this->input->post('cmbapproved'),
	 			'title_id'=>$this->input->post('from_project'),
	 			'type'=>$mr_type
	 			);
	 			 
	 		$this->db->insert('MR_Main',$data);

	 	// 	/*End INSERT*/

	 	 	$result = $this->db->query("SELECT mr_id FROM mr_main WHERE mr_no = '".$this->input->post('txtmrno')."'");
	 	 	$mr_id_getter = $result->row_array();

	 	// 	/*LOOP DETAILS*/

	 	 	$loop = $this->input->post('details');

	 	 	foreach($loop as $row){	 	 		
	 	 		$data = array(
	 			'mr_id'=>$mr_id_getter['mr_id'],
	 			'equipment_id'=>$this->input->post('equipment_id'),
	 			'item_description'=>$row['equipment_description'],
	 			'item_cost'=>$row['equipment_cost'],
	 			'remarks'=>($this->input->post('equipment_id') == 0)? "Release of OBligation" : "",
	 			'plate_propertyno'=>$this->input->post('plate_property_no'),
	 			'date_saved'=>$this->input->post('dtpmrdate')
	 			);
				$this->db->insert('MR_details',$data);
	 	 	}
	 			 	
	 	// 	/*END OF LOOP*/

	 	// 	/***/

	 		$this->db->query("UPDATE db_equipmentlist SET equipment_drivercode = '".$this->input->post('cmbperson')."',equipment_driver = '".$this->input->post('cmbperson_value')."' WHERE equipment_id = '".$this->input->post('equipment_id')."'");

	 	// 	/**/

	 	// 	/*---------------*/
	 		$data = array(
	 				'mr_id'=>$mr_id_getter['mr_id'],
	 				'from_location'=>$this->input->post('cmbprojectlocation'),
	 				'to_location'=>$this->input->post('cmbtoprojectlocation'),
	 				'to_assignee'=>$this->input->post('cmbperson'),
	 				'date_transferred'=>$this->input->post('dtpmrdate'),
	 				'remarks'=>$mr_status,
	 				'equipment_id'=>$this->input->post('equipment_id')
			);

	 		$this->db->insert('mr_history',$data);

			// /*---------------*/

	 		$data = array(
	 				'request_status'=>'RELEASE'
	 			);
	 		$this->db->where('equipment_request_id',$this->input->post('request_id'));
	 		$this->db->update('equipment_request_main',$data);

	 	// 	/*---------------*/

	 		$data = array(
	 				'equipment_status'=>'AVAILABLE',
	 				'equipment_location'=>$this->input->post('cmbtoprojectlocation'),
	 				'mr_status'=>'UNRELEASE'
	 			);
	 		$this->db->where('equipment_id',$this->input->post('txtequipmentname'));
	 		$this->db->update('db_equipmentlist',$data);


	 	// 	/*---------------*/

	 	} /*end else*/

	 	if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}


	}

	public function get_details($location,$mr_id){
		$sql = "
			SELECT
			  `mr_details`.`mr_detail_id`,
			  `mr_details`.`mr_id`,
			  `mr_details`.`equipment_id`,
			  `mr_details`.`item_description`    'Equipment Name',
			  (SELECT
			     equipment_status
			   FROM db_equipmentlist
			   WHERE equipment_id = `mr_details`.equipment_id)    'Status',
			  (SELECT
			     CONCAT(`setup_project`.project,' - ',`setup_project`.project_name,' - ',`setup_project`.project_location)
			   FROM setup_project
			   WHERE project_id = '".$location."') AS 'Equipment Location'
			FROM `mr_details`
			WHERE `mr_details`.mr_id = '".$mr_id."'";

		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	public function get_mainTable($id){

		$sql = "SELECT 
			MR_Main.*,
			db_equipmentlist.equipment_description,
			db_equipmentlist.equipment_status,
			db_equipmentlist.equipment_platepropertyno
			FROM MR_Main
			INNER JOIN db_equipmentlist 
			ON (Mr_main.equipment_id = db_equipmentlist.equipment_id)
			WHERE MR_main.MR_id = '".$id."'";
		$result = $this->db->query($sql);
		return $result->row_array();

	}

	public function get_detailTable($id){

		$sql = "SELECT item_description as 'equipment_description',
				item_cost as 'equipment_cost',
				plate_propertyno as 'equipment_platepropertyno'
 				FROM  MR_details WHERE mr_id = '".$id."'"; 				
		$result  = $this->db->query($sql);
		return $result->result_array();

	}
		

	public function changeMRStatus(){

		$sql = "UPDATE MR_Main SET mr_status = '".$this->input->post('status')."' WHERE MR_id = '".$this->input->post('id')."'";
		$this->db->query($sql);
		$this->db->close();
		return $this->input->post('status');

	}


	public function update_obligation(){

		$mr_type = "MR";
		$mr_status = "RELEASE";

		$sql = "UPDATE mr_main SET mr_status = ? WHERE equipment_id = ? AND mr_status = 'ACTIVE'";
		 		$data = array(
		 				$mr_status,
		 				$this->input->post('equipment_id'),
		 			);		 		
		$this->db->query($sql,$data);

		$data = array(
	 			'mr_no'=>$this->input->post('txtmrno'),
	 			'person_id'=>$this->input->post('cmbperson'),
	 			'equipment_id'=>$this->input->post('equipment_id'),
	 			'item_no'=>$this->input->post('itemno'),
	 			'project_id'=>$this->input->post('cmbprojectlocation'),
	 			'made_in'=>$this->input->post('txtmadein'),
	 			'date_saved'=>$this->input->post('dtpmrdate'),
	 			'item_cost'=>$this->input->post('txtvalue'),
	 			'to_project_id'=>$this->input->post('cmbtoprojectlocation'),
	 			'requestedby'=>$this->input->post('cmbrequested'),
	 			'issuedby'=>$this->input->post('cmbissued'),
	 			'approvedby'=>$this->input->post('cmbapproved'),
	 			'title_id'=>$this->input->post('from_project'),
	 			'type'=>$mr_type
	 			);
	 		$this->db->where('MR_id',$this->input->post('mr_id'));
	 		$this->db->update('MR_Main',$data);

	 


	 	$this->db->query("DELETE FROM mr_details WHERE mr_id ='".$this->input->post('mr_id')."'");
	 	$loop = $this->input->post('details');


 	 	foreach($loop as $row){	 	 		
 	 		$data = array(
 			'mr_id'=>$this->input->post('mr_id'),
 			'equipment_id'=>$this->input->post('equipment_id'),
 			'item_description'=>$row['equipment_description'],
 			'item_cost'=>$row['equipment_cost'],
 			'remarks'=>($this->input->post('equipment_id') == 0)? "Release of OBligation" : "",
 			'plate_propertyno'=>$this->input->post('plate_property_no'),
 			'date_saved'=>$this->input->post('dtpmrdate')
 			);
			$this->db->insert('MR_details',$data);
 	 	}

 	 	$this->db->query("UPDATE db_equipmentlist SET equipment_drivercode = '".$this->input->post('cmbperson')."',equipment_driver = '".$this->input->post('cmbperson_value')."' WHERE equipment_id = '".$this->input->post('equipment_id')."'");


		$result = $this->db->query("SELECT mr_id FROM mr_history where mr_id = '".$this->input->post('mr_id')."'");
		$exist_mr = $result->row_array();


		if(empty($exist_mr['mr_id'])){

			$data = array(
 				'mr_id'=>$this->input->post('mr_id'),
 				'from_location'=>$this->input->post('cmbprojectlocation'),
 				'to_location'=>$this->input->post('cmbtoprojectlocation'),
 				'to_assignee'=>$this->input->post('cmbperson'),
 				'date_transferred'=>$this->input->post('dtpmrdate'),
 				'remarks'=>$mr_status,
 				'equipment_id'=>$this->input->post('equipment_id')
				);
 			$this->db->insert('mr_history',$data);

		}else{

			$data = array( 				
 				'from_location'=>$this->input->post('cmbprojectlocation'),
 				'to_location'=>$this->input->post('cmbtoprojectlocation'),
 				'to_assignee'=>$this->input->post('cmbperson'),
 				'date_transferred'=>$this->input->post('dtpmrdate'),
 				'remarks'=>$mr_status,
 				'equipment_id'=>$this->input->post('equipment_id')
				);
			$this->db->where('mr_id',$this->input->post('mr_id'));
 			$this->db->update('mr_history',$data);

		}

 		$data = array( 				
 				'equipment_location'=>$this->input->post('cmbtoprojectlocation'), 				
 			);
 		$this->db->where('equipment_id',$this->input->post('txtequipmentname'));
 		$this->db->update('db_equipmentlist',$data);

 		if ($this->db->trans_status() === FALSE)
		{
		    $this->db->trans_rollback();
		}
		else
		{
		    $this->db->trans_commit();
		    return true;
		}

		
	}






}



