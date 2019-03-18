<?php defined('BASEPATH') OR exit('No direct script access allowed');

class md_project extends CI_MODEL {

	public function __construct(){
		parent :: __construct();		
	}


	function get_main($project_code,$project_main){

		$data = array();
		$sql = "SELECT CONCAT('(',project,')',' - ',project_location) AS 'Project_F' FROM setup_project WHERE project_id = '".$project_code."'";
		$result = $this->db->query($sql);
		
		$value = $result->row_array();
		$data['project_code'] = $value['Project_F'];

		$sql = "SELECT title_name FROM project_title  WHERE title_id = '".$project_main."'";
		$result = $this->db->query($sql);
		$value = $result->row_array();

		$data['project_main'] = $value['title_name'];
		return $data;

	}

	function get_project_site($title_id = ''){

		if(empty($title_id))
		{
			$title_id = $this->session->userdata('Proj_Main');
		}

		$sql = "
			SELECT * FROM setup_project WHERE title_id = '".$title_id."';
		";
				
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}


	function get_setup_project($project_id = ''){
				
		$sql = "
			SELECT * FROM setup_project WHERE project_id = '".$project_id."';
		";

		$result = $this->db->query($sql);
		return $result->row_array();

	}

	
	function get_tenant(){
		$sql = "SELECT *,name'full_name' FROM withdraw_tenant WHERE STATUS = 'ACTIVE';";
		$result = $this->db->query($sql);
		return $result->result_array();
	}


	function get_project_site_not_me($title_id = ''){

		if(empty($title_id))
		{
			$title_id = $this->session->userdata('Proj_Main');
		}

		$sql = "
			SELECT *,CONCAT(project,' : ',project_name,' : ',project_location) 'project_full_name' FROM setup_project WHERE title_id = '".$title_id."' AND project_id <> '".$this->session->userdata('Proj_Code')."' ;
		";
				
		$result = $this->db->query($sql);
		return $result->result_array();		
	}



	function get_supplier_affiliate($id = ''){
		$where = '';
		if(!empty($id)){
			$where = " AND hr_person_profile.pp_person_code = '".$id."' ";
		}

		$sql = "
	 			SELECT
				hr_person_profile.pp_person_code 'supplier_id',
				CONCAT(hr_person_profile.pp_lastname,', ',hr_person_profile.pp_firstname, cho_Middle_Initial(hr_person_profile.pp_middlename)) 'business_name',
				pp_Address1 'address',
				'' AS 'contact_no'
				FROM hr_person_profile
				where pp_type = 'SUPPLIER'				
				".$where."
				ORDER BY `business_name` ASC
		";
		$result = $this->db->query($sql);
		return $result->result_array();
	}


	function get_supplier_business($id = ''){

		$where = '';
		if(!empty($id)){
			$where = " AND business_number =  '".$id."'";
		}

		$sql = "
				SELECT business_number 'supplier_id',business_name,trade_name,address,contact_no,term_type,term_days 
				FROM business_list WHERE `status` = 'ACTIVE' AND type = 'BUSINESS' ".$where." ORDER BY business_name ASC ;
		";

		$result = $this->db->query($sql);
		return $result->result_array();

	}

	function get_supplier(){
		$sql = "
			SELECT business_number,business_name,address,tin_number FROM business_list WHERE `status` = 'ACTIVE' AND type = 'BUSINESS' ORDER BY business_name ASC 
		";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	function get_projects($id = null){
		$sql = "SELECT * FROM project_title";
		$result=$this->db->query($sql);
		if ($result)
			return $result->result_array();
		else 
			return false;
	}
	
	function get_project_category($arg = ""){
		$sql = "select * from project_category ";
		if(gettype($arg) == 'string'){
			$sql .= $arg;
		}
		
		$result = $this->db->query($sql);
		return $result->result_array();
	}

	function get_profit_center($id = null){
		$sql = "SELECT CONCAT(project_name,' - ',project_location) as 'project_full_name',project_location,title_id,project_id,project,CONCAT(project,' - ',project_name,' - ',project_location) AS 'project_full',project_no,CONCAT('(',project,') - ',project_location) AS location,project_effectivity FROM setup_project";

		if(!empty($id)){
			$sql .=" WHERE title_id = '".$id."'";
		}		
		$sql.=" ORDER BY project_name ASC";
			$result=$this->db->query($sql);
		if ($result)
			return $result->result_array();
		else 
			return false;
	}

