<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_withdrawal extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('inventory/md_stock_withdrawal');
	}

	public function index(){

		$this->lib_auth->title = "Stock Withdrawal";		
		$this->lib_auth->build = "inventory/Stock_withdrawal/cumulative";
		
		$this->lib_auth->render();		
	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$result = $this->md_stock_withdrawal->get_cumulative();
		$option = array(
			'result'=>$result,	
			'hide'=>array(
					'MIS NO',
					'MIS DATE',
					'MIS STATUS',
					'ISSUED BY',
					'NOTED BY',
				),
			'bool'=>true
			);
		echo $this->extra->generate_table($option);
	}

	public function new_request(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['post'] = $this->input->post();
		$data['signatory']  = $this->md_stock_withdrawal->signatory();
		$data['cost_center'] = $this->md_stock_withdrawal->cost_center();
		
		$this->load->view('inventory/Stock_withdrawal/create',$data);
	}

	public function get_item(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$tmpl = array ( 'table_open'  => '<table class="table item-table table-striped">' );
		$this->table->set_template($tmpl);
				
		$result = $this->md_stock_withdrawal->get_item($this->input->post('type'));
		
		$header = array(
					array('data'=>'Inventory Id','style'=>'display:none'),
					array('data'=>'account_code','style'=>'display:none'),
					array('data'=>'division_code','style'=>'display:none'),
					array('data'=>'item_cost','style'=>'display:none'),
					'Item No',
					'Item Description',					
					'Quantity',
					'Action',
			 		);

			foreach($result->result_array() as $key => $value){
				
				$row_content   = array();
				$row_content[] = array('data'=>$value['inventory_id'],'style'=>'display:none','class'=>'inventory_id');
				$row_content[] = array('data'=>$value['account_code'],'style'=>'display:none','class'=>'account_code');
				$row_content[] = array('data'=>$value['division_code'],'style'=>'display:none','class'=>'division_code');
				$row_content[] = array('data'=>$value['item_cost'],'style'=>'display:none','class'=>'item_cost');
				$row_content[] = array('data'=>(empty($value['item_no']))? "" : $value['item_no'] ,'class'=>'item_no');
				$row_content[] = array('data'=>$value['item_description'],'class'=>'item_description');
				$row_content[] = array('data'=>$value['Received Quantity'],'class'=>'received_quantity');
				$row_content[] = array('data'=>'<a href="javascript:void(0)" class="action-add">Add</a>');
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($header);
			echo $this->table->generate();

	}

	public function save_withdrawal(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_withdrawal->save_withdrawal();

	}

	public function save_withdrawal2(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_withdrawal->save_withdrawal2();

	}

	public function update_withdrawal(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_withdrawal->update_withdrawal();

	}


	public function get_details(){
		if(!$this->input->is_ajax_request()){
					exit(0);
		}		

		$result = $this->md_stock_withdrawal->get_details($this->input->post('withdraw_id'));

		$option = array(
				'result'=>$result,
				'hide'=>array(
					'item_no',
					'item_description',
					'withdrawn_qty',
					),
				'bool'=>true,
			);

		$data['table'] =  $this->extra->generate_table($option);
		$data['id']    =  $this->input->post('withdraw_id');
		$data['edit_url'] = 'inventory/stock_withdrawal/edit';
		$this->load->view('template/cumulative_3_a',$data);

	}

	public function edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main']        = $this->md_stock_withdrawal->get_main($this->input->post('po_id'));		
		$data['details']     = json_encode($this->md_stock_withdrawal->get_details($this->input->post('po_id'))->result_array());
		$data['signatory']   = $this->md_stock_withdrawal->signatory();
		$data['cost_center'] = $this->md_stock_withdrawal->cost_center();
		
		$this->load->view('inventory/stock_withdrawal/edit',$data);

	}



}