<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Equip_checklist extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('maintenance/md_equip_checklist');

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl); 
	}

	public function index(){

		$this->lib_auth->title = "Equipment Checklist Setup";		
		$this->lib_auth->build = "maintenance/equip_checklist/index";
		
		$this->lib_auth->render();
		
	}


	public function get_checklist(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$result = $this->md_equip_checklist->get_checklist();
		$header = array(
					array('data'=>'id','style'=>'display:none'),
					array('data'=>'Category'),
				);
		$this->table->set_heading($header);

		$action = "<span class='action'><a href='javascript:void(0)' id='edit'>Edit</a></span>";

		foreach($result->result_array() as $value){
			$row_content = array();
			foreach ($value as $key1 => $value1){
				switch ($key1) {
					case 'id':
						$row_content[] = array('data'=>$value1,'style'=>'display:none;');
						break;					
					default:
						$row_content[] = array('data'=>"<span class='data'>".$value1."</span>".$action);
						break;
				}			
			}		
			$this->table->add_row($row_content);
		}

		echo $this->table->generate();
		
	}


	public function insert_checklist(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->input->post('id')!=""){

			if($this->md_equip_checklist->update_checklist()){
				echo 2;
			}else{
				echo 1;
			}

		}else{

			if($this->md_equip_checklist->insert_checklist()){
				echo 0;
			}else{
				echo 1;
			}

		}

		

	}



}