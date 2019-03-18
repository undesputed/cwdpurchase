<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Person_setup extends CI_Controller {

	public function __construct(){

		parent :: __construct();		
		$this->load->model('setup/md_person_setup');

	}


	public function index(){

		$this->lib_auth->title = "Person Setup";		
		$this->lib_auth->build = "setup/person_setup/index";
		
		$this->lib_auth->render();

	}

	public function get_person(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_person_setup->get_person();

		$show = array(
				'ID',
				// 'PREFIX',
				'LASTNAME',
				'FIRST NAME',
				'MIDDLE NAME',
				'ACTION',
			 	);
			
			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['pp_person_code'],'class'=>'id');
				// $row_content[] = array('data'=>$value['pp_prefix'],'class'=>'prefix');
				$row_content[] = array('data'=>$value['pp_lastname'],'class'=>'lastname');
				$row_content[] = array('data'=>$value['pp_firstname'],'class'=>'firstname');
				$row_content[] = array('data'=>$value['pp_middlename'],'class'=>'middlename');
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span> <span class="event">|</span> <span class="btn-link event delete_class">Delete</span>','class'=>'');
				$this->table->add_row($row_content);

			}
			
			$this->table->set_heading($show);
			echo $this->table->generate();


	}

	public function create_person(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$this->load->view('setup/person_setup/create');

	}

	public function update(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['person'] = $this->md_person_setup->get_personId($this->input->post('id'));
		$this->load->view('setup/person_setup/edit',$data);

	}



	public function save_person(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_person_setup->save_person();

	}

	public function update_person(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_person_setup->update_person();
		
	}

	public function delete(){
		if(!$this->input->is_ajax_request()){
					exit(0);
		}

		echo $this->md_person_setup->delete($this->input->post('pp_person_code'));

	}



}