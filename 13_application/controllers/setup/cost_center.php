<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cost_center extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_cost_center');

	}

	public function index(){

		$this->lib_auth->title = "Cost Center";
		$this->lib_auth->build = "setup/cost_center/index";
		$this->lib_auth->render();

	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_cost_center->get_cumulative();

		$show = array(
				'ID',
				'TYPE',
				'Action',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['PK'],'class'=>'id');
				$row_content[] = array('data'=>$value['TYPE'],'class'=>'type');				
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function save(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_cost_center->save();


	}




}