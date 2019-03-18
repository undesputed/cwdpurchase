<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_transfer extends CI_Controller {

	public function __construct(){
		parent :: __construct();
		$this->load->model('inventory/md_stock_transfer');
		$this->load->model('inventory/md_stock_withdrawal');

	}

	public function index(){

		$this->lib_auth->title = "Stock Transfer";		
		$this->lib_auth->build = "inventory/Stock_transfer/cumulative";		
		$this->lib_auth->render();

	}


	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}	

		$result = $this->md_stock_transfer->get_cumulative($this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide'=>array(
				'REQUEST NO.',
				'REQUEST DATE',
				'REQUEST STATUS',
				'REQUESTED BY',
				'APPROVED BY',
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
		// $data['cost_center'] = $this->md_stock_withdrawal->cost_center();
		$data['division'] = $this->md_stock_withdrawal->division();		
		$this->load->view('inventory/Stock_transfer/create',$data);

	}


	public function save_stockTransfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		echo $this->md_stock_transfer->save_stockTransfer();

	}

	public function get_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_stock_transfer->get_details($this->input->post('id'));

		$option = array(
				'result'=>$result,
				'table_class'=>array('table','table-striped','detail_table'),
			);
		$data['table']    =  $this->extra->generate_table($option);
		$data['id']       =  $this->input->post('id');
		$data['edit_url'] = 'inventory/stock_transfer/edit';
		$this->load->view('template/cumulative_3_a',$data);
		
				
	}


	public function edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['main']    = $this->md_stock_transfer->get_mainTbl($this->input->post('po_id'));		
		$data['details'] = json_encode($this->md_stock_transfer->get_detailsTbl($this->input->post('po_id')));

		$data['signatory']  = $this->md_stock_withdrawal->signatory();		
		$data['division']   = $this->md_stock_withdrawal->division();
		
		$this->load->view('inventory/stock_transfer/edit',$data);

	}

	public function update_stockTransfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_transfer->update_stockTransfer();

	}



}