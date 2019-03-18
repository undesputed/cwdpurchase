<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('setup/md_user_setup');

	}

	public function index(){

		$this->lib_auth->title = "User Setup";		
		$this->lib_auth->build = "setup/user_setup/index";
				
		$data['employee'] = $this->md_user_setup->get_employee();
		$this->lib_auth->render($data);

	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_user_setup->get_cumulative();
		
		$show = array(
				array('data'=>'id','style'=>'display:none'),
				array('data'=>'proj_code','style'=>'display:none'),
				array('data'=>'proj_main','style'=>'display:none'),
				array('data'=>'dept_code','style'=>'display:none'),
				'Username',
				'Name',
				'User Type',
				'Emp Id',
				'Dept Name',
				'Action'
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['SYSPK'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Proj_Code'],'class'=>'proj_code','style'=>'display:none');
				$row_content[] = array('data'=>$value['Proj_Main'],'class'=>'proj_main','style'=>'display:none');
				$row_content[] = array('data'=>$value['dept_code'],'class'=>'dept_code','style'=>'display:none');
				$row_content[] = array('data'=>$value['USERNAME'],'class'=>'username');
				$row_content[] = array('data'=>$value['NAME'],'class'=>'name');
				$row_content[] = array('data'=>$value['USER TYPE'],'class'=>'user_type');
				$row_content[] = array('data'=>$value['EMP ID'],'class'=>'emp_id');
				$row_content[] = array('data'=>$value['DEPT NAME'],'class'=>'dept_name');
				
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);
				
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

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
		
		echo $this->md_user_setup->save_userSetup();
		
	}



}