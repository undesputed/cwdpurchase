<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utilization extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('maintenance/md_utilization');
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );	
		$this->table->set_template($tmpl); 
	}

	public function index(){

		$this->lib_auth->title = "Utilization";
		$this->lib_auth->build = "maintenance/utilization/index";
		

		$data['item_name']	= $this->md_utilization->get_itemName();
		$this->lib_auth->render($data);
		
	}


	public function get_checklist(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		if($this->input->post('item_no')==""){
			$result = $this->md_utilization->get_checklist();
		}else{
			$result = $this->md_utilization->checked_equipmentName($this->input->post('item_no'));
		}

		$header = array(
					array('data'=>'id','style'=>'display:none'),
					array('data'=>'<input type="checkbox" id="checkboxAll"/>'),
					array('data'=>'Category'),
				);
		$this->table->set_heading($header);

		// $action = "<span class='action'><a href='javascript:void(0)' id='edit'>Edit</a></span>";

		foreach($result->result_array() as $value){
			$row_content = array();
			foreach ($value as $key1 => $value1){
				switch ($key1) {
					case 'id':
						$row_content[] = array('data'=>$value1,'style'=>'display:none;');
						break;
					case 'INCLUDE?':
						$checked = ($value1=='FALSE' || $value1=="")? "" : "checked" ;
						$row_content[] = array('data'=>'<input class="chk-box" type="checkbox" '.$checked.' />');
						break;						
					default:
						$row_content[] = array('data'=>"<span class='data'>".$value1."</span>");
						break;
				}			
			}		
			$this->table->add_row($row_content);
		}

		echo $this->table->generate();
		
	}


	public function save_utilization(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		if($this->md_utilization->save_utilization()){
				echo 0;
		}else{
				echo 1;
		}
		

		
	}





}