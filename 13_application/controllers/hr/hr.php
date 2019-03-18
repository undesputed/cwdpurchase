<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hr extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('hr/md_hr');
	}

	public function index(){

		$this->lib_auth->title = "Human Resource Management";		
		$this->lib_auth->build = "hr/hr";

		$data['department'] = $this->md_hr->get_department();

		$this->lib_auth->render($data);
		
	}

	public function dtr(){

		$this->lib_auth->title = "Human Resource Management";		
		$this->lib_auth->build = "hr/hr";
		$data['department'] = $this->md_hr->get_department();		
		$this->lib_auth->render($data);		
	}
		
	public function get_employee(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$department = $this->input->post('department');
		$result = $this->md_hr->get_employee($department);
		$div = '';

		foreach($result as $row){		
			$div .="<option value='".$row['UserID_Empl1']."' data-position='".$row['position']."' data-id='".$row['UserID_Empl1']."' data-name='".$row['Name_Empl']."'>".$row['UserID_Empl1']." - ".$row['Name_Empl']."</option>";			
		}

		$output = array(
			'div'=>$div,
			'total'=>count($result),
			);

		echo json_encode($output);

	}

	public function get_dtr(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


	$arg = $this->input->post();
	$result = $this->md_hr->get_dtr($arg['date_from'],$arg['date_to'],$arg['emp_id']);


	$dates = date('F d, Y',strtotime($arg['date_from']))." To ".date('F d, Y',strtotime($arg['date_to']));
	$name  = $arg['emp_name'];


	$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
	$this->table->set_template($tmpl);


		$show = array(
					array('data'=>'#','style'=>'width:30px'),
					'DTR DATE',
					'DAY NAME',
					'REMARKS',
					'TIME IN',
					'TIME OUT',
					'LATE HOURS',
					'UT HOURS',
					'OT HOURS',
					'ND HOURS',
			 		);
			if(count($result) > 0) {
					$row_group['late_hours'] = 0;
					$row_group['ut_hours'] = 0;
					$row_group['ot_hours'] = 0;
					$row_group['nd_hours'] = 0;
					$cnt = 1;
				foreach($result as $key => $value){
					$row_content = array();
					$date = date('F d, Y',strtotime($value['DTR DATE']));
					$time_in = ($value['TIME IN'] == '00:00:00')? '-' : date('h:i:s A',strtotime($value['TIME IN']));
					$time_out = ($value['TIME OUT'] == '00:00:00')? '-' : date('h:i:s A',strtotime($value['TIME OUT']));
					$row_content[] = array('data'=>$cnt);
					$row_content[] = array('data'=>$date);
					$row_content[] = array('data'=>$value['DAY NAME']);
					$row_content[] = array('data'=>$this->extra->label($value['DAY TYPE']));
					$row_content[] = array('data'=>$time_in);
					$row_content[] = array('data'=>$time_out);
					$row_content[] = array('data'=>$value['LATE HOURS']);
					$row_content[] = array('data'=>$value['UT HOURS']);
					$row_content[] = array('data'=>$value['OT HOURS']);
					$row_content[] = array('data'=>$value['ND HOURS']);
					
					$row_group['late_hours'] += $value['LATE HOURS'];
					$row_group['ut_hours']   += $value['UT HOURS'];					
					$row_group['ot_hours']   += $value['OT HOURS'];
					$row_group['nd_hours']   += $value['ND HOURS'];
					$this->table->add_row($row_content);
					$cnt++;

				}
					$row_content = array();
					$row_content[] = array('data'=>'<h5>Total</h5>');
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'');
					$row_content[] = array('data'=>'<h5>'.$row_group['late_hours'].' <small>hrs</small></h5>');
					$row_content[] = array('data'=>'<h5>'.$row_group['ut_hours'].' <small>hrs</small></h5>');
					$row_content[] = array('data'=>'<h5>'.$row_group['ot_hours'].' <small>hrs</small></h5>');
					$row_content[] = array('data'=>'<h5>'.$row_group['nd_hours'].' <small>hrs</small></h5>');

					$this->table->add_row($row_content);

			}else{
				$row_content = array();
				$row_content = array('data'=>'Empty Result','colspan'=>'9');
				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($show);
			 
			$output = array(
				'table'=>$this->table->generate(),
				'name'=>$name,
				'dates'=>$dates
			);

			

			echo json_encode($output);
	}



	/*****/

	public function payroll(){
				
		$this->lib_auth->title = "Payroll - Human Resource Management";		
		$this->lib_auth->build = "hr/payroll";		
		$data['department'] = $this->md_hr->get_department();		
		$this->lib_auth->render($data);

	}

	public function get_payroll(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_hr->get_payroll();

		echo json_encode($result);

	}

	/******/

	public function driver_monitoring(){

		$this->lib_auth->title = "Driver Monitoring";
		$this->lib_auth->build = "hr/driver_monitoring";				
		$this->lib_auth->render();

	}

	public function get_driver_trips(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$arg    = $this->input->get();
		$result = $this->md_hr->get_driver_trips($arg);

		$data['result']  = $result['content'];
		$data['sidebar'] = $result['count'];

		$output = array(
			'content'=>$this->load->view('hr/tbl_driver_trips',$data,true),
			'sidebar'=>$this->load->view('hr/tbl_sidebar',$data,true),
			);

		echo json_encode($output);
		

	}




}