	/**Signatory**/

	public function all_employee(){
		$sql = "
			SELECT
			    `hr_employee`.`emp_number`
			    , `hr_person_profile`.`pp_person_code`
			    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'person_name'   
			FROM
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			    WHERE record_status = 'ACTIVE'
		";
		$result =  $this->db->query($sql);
		return $result->result_array();
	}

	public function all_employee2(){
		$sql = "
			SELECT
			    `hr_employee`.`emp_number`
			    , `hr_person_profile`.`pp_person_code`
			    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'Person Name'   
			FROM
			    `hr_employee`
			    INNER JOIN `hr_person_profile` 
			        ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			    WHERE record_status = 'ACTIVE'
		";
		$result =  $this->db->query($sql);
		return $result->result_array();
	}

	function addtolist_signatory($arg){

		$insert = array(
			'form'=>$arg['form'],
			'signatory'=>$arg['signatory'],
			'employee_id'=>$arg['employee_id'],
			'project_id'=>$this->session->userdata('Proj_Code'),
			'title_id'=>$this->session->userdata('Proj_Main'),
			);
		$this->db->insert('web_signatory',$insert);

	}

	function addtolist_signatory_print($arg){

		$insert = array(
			'form'=>$arg['form'],
			'signatory'=>$arg['signatory'],
			'employee_id'=>$arg['employee_id'],
			'designation'=>$arg['designation'],
			'project_id'=>$this->session->userdata('Proj_Code')
			);
		$this->db->insert('setup_signatory_print',$insert);

	}

	function get_websignatory($arg){

		$sql = 
		"
			SELECT 
			* 
			FROM web_signatory
			 INNER JOIN (	
				SELECT
				    `hr_employee`.`emp_number`
				    , `hr_person_profile`.`pp_person_code`
				    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'Person Name'   
				FROM
				    `hr_employee`
				    INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			   ) signatory
			 ON (signatory.emp_number = web_signatory.employee_id)
			 WHERE form = '".$arg['form']."' AND signatory = '".$arg['signatory']."' AND project_id = '".$this->session->userdata('Proj_Code')."' AND title_id = '".$this->session->userdata('Proj_Main')."'
 		";
 		
 		$result = $this->db->query($sql); 		
 		return $result->result_array();
 		
	}

	function get_signatory_print($arg){

		$sql = 
		"
			SELECT 
			* 
			FROM setup_signatory_print
			 INNER JOIN (	
				SELECT
				    `hr_employee`.`emp_number`
				    , `hr_person_profile`.`pp_person_code`
				    , CONCAT(`hr_person_profile`.`pp_lastname`,', ', `hr_person_profile`.`pp_firstname`,' ', `hr_person_profile`.`pp_middlename`) 'Person Name'   
				FROM
				    `hr_employee`
				    INNER JOIN `hr_person_profile` 
					ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			   ) signatory
			 ON (signatory.emp_number = setup_signatory_print.employee_id)
			 WHERE form = '".$arg['form']."' AND signatory = '".$arg['signatory']."' AND project_id = '".$this->session->userdata('Proj_Code')."'
 		";
 		
 		$result = $this->db->query($sql); 		
 		return $result->result_array();
 		
	}

	function remove_signatory($arg){

		$sql = "DELETE FROM web_signatory WHERE form = '".$arg['form']."' AND signatory = '".$arg['signatory']."' AND employee_id = '".$arg['employee_id']."'";
		$this->db->query($sql);
		

	}

	function remove_signatory_print($arg){

		$sql = "DELETE FROM setup_signatory_print WHERE id = '".$arg['id']."'";
		$this->db->query($sql);
		

	}

	function signatory1(){
		$sql = "CALL display_person_signatory1(?)";
		$result = $this->db->query($sql,array($this->session->userdata('lblpersoncode')));
		$this->db->close();
		return $result->result_array();
	}


	function signatory($a,$b,$c,$d){
		
		$sql = "CALL display_person_signatory('".$a."','".$b."','".$c."','".$d."')";
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result->result_array();
		
	}

