<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_employee_setup extends CI_Model {

	public function __construct(){
		parent :: __construct();		
	}



	public function get_cumulative(){

		/*$sql = "CALL display_list_employee_profile_view('1','".$this->session->userdata('Proj_Main')."');";*/

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
				  `hr_employee`.wholdingTax 'W/holding Tax',
				   digital_signature.path  
				FROM `hr_employee`
				LEFT JOIN `hr_person_profile` ON (`hr_employee`.`person_profile_no` = `hr_person_profile`.`pp_person_code`)
				LEFT JOIN `hr_job_status` ON (`hr_employee`.`emp_status` = `hr_job_status`.`jobStatusCode`)
				LEFT JOIN `hr_job_position` ON (`hr_employee`.`emp_position` = `hr_job_position`.`jobtit_code`)
				LEFT JOIN `division_setup` ON (`hr_employee`.`department_code` = `division_setup`.`division_id`)
				LEFT JOIN `hr_salary_grade` ON (`hr_employee`.`sal_grd_code` = `hr_salary_grade`.`sal_grade_code`)
				LEFT JOIN (
						SELECT * FROM digital_signature where status = 'active'
					) digital_signature 
				ON (hr_employee.emp_number = digital_signature.emp_id)
				WHERE hr_employee.record_status = 'ACTIVE'
				ORDER BY `hr_person_profile`.`pp_fullname`;
		";		
		$result = $this->db->query($sql);		
		$this->db->close();
		return $result;		
	}



	public function get_emp_no(){

		$sql = "SELECT IF(ISNULL(MAX(emp_number)),1,MAX(emp_number) +1) as max FROM hr_employee;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->row_array();

	}
		

		
	public function get_person(){
		$sql = "call display_list_person_profile()";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();		
	}


	public function get_jobStatus(){
		$sql = "SELECT jobStatusCode,jobStatusName FROM hr_job_status";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();		
	}

	public function get_jobPosition(){
		$sql = "SELECT jobtit_code,jobtit_name FROM hr_job_position";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();		
	}

	public function get_salaryGrade(){
		$sql = "SELECT sal_grade_code,sal_grade_name FROM hr_salary_grade";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();		
	}
	
	public function get_divisionSetup(){
		$sql = "SELECT division_id,division_name FROM division_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();	
	}

	public function get_divisionCurrent(){
		$sql = "SELECT division_code,division_name FROM division_setup";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result->result_array();	
	}

	public function save_employee_setup(){
		
		$insert = array(
			 'emp_number'=>$this->input->post('emp_number'),
             'person_profile_no'=>$this->input->post('person_profile_no'),
             'employee_id'=>$this->input->post('employee_id'),
             'emp_status'=>$this->input->post('emp_status'),
             'emp_position'=>$this->input->post('emp_position'),
             'department_code'=>$this->input->post('department_code'),
             'sal_grd_code'=>$this->input->post('sal_grd_code'),
             'joined_date'=>$this->input->post('joined_date'),
             'emp_email'=>$this->input->post('emp_email'),
             'terminated_date'=>$this->input->post('terminated_date'),
             'termination_reason'=>$this->input->post('termination_reason'),
             'record_status'=>$this->input->post('record_status'),
             'emp_remarks'=>$this->input->post('emp_remarks'),
             'current_assignDept'=>$this->input->post('current_assignDept'),
             'rate_per_day' =>$this->input->post('rate_per_day'),
	     	 'sss' =>$this->input->post('sss'),
             'hdmf' =>$this->input->post('hdmf'),
             'philhealth' =>$this->input->post('philhealth'),
             'wholdingTax'=>$this->input->post('wholdingTax'),
             'title_id'=>$this->input->post('title_id'),
			);
			
		$this->db->insert('hr_employee',$insert);

		return true;
	}

	/**************/


	public function get_status(){

		$sql = "CALL display_hr_employee_status_setup();";
		$result = $this->db->query($sql);
		return $result;

	}

	public function save_status(){


		if($this->input->post('id')==""){

			$insert = array(
				'jobStatusCode'=>$this->input->post('status_code'),
				'jobStatusName'=>$this->input->post('status_name'),
			);
			$this->db->insert('hr_job_status',$insert);

		}else{

			$insert = array(
				'jobStatusCode'=>$this->input->post('status_code'),
				'jobStatusName'=>$this->input->post('status_name'),
			);
			$this->db->where('jobStatusNumber',$this->input->post('id'));
			$this->db->update('hr_job_status',$insert);


		}
				
		return true;

	}



	/*******GET POSITION*******/

	public function get_position(){
		
		$sql = "SELECT
				jobtit_number,
				jobtit_code 'JOBTITLE CODE',
				jobtit_name 'JOB TITLE',
				jobtit_desc 'JOB DESCRIPTION',
				sal_grd_code 'SALARY GRADE CODE',
				(SELECT sal_grade_name FROM hr_salary_grade WHERE sal_grade_code = sal_grd_code) 'SALARY GRADE',
				IF(is_active=0,'Inactive','Active') 'STATUS'
				FROM
				hr_job_position;";

		$result = $this->db->query($sql);
		return $result;

	}

	public function position_salary(){

		$sql = "SELECT sal_grade_code, sal_grade_name FROM hr_salary_grade";
		$result = $this->db->query($sql);
		return $result->result_array();


	}

	public function save_position(){


		if($this->input->post('id')==""){
			$sql = "SELECT IFNULL(MAX(jobtit_number),0) + 1 as 'max' FROM hr_job_position";
			$result = $this->db->query($sql);
			$row = $result->row_array();

			$insert = array(
				'jobtit_code'=>'POS'.$row['max'],
				'jobtit_name'=>$this->input->post('name'),
				'jobtit_desc'=>$this->input->post('description'),
				'sal_grd_code'=>$this->input->post('salary_grade'),
				'is_active'=>$this->input->post('status'),
			);		
			$this->db->insert('hr_job_position',$insert);

		}else{
			$insert = array(				
				'jobtit_name'=>$this->input->post('name'),
				'jobtit_desc'=>$this->input->post('description'),
				'sal_grd_code'=>$this->input->post('salary_grade'),
				'is_active'=>$this->input->post('status'),
			);
			$this->db->where('jobtit_number',$this->input->post('id'));
			$this->db->update('hr_job_position',$insert);
		}
		

	}


	/****Salary Grade*****/

	public function get_salary(){

		$sql = "SELECT * FROM hr_salary_grade;";
		$result = $this->db->query($sql);
		$this->db->close();
		return $result;

	}

	public function save_salary(){
				
		if($this->input->post('id')==""){

			$insert = array(
				'sal_grade_code'=>$this->input->post('salaryGradeCode'),
				'sal_grade_name'=>$this->input->post('salaryGradeName'),
				'sal_grade_minsalary'=>$this->input->post('minimumSalary'),
				'sal_grade_stepsalary'=>$this->input->post('stepSalary'),
				'sal_grade_maxsalary'=>$this->input->post('maximumSalary'),
			);
			$this->db->insert('hr_salary_grade',$insert);		
			
		}else{

			$insert = array(
						'sal_grade_code'=>$this->input->post('salaryGradeCode'),
						'sal_grade_name'=>$this->input->post('salaryGradeName'),
						'sal_grade_minsalary'=>$this->input->post('minimumSalary'),
						'sal_grade_stepsalary'=>$this->input->post('stepSalary'),
						'sal_grade_maxsalary'=>$this->input->post('maximumSalary'),
					);

			$this->db->where('sal_grade_number',$this->input->post('id'));
			$this->db->update('hr_salary_grade',$insert);	
		}		
		return true;
	}


	public function remove_emp($emp_number){		
		$sql = "UPDATE hr_employee SET record_status = 'CANCELLED' WHERE emp_number = '".$emp_number."'";
		$this->db->query($sql);
		return true;		
	}

	/*******/

	public function save_digital_signature($arg){
		$sql = "SELECT * FROM digital_signature where emp_id = '".$arg['emp_id']."' AND status='active'";
		$result = $this->db->query($sql);

		if($result->num_rows() == 0 ){
			$this->db->insert('digital_signature',$arg);
		}else{			
			$this->db->where('emp_id',$arg['emp_id']);			
			$this->db->update('digital_signature',$arg);
		}		
		return true;		
	}
	
	public function get_digital_signature($emp_id){
		$sql = "SELECT * from digital_signature where emp_id = '".$emp_id."' AND status = 'active'";
		$result = $this->db->query($sql);
		return $result->row_array();
	}
	
	public function remove_digital($emp){
		$update = array(
			'status'=>'inactive'
			);
		$this->db->where('emp_id',$emp);
		$this->db->update('digital_signature',$update);
		return true;
	}
	
}