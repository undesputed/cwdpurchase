<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Equipment_registry extends CI_Controller {

	public function __construct(){
		parent :: __construct();		
		$this->load->model('fixed_asset/md_equipment_registry');
		$this->load->model('inventory/md_stock_withdrawal');
	}

	public function index(){

		$this->lib_auth->title = "Equipment Registry";		
		$this->lib_auth->build = "fixed_asset/equipment_registry/cumulative";
		
		$this->lib_auth->render();		
	}

	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}	

		$result = $this->md_equipment_registry->get_cumulative($this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide'=>array(
					'Equip ID',
					'Equip Name',
					'Plate No.',
					'Unit Measure',
					'Current Qty',
					'Max Qty',
					'Min Qty',
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
		$data['signatory'] = $this->md_stock_withdrawal->signatory();
		$data['equipment_type'] = $this->md_equipment_registry->equipment_type();

		$this->load->view('fixed_asset/equipment_registry/create',$data);
	}

	public function get_equipment(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_equipment_registry->get_equipment($this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide'=>array(
					'Quantity on Hand',
					'Registered Item',
					'Division Code',
					'Inventory ID',
					'Receipt No',
				),
			'table_class'=>array(
				'table',
				'table-striped',
				'equipment_table',
				)
			);

		echo $this->extra->generate_table($option);

	}

	public function save_equipmentRegistry(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_equipment_registry->save_equipmentRegistry();				
	}

	public function update_equipmentRegistry(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_equipment_registry->update_equipmentRegistry();				
	}

	public function get_details(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		$tmpl = array ( 'table_open'  => '<table class="table details_table table-striped">' );
		$this->table->set_template($tmpl);
		
		$result = $this->md_equipment_registry->get_details($this->input->post('id'),$this->input->post('location'),$this->input->post('project'));
		$operator = array('MINUS'=>'-','PLUS'=>'+');
		$show = array(
					'Reference No',
					'Date Received',
					'Remarks',
					'Prev Qty',
					'',
					'on Hand',
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
	
			$this->table->set_heading($show);		

		$data['table'] = $this->table->generate();
		$data['id']    = $this->input->post('id');
		$data['edit_url'] = 'fixed_asset/equipment_registry/edit';
		$this->load->view('template/cumulative_3_a',$data);
	}


	public function edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}	
		$data['main'] = $this->md_equipment_registry->get_mainData($this->input->post('po_id'));

		$data['signatory'] = $this->md_stock_withdrawal->signatory();
		$data['equipment_type'] = $this->md_equipment_registry->equipment_type();

		$this->load->view('fixed_asset/equipment_registry/edit',$data);

	}	


}