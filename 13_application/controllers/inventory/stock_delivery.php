<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_delivery extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('inventory/md_stock_delivery');		
		$this->load->model('inventory/md_stock_withdrawal');
	}

	public function index(){

		$this->lib_auth->title = "Stock Delivery";		
		$this->lib_auth->build = "inventory/stock_delivery/cumulative";	
		$this->lib_auth->render();
		
	}

	public function get_cumulative(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}	

		$result = $this->md_stock_delivery->get_cumulative($this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide'=>array(
					'REF. No.',
					'REF. DATE',
					'remarks',
					'Status',
					'Delivery Location',
				),
			'bool'=>true
			);		
		echo $this->extra->generate_table($option);		
	}


	public function get_dispatch(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table item-table table-striped">' );
		$this->table->set_template($tmpl);
		
		$result = $this->md_stock_delivery->get_dispatch($this->input->post('location'));

		$show = array(
						array('data'=>'to_location','style'=>'display:none'),
						array('data'=>'equipment_id','style'=>'display:none'),
						array('data'=>'equipment_request_id','style'=>'display:none'),
						'Delivery Ref. No',
						'Delivery Location',
						'Delivery Date',
						'Delivery Status',
						'Action',
				 		);
				foreach($result->result_array() as $key => $value){
					$row_content = array();
					$row_content[] = array('data'=>$value['to_location'],'style'=>'display:none','class'=>'to_location');
					$row_content[] = array('data'=>(isset($value['equipment_id']))? $value['equipment_id']:0 ,'style'=>'display:none','class'=>'equipment_id');
					$row_content[] = array('data'=>$value['equipment_request_id'],'style'=>'display:none','class'=>'equipment_request_id');
					$row_content[] = array('data'=>$value['equipment_request_no'],'class'=>'equipment_request_no');
					$row_content[] = array('data'=>$value['To Project Location'],'title'=>$value['To Project Location'],'class'=>'to_project_location');
					$row_content[] = array('data'=>$value['date_requested'],'class'=>'date_requested');
					$row_content[] = array('data'=>$value['request_status'],'class'=>'request_status');	
					$row_content[] = array('data'=>'<a href="javascript:void(0)" class="details" id="'.$value['equipment_request_id'].'" data-poload="'.$value['equipment_request_id'].'">details</a>');
					$this->table->add_row($row_content);			
				}
		
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	public function new_request(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['post'] = $this->input->post();
		$data['signatory']  = $this->md_stock_withdrawal->signatory();
		// // $data['cost_center'] = $this->md_stock_withdrawal->cost_center();
		// $data['division'] = $this->md_stock_withdrawal->division();
		$this->load->view('inventory/stock_delivery/create',$data);

	}


	public function get_item_details($id){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result  = $this->md_stock_delivery->get_itemDetails($id);
		$option = array(
			'result'=>$result,
			'hide'=>array(
					'Item No',
					'Item Description',
					'quantity',
				),
			'bool'=>true,
			'table_class'=>array('table','table-striped','add-to-list'),
			);
		echo $this->extra->generate_table($option);
		echo "<button class=\"btn btn-primary btn-margin btn-addToList btn-sm\">Add to List</button>";

	}


	public function save_delivery(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_delivery->save_delivery();
		
	}

	public function get_details(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_stock_delivery->get_details($this->input->post('id'));
		$option = array(
			'result'=>$result,
			'table_class'=>array('table','table-striped','details_table')
		);

		$data['table'] = $this->extra->generate_table($option);
		$data['id']    = $this->input->post('id');
		$data['edit_url'] = 'inventory/stock_delivery/edit_form';
		$this->load->view('template/cumulative_3_a',$data);

	}

	public function edit_form(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$data['main']    = $this->md_stock_delivery->get_mainData($this->input->post('po_id'));
		$data['details'] = json_encode($this->md_stock_delivery->get_detailsData($this->input->post('po_id')));
		$data['signatory']  = $this->md_stock_withdrawal->signatory();
		
		$this->load->view('inventory/stock_delivery/edit',$data);
	}

	public function update_delivery(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_stock_delivery->update_delivery();
		
		
	}



}