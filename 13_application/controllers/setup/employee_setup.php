<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();
				
		$this->load->model('setup/md_employee_setup');

	}

	public function index(){

		$this->lib_auth->title = "Employee Setup";		
		$this->lib_auth->build = "setup/employee_setup/index";	
		$this->lib_auth->render();		
	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_employee_setup->get_cumulative();

		$show = array(
				array('data'=>'id','style'=>'display:none'),	
				'',		
				'EMP NAME',
				'POSITION',
				'STATUS ',
				'ACTION',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();
				$signatory = "";
				if(!empty($value['path']) || $value['path'] != "")
				{
					$signatory = "<span class='label label-success'>w/Signature</span>";
				}else{
					$signatory = "";
				}

				$row_content[] = array('data'=>$value['emp_number'],'class'=>'emp_number','style'=>'display:none');
				$row_content[] = array('data'=>$signatory,'class'=>'signature');
				$row_content[] = array('data'=>$value['pp_fullname'],'class'=>'pp_fullname');
				$row_content[] = array('data'=>$value['jobtit_name'],'class'=>'jobtit_name');
				$row_content[] = array('data'=>$value['jobStatusName'],'class'=>'jobStatusName');
				$row_content[] = array('data'=>'<span class="btn-link event signatory_class">Set Digital Signiture</span> <span class="event">|</span> <span class="btn-link event remove_class">Remove</span>','class'=>'');
				
				/*
					$row_content[] = array('data'=>$this->extra->if_null($value['Rate Per Day']),'class'=>'Rate Per Day');
					$row_content[] = array('data'=>$this->extra->if_null($value['SSS']),'class'=>'SSS');
					$row_content[] = array('data'=>$this->extra->if_null($value['HDMF']),'class'=>'HDMF');
					$row_content[] = array('data'=>$this->extra->if_null($value['Philhealth']),'class'=>'Philhealth');
					$row_content[] = array('data'=>$this->extra->if_null($value['W/holding Tax']),'class'=>'W/holding Tax');
				*/

				/*
					$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				*/

				$this->table->add_row($row_content);
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function create(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['get_emp_no'] = $this->md_employee_setup->get_emp_no();

		$data['get_person'] = $this->md_employee_setup->get_person();
		$data['get_jobStatus'] = $this->md_employee_setup->get_jobStatus();
		$data['get_jobPosition'] = $this->md_employee_setup->get_jobPosition();
		$data['get_salaryGrade'] = $this->md_employee_setup->get_salaryGrade();
		$data['get_divisionSetup'] = $this->md_employee_setup->get_divisionSetup();
		$data['get_divisionCurrent'] = $this->md_employee_setup->get_divisionCurrent();

		$this->load->view('setup/employee_setup/create',$data);

	}


	public function get_division(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_user_setup->get_department($this->input->post('location'));

		echo json_encode($result);
	}


	public function save_user(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_employee_setup->save_employee_setup();
		
	}


	/***Add Status**/

	public function add_status(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view('setup/employee_setup/add_status');

	}

	public function get_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table sub-table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_employee_setup->get_status();

		$show = array(
					array('data'=>'jobStatusNumber','style'=>'display:none'),
					'jobStatusCode',
					'jobStatusName',					
					'Action',			 		
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();
			

				$row_content[] = array('data'=>$value['jobStatusNumber'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Job Status Code'],'class'=>'status_code');
				$row_content[] = array('data'=>$value['Job Status Name'],'class'=>'status_name');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
		
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	public function save_status(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		echo $this->md_employee_setup->save_status();		
	}


	/****ADD POSITION*****/

	public function add_position(){

		if(!$this->input->is_ajax_request()){
				exit(0);
		}

		$data['salary_grade'] = $this->md_employee_setup->position_salary();
		$this->load->view('setup/employee_setup/add_position',$data);

	}

	public function get_position(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table sub-table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_employee_setup->get_position();

		$show = array(
					array('data'=>'jobStatusNumber','style'=>'display:none'),
					'JOBTITLE CODE',
					'JOB TITLE',
					'JOB DESCRIPTION',					
					'STATUS',
	 				'Action'
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();				
				$row_content[] = array('data'=>$value['jobtit_number'],'class'=>'id','style'=>'display:none');				
				$row_content[] = array('data'=>$value['JOBTITLE CODE'],'class'=>'title_code');
				$row_content[] = array('data'=>$value['JOB TITLE'],'class'=>'title');
				$row_content[] = array('data'=>$value['JOB DESCRIPTION'],'class'=>'description');			
				$row_content[] = array('data'=>$value['STATUS'],'class'=>'status');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
		
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	public function save_position(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_employee_setup->save_position();


	}


	/***Salary Grade***/


	public function add_salaryGrade(){

		if(!$this->input->is_ajax_request()){			
			exit(0);
		}

		$this->load->view('setup/employee_setup/add_salaryGrade');

	}


	public function get_salaryGrade(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$tmpl = array ( 'table_open'  => '<table class="table sub-table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_employee_setup->get_salary();

		$show = array(
					array('data'=>'sal_grade_number','style'=>'display:none'),
					'sal_grade_code',
					'sal_grade_name',
					'sal_grade_minsalary',
					'sal_grade_stepsalary',
					'saL_grade_maxsalary',
					'Action',
			 	);

			foreach($result->result_array() as $key => $value){
				
				$row_content = array();				
				$row_content[] = array('data'=>$value['sal_grade_number'],'class'=>'id','style'=>'display:none');				
				$row_content[] = array('data'=>$value['sal_grade_code'],'class'=>'grade_code');
				$row_content[] = array('data'=>$value['sal_grade_name'],'class'=>'grade_name');
				$row_content[] = array('data'=>$value['sal_grade_minsalary'],'class'=>'grade_minsalary');
				$row_content[] = array('data'=>$value['sal_grade_stepsalary'],'class'=>'grade_stepsalary');
				$row_content[] = array('data'=>$value['sal_grade_maxsalary'],'class'=>'grade_maxsalary');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
		
		$this->table->set_heading($show);
		echo $this->table->generate();

	}

	public function save_salary(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->md_employee_setup->save_salary();
	}


	public function signatory_img(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$data['id'] = $this->input->post('id');		
		$data['result'] = $this->md_employee_setup->get_digital_signature($data['id']);
		$this->load->view('setup/employee_setup/signatory',$data);
	}

	/****ADD DIVISION****/	

	public function add_division(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->load->view('setup/employee_setup/add_division');		
	}

	public function remove_emp(){		
		if(!$this->input->is_ajax_request()){
					exit(0);
		}
		echo $this->md_employee_setup->remove_emp($this->input->post('emp_number'));
	}


	public function upload_img($id){


		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{
				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = '. . .No file was uploaded.';
					break;
				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}

		}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{

				$new_name = $id.'_'.$_FILES["fileToUpload"]['name'];
				$config['file_name'] = $new_name;
				$config['upload_path'] = './uploads/';
				$config['allowed_types'] = 'png';
				$config['max_size'] = '100';
				$config['overwrite'] = TRUE;
				$this->load->library('upload', $config);				
				$this->upload->initialize($config);

				$field_name = "fileToUpload";				
				if ( ! $this->upload->do_upload($field_name))
				{
					$error = $this->upload->display_errors();
				}
				else
				{	

					$data  = $this->upload->data();
					$arg   = array(
							'emp_id'=>$id,
							'path'  =>'./uploads/'.$data['file_name'],
						);					
					$this->md_employee_setup->save_digital_signature($arg);
					$msg = 'Successfully Uploaded';

					/*
					$data = array('upload_data' => );
					$this->load->view('upload_success', $data);
					*/
				}

						
				
		}

		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";		
		echo "}";
		
	}

	public function remove_signatory(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$id = $this->input->post('id');
		echo $this->md_employee_setup->remove_digital($id);

	}
	

}