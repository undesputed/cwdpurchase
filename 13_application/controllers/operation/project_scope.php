<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Project_scope extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('operation/md_project_scope');
		$tbl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tbl);

	}

	public function index(){

		$this->lib_auth->title = "Project Scope Setup";		
		$this->lib_auth->build = "operation/project_scope/index";
		
		$data['all_scope']  =  $this->md_project_scope->all_scope();

		$this->lib_auth->render($data);
	}

	public function get_scope(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_project_scope->get_scope();	
		$row_content = array();

		foreach ($result as $key => $value) {			
			$row['id']       =  $value['scope_id'];
			$row['title']    =  $value['type_desc'];
			$row['ref_no']   =  $value['project_no'];
			$row['location_name'] =  $value['project_name'];
			$row['location'] =  $value['location'];
			$row['project']  =  $value['title_id'];
			$row['type']     =  $value['type_code'];
			$row_content[] = $row;
		}
	

		echo json_encode($row_content);

	}


	function save_scope(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->md_project_scope->insert_scope_setup()){
			echo "1";
		}else{
			echo "0";
		}

	}

}