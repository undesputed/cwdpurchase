<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_dispatch extends CI_Model {

	var $department;
	public function __construct(){
		parent :: __construct();

		$this->department = $this->session->userdata('department');
		switch($this->department){
			case"mod":
				$this->db_hr = $this->load->database('hr2', true);
			break;			
			case"pod":
				$this->db_hr = $this->load->database('hr', true);
			break;			
		}
		

	}	


	public function get_data($arg){
		
		$sql = "CALL display_maintenance_ben('".$arg['date']."');";
		$result = $this->db->query($sql);		
		$this->db->close();		
		return $result->result_array();
		
	}

	public function get_cause_delay(){

		switch($this->department)
		{	

			case"mod":
				$extend = "MINE";
			break;
			case"pod":
				$extend = "BARGE";
			break;
			default:
				$extend = "%%";
			break;
		};

		$sql = "select * from web_dispatch_delay where type = '".$extend."'";
		$result = $this->db->query($sql);
		return $result->result_array();

	}

	public function set_delay(){

		$insert = array(
			'equipment_name'=>$this->input->post('equipment_name'),
			'equipment_id'=>$this->input->post('equipment_id'),
			'delay_id'=>$this->input->post('delay_id'),
			'delay_title'=>$this->input->post('delay_title'),
			'shift'=>$this->input->post('shift'),
			'date'=>$this->input->post('date'),
			);
		$this->db->insert('web_dispatch_standby',$insert);

	}


	public function get_available_equipment($arg){

		$sql = "
				SELECT
				DISTINCT del.equipment_brand,
				del.equipment_id,	
				del.equipment_description,
				del.owner,
		        wdm.equipment_name,
		        wdm.employee_name,
		        wds.delay_title,
		        wds.delay_id,
		        wds.shift
				FROM db_equipmentlist del
				LEFT JOIN 
				(
					SELECT	
					del.equipment_brand,
					pjm.job_status,
					pjm.bd_end_date,
					pjm.bd_start_date,	
					del.equipment_itemno
					FROM 
					( 
					    SELECT
						*
						FROM pm_jo_master
						GROUP BY db_equipment_id 
						ORDER BY id DESC

					)pjm
					INNER JOIN db_equipmentlist del
					ON (del.equipment_id = pjm.db_equipment_id )
					INNER JOIN setup_group_detail sgd
					ON (sgd.group_detail_id = del.equipment_itemno)		
					INNER JOIN inventory_main im
					ON (im.inventory_id = del.inventory_id)
					INNER JOIN project_scope_setup pss
					ON (pss.sub_con_id = im.supplier_id)
					INNER JOIN project_scope_equipment pse
					ON (pse.db_equipment_id = del.equipment_id)				
					WHERE sgd.description = '".$arg['equipment']."'	
					AND pjm.job_status = 'PENDING'
					AND pss.type = 'FVC'
					AND del.active = 'YES'
					GROUP BY equipment_brand) AS pending
									
				ON (pending.equipment_brand = del.equipment_brand)
				INNER JOIN setup_group_detail sgd
				ON (sgd.group_detail_id = del.equipment_itemno)	
				INNER JOIN inventory_main im
				ON (im.inventory_id = del.inventory_id)
				INNER JOIN project_scope_setup pss
				ON (pss.sub_con_id = im.supplier_id)
				INNER JOIN project_scope_equipment pse
				ON (pse.db_equipment_id = del.equipment_id)
				LEFT JOIN 
				(
					SELECT 
					*
					FROM 
					web_dispatch_main 
					WHERE 
					DATE = '".$arg['date']."'
					AND assign_status = 'lock'
					AND shift = '".$arg['shift']."'
				) AS wdm
				ON (wdm.equipment_id = del.equipment_id)
				LEFT JOIN web_dispatch_standby wds
				ON (wds.equipment_id = del.equipment_id AND wds.date = '".$arg['date']."')
				WHERE sgd.description = '".$arg['equipment']."'
				AND pending.equipment_brand IS NULL	
				AND pss.type = 'FVC'
				AND  del.active = 'YES'
				AND pse.status = 'ACTIVE'
				AND wdm.equipment_name IS NULL	
				GROUP BY del.equipment_brand
				ORDER BY SUBSTRING(del.equipment_brand,4,9)*1
				
		";
		$result = $this->db->query($sql);		
		return $result->result_array();

	}


	public function get_person($arg){

		if(isset($arg['department']))
		{
				if($arg['department']=='mine')
				{
					$this->db_hr = $this->load->database('hr2', true);	
					$dept = " AND er.Department_Empl = '1MO'";
					
				}else
				{
					$this->db_hr = $this->load->database('hr', true);
					$dept = " AND er.Department_Empl = '2PO'";
				}
		}else{
			$arg['department'] = $this->session->userdata('department');
			if($arg['department'] == 'mod')
			{
				$dept = " AND er.Department_Empl = '1MO'";
			}else
			{
				$dept = " AND er.Department_Empl = '2PO'";
			}

		}
		
				
		if($arg['shift'] =='ds'){
			$extend = "AND hea.in_am IS NOT NULL ";
		}else{
			$extend = "AND hea.in_pm IS NOT NULL ";	
		}

		if(isset($arg['equipment']) && $arg['equipment'] == 'all')
		{
			$equipment = "ORDER BY equipment ASC  ";
		}else{
			$equipment = "AND equipment = '".$arg['equipment']."'";
		}

		if(isset($arg['range_equipment']))
		{
			$equipment = $arg['range_equipment'];
		}

		$sql = "
				SELECT 
					 person.UserID_Empl1 'id',
					 person.name,  
					 person.position,
					 person.equipment,
					 hea.in_am 'ds_in',
					 hea.out_am 'ds_out',
					 hea.in_pm 'ns_in',
					 hea.out_pm 'ns_out'					 
					FROM hr_emp_attendance hea
					INNER JOIN (
						SELECT
						er.Position_Empl 'position',
						UserID_Empl1 ,
						Name_Empl 'name',
						SysPK_Empl,
						er.equipment
						FROM employees e
						INNER JOIN employees_rate er
						ON (e.Position_Empl = er.id)
						WHERE er.equipment IS NOT NULL	
						".$dept."
						) AS person
					ON (hea.employee_number = person.UserID_Empl1)
				WHERE 
				hea.dtr_date  = '".$arg['date']."'
				".$extend."	
				".$equipment.";
				";
		$result = $this->db_hr->query($sql);		
		return $result->result_array();

	}


	public function get_online($arg){

		$department = $this->session->userdata('department');
		$dept = " ";

		switch($department){
			case "mod":
				$dept = " AND er.Department_Empl = '1MO'";
			break;
			case "pod":
				$dept = " AND er.Department_Empl = '2PO'";
			break;
		
		}


		if($department == 'admin'){
				$output = array();
				$this->db_hr = $this->load->database('hr', true);
					if($arg['shift'] =='ds'){
						$extend = "AND hea.in_am IS NOT NULL ";
					}else{
						$extend = "AND hea.in_pm IS NOT NULL ";	
					}
							
					$sql = "				
							SELECT  
							 REPLACE(TRIM(person.equipment),' ','_') 'equipment',
							 COUNT((CASE
							   WHEN hea.in_am IS NOT NULL THEN hea.in_am
							 END)) 'ds',
							 COUNT((CASE
							   WHEN hea.in_pm IS NOT NULL THEN hea.in_pm
							 END)) 'ns'
							 
							FROM hr_emp_attendance hea
							INNER JOIN (
					
								SELECT
								er.Position_Empl 'position',
								UserID_Empl1 ,
								Name_Empl 'name',
								er.equipment
								FROM employees e
								INNER JOIN employees_rate er
								ON (e.Position_Empl = er.id)
								WHERE er.equipment IS NOT NULL
								AND er.Department_Empl = '2PO'					
								) AS person
							ON (hea.employee_number = person.UserID_Empl1)
							WHERE hea.dtr_date  = '".$arg['date']."'
							".$extend."
							GROUP BY equipment;							
					";

				$result = $this->db_hr->query($sql);		
				$output['port'] = $result->result_array();


				$this->db_hr = $this->load->database('hr2', true);
					if($arg['shift'] =='ds'){
						$extend = "AND hea.in_am IS NOT NULL ";
					}else{
						$extend = "AND hea.in_pm IS NOT NULL ";	
					}
							
					$sql = "				
							SELECT  
							 REPLACE(TRIM(person.equipment),' ','_') 'equipment',
							 COUNT((CASE
							   WHEN hea.in_am IS NOT NULL THEN hea.in_am
							 END)) 'ds',
							 COUNT((CASE
							   WHEN hea.in_pm IS NOT NULL THEN hea.in_pm
							 END)) 'ns'
							 
							FROM hr_emp_attendance hea
							INNER JOIN (
					
								SELECT
								er.Position_Empl 'position',
								UserID_Empl1 ,
								Name_Empl 'name',
								er.equipment
								FROM employees e
								INNER JOIN employees_rate er
								ON (e.Position_Empl = er.id)
								WHERE er.equipment IS NOT NULL
								AND er.Department_Empl = '1MO'					
								) AS person
							ON (hea.employee_number = person.UserID_Empl1)
							WHERE hea.dtr_date  = '".$arg['date']."'
							".$extend."
							GROUP BY equipment;							
					";

				$result = $this->db_hr->query($sql);		
				$output['mine'] = $result->result_array();				
				return $output;


			}else
			{

				if($arg['shift'] =='ds'){
						$extend = "AND hea.in_am IS NOT NULL ";
					}else{
						$extend = "AND hea.in_pm IS NOT NULL ";	
					}
							
					$sql = "				
							SELECT  
							 REPLACE(TRIM(person.equipment),' ','_') 'equipment',
							 COUNT((CASE
							   WHEN hea.in_am IS NOT NULL THEN hea.in_am
							 END)) 'ds',
							 COUNT((CASE
							   WHEN hea.in_pm IS NOT NULL THEN hea.in_pm
							 END)) 'ns'
							 
							FROM hr_emp_attendance hea
							INNER JOIN (
					
								SELECT
								er.Position_Empl 'position',
								UserID_Empl1 ,
								Name_Empl 'name',
								er.equipment
								FROM employees e
								INNER JOIN employees_rate er
								ON (e.Position_Empl = er.id)
								WHERE er.equipment IS NOT NULL
								".$dept."					
								) AS person
							ON (hea.employee_number = person.UserID_Empl1)
							WHERE hea.dtr_date  = '".$arg['date']."'
							".$extend."
							GROUP BY equipment;
							
					";

					$result = $this->db_hr->query($sql);		
					return $result->result_array();

		}


	

	}


	public function check_if_exist($emp_id,$equip_id,$date,$shift){

		$sql = "
		SELECT * FROM web_dispatch_main 
		WHERE (employee_id = '".$emp_id."' 
		OR equipment_id = '".$equip_id."')
        and equipment_id <> '0'
        and assign_status = 'lock'
        and date = '".$date."'
        and shift = '".$shift."'
		";

		$result = $this->db->query($sql);		
		if($result->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}


	public function release_dispatch(){
		$msg = array(
			'msg'=>'',
			'status'=>''
			);

		$update = array(	
			'assign_status'=>'release',			
			);

		$this->db->where(array(
			'employee_id'=>$this->input->post('id'),
			'equipment_id'=>$this->input->post('equipment_id'),
			));
		
		$this->db->update('web_dispatch_main',$update);

		$msg['msg'] = "Successfully Release!";
		$msg['status'] = '3';

		/*$return = $this->check_if_exist($this->input->post('id'),$this->input->post('equipment_id'));*/

		/*	
		if($return){
			$msg['msg'] = "This Unit or Driver is Already Assigned !";
			$msg['status'] = '1';
		}else{
			$this->db->insert('web_dispatch_main',$insert);	
			$msg['msg'] = "Successfully Assigned !";
			$msg['status'] = '0';
		}*/
		
		return $msg;

	}

	public function save_dispatch(){

		$msg = array(
			'msg'=>'',
			'status'=>''
			);

		if($this->input->post('equipment_id')==''){
			$msg['msg'] = "Please select an Equipment to be Assigned!";
			$msg['status'] = '5';
			return $msg;
		}


		$insert = array(
			'equipment_category'=>$this->input->post('equipment_category'),
			'equipment_id'=>$this->input->post('equipment_id'),
			'equipment_name'=>$this->input->post('equipment_name'),
			'employee_id'=>$this->input->post('id'),
			'employee_name'=>$this->input->post('person'),
			'date'=>$this->input->post('date'),
			'shift'=>$this->input->post('shift'),
			'remarks'=>$this->input->post('remarks'),			
			'assign_status'=>'lock',
			'department'=>$this->session->userdata('department'),
		);

		$return = $this->check_if_exist($this->input->post('id'),
						$this->input->post('equipment_id'),
						$this->input->post('date'),
						$this->input->post('shift'));

		if($return){
			$msg['msg'] = "This Unit or Driver is Already Assigned !";
			$msg['status'] = '1';
		}else{
			$this->db->insert('web_dispatch_main',$insert);	
			$msg['msg'] = "Successfully Assigned !";
			$msg['status'] = '0';
		}
		
		return $msg;

	}


	public function get_assigned($arg){
		switch($this->department)
		{	

			case"mod":
				$extend = " and department = 'mod'";
			break;
			case"pod":
				$extend = " and department = 'pod'";
			break;

		};

		if($this->department == 'admin'){
			$sql = "			
				SELECT 
				department,
				equipment_category,
				COUNT(*)'assigned',
				`date`,
				shift
				FROM web_dispatch_main	
				WHERE assign_status = 'lock' 
				AND `date` = '".$arg['date']."'				
				GROUP BY department,equipment_category,shift
				
			";
			$result = $this->db->query($sql);		
			return $result->result_array();

		}else{
			$sql = "			
				SELECT 
				equipment_category,
				COUNT(*)'assigned',
				`date`,
				shift
				FROM web_dispatch_main	
				WHERE assign_status = 'lock' 
				AND `date` = '".$arg['date']."'
				".$extend."
				GROUP BY equipment_category,shift				
			";
			$result = $this->db->query($sql);		
			return $result->result_array();
		}

	}

	public function get_dispatch_person($arg){
		
		$sql = "SELECT * FROM web_dispatch_main where shift = '".$arg['shift']."' and assign_status='lock' and date = '".$arg['date']."'";
		$result = $this->db->query($sql);
		return $result->result_array();

	}





}