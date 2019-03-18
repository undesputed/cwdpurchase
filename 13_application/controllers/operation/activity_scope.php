<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Activity_scope extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		
		$this->load->model('operation/md_project_operation');	
		$tbl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tbl);

	}

	public function index(){

		$this->lib_auth->title = "Activity Scope";		
		$this->lib_auth->build = "operation/activity_scope/index";
		

		$this->lib_auth->render();
		
	}


	public function get_list(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$overide = array('type'=>'Description','code'=>'Code');
		$result = $this->md_project_operation->activity_scope();

		
		$action = "<span class='action'><a href='javascript:void(0)' id='edit'>Edit</a></span>";
		foreach($result->result_array() as $value){
			$row_content = array();
			$header = array();
			
			foreach ($value as $key1 => $value1){				
				switch ($key1){

						case 'id':
						case 'remarks':
						case 'status':
						case 'savedate':
						case 'userid':
						case 'location':
						case 'title_id':

						$value1 = (!empty($value1))? $value1 : "";
						$row_content[] = array('data'=>$value1,'style'=>'display:none;');
						$header[]      = array('data'=>$key1,'style'=>'display:none;');
						break;

					default:						
						$row_content[] = array('data'=>"<span class='data'>".$value1."</span>");
						$header[]      = array('data'=>$overide[$key1]);
						break;
				}				
			}		

			
			$row_content[] = $action;
			$this->table->add_row($row_content);
		}	

			$header[] = array("data"=>"Action","style"=>'width:100px');
			$this->table->set_heading($header);


		echo $this->table->generate();

	}

	public function insert_list(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->input->post('id')!=""){

			if($this->md_project_operation->update_activity_scope()){
				echo "2";
			}else{
				echo "1";
			}
			
		}else{

			if($this->md_project_operation->insert_activity_scope()){
				echo "0";
			}else{
				echo "1";
			}
			
		}
		
	}




}