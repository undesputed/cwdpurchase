<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Company_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_project_setup');

	}
	

	public function index(){

		$this->lib_auth->title = "Company Setup";		
		$this->lib_auth->build = "setup/project_setup/index";
		
		$this->lib_auth->render();

	}

	public function get_data(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_project_setup->get_cumulative();

		$show = array(
				'ID',
				'Company Name',
				'Address',
				'Contact No',
				'Action',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['title_id'],'class'=>'id');
				$row_content[] = array('data'=>$value['title_name'],'class'=>'title_name');
				$row_content[] = array('data'=>$value['title_desc1'],'class'=>'title_desc1');
				$row_content[] = array('data'=>$value['title_desc2'],'class'=>'title_desc2');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function save_project(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_project_setup->save();


	}




}