<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_user_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}

	function get_cumulative(){

		$sql = "CALL cto_display_all_users1('1','".$this->input->post('location')."');";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	public function get_employee(){

		$sql = "
			SELECT
			  `hr_employee`.`emp_number`,
			  `hr_employee`.`employee_id`,
			  `hr_person_profile`.`pp_fullname`,
			  `hr_employee`.`person_profile_no`,
			  `hr_job_status`.`jobStatusName`,
			  `hr_employee`.`emp_status`,
			  `hr_job_position`.`jobtit_name`,
			  `hr_employee`.`emp_position`,
			  `division_setup`.`division_name`,
			  `hr_employee`.`department_code`,
			  `hr_employee`.`sal_grd_code`,
			  DATE_FORMAT(`hr_employee`.`joined_date`,'%M %d, %Y') 'joined_date',
			  `hr_employee`.`emp_email`,
			  `hr_employee`.`terminated_date`,
			  `hr_employee`.`termination_reason`,
			  `hr_employee`.`record_status`,
			  `hr_employee`.`emp_remarks`,
			  `hr_employee`.`current_assignDept`,
			  hr_salary_grade.sal_grade_name 'sal_grade_name',
			  `hr_employee`.rate_per_day 'Rate Per Day',
			  `hr_employee`.sss 'SSS',
			  `hr_employee`.hdmf 'HDMF',
			  `hr_employee`.philhealth 'Philhealth',
			  `hr_employee`.wholdingTax 'W/holding Tax'
			FROM `hr_employee`
			LEFT JOIN `hr_person_profile` ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
			LEFT JOIN `hr_job_status` ON (`hr_employee`.`emp_status` = `hr_job_status`.`jobStatusCode`)
			LEFT JOIN `hr_job_position` ON (`hr_employee`.`emp_position` = `hr_job_position`.`jobtit_code`)
			LEFT JOIN `division_setup` ON (`hr_employee`.`department_code` = `division_setup`.`division_id`)
			LEFT JOIN `hr_salary_grade` ON (`hr_employee`.`sal_grd_code` = `hr_salary_grade`.`sal_grade_code`)
		WHERE record_status = 'ACTIVE'	
			ORDER BY `hr_person_profile`.`pp_fullname`;			
		";
		
		$result = $this->db->query($sql);
		$this->db->close();		
		return $result->result_array();

	}

	public function get_department($title_id){

		$sql = "SELECT * FROM division_setup WHERE title_id = '".$title_id."'";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();

	}

	public function save_userSetup(){

		if($this->input->post('id')==""){

			$sql = "SELECT IFNULL(MAX(SysPK_User),0) + 1 'id' FROM users;";
			$result = $this->db->query($sql);
			$row = $result->row_array();
			if($row['id']==NULL){
				$row['id'] = 1;
			}
			$insert = array(
				'SysPK_User'=>$row['id'],
				'UserName_User'=>$this->input->post('username'),
				'Password_User'=>$this->input->post('password'),
				'UserFull_name'=>$this->input->post('employee_name'),
				'Type_User'=>$this->input->post('usertype'),
				'Dept_Code'=>$this->input->post('department'),
				'Employee_id'=>$this->input->post('employee_id'),
				'person_code'=>$this->input->post('person_code'),
				'Proj_Code'=>$this->input->post('profit_center'),
				'Proj_Main'=>$this->input->post('project'),			
			);
			$this->db->insert('users',$insert);
	
		}else{

			$insert = array(				
				'UserName_User'=>$this->input->post('username'),
				'Password_User'=>$this->input->post('password'),
				'UserFull_name'=>$this->input->post('employee_name'),
				'Type_User'=>$this->input->post('usertype'),
				'Dept_Code'=>$this->input->post('department'),
				'Employee_id'=>$this->input->post('employee_id'),
				'person_code'=>$this->input->post('person_code'),
				'Proj_Code'=>$this->input->post('profit_center'),
				'Proj_Main'=>$this->input->post('project'),
			);

			$this->db->where('SysPK_User',$this->input->post('id'));			
			$this->db->where('Proj_Main',$this->input->post('project'));
			$this->db->update('users',$insert);

		}

		


	}

		

}