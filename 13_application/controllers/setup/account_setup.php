<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Account_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();		

		$this->load->model('setup/md_account_setup');

	}

	public function index(){

		$this->lib_auth->title = "Account Setup";		
		$this->lib_auth->build = "setup/account_setup/index";

		$this->lib_auth->render();
		
	}

	
	public function get_account(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_account_setup->get_account();		

		$show = array(
					array('data'=>'account_id','style'=>'display:none'),
					'Account Code',
			 		'Account Description',
			 		'Sub Classification',
			 		'Classification',
			 		'Account Type',
			 		'Action',
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['account_id'],'class'=>'account_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Account Code'],'class'=>'account_code');
				$row_content[] = array('data'=>$value['Account Description'],'class'=>'account_description');
				$row_content[] = array('data'=>$value['Sub Classification'],'class'=>'sub_classification');
				$row_content[] = array('data'=>$value['Classification'],'class'=>'classification');
				$row_content[] = array('data'=>$value['Account Type'],'class'=>'account_type');
				$row_content[] = array('data'=>'<span class="btn-link action update">Update</span>','class'=>'account_type');
				
				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();
			
	}

	public function new_request(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['ledger'] = $this->md_account_setup->get_ledger();
		$data['account_type'] = $this->md_account_setup->get_account_type();
		$data['account_setup'] = $this->md_account_setup->get_account_setup();

		$this->load->view('setup/account_setup/create',$data);

	}


	public function update_request(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main'] = $this->md_account_setup->get_account_setup_single($this->input->post('id'));

		$data['ledger'] = $this->md_account_setup->get_ledger();
		$data['account_type'] = $this->md_account_setup->get_account_type();
		$data['account_setup'] = $this->md_account_setup->get_account_setup();

		$this->load->view('setup/account_setup/edit',$data);		
	}

	public function get_classification(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_account_setup->get_classification($this->input->post('account_type'));
		echo json_encode($result);
		
	}

	public function get_sub_classification(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_account_setup->get_sub_classification($this->input->post('class_id'));
		echo json_encode($result);
	}

	public function save_account_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_account_setup->save_account_setup();		
	}

	public function update_account_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_account_setup->update_account_setup();		
	}

	/****************************EXTRA******************************/

	/**CLASSIFICATION**/

	public function add_classification(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->load->view('setup/account_setup/add_classification');

	}

	public function  get_classification_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_account_setup->get_classification_setup();
		$show = array(
					array('data'=>'id','style'=>'display:none'),
					'Classification Code',
					'Short Description',
					'Classification Name',
					'Action',
					);

		foreach($result->result_array() as $key => $value){

			$row_content = array();
			    $row_content[] = array('data'=>$value['id'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Classification Code'],'class'=>'classification_code');
				$row_content[] = array('data'=>$value['Short Description'],'class'=>'short_description');
				$row_content[] = array('data'=>$value['Classification Name'],'class'=>'classification_name');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
			$this->table->add_row($row_content);

		}

		$this->table->set_heading($show);
		echo $this->table->generate();

	}

	public function save_classification_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo  $this->md_account_setup->save_classification_setup();

	}

	/***SUB CLASSIFICATION***/


	public function add_sub_classification(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view('setup/account_setup/add_sub_classification');

	}


	public function  get_sub_classification_setup(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_account_setup->get_sub_classification_setup();

		$show = array(
					array('data'=>'class_id','style'=>'display:none'),
					'ID',
					'Code',
					'Sub Classification Name',
					'Classification',
					'Action',
					);

		foreach($result->result_array() as $key => $value){

			$row_content = array();
				$row_content[] = array('data'=>$value['class_id'],'class'=>'class_id','style'=>'display:none');
			    $row_content[] = array('data'=>$value['ID'],'class'=>'id','style'=>'');
				$row_content[] = array('data'=>$value['Code'],'class'=>'code');
				$row_content[] = array('data'=>$value['Sub Classification Name'],'class'=>'sub_classification_name');
				$row_content[] = array('data'=>$value['Classification'],'class'=>'classification');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
			$this->table->add_row($row_content);

		}

		$this->table->set_heading($show);
		echo $this->table->generate();

	}


	public function save_sub_classification_setup(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo  $this->md_account_setup->save_sub_classification_setup();		

	}



	/****LEDGER*****/

	public function add_ledger(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view('setup/account_setup/add_ledger');		
	}

	public function get_ledger(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_account_setup->get_ledger_setup();

		$show = array(
					array('data'=>'id','style'=>'display:none'),
					'Type',				
					'Action',
					);

		foreach($result->result_array() as $key => $value){

			$row_content = array();
				$row_content[] = array('data'=>$value['id'],'class'=>'id','style'=>'display:none');
			    $row_content[] = array('data'=>$value['type'],'class'=>'type','style'=>'');				
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
			$this->table->add_row($row_content);
					
		}

		$this->table->set_heading($show);
		echo $this->table->generate();

	}

	public function save_ledger(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_account_setup->save_ledger();
	}


	/*****ACCOUNT TYPE *****/

	public function add_account_type(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$this->load->view('setup/account_setup/add_accounts');
	}


	public function get_account_type_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_account_setup->get_account_type_setup();

		$show = array(
					array('data'=>'id','style'=>'display:none'),
					'Type',				
					'Action',
					);

		foreach($result->result_array() as $key => $value){

			$row_content = array();
				$row_content[] = array('data'=>$value['ID'],'class'=>'id','style'=>'display:none');
			    $row_content[] = array('data'=>$value['TYPE'],'class'=>'type','style'=>'');				
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
			$this->table->add_row($row_content);

		}

		$this->table->set_heading($show);
		echo $this->table->generate();

	}

	public function save_account_type_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_account_setup->save_account_type_setup();
	}




}