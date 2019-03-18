<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Md_hr extends CI_model {
		
	private $db_hr;
	function __construct()
	{
		$this->db_hr = $this->load->database('hr', true);	
	}		
	

	public function get_department(){
		$sql = "SELECT GroupName_Dept,DeptCode_Dept,Name_Dept FROM department WHERE Status_Dept = 'ACTIVE'";
		$result = $this->db_hr->query($sql);
		$this->db_hr->close();

		return $result->result_array();

	}


	public function get_employee($dept = ''){

		$sql = "
			SELECT
			a.UserID_Empl1,
			a.Name_Empl,
			a.FirstName_Empl,
			a.MiddleName_Empl,
			a.LastName_Empl,
			a.Department_Empl,
			a.Status_Empl,
			a.Position_Empl,
			b.Position_Empl 'position'
			FROM employees a
			LEFT JOIN employees_rate b
			ON (a.Position_Empl = b.id)
			WHERE a.Department_Empl LIKE '".$dept."' and Status_Empl = 'ACTIVE'
		";

		$result = $this->db_hr->query($sql);
		$this->db_hr->close();
		return $result->result_array();
	}



	public function get_dtr($from,$to,$emp_id){

		$sql = "CALL display_hr_empl_attendance_dtr_compute('".$from."','".$to."','".$emp_id."');";				
		$result = $this->db_hr->query($sql);	
		$this->db_hr->close();
		return $result->result_array();

	}



	public function generate_date(){
		
		$sql = "SELECT submission_date FROM tbl_report GROUP BY SUBSTRING(submission_date,1,7);";
		$result = $this->db->query($sql);
		return $result->result_array();
		
	}


	public function get_row($id){

		$sql = "SELECT * FROM tbl_report WHERE id ='$id'";
		$result = $this->db->query($sql);
		return $result->row_array();

	}

	public function view($date = ""){

		$date = date('Y-m',strtotime($date));		
		$sql = "SELECT * FROM tbl_report WHERE STATUS = 'ACTIVE' AND  submission_date  LIKE '".$date."%' ORDER BY submission_date desc";
		$result = $this->db->query($sql);
		return $result;
		
	}


	/***PAYROLL***/


	public function get_payroll(){


		$checklist = $this->input->post('checkbox');
		$checklist = (!empty($checklist))? $checklist : array();				
		$dept_code = $this->input->post('dept_code');
		$date_from = $this->input->post('date_from');
		$date_to   = $this->input->post('date_to');


		$dt_display = array();

		$sql = "CALL display_employee_payslip_new('".$date_from."','".$date_to."','".$dept_code."','FVC001');";
		$result1 = $this->db_hr->query($sql);		
		$this->db_hr->close();


	/*	$sql = "CALL display_employee_byDept_group('".$dept_code."','FVC001')";
		$result_emp = $this->db_hr->query($sql);		
		$this->db_hr->close();
		$bool = false;*/

		$cnt = 0;

		foreach($result1->result_array() as $row)
		{
													
						 $ini_rows = array();	
						 $ini_rows['SysPK_Empl'] = $row['SysPK_Empl'];
						 $ini_rows['EMPLOYEE NAME'] = $row['NAME'];
						 $ini_rows['ID. NO.'] = $row['SysPK_Empl'];
						 $ini_rows['DESIGNATION'] = $row['POSITION'];
						 $ini_rows['RATE PER DAY'] = $row['RATE PER DAY'];
						 $ini_rows['NO. OF DAYS'] = $row['DAYS WORK'];
						 $ini_rows['NIGHT DIFFERENTIAL'] = $row['NIGHT DIFFERENTIAL AMOUNT'];
						 $ini_rows['REGULAR AMOUNT'] = $row['REGULAR DAYS AMOUNT'];
						 $ini_rows['RESTDAY AMOUNT'] = $row['RESTDAY AMOUNT'];
						 $ini_rows['SPECIAL DAYS AMOUNT'] = $row['SPECIAL DAYS AMOUNT'];
						 $ini_rows['LEGAL DAYS AMOUNT'] = $row['LEGAL DAYS AMOUNT'];
						 $ini_rows['REGULAR OT AMOUNT'] = $row['REGULAR DAYS OT AMOUNT'];
						 $ini_rows['RESTDAY OT AMOUNT'] = $row['RESTDAYS OT AMOUNT'];
						 $ini_rows['SPECIAL OT AMOUNT'] = $row['SPECIAL DAYS OT AMOUNT'];
						 $ini_rows['LEGAL OT AMOUNT'] = $row['LEGAL DAYS OT AMOUNT'];
						 $ini_rows['GROSS PAY'] = $row['GROSS AMOUNT'];
						 $ini_rows['SSS'] = $row['SSS AMOUNT'];
						 $ini_rows['PHILHEALTH'] = $row['PHILHEALTH AMOUNT'];
						 $ini_rows['WITH\'G TAX'] = $row['WITHHOLDING TAX AMOUNT'];
						 $ini_rows['PAG-IBIG CONT.'] = $row['PAGIBIG AMOUNT'];
						 $ini_rows['ADVANCS/TOOLS'] = $row['CASH ADVANCE AMOUNT'];
						 $ini_rows['UNIFORM'] = $row['UNIFORM AMOUNT'];
						 $ini_rows['OBMEALS'] = $row['OBMEALS AMOUNT'];
						 $ini_rows['NET PAY'] = $row['NETPAY AMOUNT'];
						 $ini_rows['SysPK_PaySlip'] = $row['SysPK_PaySlip'];

						 $dt_display[] = $ini_rows;

					$bool = true;
				

										
		}



/*
		foreach($result_emp->result_array() as $row_emp)
		{
			$cnt++;
			foreach($result1->result_array() as $row)
			{
				
				if($row['SysPK_Empl'] == $row_emp['SysPK_Empl'])
				{		
						
						 $ini_rows = array();	
						 $ini_rows['SysPK_Empl'] = $row['SysPK_Empl'];
						 $ini_rows['EMPLOYEE NAME'] = $row['NAME'];
						 $ini_rows['ID. NO.'] = $row['SysPK_Empl'];
						 $ini_rows['DESIGNATION'] = $row['POSITION'];
						 $ini_rows['RATE PER DAY'] = $row['RATE PER DAY'];
						 $ini_rows['NO. OF DAYS'] = $row['DAYS WORK'];
						 $ini_rows['NIGHT DIFFERENTIAL'] = $row['NIGHT DIFFERENTIAL AMOUNT'];
						 $ini_rows['REGULAR AMOUNT'] = $row['REGULAR DAYS AMOUNT'];
						 $ini_rows['RESTDAY AMOUNT'] = $row['RESTDAY AMOUNT'];
						 $ini_rows['SPECIAL DAYS AMOUNT'] = $row['SPECIAL DAYS AMOUNT'];
						 $ini_rows['LEGAL DAYS AMOUNT'] = $row['LEGAL DAYS AMOUNT'];
						 $ini_rows['REGULAR OT AMOUNT'] = $row['REGULAR DAYS OT AMOUNT'];
						 $ini_rows['RESTDAY OT AMOUNT'] = $row['RESTDAYS OT AMOUNT'];
						 $ini_rows['SPECIAL OT AMOUNT'] = $row['SPECIAL DAYS OT AMOUNT'];
						 $ini_rows['LEGAL OT AMOUNT'] = $row['LEGAL DAYS OT AMOUNT'];
						 $ini_rows['GROSS PAY'] = $row['GROSS AMOUNT'];
						 $ini_rows['SSS'] = $row['SSS AMOUNT'];
						 $ini_rows['PHILHEALTH'] = $row['PHILHEALTH AMOUNT'];
						 $ini_rows['WITH\'G TAX'] = $row['WITHHOLDING TAX AMOUNT'];
						 $ini_rows['PAG-IBIG CONT.'] = $row['PAGIBIG AMOUNT'];
						 $ini_rows['ADVANCS/TOOLS'] = $row['CASH ADVANCE AMOUNT'];
						 $ini_rows['UNIFORM'] = $row['UNIFORM AMOUNT'];
						 $ini_rows['OBMEALS'] = $row['OBMEALS AMOUNT'];
						 $ini_rows['NET PAY'] = $row['NETPAY AMOUNT'];
						 $ini_rows['SysPK_PaySlip'] = $row['SysPK_PaySlip'];

						 $dt_display[] = $ini_rows;

					$bool = true;
					break;


				}else{
					$bool = false;
				}

			}

			if($bool == false)
			{

						$exist_array  = array();
						$exist_array['SysPK_emp'] = $row_emp['SysPK_Empl'];						
						$exist_array['UserID'] = $row_emp['UserID_Empl'];
						$exist_array['RestDay'] = $row_emp['Restday'];
						$exist_array['EmployeeName'] = $row_emp['Employee Name'];

						# DEFAULT ZERO 
						$exist_array['CashAdvance'] = 0;
						$exist_array['Wtax'] = 0;
						$exist_array['SSS'] = 0;
						#END
						
						# Rate per Day 
						$exist_array['Rate_per_day'] = ($row_emp['Rate per day'] == 0)? $row_emp['Monthly Rate'] / 30 : $row_emp['Rate per day'];


						if($exist_array['Rate_per_day'] == 268){
							$rate_regularday = $exist_array['Rate_per_day'] - 15;
						}else{
							$rate_regularday = $exist_array['Rate_per_day'];
						}

						$exist_array['regular_wage'] = ($exist_array['Rate_per_day'] * 26);

						$exist_array['salaryType'] = ($row_emp['Rate per day'] == 0)? "Monthly" : "Daily";

						$sql = "CALL display_hr_empl_attendance_dtr_compute_all('".$date_from."','".$date_to."','".$exist_array['UserID']."')";
						$result = $this->db_hr->query($sql);
						$emp_attentance = $result->row_array();
						$this->db_hr->close();

						$sql = "CALL display_hr_empl_attendance_dtr_compute_holidays('".$date_from."','".$date_to."','".$exist_array['UserID']."')";						
						$result = $this->db_hr->query($sql);
						$emp_attendance_holiday = $result->row_array();
						$this->db_hr->close();


						$legalRGdaycnt =@ ($emp_attendance_holiday['LEGAL RG HOURS'] / 8);
						$legalRGHrscnt =@  $emp_attendance_holiday['LEGAL RG OT HOURS'];

						
						$legalRDdaycnt =@ ($emp_attendance_holiday['LEGAL RD HOURS'] / 8);
						$legalRDHrscnt =@ $emp_attendance_holiday['LEGAL RD OT HOURS'];

						$specialRGdaycnt =@ (($emp_attendance_holiday['SPECIAL RG HOURS']) / 8 );
						$specialRGHrscnt =@ $emp_attendance_holiday['SPECIAL RG OT HOURS'];
	

						$specialRDdaycnt =@ ($emp_attendance_holiday['SPECIAL RD HOURS'] / 8);
						$specialRDHrscnt =@ $emp_attendance_holiday['SPECIAL RD OT HOURS'];
		                    


						$TotalHolidayCnt = $legalRGdaycnt + $legalRDdaycnt + $specialRGdaycnt + $specialRDdaycnt;
						$TotalHolidayHrs = $legalRGHrscnt + $specialRGHrscnt;
						                    	
                    	$RegularDayCnt = $emp_attentance['RG HOURS'] / 8;

						if($TotalHolidayCnt > 0){
							$exist_array['Regular_DaysWork'] = $RegularDayCnt - $TotalHolidayCnt;
						}else{
							$exist_array['Regular_DaysWork'] = $RegularDayCnt;
						}

						$exist_array['Regular_DaysAmount'] = ($exist_array['Regular_DaysWork'] * $exist_array['Rate_per_day']);


						$exist_array['Rest_DaysWork'] = ($emp_attentance['RD HOURS'] / 8) - $legalRDdaycnt;
						$exist_array['Rest_DaysAmount'] = ($exist_array['Rest_DaysWork'] * $rate_regularday * 0.3) + ($exist_array['Rest_DaysWork'] * $rate_regularday);


						if($emp_attentance['RG OT HOURS'] > $TotalHolidayHrs){
							$exist_array['Regular_OTHrs'] = $emp_attentance['RG OT HOURS'] - $TotalHolidayHrs;
						}else{
							$exist_array['Regular_OTHrs'] = $emp_attentance['RG OT HOURS'];
						}

						$DummyOTPay = ($exist_array['Regular_OTHrs'] * $rate_regularday) / 8 ;
						$exist_array['Regular_OTAmount'] = $DummyOTPay + ($DummyOTPay * 0.25);


						$exist_array['Restday_OTHrs'] = $emp_attentance['RD OT HOURS'] - $legalRDHrscnt;
						$DummyRDOTPay = ($exist_array['Restday_OTHrs'] * $rate_regularday) / 8;
						$exist_array['Restday_OTAmount'] = $DummyRDOTPay + ($DummyRDOTPay * 0.6);

						$exist_array['Night_DifferentialHrs'] = $emp_attentance['ND HOURS'];						
						$exist_array['Night_DifferentialAmount'] = ((($exist_array['Night_DifferentialHrs'] * $rate_regularday) / 8) * 0.1 );	

						$LegalRGDaysWork = $legalRGdaycnt;
						
						if($LegalRGDaysWork > 0.0)
						{
							$LegalRGDaysAmount = ($exist_array['Rate_per_day'] * 2) * $LegalRGDaysWork;
						}else if($exist_array['Regular_DaysWork'] > 0.0)
						{
							$LegalRGDaysAmount = $exist_array['Rate_per_day'];
						}else
						{
							$LegalRGDaysAmount = 0.0;
						}

						$LegalRDDaysWork = $legalRDdaycnt;

						if($LegalRDDaysWork > 0.0)
						{	
							$LegalRDDaysAmount = ($exist_array['Rate_per_day'] * 2.6) * $LegalRDDaysWork;
						}else if($exist_array['Regular_DaysWork'] > 0.0)
						{	
							$LegalRDDaysAmount = $exist_array['Rate_per_day'];
						}else{
							$LegalRDDaysAmount = 0.0;
						}
						

						if($legalRGdaycnt > 0.0){
							$exist_array['Legal_DaysWork'] = $LegalRGDaysWork;
							$exist_array['Legal_DaysAmount'] = $LegalRGDaysAmount;
						}else{
							$exist_array['Legal_DaysWork'] = $LegalRDDaysWork;
							$exist_array['Legal_DaysAmount'] = $LegalRDDaysAmount;
						}
					


						$LegalRGOTHrsCount = $emp_attendance_holiday['SPECIAL RD HOURS'];
                        $LegalOTHrsRate = ($rate_regularday / 8) * 2;
                        $LegalOTRatePay = $LegalOTHrsRate * 0.3 + $LegalOTHrsRate;
                        $LegalRGOTHrsAmount = $LegalRGOTHrsCount * $LegalOTRatePay;


                        $LegalRDOTHrsCount = $emp_attendance_holiday['LEGAL RD OT HOURS'];
                        $LegalRDOTHrsRate = ($rate_regularday / 8) * 2;
                        $LegalRDOTRatePay = $LegalRDOTHrsRate * 0.6 + $LegalRDOTHrsRate;
                        $LegalRDOTHrsAmount = $LegalRDOTHrsCount * $LegalRDOTRatePay;


                        $exist_array['Legal_OTHrs'] = $LegalRGOTHrsCount + $LegalRDOTHrsCount;
                    	$exist_array['Legal_OTAmount'] = $LegalRGOTHrsAmount + $LegalRDOTHrsAmount;


                    	$SpecialRGDaysWork = $specialRGdaycnt;
                    	if($SpecialRGDaysWork > 0.0)
                    	{	
                    		$SpecialRGDaysAmount = ($exist_array['Rate_per_day'] * 1.3) * $SpecialRGDaysWork;
                    	}else
                    	{
                    		$SpecialRGDaysAmount = $SpecialRGDaysWork * $exist_array['Rate_per_day'];
                    	}


                    	$SpecialRDDaysWork = $specialRDdaycnt;

                    	if($exist_array['Legal_DaysWork'] > 0.0){
                    		$SpecialRDDaysAmount = ($exist_array['Rate_per_day'] * 1.5) * $SpecialRDDaysWork;
                    	}else
                    	{
                    		$SpecialRDDaysAmount = $SpecialRDDaysWork * $exist_array['Rate_per_day'];
                    	}

                    	$exist_array['Special_DaysWork']   = $SpecialRGDaysWork + $SpecialRDDaysWork;                   
                    	$exist_array['Special_DaysAmount'] = $SpecialRGDaysAmount + $SpecialRDDaysAmount;



                    	$SpecialRGOTHrsCount = $emp_attendance_holiday['SPECIAL RG OT HOURS'];
                    	$SpecialOTHrsRate = ($rate_regularday / 8) * 1.3;
                    	$SpecialOTRatePay = $SpecialOTHrsRate * 0.3 + $SpecialOTHrsRate;
                    	$SpecialRGOTHrsAmount = $SpecialRGOTHrsCount * $SpecialOTRatePay;


                    	$SpecialRDOTHrsCount  = 0.0;
		                $SpecialRDOTHrsAmount = 0.0;
                    	$SpecialRDOTHrsCount  = $emp_attendance_holiday['SPECIAL RD OT HOURS'];
                 		$SpecialRDOTHrsRate   = ($rate_regularday / 8) * 1.5;
                 		$SpecialRDOTRatePay   = $SpecialRDOTHrsRate * 0.3 + $SpecialRDOTHrsRate;
                 		$SpecialRDOTHrsAmount = $SpecialRDOTHrsCount * $SpecialRDOTRatePay;
		                

                   		$exist_array['Special_OTHrs']    = $SpecialRGOTHrsCount + $SpecialRDOTHrsCount;
                   		$exist_array['Special_OTAmount'] = $SpecialRGOTHrsAmount + $SpecialRDOTHrsAmount;
                   

                   		$total_dayswork = $exist_array['Regular_DaysWork'] + $exist_array['Rest_DaysWork'] + $exist_array['Legal_DaysWork'] + $exist_array['Special_DaysWork'];

                   		$sql = "SELECT employees_rate.with_rice 'WITH RICE' FROM `employees` INNER JOIN `department` ON (`employees`.`Department_Empl` = `department`.`DeptCode_Dept`)INNER JOIN `employees_rate`  ON (`employees`.`Position_Empl` = `employees_rate`.`id`)WHERE `employees`.UserID_Empl1 = '".$exist_array['UserID']."'";
                   		$result = $this->db_hr->query($sql);
                   		$withrice = $result->row_array();

                   		if($withrice['WITH RICE'] == 'YES')
                   		{
                   			if($exist_array['Regular_DaysWork'] < 13)
                   			{
                   				$RiceAllowance = (900 / 13 * $exist_array['Regular_DaysWork']);
                   			}else
                   			{
                   				$RiceAllowance = 900.00;
                   			}
                   		}else
                   		{
                   			$RiceAllowance = 0.0;
                   		}

                   		$sql = "CALL display_OtherDeduction_Adjustment_Incentives('".$date_from."','".$date_to."','".$exist_array['UserID']."')";
                   		$result = $this->db_hr->query($sql);
                   		$this->db_hr->close();
                   		$DT_OtherDeductionsIncentives = $result->row_array();


                   		$exist_array['Adjustment'] = (isset($DT_OtherDeductionsIncentives['Adjustment'])?  $DT_OtherDeductionsIncentives['Adjustment'] : 0 );
                   		$exist_array['Incentives'] = (isset($DT_OtherDeductionsIncentives['Incentives'])?  $DT_OtherDeductionsIncentives['Incentives'] : 0 );


                   		$exist_array['Uniform'] = 0;
                   		$exist_array['OBmeals'] = 0;
                   		$exist_array['OtherDeductions'] = 0;


                   		if(isset($checklist) && in_array('uniform',$checklist))
                   		{
                   				$exist_array['Uniform'] = (isset($DT_OtherDeductionsIncentives['Uniform'])?  $DT_OtherDeductionsIncentives['Uniform'] : 0 );                    			
                   		}

                   		if(isset($checklist) && in_array('meals',$checklist))
                   		{
                   			$exist_array['OBmeals'] = (isset($DT_OtherDeductionsIncentives['OBmeals'])?  $DT_OtherDeductionsIncentives['OBmeals'] : 0 ); 
                   		}
                   		

                   		$exist_array['OtherDeductions'] = (isset($DT_OtherDeductionsIncentives['Others'])?  $DT_OtherDeductionsIncentives['Others'] : 0 );


                   		$position = $row_emp['Position'];

                   		$sql = "SELECT employees.BirthDate_Empl FROM employees WHERE employees.UserID_Empl1 = '".$exist_array['UserID']."'";
                   		$result = $this->db_hr->query($sql);                   		
                   		$raw_data = $result->row_array();
                   		$EMP_DOB = $raw_data['BirthDate_Empl'];

                   		$sql = "SELECT AGE1('".$EMP_DOB."') 'age'";
                   		$result   = $this->db_hr->query($sql);
                   		$raw_data = $result->row_array();
                   		$EMP_AGE  = $raw_data['age'];

                   		$sss = 0;

                   		if($position == 'BARGE ORE KEEPER' OR $EMP_AGE > 59)
                   		{	
                   			$sss = 0;
                   		}else
                   		{	
							if(isset($checklist) && in_array('sss',$checklist)){
								$sss = $exist_array['SSS'];
							}else
							{
								$sss = 0;
							}
                   		}

                   		$philhealth = 0;

                   		if($position == 'BARGE ORE KEEPER' OR $EMP_AGE > 59)
                   		{	
                   			$philhealth = 0;
                   		}else
                   		{
							if(isset($checklist) && in_array('philhealth',$checklist)){
								$philhealth = 100;
							}else
							{
								$philhealth = 0;
							}
                   		}


                   		$pagibig = 0;

                   		if($position == 'BARGE ORE KEEPER' OR $EMP_AGE > 59)
                   		{	
                   			$pagibig = 0;
                   		}else
                   		{
							if(isset($checklist) && in_array('hdmf',$checklist)){
								$pagibig = 100;
							}else
							{
								$pagibig = 0;
							}
                   		}


                   		$withholdingtax = 0;

                   		if($position == 'BARGE ORE KEEPER' )
                   		{	
                   			$withholdingtax = 0;
                   		}else
                   		{
							if(isset($checklist) && in_array('w_tax',$checklist)){
								$withholdingtax = 0;
							}else
							{
								$withholdingtax = 0;
							}
                   		}


                   		$exist_array['Gross'] = $exist_array['Regular_DaysAmount'] + $exist_array['Rest_DaysAmount'] + $exist_array['Legal_DaysAmount'] + $exist_array['Special_DaysAmount'] + $exist_array['Regular_OTAmount'] + $exist_array['Restday_OTAmount'] + $exist_array['Legal_OTAmount'] + $exist_array['Special_OTAmount'] + $exist_array['Night_DifferentialAmount'] + $exist_array['Adjustment'] + $exist_array['Incentives'] + $RiceAllowance;
                   		$exist_array['TotalDeductions'] = $sss + $pagibig + $philhealth + $withholdingtax + $exist_array['CashAdvance'] + $exist_array['OBmeals'] + $exist_array['Uniform'] + $exist_array['OtherDeductions'];


                   		$netpay = 0;
	                    if ($exist_array['Gross'] > 999.9)
	                    {
	                    	$netpay = $exist_array['Gross'] - $exist_array['TotalDeductions'];	                    	
	                    }else
	                    {	                    	
	                    	
	                    	$netpay = $exist_array['Gross'];
	                        $sss = 0.0;
	                        $pagibig = 0.0;
	                        $philhealth = 0.0;
	                        $exist_array['OBmeals'] = 0.0;
	                        $exist_array['OtherDeductions'] = 0.0;
	                        $exist_array['Uniform'] = 0.0;
	                        $exist_array['TotalDeductions'] = 0.0;

	                    }
	                    
	      
	                    $dt_display[] = array(
	                    	'SysPK_Empl'=>$exist_array['SysPK_emp'],
	                    	'EMPLOYEE NAME'=>$exist_array['EmployeeName'],
	                    	'ID. NO.'=>$exist_array['UserID'],
	                    	'DESIGNATION'=>$position,
	                    	'RATE PER DAY'=>$exist_array['Rate_per_day'],
	                    	'NO. OF DAYS'=>$total_dayswork,
	                    	'NIGHT DIFFERENTIAL'=>$exist_array['Night_DifferentialAmount'],
	                    	'REGULAR AMOUNT'=>$exist_array['Regular_DaysAmount'],
	                    	'RESTDAY AMOUNT'=>$exist_array['Rest_DaysAmount'],
	                    	'SPECIAL DAYS AMOUNT'=>$exist_array['Special_DaysAmount'],
	                    	'LEGAL DAYS AMOUNT'=>$exist_array['Legal_DaysAmount'],
	                    	'REGULAR OT AMOUNT'=>$exist_array['Regular_OTAmount'],
	                    	'RESTDAY OT AMOUNT'=>$exist_array['Restday_OTAmount'],
	                    	'SPECIAL OT AMOUNT'=>$exist_array['Special_OTAmount'],
	                    	'LEGAL OT AMOUNT'=>$exist_array['Legal_OTAmount'],
	                    	'GROSS PAY'=>$exist_array['Gross'],
	                    	'SSS'=>$sss,
	                    	'PHILHEALTH'=>$philhealth,
	                    	'WITH\'G TAX'=>$exist_array['Wtax'],
	                    	'PAG-IBIG CONT.'=>$pagibig,
	                    	'ADVANCS/TOOLS'=>$exist_array['CashAdvance'],
	                    	'UNIFORM'=>$exist_array['Uniform'],
	                    	'OBMEALS'=>$exist_array['OBmeals'],
	                    	'NET PAY'=>$netpay,	                    	
	                    );
	                 

				

			}

			if($cnt == 5){
				break;
			}

		} */
		

	$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped ">' );
		$this->table->set_template($tmpl);
		$payroll_amount = 0;
		$array_payroll = array();
		$show = array(
					'ID. NO.',
					'EMPLOYEE NAME',
					'DESIGNATION',
					'RATE PER DAY',
					'NO. OF DAYS',
					'NIGHT DIFFERENTIAL',
					'REGULAR AMOUNT',
					'RESTDAY AMOUNT',
					'SPECIAL DAYS AMOUNT',
					'LEGAL DAYS AMOUNT',
					'REGULAR OT AMOUNT',
					'RESTDAY OT AMOUNT',
					'SPECIAL OT AMOUNT',
					'LEGAL OT AMOUNT',
					'GROSS PAY',
					array('data'=>'SSS','class'=>'th-deduction'),
					array('data'=>'PHILHEALTH','class'=>'th-deduction'),
					array('data'=>'WITH\'G TAX','class'=>'th-deduction'),					
					array('data'=>'PAG-IBIG CONT.','class'=>'th-deduction'),					
					array('data'=>'ADVANCS/TOOLS','class'=>'th-deduction'),					
					array('data'=>'UNIFORM','class'=>'th-deduction'),					
					array('data'=>'OBMEALS','class'=>'th-deduction'),					
					'NET PAY',
			 		);
			foreach($dt_display as $key => $value){
				$row_content = array();
				
				$row_content[] = array('data'=>$value['ID. NO.']);
				$row_content[] = array('data'=>$value['EMPLOYEE NAME']);
				$row_content[] = array('data'=>$value['DESIGNATION']);
				$row_content[] = array('data'=>$this->extra->number_format($value['RATE PER DAY']));
				$row_content[] = array('data'=>$this->extra->number_format($value['NO. OF DAYS']));
				$row_content[] = array('data'=>$this->extra->number_format($value['NIGHT DIFFERENTIAL']));
				$row_content[] = array('data'=>$this->extra->number_format($value['REGULAR AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['RESTDAY AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['SPECIAL DAYS AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['LEGAL DAYS AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['REGULAR OT AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['RESTDAY OT AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['SPECIAL OT AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['LEGAL OT AMOUNT']));
				$row_content[] = array('data'=>$this->extra->number_format($value['GROSS PAY']));
				$row_content[] = array('data'=>$this->extra->number_format($value['SSS']));
				$row_content[] = array('data'=>$this->extra->number_format($value['PHILHEALTH']));
				$row_content[] = array('data'=>$this->extra->number_format($value['WITH\'G TAX']));
				$row_content[] = array('data'=>$this->extra->number_format($value['PAG-IBIG CONT.']));
				$row_content[] = array('data'=>$this->extra->number_format($value['ADVANCS/TOOLS']));
				$row_content[] = array('data'=>$this->extra->number_format($value['UNIFORM']));
				$row_content[] = array('data'=>$this->extra->number_format($value['OBMEALS']));
				$row_content[] = array('data'=>$this->extra->number_format($value['NET PAY']));
				$payroll_amount = $payroll_amount + $value['NET PAY'];
								
				$this->table->add_row($row_content);
				
			}
	
		$this->table->set_heading($show);
			
	
		$output = array(
			'table'=>$this->table->generate(),
			'payroll_amt'=>$this->extra->number_format($payroll_amount),
		);
				
		return $output;	

	}



	public function get_driver_trips($arg){

		$this->load->model('dispatch/md_dispatch');

		/*	
		$arg['department'] = 'mine';
		$arg['date']       = '2014-08-24';
		$arg['shift']      = 'ds';
		*/

		if($arg['department'] == 'mine')
		{
			$arg['equipment']  = 'HOWO DT';	
			$arg['range_equipment'] = " AND (equipment = 'HOWO DT' or equipment = 'ADT')";
		}else{
			$arg['equipment']  = 'HOWO DT';			
		}
				
		$sql = "
			SELECT 
			ass.del_id 'del_id',
			ass.driver 'driver',
			del.equipment_brand,
			COUNT(ass.driver)'trips' 
			FROM qpsii_constsystem.area_delivery ass
			INNER JOIN qpsii_constsystem.db_equipmentlist del
			ON (ass.haul_equip = del.equipment_id)
			WHERE ass.trans_date = '".$arg['date']."'
			AND ass.haul_owner = '1'
			GROUP BY ass.driver
		";

		$result = $this->db->query($sql);
		$trips = $result->result_array();
		

		$person = $this->md_dispatch->get_person($arg);

		$merge = array();
		$cnt = 0;
		$cnt1 = 0;

		if($arg['shift']=='ds'){
			$time['in']  = 'ds_in';
			$time['out'] = 'ns_out';
		}else{
			$time['in']  = 'ns_in';
			$time['out'] = 'ds_out';
		}

		

		$count_group = array();


		foreach($person as $row)
		{

			if(!isset($count_group[trim($row['equipment'])]['no_unit']))
			{
				$count_group[trim($row['equipment'])]['no_unit'] = 0;
			}
			
			$bool = false;
			foreach($trips as $row1)
			{

				if($row['id'] == $row1['driver'])
				{

					$merge[trim($row['equipment'])][] = array(
							'id'=>$row['id'],
							'name'=>$row['name'],
							'position'=>$row['position'],
							'am_in'=> (isset($row[$time['in']]))? date('h:i:s A',strtotime($row[$time['in']])) : '-' ,
							'pm_out'=> (isset($row[$time['out']]))? date('h:i:s A',strtotime($row[$time['out']])) : '-' ,
							'body_no'=>$row1['equipment_brand'],
							'trips'=>$row1['trips'],
						);
					$bool = true;
					
						$count_group[trim($row['equipment'])]['no_unit']++;	
						
				
				}

			}
			

			if(!isset($count_group[trim($row['equipment'])]['total']))
			{
				$count_group[trim($row['equipment'])]['total'] = 0;
				$count_group[trim($row['equipment'])]['total']++;	
			}else{
				$count_group[trim($row['equipment'])]['total']++;											
			}
						

			/*		
			if(isset($count_group[trim($row['equipment'])]['no_unit']))
			{
				$count_group[trim($row['equipment'])]['total'] = 0;
				$count_group[trim($row['equipment'])]['total'] += 1;
			}else{
				$count_group[trim($row['equipment'])]['total'] += 1;
			}*/


						
			if($bool == false){
				
				$merge[trim($row['equipment'])][] = array(
							'id'=>$row['id'],
							'name'=>$row['name'],
							'position'=>$row['position'],
							'am_in'=> (isset($row[$time['in']])) ? date('h:i:s A',strtotime($row[$time['in']])) : '-' ,
							'pm_out'=> (isset($row[$time['out']])) ? date('h:i:s A',strtotime($row[$time['out']])) : '-' ,
							'body_no'=>'<span class="label label-warning"> No Unit</span>',
							'trips'=>'-',
						);

			}

		}
		
		
		
		$output = array(
			'count'=>$count_group,
			'content'=>$merge
			);
		
		return $output;

	}
	




	

}