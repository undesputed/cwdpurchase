<?php defined('BASEPATH') OR exit('No direct script access allowed');

class consumption extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model(array(
			'monitoring/md_consumption',
			));

		$this->load->library('table');
	}


	public function index(){
		$this->lib_auth->title = "Equipment Consumption";
		$this->lib_auth->build = "monitoring/consumption/index";
		$this->lib_auth->render();
	}


	public function apply_filter(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

				
		$tmpl = array ( 'table_open'  => '<table class="table table-condensed">');
		$this->table->set_template($tmpl); 

		$result = null;

		$result_heading = $this->md_consumption->display_heading();

		if($this->input->post('type')=='amount'){
			$result = $this->md_consumption->display_amount();
		}else{
			$result = $this->md_consumption->display_quantity();
		}
		
		$tbl_row = array();
		$type_desc = array();
		$hide = array('type_desc'=>'');
		$heading = array(
				array('data'=>'Unit','class'=>'heading'),						
			);

		if(!empty($result_heading)){
			foreach($result_heading as $row){
				$heading[] = array('data'=>$row['itemdescription'],'class'=>'heading');
			}
		}
		

		$output = $this->_row($result);		
		$data = array();

		
		$this->table->add_row($heading);
		foreach($output as $k=>$row){
			$column = array();

				for ($i=0; $i < count($row[0]) ; $i++) { 
					switch ($i) {
						case 0:
							$column[] = $k;
							break;					
						default:
							$column[] = "";
							break;
					}
				}
				
			$this->table->add_row($column);

			foreach($row as $inner){	
			$data = array();			
				foreach($inner as $key=>$inner_data){
										switch ($key) {
											case 0:
												$data[] = array('data'=>'<span style="margin-left:3em;"></span>'.$inner_data);
												break;										
											default:
												$data[] = array('data'=>(isset($inner_data))? $inner_data : "");
												break;
										}										
				}
				$this->table->add_row($data);
			}
		}

		echo $this->table->generate();

	}


	function _row($data){		
			
		$row_data  = array();
		
		foreach($data as $row){			
				$row_data = array();
				foreach($row as $k=>$inner){
					if($k=='type_desc' && !is_numeric($k)){
						continue;						
					}
					$row_data[] = $inner;					
				}
				$type_desc[$row['type_desc']][]= $row_data;			
		}
		return $type_desc;
	}







}