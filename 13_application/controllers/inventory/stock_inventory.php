<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_inventory extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('inventory/md_stock_inventory');
	}

	public function index(){

		$this->lib_auth->title = "Stock Inventory";		
		$this->lib_auth->build = "inventory/stock_inventory/index";

		$this->lib_auth->render();
		
	}


	public function get_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		
		$result = $this->md_stock_inventory->get_items($this->input->post('location'));
		
		/*
		echo "<pre>";
		print_r($result->result_array());
		echo "</pre>";*/
		$options = array(
			'result'=>$result,
			'hide'=>array(
				'Item No',
				'Item Description',
				'Item Cost',
				'Item Code',
				'Unit Measure',
				'Current Qty',
				'Max Qty',
				'Min Qty',
				),
			'bool'=>true,
		);

		echo $this->extra->generate_table($options);
	}


	public function get_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table details-table table-striped">' );
		$this->table->set_template($tmpl);


		$result = $this->md_stock_inventory->get_details($this->input->post('item_id'),$this->input->post('location'),$this->input->post('project'));
			
			$operator = array(
				'MINUS'=>'-',
				'PLUS'=>'+',
				);
			$header = array(
						'Reference No',
						'Date Received',
						'Remarks',
						'Prev Quantity',						
						'',
						'Quantity on Hand',
						'Balance',
				 		);
				foreach($result->result_array() as $key => $value){
					$row_content = array();					
					$row_content[] = $value['Reference No'];
					$row_content[] = $value['Date Received'];
					$row_content[] = $value['Remarks'];
					$row_content[] = $value['Prev Quantity'];					
					$row_content[] = $operator[$value['Legend']];
					$row_content[] = $value['Quantity on Hand'];
					$row_content[] = $value['Balance'];
					$this->table->add_row($row_content);
				}

				$this->table->set_heading($header);
		
		$data['table'] = $this->table->generate();
		$data['post'] = $this->input->post();
		
		$this->load->view('inventory/stock_inventory/details',$data);

	}


	public function update_stock_inventory(){		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_stock_inventory->update_stock_inventory();

	

	}

	
}