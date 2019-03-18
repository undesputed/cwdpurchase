<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Obligation_release extends CI_Controller {

	public function __construct(){
		parent :: __construct();

		$this->load->model('fixed_asset/md_obligation_release');
		$this->load->model('fixed_asset/md_obligation_request');
		$this->load->model('inventory/md_stock_withdrawal');

	}

	public function index(){

		$this->lib_auth->title = "Obligation Release";		
		$this->lib_auth->build = "fixed_asset/obligation_release/cumulative";		
		$this->lib_auth->render();		
	}


	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_obligation_release->get_cumulative($this->input->post('project'),$this->input->post('location'));
		$option = array(
			'result'=>$result,
			'hide' =>array(
				'MR No.',
				'Equipment Name',
				'Plate No.',
				'Chassis No.',
				'Engine No.',
				'Equipment Cost',
				),
			'bool'=>true
			);

		echo $this->extra->generate_table($option);
		
	}

	public function get_cumulative_2(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table equipment_table table-hover table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_obligation_release->get_cumulative_2($this->input->post('project'),$this->input->post('location'));

		$show = array(
					array('data'=>'mr_id','style'=>'display:none'),					
					array('data'=>'chassis_no','style'=>'display:none'),
					array('data'=>'engine_no','style'=>'display:none'),
					array('data'=>'made_in','style'=>'display:none'),
					array('data'=>'equipment_id','style'=>'display:none'),
					array('data'=>'date_saved','style'=>'display:none'),
					array('data'=>'requestedby','style'=>'display:none'),
					array('data'=>'issuedby','style'=>'display:none'),
					array('data'=>'approvedby','style'=>'display:none'),
					'MR No.',
					'Equipment Name',
					'Plate No.',
					'Chassis No.',
					'Engine No.',
					'Equipment Cost',
					'Action',
			 		);	 
			foreach($result->result_array() as $key => $value){

				$row_content = array();
				
				$row_content[] = array('data'=>$this->is_isset($value['MR_id']),'style'=>'display:none','class'=>'mr_id');								
				$row_content[] = array('data'=>$this->is_isset($value['Chassis No.']),'style'=>'display:none','class'=>'chassis_no');
				$row_content[] = array('data'=>$this->is_isset($value['Engine No.']),'style'=>'display:none','class'=>'engine_no');				
				$row_content[] = array('data'=>$this->is_isset($value['made_in']),'style'=>'display:none','class'=>'made_in');
				$row_content[] = array('data'=>$this->is_isset($value['equipment_id']),'style'=>'display:none','class'=>'equipment_id');
				$row_content[] = array('data'=>$this->is_isset($value['date_saved']),'style'=>'display:none','class'=>'date_saved');
				$row_content[] = array('data'=>$this->is_isset($value['requestedby']),'style'=>'display:none','class'=>'requestedby');
				$row_content[] = array('data'=>$this->is_isset($value['issuedby']),'style'=>'display:none','class'=>'issuedby');
				$row_content[] = array('data'=>$this->is_isset($value['approvedby']),'style'=>'display:none','class'=>'approvedby');
								
				$row_content[] = array('data'=>$this->is_isset($value['MR No.']),'style'=>'','class'=>'mr_no');
				$row_content[] = array('data'=>$this->is_isset($value['Equipment Name']),'style'=>'','class'=>'equipment_name');
				$row_content[] = array('data'=>$this->is_isset($value['Plate No.']),'style'=>'','class'=>'plate_no');
				$row_content[] = array('data'=>$this->is_isset($value['Chassis No.']),'style'=>'','class'=>'chassis_no');
				$row_content[] = array('data'=>$this->is_isset($value['Engine No.']),'style'=>'','class'=>'engine_no');			
				$row_content[] = array('data'=>$this->is_isset($value['Equipment Cost']),'style'=>'','class'=>'equipment_cost');	
				$row_content[] = array('data'=>'<span class="btn-link event release">Release</span>','style'=>'','class'=>'');

				$this->table->add_row($row_content);			

			}
	
		$this->table->set_heading($show);
		echo $this->table->generate();
		
	}

	private function is_isset($value){
		return (isset($value))? $value : '-';

	}


	public function new_request(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['post'] = $this->input->post();
		$data['signatory']   = $this->md_stock_withdrawal->signatory();
		$this->load->view('fixed_asset/obligation_release/create',$data);

	}

	public function get_equipment(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table equipment_table table-striped">' );
		$this->table->set_template($tmpl);

		$result = $this->md_obligation_request->get_equipment($this->input->post('location'));
		
		$show = array(
					array('data'=>'equipment_id','style'=>'display:none'),
					array('data'=>'made_in','style'=>'display:none'),
					array('data'=>'equipment_cost','style'=>'display:none'),					
					array('data'=>'from_location','style'=>'display:none'),
					'Equipment Description',
					'plate property no',
					'Chassis No',
					'Brand',
					'Engine No',
					'Status',
					'Action',
			 		);
							
			foreach($result->result_array() as $key => $value){

				$row_content = array();
								
				$row_content[] = array('data'=>$value['equipment_id'],'style'=>'display:none','title'=>$value['equipment_id'],'class'=>'equipment_id');
				$row_content[] = array('data'=>$value['made_in'],'style'=>'display:none','title'=>$value['made_in'],'class'=>'made_in');
				$row_content[] = array('data'=>$value['equipment_cost'],'style'=>'display:none','title'=>$value['equipment_cost'],'class'=>'equipment_cost');								
				$row_content[] = array('data'=>$value['from_location'],'style'=>'display:none','title'=>$value['from_location'],'class'=>'from_location');								
				$row_content[] = array('data'=>$value['equipment_description'],'style'=>'','title'=>$value['equipment_description'],'class'=>'equipment_description');
				$row_content[] = array('data'=>$value['equipment_platepropertyno'],'style'=>'','title'=>$value['equipment_platepropertyno'],'class'=>'equipment_platepropertyno');
				$row_content[] = array('data'=>$value['equipment_chassisno'],'style'=>'','title'=>$value['equipment_chassisno'],'class'=>'equipment_chassisno');
				$row_content[] = array('data'=>$value['equipment_brand'],'style'=>'','title'=>$value['equipment_brand'],'class'=>'equipment_brand');
				$row_content[] = array('data'=>$value['equipment_engineno'],'style'=>'','title'=>$value['equipment_engineno'],'class'=>'equipment_engineno');
				$row_content[] = array('data'=>$this->extra->label($value['equipment_status']),'style'=>'','title'=>$value['equipment_status'],'class'=>'equipment_status');
				$row_content[] = array('data'=>'<span class="btn-link event">Request This</span>','style'=>'','title'=>'');

				/*
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
				*/

				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();


	}


	function get_mr_accessories(){

		if(!$this->input->is_ajax_request()){
			exit(0);					
		}
		$tmpl = array ( 'table_open'  => '<table class="table tools-table table-striped table-hover">' );
		$this->table->set_template($tmpl);
		
		$result = $this->md_obligation_request->mr_accessories($this->input->post('location'));

		$show = array(
						'Description',
						'Property No',
						'Cost',
						'Action',
				 	);

					foreach($result->result_array() as $key => $value){					
						$row_content = array();
																	
						$row_content[] = array('data'=>$value['equipment_description'],'class'=>'equipment_description');
						$row_content[] = array('data'=>$value['equipment_platepropertyno'],'class'=>'equipment_platepropertyno');
						$row_content[] = array('data'=>$value['equipment_cost'],'class'=>'equipment_cost');
						$row_content[] = array('data'=>'<span class="btn-link event">Add</span>');
						$this->table->add_row($row_content);

					}

				$this->table->set_heading($show);
			echo $this->table->generate();


	}
		

	public function get_details(){

		if(!$this->input->is_ajax_request()){
					exit(0);
		}		

		$result = $this->md_obligation_request->get_details($this->input->post('location'),$this->input->post('id'));
		$data['main'] = $this->md_obligation_request->get_mainTable($this->input->post('id'));

		$option = array(
				'result'=>$result,
				'hide'=>array(
					'Equipment Name',
					'Status',
					),
				'bool'=>true,
				'table_class'=>array('table','table-striped','details-table')
		);
		
		$data['table'] =  $this->extra->generate_table($option);
		$data['edit_url'] = 'fixed_asset/obligation_request/edit';
	
		$data['id']    = $this->input->post('id');
				
		$this->load->view('template/cumulative_3_b',$data);

	}


	public function edit(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$data['main'] = $this->md_obligation_request->get_mainTable($this->input->post('po_id'));
		$data['detail'] = json_encode($this->md_obligation_request->get_detailTable($this->input->post('po_id')));
		
		$data['signatory']  = $this->md_stock_withdrawal->signatory();
					
		$this->load->view('fixed_asset/obligation_request/edit',$data);

	}


	public function update_obligation(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_obligation_request->update_obligation();

	}

	public function get_tools(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['mr_id']        = $this->md_obligation_release->get_tools($this->input->post('mr_id'));	
		$data['equipment_id'] = $this->md_obligation_release->get_status($this->input->post('equipment_id'));
		
		echo json_encode($data);

	}

	public function save_obligation(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		echo $this->md_obligation_release->save_obligationRelease();

	}











}