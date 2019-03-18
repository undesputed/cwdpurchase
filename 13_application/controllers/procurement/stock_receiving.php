<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_receiving extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('procurement/md_stock_receiving');
	}

	public function index(){


		$this->lib_auth->title = "Stock Receiving";		
		$this->lib_auth->build = "procurement/stock_receiving/index";

		$data['itemType'] = $this->md_stock_receiving->get_itemType();
		$this->lib_auth->render($data);

	}

	public function get_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped tbl-event">' );
		$this->table->set_template($tmpl);


		$result = $this->md_stock_receiving->get_item($this->input->post('itemType'));

		$show = array(
						'Item No.',
						'Item Description',
						'Quantity',
						'Unit Cost',
						'Unit Measure',
						'Action',
				 		);
				foreach($result->result_array() as $key => $value){

					$row_content   = array();

					$row_content[] = array('data'=>$value['Item No.'],'style'=>'','class'=>'item_no');
					$row_content[] = array('data'=>$value['Item Description'],'style'=>'','class'=>'item_description');
					$row_content[] = array('data'=>$value['Quantity'],'style'=>'','class'=>'quantity');
					$row_content[] = array('data'=>$value['Unit Cost'],'style'=>'','class'=>'unit_cost');
					$row_content[] = array('data'=>$value['Unit Measure'],'style'=>'','class'=>'unit_measure');
					$row_content[] = array('data'=>'<span class="event btn-link receive">Receive</span>','style'=>'','class'=>'');
					
					$this->table->add_row($row_content);

				}
				
				$this->table->set_heading($show);
				echo $this->table->generate();

	}

	public function assign_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main'] = $this->md_stock_receiving->get_itemID($this->input->post('id'));
		$data['supplier'] = $this->md_stock_receiving->get_supplier();
		$data['operator'] = $this->md_stock_receiving->get_employee();
		$data['type'] = $this->md_stock_receiving->get_stationary();
		$data['model'] = $this->md_stock_receiving->get_model();
		$data['itemType'] = $this->input->post('itemType');	

		$data['location'] = $this->input->post('location');
		$data['project']  = $this->input->post('project');
				
		$this->load->view('procurement/stock_receiving/create',$data);

	}

	public function save(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		echo $this->md_stock_receiving->save();

	}

}