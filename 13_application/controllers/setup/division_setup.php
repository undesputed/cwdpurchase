<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Division_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_division_setup');

	}


	public function index(){

		$this->lib_auth->title = "Division Setup";		
		$this->lib_auth->build = "setup/division_setup/index";
		
		$this->lib_auth->render();

	}

	public function get_division(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_division_setup->get_cumulative();

		$show = array(
				'Division ID',
				'Division Name',
				'Division Code',
				'Action',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['division_id'],'class'=>'id');
				$row_content[] = array('data'=>$value['Division Name'],'class'=>'division_name');
				$row_content[] = array('data'=>$value['Division Code'],'class'=>'division_code');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function save_division(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_division_setup->save_division();


	}




}