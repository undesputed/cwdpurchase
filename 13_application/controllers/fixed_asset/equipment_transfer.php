<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment_transfer extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('fixed_asset/md_equipment_transfer');
		$this->load->model('inventory/md_stock_withdrawal');
	}

	public function index(){

		$this->lib_auth->title = "Equipment Transfer";		
		$this->lib_auth->build = "fixed_asset/Equipment_transfer/cumulative";
		$this->lib_auth->render();

	}


	public function get_cumulative(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$result = $this->md_equipment_transfer->get_cumulative($this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide' =>array(
				'equipment_request_no',
				'Equipment Name',
				'Plate Property No',
				'location',
				'Receiver',
				'date_requested',
				'request_status',
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
		// $data['signatory'] = $this->md_stock_withdrawal->signatory();
		// $data['equipment_type'] = $this->md_equipment_registry->equipment_type();
		$data['signatory'] = $this->md_stock_withdrawal->signatory();
		$this->load->view('fixed_asset/equipment_transfer/create',$data);
	}

	public function get_equipment(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table equipment_table table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_equipment_transfer->get_items($this->input->post('location'));
		
		$show = array(
					array('data'=>'equipment_id','style'=>'display:none'),
					array('data'=>'equipment_itemno','style'=>'display:none'),					
					array('data'=>'inventory_id','style'=>'display:none'),					
					array('data'=>'equipment_model','style'=>'display:none'),					
					array('data'=>'equipment_fulltank','style'=>'display:none'),	
					array('data'=>'equipment_brand','style'=>'display:none'),	
					array('data'=>'equipment_type','style'=>'display:none'),	
					array('data'=>'equipment_fueltype','style'=>'display:none'),					
					array('data'=>'equipment_chassisno','style'=>'display:none'),
					array('data'=>'equipment_costcenter','style'=>'display:none'),
					array('data'=>'equipment_engineno','style'=>'display:none'),
					array('data'=>'equipment_driver_code','style'=>'display:none'),
					array('data'=>'equipment_cost','style'=>'display:none'),
					'Equipment Description',
					'Equipment Status',
					'Equipment Plate Number',
					'Equipment Driver',
					'Referrence No',
					'Action',
			 		);
				
				
			foreach($result->result_array() as $key => $value){
				$row_content = array();
				$row_content[] = array('data'=>$value['equipment_id'],'style'=>'display:none','title'=>$value['equipment_id'],'class'=>'equipment_id');
				$row_content[] = array('data'=>$value['equipment_itemno'],'style'=>'display:none','title'=>$value['equipment_itemno'],'class'=>'equipment_itemno');
				$row_content[] = array('data'=>$value['inventory_id'],'style'=>'display:none','title'=>$value['inventory_id'],'class'=>'inventory_id');
				$row_content[] = array('data'=>$value['equipment_model'],'style'=>'display:none','title'=>$value['equipment_model'],'class'=>'equipment_model');
				$row_content[] = array('data'=>$value['equipment_fulltank'],'style'=>'display:none','title'=>$value['equipment_fulltank'],'class'=>'equipment_fulltank');
				$row_content[] = array('data'=>$value['equipment_brand'],'style'=>'display:none','title'=>$value['equipment_brand'],'class'=>'equipment_brand');
				$row_content[] = array('data'=>$value['equipment_type'],'style'=>'display:none','title'=>$value['equipment_type'],'class'=>'equipment_type');				
				$row_content[] = array('data'=>$value['equipment_fueltype'],'style'=>'display:none','title'=>$value['equipment_fueltype'],'class'=>'equipment_fueltype');				
				$row_content[] = array('data'=>$value['equipment_chassisno'],'style'=>'display:none','title'=>$value['equipment_chassisno'],'class'=>'equipment_chassisno');
				$row_content[] = array('data'=>$value['equipment_costcenter'],'style'=>'display:none','title'=>$value['equipment_costcenter'],'class'=>'equipment_costcenter');
				$row_content[] = array('data'=>$value['equipment_engineno'],'style'=>'display:none','title'=>$value['equipment_engineno'],'class'=>'equipment_engineno');
				$row_content[] = array('data'=>$value['equipment_drivercode'],'style'=>'display:none','title'=>$value['equipment_drivercode'],'class'=>'equipment_drivercode');
				$row_content[] = array('data'=>$value['equipment_cost'],'style'=>'display:none','title'=>$value['equipment_cost'],'class'=>'equipment_cost');				
				$row_content[] = array('data'=>$value['equipment_description'],'style'=>'','title'=>$value['equipment_description'],'class'=>'equipment_description');
				$row_content[] = array('data'=>$this->extra->label($value['equipment_status']),'style'=>'','title'=>$value['equipment_status'],'class'=>'equipment_status');
				$row_content[] = array('data'=>$value['equipment_platepropertyno'],'style'=>'','title'=>$value['equipment_platepropertyno'],'class'=>'equipment_platepropertyno');
				$row_content[] = array('data'=>$value['equipment_driver'],'style'=>'','title'=>$value['equipment_driver'],'class'=>'equipment_driver');
				$row_content[] = array('data'=>$value['referrence_no'],'style'=>'','title'=>$value['referrence_no'],'class'=>'referrence_no');
				$row_content[] = array('data'=>'<span class="btn-link event">Transfer</span>','style'=>'','title'=>'');				
				$this->table->add_row($row_content);
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}


	public function get_mr(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_equipment_transfer->get_mr($this->input->post('id'));		
		echo json_encode($result);

	}


	public function save_equipmentTransfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}		
		echo $this->md_equipment_transfer->save_equipment_list();

	}


	public function get_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}


		$main = $this->md_equipment_transfer->get_main_table($this->input->post('id'));

		$result = $this->md_equipment_transfer->get_details($this->input->post('id'));
		$option = array(
			'result'=>$result,
			'hide'=>array(
				'item_no',
				'description',
				'quantity',
				),
			'bool'=>true,
			'table_class'=>array('table','table-striped','details_table')
			);
		$data['table']    = $this->extra->generate_table($option);
		$data['id']       = $this->input->post('id');
		$data['edit_url'] = 'fixed_asset/equipment_transfer/edit';
		$data['type'] = "EQ";
		$data['status'] = $main['request_status'];

		$this->load->view('template/cumulative_3',$data);


	}

	public function edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$data['main']      = $this->md_equipment_transfer->get_main_table($this->input->post('po_id'));
		$data['details']   = $this->md_equipment_transfer->get_details_table($this->input->post('po_id'));
		$data['db_equipment'] = $this->md_equipment_transfer->get_db_equipmentlist($data['main']['equipment_id']);			
		$data['signatory'] = $this->md_stock_withdrawal->signatory();

		$this->load->view('fixed_asset/equipment_transfer/edit',$data);
		
		
	}

	public function update_equipmentTransfer(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

			echo $this->md_equipment_transfer->update_equipment_list();


	}
	



}