	function get_max_pr($month,$year){
		
		$sql = "SELECT
				MAX(`purchaseNo`)
				as max
				FROM `purchaserequest_main`
				WHERE SUBSTRING(`purchaseNo`,9,2) = ?
				    AND SUBSTRING(`purchaseNo`,4,4) = ?
				    AND title_id = ?";		
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));

		return $query->result_array();

	}

	function get_max_ref(){
		$sql = "SELECT
				MAX(`referenc_no`)
				as max
				FROM `purchase_order_main`
				WHERE SUBSTRING(`reference_no`,9,2) = ?
				    AND SUBSTRING(`reference_no`,4,4) = ?
				    AND title_id = ?";		
	}


	function get_item_category(){
			
		$sql    = "SELECT * FROM setup_group ORDER BY group_description ASC;";
		$result = $this->db->query($sql);
		return $result->result_array();

	}


	/*function get_max_pr($month,$year){
		
		$sql = "SELECT
				MAX(`purchaseNo`)
				as max
				FROM `purchaserequest_main`
				WHERE SUBSTRING(`purchaseNo`,4,2) = ?
				    AND SUBSTRING(`purchaseNo`,11,4) = ?
				    AND project_id = ?";		
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Code')));
		return $query->result_array();

	}*/


	
	function get_max_canvass($month,$year){
		$sql = "SELECT MAX(`c_number`) as max FROM `canvass_main` WHERE SUBSTRING(`c_number`,9,2) = ? AND SUBSTRING(`c_number`,4,4) = ? AND title_id = ?";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));	

		return $query->result_array();

	}


	function get_max_po($month,$year){
			$sql = "SELECT MAX(`po_number`) as max FROM `purchase_order_main` WHERE   SUBSTRING(`po_number`,9,2) = ? AND SUBSTRING(`po_number`,4,4) = ? AND title_id = ?";		
			$data = array(
				$month,
				$year,
				$this->session->userdata('Proj_Main')
			);
			$query = $this->db->query($sql,$data);		
			return $query->result_array();
	}

	function get_max_rr($month,$year){
		$sql = "SELECT MAX(`receipt_no`) AS max FROM `receiving_main` WHERE SUBSTRING(`receipt_no`,9,2) = ? AND SUBSTRING(`receipt_no`,4,4) = ? AND title_id = ?";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	
	function get_max_itemCode($month,$year){
		$sql = "SELECT MAX(`item_code`) as max FROM `inventory_setup` WHERE SUBSTRING(`item_code`,1,2) = ? AND SUBSTRING(`item_code`,8,4) = ?";
		$query = $this->db->query($sql,array($month,$year));		
		return $query->result_array();
	}

	function get_maxMIS($month,$year){
		$sql = "SELECT IFNULL(MAX(withdraw_id),0) as max FROM withdraw_main";
		$query = $this->db->query($sql,array($month,$year));		
		return $query->result_array();
	}

	function get_max_equipmentRequest($month,$year){
		$sql = "SELECT MAX(`equipment_request_no`) as max FROM `equipment_request_main` WHERE  equipment_id ='0' and SUBSTRING(`equipment_request_no`,9,2) = ? AND SUBSTRING(`equipment_request_no`,4,4) = ? AND title_id =  ? ";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	function get_max_equipmentTransfer($month,$year){
		$sql = "SELECT `equipment_request_no` AS max FROM `equipment_request_main` WHERE  SUBSTRING(`equipment_request_no`,9,2) = ? AND SUBSTRING(`equipment_request_no`,4,4) = ? AND title_id =  ? ORDER BY equipment_request_id  DESC LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));				
		return $query->result_array();
	}

	function get_max_withdrawal($month,$year){
		$sql = "SELECT max(`withdraw_id`) AS max FROM `withdraw_main` WHERE  SUBSTRING(`withdraw_no`,9,2) = ? AND SUBSTRING(`withdraw_no`,4,4) = ? AND title_id =  ? LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	function get_max_issuance($month,$year){
		$sql = "SELECT max(`id`) AS max FROM `item_issuance_main` WHERE  SUBSTRING(`issuance_no`,9,2) = ? AND SUBSTRING(`issuance_no`,4,4) = ? AND title_id =  ? LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	function get_max_transfer($month,$year){
		$sql = "SELECT max(`id`) AS max FROM `item_transfer_main` WHERE  SUBSTRING(`transfer_no`,9,2) = ? AND SUBSTRING(`transfer_no`,4,4) = ? AND title_id =  ? LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	function get_max_invoice($month,$year){
		$sql = "SELECT max(`invoice_id`) AS max FROM `tbl_invoice` WHERE  SUBSTRING(`invoice_no`,5,2) = ? AND SUBSTRING(`invoice_no`,12,4) = ? AND title_id =  ? LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}

	
	function get_max_dispatchMain($month,$year){		
		$sql = "SELECT MAX(`dispatch_no`) as max FROM `dispatch_main` WHERE SUBSTRING(`dispatch_no`,5,2) = ? AND SUBSTRING(`dispatch_no`,12,4) = ? AND from_title_id = ? ";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
		
	}
	
	function get_max_equipmentlist($month,$year){
		$sql ="SELECT MAX(`equipment_platepropertyno`) as max FROM `db_equipmentlist` WHERE SUBSTRING(`equipment_platepropertyno`,1,2) = ? AND SUBSTRING(`equipment_platepropertyno`,8,4) = ?";
		$query = $this->db->query($sql,array($month,$year));
		return $query->result_array();
	}
	
	function get_max_request($month,$year){
		$sql = "SELECT max(`id`) AS max FROM `item_request_main` WHERE  SUBSTRING(`transfer_no`,9,2) = ? AND SUBSTRING(`transfer_no`,4,4) = ? AND title_id =  ? LIMIT 1";
		$query = $this->db->query($sql,array($month,$year,$this->session->userdata('Proj_Main')));		
		return $query->result_array();
	}


	function get_max_mr2($month,$year){
			$sql = "SELECT MAX(`MR_no`) as max FROM `mr_main` WHERE TYPE = 'MR' AND SUBSTRING(`MR_no`,9,2) = ? AND SUBSTRING(`MR_no`,4,4) = ? AND title_id = ?";
			$data = array(
				$month,
				$year,
				$this->session->userdata('Proj_Main')
			);
			$query = $this->db->query($sql,$data);					
			return $query->result_array();				
	}

	function get_journalEntry($month,$year,$type_name = '',$project_id){

			if(isset($type_name)){
				$type_name = strtoupper($type_name);
			}

			$sql = "SELECT IFNULL(refno,0) + 1 AS `max` FROM(SELECT CAST(SUBSTRING(reference_no,LOCATE('-',reference_no,'10')+1,3) AS DECIMAL(25)) `refno` FROM journal_main1 WHERE MONTH(trans_date) = ? AND YEAR(trans_date) = ? AND `status` <> 'CANCELLED' AND title_id = ? ORDER BY journal_id DESC LIMIT 1) AS z";
			$data = array(
				$month,
				$year,
				$this->session->userdata('Proj_Main'),
			);			
			$query = $this->db->query($sql,$data);			
			return $query->result_array();
	}

	function get_receivedTransfer($month,$year){

			$sql = "SELECT MAX(`receipt_no`) FROM `receiving_main` WHERE SUBSTRING(`receipt_no`,9,2) = ? AND SUBSTRING(`receipt_no`,4,4) = ? ";
			$data = array(
					$month,
					$year,					
				);			
			$this->db->query($sql,$data);
			$query = $this->db->query($sql,$data);					
			return $query->result_array();

	}

	function get_journal_max_id($month,$year){
			$sql = "SELECT MAX(SUBSTRING(`reference_no`,8,3)) FROM `journal_main` WHERE  trans_type = 'PAY PAYABLE' AND title_id = ? AND SUBSTRING(`reference_no`,9,2) = ? AND SUBSTRING(`reference_no`,4,4) = ?";
			$data = array(
					$month,
					$year,
				);			
			$this->db->query($sql,$data);
			$query = $this->db->query($sql,$data);
			return $query->result_array();
	}

	
	function division($project){
			$sql = "SELECT division_id,division_code,division_name FROM division_setup WHERE title_id = '".$project."';";
			$result = $this->db->query($sql);
			$this->db->close();
			return $result->result_array();
	}
	
	public function get_print(){
		$sql = "SELECT
					*
				FROM setup_print
				LIMIT 1";
		$result = $this->db->query($sql);

		if($result->num_rows() > 0){
			return $result->result_array();
		}
	}

}