<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Material_inventory extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model(array('inventory/md_material_inventory'));	
	}

	public function index(){

		$this->lib_auth->title = "Ending Inventory";		
		$this->lib_auth->build = "procurement/material_inventory/ending_inventory";		
		$this->lib_auth->render();
				
	}


	public function get_inventory(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
				
		$arg = $this->input->post();
		$result = $this->md_material_inventory->get_inventory($arg);

		/*echo "<pre>";
		print_r($result);
		echo "</pre>";*/

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped">' );
		$this->table->set_template($tmpl);

		$show = array(
					'SKU',
					'Name/Description',
					'Unit',
					'Beginning',
					'Received Qty',
					'Withdrawn Qty',
					'Current Qty',
			 		);
			foreach($result as $key => $value){
				$row_content = array();
				$row_content[] = array('data'=>$value['SKU']);
				$row_content[] = array('data'=>$value['DESCRIPTION']);
				$row_content[] = array('data'=>$value['UoM']);
				$row_content[] = array('data'=>$value['BEGINNING']);
				$row_content[] = array('data'=>$value['RECEIVED QTY']);
				$row_content[] = array('data'=>$value['WITHDRAWN QTY']);
				$row_content[] = array('data'=>$value['CURRENT QTY']);
				
				$this->table->add_row($row_content);			
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

	}





}