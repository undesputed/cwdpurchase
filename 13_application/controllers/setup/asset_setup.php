<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_setup extends CI_Controller {

	public function __construct(){
		parent :: __construct();	

		$this->lib_auth->default = "default-accounting";
		$this->load->model('setup/md_asset_setup');
	}

	public function index(){

		$this->lib_auth->title = "Asset Setup";
		$this->lib_auth->build = "setup/asset_setup/index";		
		$this->lib_auth->render();
		
	}
		
	public function get_cumulative(){

		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$date = $this->input->post('date');
		$tmpl = array ( 'table_open'  => '<table class="table myTable table-striped table-hover">' );
		$this->table->set_template($tmpl);

		$result = $this->md_asset_setup->get_cumulative($date);		

		$show = array(
					array('data'=>'asset_id','style'=>'display:none'),
					'Account Description',
					'Total Amount',
					'Account Classification',
					'Project',			 		
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$row_content[] = array('data'=>$value['asset_id'],'class'=>'asset_id','style'=>'display:none');
				$row_content[] = array('data'=>$value['Account Description'],'class'=>'account_description');
				$row_content[] = array('data'=>$value['Total Amount'],'class'=>'total_amount');
				$row_content[] = array('data'=>$value['Account Classification'],'class'=>'account_classification');
				$row_content[] = array('data'=>$value['Project'],'class'=>'project');
				
				$this->table->add_row($row_content);

			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();
			
	}


	public function new_request(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$data['bank'] = $this->md_asset_setup->get_bank_setup();
		$this->load->view('setup/asset_setup/create',$data);
		
		
	}

	public function save_asset_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_asset_setup->save_asset_setup();

	}

	public function account_description(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_asset_setup->account_description($this->input->post('location'),$this->input->post('classification'),$this->input->post('sub_classification'));
		echo json_encode($result);

	}

	public function get_details(){
		
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$result = $this->md_asset_setup->get_details($this->input->post('id'));

		$option = array(
			'result'=>$result,
			'hide'=>array(
				'Name',
				'Account Name',
				'Account Number',
				'Amount',
				'Date Saved',
				),
			'bool'=>true,
			'table_class'=>array('table','table-striped','details_table'),

		);

		$data['table'] = $this->extra->generate_table($option);
		$data['id']    = $this->input->post('id');
		$data['edit_url'] = 'setup/asset_setup/edit';
		$this->load->view('template/cumulative_3_a',$data);


	}

	public function edit(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}
		
		$data['main'] = $this->md_asset_setup->get_mainTable($this->input->post('po_id'));
		$data['details'] = json_encode($this->md_asset_setup->get_detailTable($this->input->post('po_id')));
		$data['bank'] = $this->md_asset_setup->get_bank_setup();
		
		$this->load->view('setup/asset_setup/edit',$data);

	}

	public function update_asset_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_asset_setup->update_asset_setup();
	}

	public function bank_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$this->load->view('setup/asset_setup/add_bank');

	}

	public function get_bank_setup(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		$tmpl = array ( 'table_open'  => '<table class="table classification_table tbl-event table-hover table-striped">' );
		$this->table->set_template($tmpl);
			
		$result = $this->md_asset_setup->get_bank_setup2();

		$show = array(
					array('data'=>'bank_id','style'=>'display:none'),
					'Bank Name',
					'Address',
					'Account No',
					'Subsidary Name',							
					'Action',			 		
			 	);

			foreach($result->result_array() as $key => $value){
				$row_content = array();

				$value['bank_name']    = (isset($value['bank_name']))? $value['bank_name'] : '';
				$value['bank_address'] = (isset($value['bank_address']))? $value['bank_address'] : '';
				$value['account_name'] = (isset($value['account_name']))? $value['account_name'] : '';
				$value['account_no'] = (isset($value['account_no']))? $value['account_no'] : '';
				$value['short_name'] = (isset($value['short_name']))? $value['short_name'] : '';
				
				$row_content[] = array('data'=>$value['bank_id'],'class'=>'id','style'=>'display:none');
				$row_content[] = array('data'=>$value['bank_name'],'class'=>'bank_name');
				$row_content[] = array('data'=>$value['bank_address'],'class'=>'bank_address');
				$row_content[] = array('data'=>$value['account_no'],'class'=>'account_no');
				$row_content[] = array('data'=>$value['short_name'],'class'=>'short_name');								
				$row_content[] = array('data'=>'<span class="btn-link event update_class">Update</span>','class'=>'');
				$this->table->add_row($row_content);
				
			}
	
			$this->table->set_heading($show);
			echo $this->table->generate();

	}

	public function save_bank(){
		if(!$this->input->is_ajax_request()){
			exit(0);
		}

		echo $this->md_asset_setup->save_bank();


	}




}